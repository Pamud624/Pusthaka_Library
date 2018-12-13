<?php
function ChooseOperation($str, $rowMember){
	// Process string and identify the relevant copy (Get CID)
	if (substr($str,0,1) == "A") { // A barcode of a book was scanned
		// Get the copy
		$sql = sprintf("select 	* FROM copy WHERE barcode='%s'", $str);
		$rs = executeSqlQuery($sql);
		$cnt = mysqli_num_rows($rs);
		if($cnt==1){
			$row = mysqli_fetch_assoc($rs);
			$cid = $row['cid'];
		} elseif ($cnt==0) { // No book corresponding to this barcode
			$_SESSION['msg'] = "There is no book corresponding to this barcode<br>" .
				"Barcode = $str";
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();
		} else { // DATA Error
			$_SESSION['msg'] = "Invalid barcode.<br>" .
				"Barcode = $str";
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();			
		}
	} elseif ($str>=1) { // An access number was entered manually
		// check if the access number is a number
		if(!is_numeric($str)){
			$_SESSION['msg'] = "If you are manually entering a number, please make sure that it is" .
			" an integer that represents an access number.<br>You entered: $str";
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();		
		}
		
		// Get the copy
		$sql = sprintf("select * FROM copy WHERE access_no='%s'", $str);
		$rs = executeSqlQuery($sql);
		$cnt = mysqli_num_rows($rs);
		if($cnt==1){
			$row = mysqli_fetch_assoc($rs);
			$cid = $row['cid'];
		} elseif ($cnt==0) { // No book corresponding to this access number
			$_SESSION['msg'] = "There is no book corresponding to access number: $str";
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();
		} else { // DATA Error
			$_SESSION['msg'] = "Invalid Access Number: $str<br>";
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();			
		}
	} else { // Invalid input
		$_SESSION['msg'] = "Invalid input: <strong>$str</strong><br>" .
			"Please scan the barcode on the book you want to Issue or Return.<br>" .
			"You may also manually enter the access number of the book.";
		$_SESSION['msgIcon'] = 'ERROR';
		header("Location: ir2.php?mid=" . $rowMember['mid']);
		exit();
	}

	// Save the IRdate in the session
	$_SESSION['irDate'] = $_REQUEST['date']	;
	
	// Get copyRow
	$sql = sprintf("select c.*, b.* FROM (copy c LEFT JOIN book b ON  c.bid = b.bid) WHERE c.cid=%d", $cid);
	$rs = executeSqlQuery($sql);
	$cnt = mysqli_num_rows($rs);
	$rowCopy = mysqli_fetch_assoc($rs);

	//Check if this book is 'OK' (not damaged, or withdrawan)
	if($rowCopy['copy_status']!='OK'){
		$_SESSION['msg'] = "This book can not be lent because it's status is not 'OK'<br>" .
			'Current Status:&nbsp;' . $rowCopy['copy_status'] . "<br>" .
			'If this book can be lent, please update the ' . "<a href='book_copy_edit.php?ID="  . $rowCopy['cid'] . "' target='_blank'>copy status</a>" . ' and then try again' . '<br>' .
			'[' . $rowCopy['access_no'] . "] " . $rowCopy['title'] . 
			" by " . $rowCopy['authors'] . "<br>";
		$_SESSION['msgIcon'] = 'ERROR';
		header("Location: ir2.php?mid=" . $rowMember['mid']);
		exit();				
	}	


	// Get rowLoan (Is this book currently on loan?)
	$sql = "SELECT l.*, concat(m.title,' ',m.firstnames,' ',m.surname) AS mem_name FROM loan l LEFT JOIN member m ON l.member = m.mid WHERE returned=0 AND copy = " . $rowCopy['cid'];
	$rs = executeSqlQuery($sql);
	$rowCount = mysqli_num_rows($rs);
	if($rowCount == 0){ // Is not on loan: To Issue
		return array("IssueBook", $rowCopy);
	} elseif($rowCount ==1){ // On loan: To Return
		$rowLoan = mysqli_fetch_assoc($rs) or die("DATA ERROR");;			
		if($rowLoan['member'] != $rowMember['mid']){ // OnLoan with different member
			$_SESSION['msg'] = "This book is on loan with a different member, " .
				$rowLoan['mem_name'] . "<br>" .
				"Book Info: [" . $rowCopy['access_no'] . "] " . $rowCopy['title'] . 
				" by " . $rowCopy['authors'] . "<br>" .
				"Please try again.";
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();			
		} else { // On Loan with this member
			return array("ReturnBook", $rowCopy, $rowLoan);
		}
	} else { // Data Integrity Error
			$_SESSION['msg'] = "A DATA INTEGRITY ERROR occured.";
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();			
	}
	
} //END: function ChooseOperation($str, $rowMember){

