<?php
	$allow = "ADMIN;LIBSTAFF;PATRON";
	$PageTitle = "Book Reservations";
	include('../inc/init.php');
	include('../classes/Reservations.php');
	
	/** Reserve Book */
	if(isset($_REQUEST['BtnReserve'])){
		$re = new Reservations;
		$bid = $_REQUEST['bid'];
		$mid = $_REQUEST['mid'];
		$result = $re->MakeReservation($bid, $mid);
		if(substr($result,0,5) == "ERROR"){
			$_SESSION['msg']['title'] = 'Could not Reserve Book!';
			$_SESSION['msg']['msg'] = $result;
			$_SESSION['msg']['backlink'] = $_SERVER['PHP_SELF'] . "?ID=" . $bid;
			header("Location: reservations_book.php?ID=".$bid);
			exit();
		} else {
			header("Location: reservations_book.php?ID=".$bid);
			exit();
		}
	}
	/** END: Reserve Book */

	/** Cancel reservation */
	if(isset($_REQUEST['do']) && ($_REQUEST['do'] == "cancel")){
		$re = new Reservations;
		$rs = $re->GetReservationByRID($_REQUEST['rid']);
		
		if (mysqli_num_rows($rs)==0){ // Invalid rid
			$_SESSION['BackLink'] = $_SERVER['PHP_SELF'] . "?ID=" . $_REQUEST['bid'];
			trigger_error("System error: invalid RID", E_USER_ERROR);
			exit();				
		}
		$row = mysqli_fetch_assoc($rs);
				
		if( ($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type']=="LIBSTAFF") || ($_SESSION['CurrentUser']['mid']==$row['mid']) ){ // Is this operation allowed
			$result = $re->CancelReservation($_REQUEST['rid']);
			header("Location: reservations_book.php?ID=" . $row['bid']);
			exit();					
		} else {
			$_SESSION['BackLink'] = $_SERVER['PHP_SELF'] . "?ID=" . $row['bid'];
			trigger_error("You are not allowed to perform this operation.", E_USER_ERROR);
			exit();		
		}
	}

	// Get book details into $row ////////////////////////////////////////////
	if(!isset($_REQUEST['ID'])){
		header("Location: book_browse.php");
		exit();
	}
	$id = $_REQUEST['ID'];
	$sql = "SELECT * FROM book WHERE bid=" . $id;
	$recordset = executeSqlQuery($sql);
	$rowcount = mysqli_num_rows($recordset);
	if ($rowcount == 0) {
			trigger_error("There is no book with ID =" . $id, E_USER_ERROR);
			exit();
	} else if ($rowcount >1) {
			trigger_error("DATA ERROR: There is more than one book with ID =" . $id, E_USER_ERROR);
			exit();
	}
	$row = mysqli_fetch_assoc($recordset);    
	
	/// Get previous loans details int $rsLH ///////////////////////
	// Get the access numbers for this book
	$sql = "SELECT cid from copy WHERE bid =" . $id;
	$rsCopies = executeSqlQuery($sql);
	$rowcountCopies = mysqli_num_rows($rsCopies);
	if(!($rowcountCopies >= 1)){
			trigger_error("DATA ERROR: There is no copies of book with  ID =" . $id, E_USER_ERROR);
			exit();		
	}
	$crit = "";
	while($r = mysqli_fetch_assoc($rsCopies)){ // Build WHERE clause
		$crit = $crit . " l.copy=" . $r['cid'] . " OR";
	}
	// Remove the last OR
	$crit = substr($crit,0,strlen($str)-2);

	// Get the data
	$sqlLH = sprintf("select l.lid, l.member mid, concat(m.title, ' ',  m.firstnames, ' ', m.surname)  membername, l.copy cid, l.date_loaned, l.date_due, l.date_returned, " .
	"l.loaned_by, c.access_no, b.bid, b.title, b.authors  " .
	"FROM (( (loan l LEFT JOIN copy c ON  l.copy = c.cid) LEFT JOIN book b ON c.bid=b.bid)) " .
	"LEFT JOIN member m ON l.member = m.mid " .
	"WHERE (l.returned=1 AND (%s)) ORDER BY l.date_loaned, c.access_no", $crit);
	$rsLH = executeSqlQuery($sqlLH);
	$rowcountLH = mysqli_num_rows($rsLH);	
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td  class="margin">
	<table width="100%"  border="0" cellpadding="0">
  <tr>
    <td><img src="images/icon-Reservations-add-200x150.jpg" width="200" height="150"></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>
	<table width="100%"  border="0">
	  <tr>
        <td align="center" class="marginLogin">
         &nbsp;
        </td>
      </tr>
    </table>
	</td>
    <td class="content">
<?php if((isset($_SESSION['msg'])) && ($_SESSION['msg'] != "")) { ?>
<h1><?php echo $_SESSION['msg']['title']; ?></h1>
<table border="0">
  <tr>
  <td>
<img src="images/icon-Error-100x75.jpg">  
  </td>
    <td>
		<?php echo stripcslashes($_SESSION['msg']['msg']); ?>
		<?php echo '<br>---------------------------------------------'; ?>
		<?php
			if($_SESSION['msg']['backlink']!=''){ 
				echo "<br><a href='" . $_SESSION['msg']['backlink'] . "'>Back</a>";
			}
		?>
	</td>
  </tr>
</table>
<?php unset($_SESSION['msg']); }?>
	
	<h1>Book Details: <?php echo $row['title']; ?></h1>
<div class="contentEm">
<div class="bg1">
<strong><?php echo  $row['title']; ?></strong>
<?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ ?>
&nbsp;&nbsp;&nbsp;(<a href="book_edit.php?id=<?php echo  $row['bid']; ?>">Edit</a>)
<?php } ?>
<br>
            <?php echo "<strong>Authors:&nbsp;</strong>" .$row['authors'] . "&nbsp;|&nbsp;<strong>Edition:&nbsp;</strong>" .$row['edition'] . "&nbsp;|&nbsp;<strong>Publisher:&nbsp;</strong>" .$row['publisher'] . "&nbsp;|&nbsp;<strong>Year:&nbsp;</strong>" .$row['published_year'] ?><br>
            <?php echo  "<strong>ISBN:&nbsp;</strong>" . $row['isbn'] . " | <strong>Class:&nbsp;</strong>" . $row['class'] .  " | (<strong>Location:&nbsp;</strong>" . $row['location'] . ") | <strong>Subject(s):&nbsp;</strong>" . $row['subjects']; ?><br>

        <?php // Display copies and availabilty
        $sqlCopies = sprintf("SELECT * FROM copy WHERE bid=%d ORDER BY access_no",$row['bid']);
        $recordsetCopies = executeSqlQuery($sqlCopies);
        $NoOfCopies = mysqli_num_rows($recordsetCopies);
        $CopiesString = "<strong>Copies:</strong>";
        while($rowCopies = mysqli_fetch_assoc($recordsetCopies)){
                $cid = $rowCopies['cid'];
                $CopiesString = $CopiesString . " " . $rowCopies['access_no'] . $rowCopies['lending_type'];

                // Check availability
                $sqlLoans = sprintf("SELECT * FROM loan WHERE copy=%d AND returned=0",$cid);
                $recordsetLoans = executeSqlQuery($sqlLoans);
                $NoOfLoans = mysqli_num_rows($recordsetLoans);

                if($NoOfLoans == 0) {
                        $CopiesString = $CopiesString . "(available)";
                } else if ($NoOfLoans == 1 ){
                        $rowLoan = mysqli_fetch_assoc($recordsetLoans);
                        $mid = $rowLoan['member'];
                        $due = $rowLoan['date_due'];

                        // Get member info
                        $sqlMember = sprintf("SELECT * FROM member WHERE mid=%d",$mid);
                        $recordsetMembers = executeSqlQuery($sqlMember);
                        $rowMember = mysqli_fetch_assoc($recordsetMembers);
                        if($rowMember) {
                                $MemberName = $rowMember['title'] . " " . $rowMember['firstnames'] . " " . $rowMember['surname'];
                                $CopiesString = $CopiesString . "(with " . $MemberName . ")";
                        }
                        mysqli_free_result($recordsetMembers);
                } else if ($NoOfLoans > 1 ){
                        $CopiesString = $CopiesString . "(<strong>Data Error</strong>)";
                }
                mysqli_free_result($recordsetLoans);
        }
        mysqli_free_result($recordsetCopies);
