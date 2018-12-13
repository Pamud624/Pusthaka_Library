<?php 
class Reservations{	
	function GetReservedBooks(){ // returns a recordset containg the books currently reserved
		$sql = "DROP TABLE IF EXISTS tmp";
		executeSqlNonQuery($sql);
		$sql = "CREATE TEMPORARY TABLE tmp (`bid` int(11))";
		executeSqlNonQuery($sql);
		$sql = "INSERT INTO tmp select DISTINCT bid from reservation WHERE status='Active' OR " . 
			"status='Available'";
		executeSqlNonQuery($sql);
		$sql = "SELECT b.* FROM tmp t LEFT JOIN book b ON t.bid=b.bid";
		$rs = executeSqlQuery($sql);
		$sql = "DROP TABLE IF EXISTS tmp";
		executeSqlNonQuery($sql);
		return $rs;
	}

	function GetReservedBooks_byMID($mid){ //returns a recordset containg the books currently reserved
		$sql = sprintf("SELECT r.rid, r.mid, r.dt_start, r.dt_end, r.status, " .
			"b.* FROM reservation r LEFT JOIN book b ON r.bid=b.bid " .
			"WHERE  ( (r.mid=%d) AND  (r.status IN ('Active','Available','Expired') ))", $mid);
		$rs = executeSqlQuery($sql);
		return $rs;
	}
	
	function GetReservationsBy_bid($bid){ // returns a recordset
	}

	function GetReservationsBy_bid_status($bid,$status){ // returns a recordset
		$sql = sprintf("SELECT r.rid, r.bid, r.cid, r.mid, r.dt_start, r.dt_end, r.status, " .
			"m.surname, m.firstnames, m.title, m.type, c.access_no " .
			"FROM (reservation r LEFT JOIN member m ON r.mid=m.mid) LEFT JOIN copy c ON c.cid=r.cid " .
				"WHERE r.bid=%d AND r.status='%s' " .
			"ORDER BY dt_start ASC",
			$bid,$status);
		$rs = executeSqlQuery($sql);
		return $rs;
	}			

	function GetReservationByRID($rid){ // returns a recordset
		$sql = sprintf("SELECT * FROM reservation WHERE rid=%d", $rid);
		$rs = executeSqlQuery($sql);
		return $rs;		
	}