function IssueBook($rowCopy, $rowMember){
	// Check if this book can be issued to the selected member
	if (!LendingAllowed($rowCopy, $rowMember)){
		$_SESSION['msg'] = $_SESSION['msgFR'];
		$_SESSION['msgIcon'] = $_SESSION['msgFRIcon'];
		unset($_SESSION['msgFR']);
		if(isset($_SESSION['msgFRIcon'])) unset($_SESSION['msgFRIcon']);
		header("Location: ir2.php?mid=" . $rowMember['mid']);
		exit();			
	}
	
	// Check if this book is on reserve
	$rowReservation=IsBookOnReserve($rowCopy, $rowMember);
	if ($rowReservation && isset($_SESSION['msgFR'])){
		$_SESSION['msg'] = $_SESSION['msgFR'];
		$_SESSION['msgIcon'] = $_SESSION['msgFRIcon'];
		unset($_SESSION['msgFR']);
		if(isset($_SESSION['msgFRIcon'])) unset($_SESSION['msgFRIcon']);
		header("Location: ir2.php?mid=" . $rowMember['mid']);
		exit();			
	}
	
	// Actually Issue this book
	$irDate = $_SESSION['irDate'];
	unset($_SESSION['irDate']);
	if(isset($irDate) && $irDate != ""){
		$t = @strtotime($irDate);
		if($t !=-1){
			$date_loaned = date("Y-m-d G:i:s",$t);
		} else {
			$_SESSION['msg'] = 'ERROR issuing book.<br>' .
				$rowCopy['title'] . " by " . $rowCopy['authors'] . '<br>' .				
				"Please enter a valid date time in the format 'YYYY-MM-DD hh:mm'.<br>".
				"Or you may leave the Date/Time box blank to use the current time.<br>";				
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();
		}
	} else {
		$date_loaned = date("Y-m-d G:i:s"); 
	}
	
	$date_due = CalcDateDue($rowCopy, $rowMember,$date_loaned);
	$sql = sprintf("INSERT into loan (returned, member, copy,date_loaned,date_due,loaned_by) VALUES (0,%d,%d,'%s','%s',%d)",
		$rowMember['mid'],$rowCopy['cid'],$date_loaned,$date_due,$_SESSION["CurrentUser"]["mid"]);
	$a = executeSqlNonQuery($sql);
	$rowsUpdated = $a['rows'];
	if($rowsUpdated != 1){
			$_SESSION['msg'] = "There was a database error in issuing the book.<br>".
				$rowCopy['title'] . " by " . $rowCopy['authors'];
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();
	}
	
	$des = '[' . $rowCopy['access_no'] . ']' . $rowCopy['title'] . ' ==> ' . '[' . $rowMember['title'] . ' ' . $rowMember['firstnames'] . ' ' . $rowMember['surname'] . ']';
	logEvent('ISSUE', $_SESSION['CurrentUser']['mid'], $rowMember['mid'],  addslashes($des));

	// If the book was reserved by this member, we need to Update Reservations for this book
	if($rowReservation['mid']==$rowMember['mid']){
		// Set Reservation Status
		$sql= sprintf("UPDATE reservation SET status='%s', cid=%d, dt_end='%s' WHERE rid=%d",
			"Fullfilled",$rowCopy['cid'], date("Y-m-d G:i:s"), $rowReservation['rid']);
		$a = executeSqlNonQuery($sql);
		$cnt = $a['rows'];
		if ($cnt != 1){
			$_SESSION['msg'] = "An error occurred in updating reservation status. But the book was issued.<br>".
				$rowCopy['title'] . " by " . $rowCopy['authors'];
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			//TODO: log error
			exit();				
		} else {
			$_SESSION['msg'] = "Book was issued.<br>".
		      $rowCopy['title'] . ' by ' . $rowCopy['authors'] . '<br>' .
                '<strong>This book was reserved by this member.</strong><br>' .
                'Reservation status was updated.';
			$_SESSION['msgIcon'] = 'INFO';
			$des = '[' . $rowCopy['access_no'] . ']' . $rowCopy['title'] . ' ==>' .  '[' . $rowMember['title'] . ' ' . $rowMember['firstnames'] . ' ' . $rowMember['surname'] . '] ';
			logEvent('RESERVATION_FULFILLED', $_SESSION['CurrentUser']['mid'], $rowMember['mid'],  addslashes($des));

			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();
		}
	}
	
	$_SESSION['msg'] = "Book was issued.<br>".
		$rowCopy['title'] . " by " . $rowCopy['authors'];
	$_SESSION['msgIcon'] = 'INFO';
	header("Location: ir2.php?mid=" . $rowMember['mid']);
	exit();
	
	
}