?>
        <br>
      <?php echo $CopiesString; ?>
	  </div>
</div>
<h1>Reserve this Book</h1>
<div class="contentEm">Please note that you will be placed at the end of the reserve queue and that you will be notified through email once the book becomes available. Once a copy of the book becomes available the person at the front of the queue is given exactly 24 hours in which to claim the book. If it is not claimed within this period, that reservation automatically expires and the book becomes available to the next person in the queue.
  <form name="frmReserve" method="post" action="">
    <input type="hidden" name="bid" value='<?php echo  $row['bid']; ?>'>
    <input type="hidden" name="mid" value='<?php echo $_SESSION['CurrentUser']['mid']; ?>'>
    <input name="BtnReserve" type="submit" id="BtnReserve" value="Reserve Book">
  </form>
</div>

<?php 	
	$re = new Reservations;
	$rs = $re->GetReservationsBy_bid_status($_REQUEST['ID'],"Available");
	$rows = mysqli_num_rows($rs);	
?> 
<?php if($rows>0){ ?>
<h1>This book is available to the following member(s)</h1>
<div class="contentEm">
<div class="bg1">
<?php while($r = mysqli_fetch_assoc($rs)){ ?>
	<?php echo $r['title'] . " " . $r['firstnames'] . " " . $r['surname'] . " (" . $r['type'] . ")" ?><br>
	Reserved:&nbsp;<strong><?php echo $r['dt_start']; ?></strong>&nbsp;&nbsp;<?php if(($_SESSION['CurrentUser']['login_type']=="ADMIN") || ($_SESSION['CurrentUser']['login_type']=="LIBSTAFF") || ($_SESSION['CurrentUser']['mid']==$r['mid'])){ ?><a href='reservations_book.php?do=cancel&rid=<?php echo $r['rid']; ?>'>Cancel Reservation</a><?php } ?><br>
	The book with Access No:&nbsp;<strong><?php echo $r['access_no']; ?></strong>&nbsp;is available. Offer expires on:&nbsp;<strong><?php echo $r['dt_end']; ?></strong><br>
<?php } ?>
</div>
<?php } ?>

<?php 	
	$re = new Reservations;
	$rs = $re->GetReservationsBy_bid_status($_REQUEST['ID'],"Active");
	$rows = mysqli_num_rows($rs);	
?> 
<?php if($rows>0){ ?>
<h1>Following&nbsp;<?php echo $rows; ?>&nbsp;member(s) have active reservations for this book</h1>
<div class="contentEm">
<div class="bg1">
<?php while($r = mysqli_fetch_assoc($rs)){ ?>
	<strong><?php echo $r['dt_start']; ?></strong>&nbsp;<?php echo $r['title'] . " " . $r['firstnames'] . " " . $r['surname'] . " (" . $r['type'] . ")" ?>&nbsp;
	<?php if(($_SESSION['CurrentUser']['login_type']=="ADMIN") || ($_SESSION['CurrentUser']['login_type']=="LIBSTAFF") || ($_SESSION['CurrentUser']['mid']==$r['mid'])){ ?><a href='reservations_book.php?do=cancel&rid=<?php echo $r['rid']; ?>'>Cancel</a><?php } ?><br>
<?php } ?>
</div>
<?php } ?>

</div>
</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