	function GetByID($rid){ // returns a row
		$sql = sprintf("SELECT * FROM reservation WHERE rid=%d", $rid);
		$rs = executeSqlQuery($sql);
        $cnt = mysql_num_rows($rs);
        if($cnt==1){
            $row = mysql_fetch_assoc($rs);
            return $row;
        } else {
            return 0;   
        }
	}    
    
    
	function MakeReservation($bid, $mid){ // returns a status message
		/** Check mid and retriew memberRow */
		$sql = 'SELECT * FROM member WHERE mid=' . $mid;
		$rs = executeSqlQuery($sql);
		$cnt = mysql_num_rows($rs);
		if($cnt==0){
			return 'ERROR: invalid member ID specified';
		} elseif ($cnt==1){
			$rowMember = mysql_fetch_assoc($rs);
		}
		
		
		/** Check if this book is already reserved by this member */
		$sql = sprintf("SELECT * FROM reservation WHERE (status='Active' OR status='Available')AND " .
			"(bid=%d AND mid=%d)",
			$bid,$mid);
		$rs = executeSqlQuery($sql);
		$count = mysql_num_rows($rs);
		if ($count > 0){			
			return "ERROR: This book is already reserved by you.";
		}


		/** Check if this book is on loan with this member */
		$sql = sprintf("SELECT * FROM (loan l LEFT JOIN copy c ON l.copy=c.cid) " .
			"LEFT JOIN book b ON c.bid=b.bid WHERE (b.bid=%d AND l.member=%d AND l.returned=0)",
			$bid,$mid);
		$rs = executeSqlQuery($sql);
		$count = mysql_num_rows($rs);
		if ($count > 0){			
			return 'ERROR: Reservation not allowed:<br>' .
				"This book is already on loan with you.";
		}


		/** Check if there are copies of the book for which the current user has borrowing permission */
		$sql = sprintf("SELECT * FROM copy WHERE bid=%d", $bid);
		$copies = executeSqlQuery($sql);
		$cnt = mysql_num_rows($copies);
		if ($cnt == 0){			
			return "ERROR: No copy of this book is available.";
		}
		$found = false;
		$foundAndAvailable = false;
		while($cpy = mysql_fetch_assoc($copies)){ // for all the copies
			// check if the user has permission to borrow this copy
			
			$sql = sprintf("select * from lending_settings where book_type='%s' AND member_type='%s'",
				$cpy['lending_type'], $rowMember['category']);
			$rs = executeSqlQuery($sql);
			$cnt = mysql_num_rows($rs);
			if ($cnt==0){ // No entry 
				return "ERROR: The lending settings for this (member category <--> book lending type) " .
					"is not defined.<br>" .
					"Member Category = " . $rowMember['category'] . ", Book Lending Type = " . 
					$cpy['lending_type'] . "<br>" .
				"Please ask the SYS ADMIN to define these settings.";
			} elseif ($cnt==1){ //There is an entry
				$sr = mysql_fetch_assoc($rs); // (Lending) Settings Row 
			} elseif($cnt>1){  // Duplicate entry
                return "ERROR: The lending_settings table contains a duplicate entry for:<br>" .
                    "Member Category = " . $rowMember['category'] . ", Book Lending Type = " .	$cpy['lending_type'] . "<br>" .
				    "Please ask the SYS ADMIN to fix this error";        
            } else { // Just in case
				return "ERROR: An un-identified error occurred.";
			}
			
			// Is it allowed?
			if ($sr['allowed']==1){
				$found=true;				
				// Check if this book is available
				$sql = 'SELECT * FROM loan WHERE returned=0 AND copy=' . $cpy['cid'];
				$rs = executeSqlQuery($sql);
				$cnt = mysql_num_rows($rs);
				if($cnt==0){ // Book available for loan
					$foundAndAvailable = true;
				} elseif($cnt>1){ // Data consistancy error
					return 'ERROR: DATA CONSISTANCY ERROR<br>' . 
						'Please inform the ADMIN';
				}
			}						
		}
		if($found==false){
			return 'ERROR: Reservation not allowed:<br>' .
				'Although there are copies of this book in the library, you do not have enough permission to borrow any of them';
		}
		if($foundAndAvailable==true){
			return 'ERROR: Reservation not allowed:<br>' .
				'This book is available for loan at the library. Please visit the library and borrow the book';
		}

		/*** Reserve Book */
		$sql = sprintf("INSERT INTO reservation (bid, mid, dt_start, status) VALUES (%d,%d,'%s','%s')",
			$bid,$mid,date("Y-m-d G:i:s"),"Active");
		$a = executeSqlNonQuery($sql);
		$rowsUpdated = $a['rows'];
		if($rowsUpdated == 1){
			require_once('Members.php'); $clsM = new Members;
            $rowMember = $clsM->getByID($mid);
            require_once('Books.php'); $clsB = new Books;
            $rowBook = $clsB->getBookByID($bid);
            
			$des = '[' . $rowBook['title'] . ' by ' . $rowBook['authors'] . ']' .  
				' <== [' . $rowMember['mid'] . '] ' . $rowMember['title'] . ' ' . $rowMember['firstnames'] . ' ' . $rowMember['surname'];
			logEvent('RESERVATION_ADDED', $_SESSION['CurrentUser']['mid'], $rowMember['mid'], addslashes($des));

			return "Reservation was successfully made";
		} else {
			return "ERROR: couldn't make reservation";
		}
	}