function ReturnBook($rowCopy, $rowLoan, $rowMember){
	
	// Set date_returned
	$irDate = $_SESSION['irDate'];
	unset($_SESSION['irDate']);
	if(isset($irDate) && $irDate != ""){
		$t = @strtotime($irDate);
		if($t!=-1){
			$date_returned = date("Y-m-d G:i:s",$t);
		} else {
			$_SESSION['msg'] = 'ERROR issuing book.<br>' .
				$rowCopy['title'] . " by " . $rowCopy['authors'] . '<br>' .				
				"Please enter a valid date time in the format 'YYYY-MM-DD hh:mm'.<br>".
				"Or you may leave the Date/Time box blank to use the current time.<br>";
			header("Location: ir2.php?mid=" . $rowMember['mid']);
			exit();			
		}
	} else {
		$date_returned = date("Y-m-d G:i:s"); 
	}
	$rowLoan['date_returned'] = $date_returned;
	
	// Update loan table
	$fineDetails = CalcFine($rowCopy, $rowLoan, $rowMember);
	$rowLoan['fine'] = $fineDetails[0];
	$rowLoan['date_returned'] = $date_returned; 
	$sql = sprintf("UPDATE loan SET returned=1, date_returned = '%s', fine=%d WHERE lid=%d",
		$date_returned, $fine,$rowLoan['lid']);
	$a = executeSqlNonQuery($sql);
	$rowsUpdated = $a['rows'];
	if($rowsUpdated != 1){
		$_SESSION['msg'] = "An error occured while returning book.";
		$_SESSION['msgIcon'] = 'ERROR';
		header("Location: ir2.php?mid=" . $rowMember['mid']);
		exit();			
	} 


	$des = '[' . $rowCopy['access_no'] . ']' . $rowCopy['title'] . ' <== ' . '[' . $rowMember['title'] . ' ' . $rowMember['firstnames'] . ' ' . $rowMember['surname'] . ']';
	logEvent('RETURN', $_SESSION['CurrentUser']['mid'], $rowMember['mid'], addslashes($des));

    include('../classes/Reservations.php');
    $re = new Reservations;
    if ($re->IsReserved($rowCopy['bid'])){
		$reservationsMsg = $re->updateReservations($rowCopy);
	}
	
	// Display Returned Books info
	if(!isset($_SESSION['Returned'])){
		$_SESSION['Returned'][0] = 0;
		$_SESSION['TotalFine'] = 0;
	}
	$_SESSION['Returned'][0] += 1;
	$n = $_SESSION['Returned'][0];

	$_SESSION['Returned'][$n]['copy'] = $rowCopy;
	$_SESSION['Returned'][$n]['member'] = $rowMember;
	$_SESSION['Returned'][$n]['loan'] = $rowLoan;			
	$_SESSION['Returned'][$n]['msg'] = $fineDetails[1];
	if(!isset($_SESSION['TotalFine'])) $_SESSION['TotalFine']=0;
	$_SESSION['TotalFine'] = (int)$_SESSION['TotalFine'] + $fineDetails[0];
	
	$_SESSION['msg'] = $_SESSION['msg'] . "Book Returned<br>".
		$rowCopy['title'] . " by " . $rowCopy['authors'] . '<br>';
	$_SESSION['msgIcon'] = 'INFO';
	if(isset($reservationsMsg)) $_SESSION['msg'] = $_SESSION['msg'] .
		"<div class='reservationsMsg'>" . $reservationsMsg . '</div>';
	
	header("Location: ir2.php?mid=" . $rowMember['mid']);
	exit();
}

function LendingAllowed($rowCopy, $rowMember){
    $sql = sprintf("select * from lending_settings where book_type='%s' AND member_type='%s'",
		$rowCopy['lending_type'], $rowMember['category']);
	$rs = executeSqlQuery($sql);
	$cnt = mysqli_num_rows($rs);
	if ($cnt==0){ // No entry 
		$_SESSION['msgFR'] = "The lending settings for this (member category <--> book lending type) is not defined.<br>" .
		"Member Category = " . $rowMember['category'] . ", Book Lending Type = " . $rowCopy['lending_type'] . "<br>" .
		"Please ask the SYS ADMIN to define these settings.";
		$_SESSION['msgFRIcon'] = 'ERROR';
		return false;
	} elseif ($cnt==1){ //There is an entry
		$sr = mysqli_fetch_assoc($rs); // (Lending) Settings Row 
	} elseif($cnt>1){  // Duplicate entry
		$_SESSION['msgFR'] = "ERROR: The lending_settings table contains a duplicate entry for:<br>" .
		"Member Category = " . $rowMember['category'] . ", Book Lending Type = " .	$rowCopy['lending_type'] . "<br>" .
		"Please ask the SYS ADMIN to fix this error"; 
		$_SESSION['msgFRIcon'] = 'ERROR';
		return false;       
     } else { // Just in case
		$_SESSION['msgFR'] = "An un-identified error occurred.";
		$_SESSION['msgFRIcon'] = 'ERROR';
		return false;
	}
	
	// Is it allowed?
	if ($sr['allowed']==0){
		$_SESSION['msgFR'] = "This member is not allowed to borrow this book by the Library Lending Policy.<br>" .
		"Member Category = " . $rowMember['category'] . ", Book Lending Type = " . $rowCopy['lending_type']. "<br>";
		$_SESSION['msgFRIcon'] = 'ERROR';
		return false;
	}
			
	// Has the member exceeded the allowed number of books
	$sql = sprintf("SELECT COUNT(l.member) FROM loan l LEFT JOIN copy c ON l.copy=c.cid " .
		"WHERE returned=0 AND member=%d AND lending_type='%s'",
			$rowMember['mid'],$rowCopy['lending_type']);
	$rs = executeSqlQuery($sql);
	$row = mysqli_fetch_array($rs);
	$numbooks = $row[0];
	
	if($sr['num_allowed'] <= $numbooks){
		$_SESSION['msgFR'] = "Member has exceeded the number of books he/she is allowed to borrow from this book type.<br>" .
		"Member Category = " . $rowMember['category'] . ", Book Lending Type = " . $rowCopy['lending_type'] . ", Maximum Number of Books: " . $numbooks . "<br>";
		$_SESSION['msgFRIcon'] = 'ERROR';
		return false;
	}
	
	return true;
} //END: function LendingAllowed($rowCopy, $rowMember){