	function CancelReservation($rid){ // returns a status message
		$sql = sprintf("UPDATE reservation SET status='Cancelled' WHERE rid=%d",$rid);
		$a = executeSqlNonQuery($sql);
		$rows_updated = $a['rows'];
		if($rows_updated != 1){
			return "ERROR: couldn't cancel reservation";
		} else{ // Cancelled, update other reservations        			
            $reservation = $this->GetByID($rid);
			require_once('Members.php'); $clsM = new Members;
            $rowMember = $clsM->getByID($reservation['mid']);
            require_once('Books.php'); $clsB = new Books;
            $rowBook = $clsB->getBookByID($reservation['bid']);
            
            $des = '[' . $rowBook['title'] . ' by ' . $rowBook['authors'] . ']' .  
				' <== [' . $rowMember['mid'] . '] ' . $rowMember['title'] . ' ' . $rowMember['firstnames'] . ' ' . $rowMember['surname'];
			logEvent('RESERVATION_CANCELLED', $_SESSION['CurrentUser']['mid'], $rowMember['mid'], addslashes($des));
        
			$sql = "SELECT * FROM reservation WHERE rid=" . $rid;
			$rs = executeSqlQuery($sql);
			$r = mysql_fetch_assoc($rs);
			$cid = $r['cid'];
			
			if($cid>0){			
				$sql = sprintf("select c.*, b.* FROM (copy c LEFT JOIN book b ON  c.bid = b.bid) WHERE c.cid=%d", $cid);
				$rs = executeSqlQuery($sql);
				$rowCopy = mysql_fetch_assoc($rs);
				return $this->updateReservations($rowCopy);
			}
						
			return "Reservation cancelled";
		}
	}

function updateReservations($rowCopy){
	$RESERVATIONS_VALID_PERIOD = 1;
	// Get the 'Available' reservations
	$sql = sprintf("SELECT r.*, m.surname, m.firstnames, m.title, m.type " .
		"FROM reservation r LEFT JOIN member m ON r.mid=m.mid WHERE r.bid=%d AND r.status='Available' " .
		"ORDER BY dt_start ASC", $rowCopy['bid']);
	$rs = executeSqlQuery($sql);
	
	// Set status from 'Available' to 'Expired' for the relevant reservations
	while($r = mysql_fetch_assoc($rs)){
		if(strtotime($r['dt_end']) < time()){ // the waiting period has passed
			// Set status
			$sql2 = sprintf("UPDATE reservation SET status='Expired', dt_end='%s' WHERE rid=%d",
				date('Y-m-d G:i:s'), $r['rid']);
			$a = executeSqlNonQuery($sql2);
			$rowsUpdated = $a['rows'];
			
			$des = '[' . $r['bid'] . ']' . ' <-/-> ' . '[' . $r['title'] . ' ' . $r['firstnames'] . ' ' . $r['surname'] . ']';
			logEvent('RESERVATION_EXPIRED', $_SESSION['CurrentUser']['mid'], $r['mid'], addslashes($des));
			
			//Notify Member ---------------------------------------------------------
				//TODO
			//END: Notify Member ----------------------------------------------------

			// We need to update the reservations again by recursively calling this function
			$sql3 = sprintf("SELECT c.*, b.* FROM copy c LEFT JOIN book b ON c.bid=b.bid WHERE c.cid=%d",
				$rowCopy['cid']);
			$rs3 = executeSqlQuery($sql3);
			$rowCopy3 = mysql_fetch_assoc($rs3);
			updateReservations($rowCopy3);
		}
	}
	
	// Get the 'Active' reservations
	$sql = sprintf("SELECT r.*, m.surname, m.firstnames, m.title, m.type " .
		"FROM reservation r LEFT JOIN member m ON r.mid=m.mid WHERE r.bid=%d AND r.status='Active' " .
		"ORDER BY dt_start ASC", $rowCopy['bid']);
	$rs = executeSqlQuery($sql);
	$cnt = mysql_num_rows($rs);
	if($cnt>0){
		// Set the first (Active --> Available)
		$availablePeriod = $RESERVATIONS_VALID_PERIOD;
		$y = date("Y"); $m = date("m"); $d = date("d");
		$hr = date('G'); $mn = date('i'); $se = date('s');
		$dt_endTS = mktime($hr,$mn,$se,$m,$d+$availablePeriod,$y);
		$dt_end = date("Y-m-d G:i:s",$dt_endTS);
		
		$r = mysql_fetch_assoc($rs);
		$sql = sprintf("UPDATE reservation SET status='Available', dt_end='%s', cid=%d WHERE rid=%d",
			$dt_end, $rowCopy['cid'], $r['rid']);
		$a = executeSqlNonQuery($sql);
		$rowsUpdated = $a['rows'];
		
		
		//Notify Member ---------------------------------------------------------
		// Retrieve member information
		$sqlT = 'SELECT * FROM member WHERE mid=' . $r['mid'];
		$rsT = executeSqlQuery($sqlT);
		$rMember = mysql_fetch_assoc($rsT);

        // Log Event
		$des = '[' . $rowCopy['access_no'] . '] ' . $rowCopy['title'] . ' ==> ' . '[' . $rMember['title'] . ' ' . $rMember['firstnames'] . ' ' . $rMember['surname'] . ']';
		logEvent('RESERVATION_NOTIFIED', $_SESSION['CurrentUser']['mid'], $rMember['mid'], addslashes($des));

		
		// Prepare and send email
		$emailTxt = 
			'<strong><u>Reserved Book Available</u></strong>' .
			'<br>Dear ' . $rMember['title'] . ' ' . $rMember['firstnames'] . ' ' . $rMember['surname'] . ',' .
			'<br>A book that you reserved at ' . $r['dt_start'] . ' is available for loan.' .
			'<br>' .
			'<br><strong>Book Details</strong>' .
			'<br>Title: ' . $rowCopy['title'] .
			'<br>Author(s): ' . $rowCopy['authors'] .
			'<br>' .
			'<br>Please note that we have allocated the copy of this book bearing access number <strong>' . $rowCopy['access_no'] . '</strong> to you.' .			
			'<br>Please collect this book before: <strong>' . $dt_end . '</strong>' . 
			'<br>If you do not claim this book before the above designated time, your reservation automatically expires and the book will be made available to the person who have reserved this book after you.' .
			'<br>' .
			"<br>To view or cancel your reservation status please visit the library website, login, and go to 'My Reservations'" .
			'<br>' .
			'<br>Thank you,' .
			'<br>' . 
			'<br>The Library,' .
			'<br>University of Colombo School of Computing.' .
			'<hr>' .
			'This is an automatically generated message' .
			'<br>E-mail : admin@pusthaka.org' .
			'<hr>';
		
		//$to = $rMember['email'];
		if($rMember['email']!=''){
            $to = $rMember['email'];    
        } else {
            $to = 'admin@pusthaka.org';   
        }
        
		$subject = 'Reserved Book Available: (' . $rowCopy['title'] . ' by ' . $rowCopy['authors'] . ')';
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: Pusthaka Admin <admin@pusthaka.org>\r\n";
		$headers .= "Cc: admin@pusthaka.org\r\n";	
	
	
		if($to==''){
			$msgEmail = 'This user has not specified an email address';
		} else {	
			if (@mail($to,$subject, $emailTxt, $headers)) {			
				$msgEmail = "Sent mail to: " . $to;
				$des = '[' . $to . ']' . ' ==> ' . '[' . $rMember['title'] . ' ' . $rMember['firstnames'] . ' ' . $rMember['surname'] . ']';
				logEvent('SENT_EMAIL', $_SESSION['CurrentUser']['mid'], $rMember['mid'],  addslashes($des));

			} else {
				$msgEmail = 'There was an error sending mail to: ' . $to;
				$des = '[' . $to . ']' . ' ==> ' . '[' . $rMember['title'] . ' ' . $rMember['firstnames'] . ' ' . $rMember['surname'] . ']';
				logEvent('ERROR_EMAIL', $_SESSION['CurrentUser']['mid'], $rMember['mid'],  addslashes($des));				
			}
		}
		//END: Notify Member ----------------------------------------------------
	
		// Notify Library Staff Member to place the book on the Reservations Shelf
		$msg = 'This book was reserved by ' . $r['title'] . ' ' . $r['firstnames'] . ' ' . $r['surname'] . ' (' . $r['type'] . ')<br>' .
			'Please place the book (access no = ' . $rowCopy['access_no'] . ') in the Reserves Shelf';				
		return $msg . '<hr><strong>' . $msgEmail . '</strong>';		
	}	
}

	function MarkReservationAsFullfilled($rid){ // returns a status message
		$sql = sprintf("UPDATE reservation SET status='Fullfilled' WHERE rid=%d",$rid);
		$a = executeSqlNonQuery($sql);
		$rows_updated = $a['rows'];
		if($rows_updated != 1){
			return "ERROR: couldn't update reservation status";
		} else {
			return "Reservation marked as fullfilled";
		}
	}

	function IsReserved($bid){ // returns a bool
		$sql = sprintf("SELECT * FROM reservation WHERE (status='Active' OR status='Available') AND bid=%d", $bid);
		$rs = executeSqlQuery($sql);
		$cnt = mysqli_num_rows($rs);
		if($cnt>0){			
			return true;		
		} else{
			return false;
		}
	}	
} // END: class Reservations
?>