function IsBookOnReserve($rowCopy, $rowMember){
	// Get the 'Active' and 'Available' reservations for this book
	$sql = sprintf("SELECT r.rid, r.bid, r.cid, r.mid, r.dt_start, r.dt_end, r.status, " .
		"m.surname, m.firstnames, m.title, m.type " .
		"FROM reservation r LEFT JOIN member m ON r.mid=m.mid WHERE r.bid=%d AND (r.status='%s' OR r.status='%s') " .
		"ORDER BY dt_start ASC",
		$rowCopy['bid'],"Active", "Available");
	$rs = executeSqlQuery($sql);
	$cnt = mysqli_num_rows($rs);
	if($cnt==0){
		return false;
	} elseif($cnt>0){ // Yes this is on reserve
		// Is this book 'Available' to this member
		while ($row = mysqli_fetch_assoc($rs)){
			if( ($row['status']=='Available') && ($row['mid'] == $rowMember['mid']) ){
				//TODO: check specific access number?
				return $row; //true
			}
		}
		// No it is not available
		$_SESSION['msgFR'] = "This book is reserved and is waiting to be collected by another member.<br>";
		$_SESSION['msgFRIcon'] = 'ERROR';
		$rs = executeSqlQuery($sql);
		while ($row = mysqli_fetch_assoc($rs)){
			if( $row['status']=='Available' ){
				$_SESSION['msgFR'] = $_SESSION['msgFR'] . $row['title'] . ' ' . $row['firstnames'] . ' ' . $row['surname'] . "<br>";
			}
		}			
		return true;
	} else { // Shouldn't happen
		$_SESSION['msg'] = "An unidentified error occurred";
		$_SESSION['msgIcon'] = 'ERROR';
		header("Location: ir2.php?mid=" . $rowMember['mid']);
		exit();		
	}
} //END: function IsBookOnReserve($rowCopy, $rowMember){

function CalcDateDue($rowCopy, $rowMember, $date_loaned){
	
	echo "<script>alert('hi1');</script>";
	// Retrieve from DB the number of days allowed on loan for this book's lending type to this member category
	$sql = sprintf("select * from lending_settings where book_type='%s' AND member_type='%s'",
		$rowCopy['lending_type'], $rowMember['category']);
	$rs = executeSqlQuery($sql);
	$cnt = mysqli_num_rows($rs);
	if ($cnt==0){ // No entry 
		$_SESSION['msg'] = "The lending settings for this (member category <--> book lending type) is not defined.<br>" .
		"Member Category = " . $rowMember['category'] . ", Book Lending Type = " . $rowCopy['lending_type'] . "<br>" .
		"Please ask the SYS ADMIN to define these settings.";
		$_SESSION['msgIcon'] = 'ERROR';
		header("Location: " . $_SERVER['PHP_SELF']);
		exit();
	} elseif ($cnt==1){ //There is an entry
		$sr = mysqli_fetch_assoc($rs); // (Lending) Settings Row 
	} elseif($cnt>1){  // Duplicate entry
		$_SESSION['msg'] =  "ERROR: The lending_settings table contains a duplicate entry for:<br>" .
		"Member Category = " . $rowMember['category'] . ", Book Lending Type = " .	$rowCopy['lending_type'] . "<br>" .
		"Please ask the SYS ADMIN to fix this error";        
		$_SESSION['msgIcon'] = 'ERROR';
		return false;
	} else { // Just in case
		$_SESSION['msgFR'] = "An un-identified error occurred.";
		$_SESSION['msgFRIcon'] = 'ERROR';
		return false;
	}	
	$days_allowed = $sr['days_allowed'];

	$dt = strtotime($date_loaned);

	$y = date("Y",$dt); $m = date("m",$dt); $d = date("d",$dt);
	$dueDateTS = mktime(0,0,0,$m,$d+$days_allowed,$y);
	$dueDate = date("Y-m-d G:i:s",$dueDateTS);

	return $dueDate;		
}

function CalcFine($rowCopy, $rowLoan, $rowMember){
	

	// $FINE_FOR_FIRST_DAY= 20; // If the book was due yesterday, fines start with 20/=
	// $FINE_PER_DAY = 9;
	// $FINE_PER_HOUR = 1;

	// $FINE_START_TIME = 9; // 10AM




	    $sql1 = "SELECT * FROM config1 WHERE id =1";
        $recordset = executeSqlQuery($sql1);
        $rowcount = mysqli_num_rows($recordset);
        $row = mysqli_fetch_assoc($recordset);

        // $sql1 = "SELECT value2 FROM config1 WHERE id =1";
        // $recordset1 = executeSqlQuery($sql1);
        // $rowcount1 = mysqli_num_rows($recordset1);
        // $row1 = mysqli_fetch_assoc($recordset1);

        //  $sql2 = "SELECT value3 FROM config1 WHERE id =1";
        // $recordset2 = executeSqlQuery($sql2);
        // $rowcount2 = mysqli_num_rows($recordset2);
        // $row2 = mysqli_fetch_assoc($recordset2);

        //  $sql3 = "SELECT value4 FROM config1 WHERE id =1";
        // $recordset3= executeSqlQuery($sql3);
        // $rowcount3 = mysqli_num_rows($recordset3);
        // $row3 = mysqli_fetch_assoc($recordset3);


      $FINE_FOR_FIRST_DAY= $row['value'];
      $FINE_PER_DAY = $row['value2'];
      $FINE_PER_HOUR = $row['value3'];
      $FINE_START_TIME =$row['value4'];

    
      


	
	$dateDue = $rowLoan['date_due'];
	$dateDueTS = strtotime($dateDue);	
	$dateReturned = $rowLoan['date_returned'];
	$dateReturnedTS = strtotime($dateReturned);
	
	
	//Holiday subtracting 
	$duedateHol = new DateTime($rowLoan['date_due']);
	$retdateHol = new DateTime($rowLoan['date_returned']);
	
	//$interval = $retdateHol->diff($duedateHol);
	
	// create an iterateable period of date (P1D equates to 1 day)
	$period = new DatePeriod($duedateHol, new DateInterval('P1D'), $retdateHol);
	
	//holiday array
		$sql = "SELECT * FROM holidays";
		$rs = executeSqlQuery($sql);

		$holidays = array();
		
		$holidaysCount =0;
		
		$rows = mysqli_num_rows($rs);
        if ($rows != 0) {
			while($holidaysData = mysqli_fetch_array($rs)){
				
				$holidays[] =$holidaysData['date'];
				
			}
		}	
		
		//Reducinng days count
		
		foreach($period as $dt) {
			$curr = $dt->format('D');

			// substract if Saturday or Sunday
			if ($curr == 'Sat' || $curr == 'Sun') {
				$holidaysCount++;
			}

			// (optional) for the updated question
			elseif (in_array($dt->format('Y-m-d'), $holidays)) {
				$holidaysCount++;
			}
		}


	if($dateDueTS >= $dateReturnedTS){ // The book is not overdue
		return array(0,'Not Overdue');
	}
	
	// Get the finable time difference  in days and hours
	$difference = $dateReturnedTS - $dateDueTS;
	$days = floor( ($difference) / (60*60*24) ); //days
	
	// Subtract the number of holidays
	$days = $days - $holidaysCount;
	
	$hours =  floor( ( $difference % (60*60*24) ) / (60*60) ) - $FINE_START_TIME; 
	if($hours<0) $hours=0; 

	if($days > 1){
		$fine = ($FINE_FOR_FIRST_DAY + ($days-1)*$FINE_PER_DAY) + ($hours * $FINE_PER_HOUR);
	} elseif($days == 1) {
		$fine = $FINE_FOR_FIRST_DAY + ($hours * $FINE_PER_HOUR);
	} 

	$msg = $days . 'd ' . $hours . 'h';	
	return array($fine,$msg);
}




?>
