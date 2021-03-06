<?php
//	$allow = "ADMIN;LIBSTAFF";
$allow = "ADMIN";
	$PageTitle = "Inventory Check 2007 Held 2008";
	include('../inc/init.php');

	
	/* get the copy from the database */
	$bookCopy = null;

	// Manual Entry
	if(isset($_REQUEST['ManualNumber'])){
		$n = $_REQUEST['ManualNumber'];
		if(is_numeric($n)){
			//Identify book. (Get CID for the Access Number)
			$sql = sprintf("SELECT * FROM copy WHERE access_no='%s'", $n);
			$rs = executeSqlQuery($sql);
			$cnt = mysqli_num_rows($rs);
			if($cnt ==1){
				$bookCopy = mysqli_fetch_assoc($rs);
			} else {
				$_SESSION['msg'] = "There is no book with the specified access number: " . $n;
				header("Location: " . $_SERVER['PHP_SELF'] . "?focus=ManualNumber");
				exit();
			}
		} else{
			$_SESSION['msg'] = 'Book Access number should be an integer.<br>You entered (<strong>' . $n . '</strong>)';
			header("Location: " . $_SERVER['PHP_SELF'] . "?focus=ManualNumber");
			exit();
		}
	}


	// Barcode Entry
	if(isset($_REQUEST['Number'])){
		$n = $_REQUEST['Number'];
		if (strtoupper(substr($n,0,1)) == "A") { //This is the barcode of an access number
			//Identify book. (Get CID for the Barcode)
			$sql = sprintf("SELECT * FROM copy WHERE barcode='%s'", $n);
			$rs = executeSqlQuery($sql);
			$cnt = mysqli_num_rows($rs);
			if($cnt==1){
				$bookCopy = mysqli_fetch_assoc($rs);
			} else {
				$_SESSION['msg'] = "There is no book with this barcode: " . $n .
					"<br/>You may need to <a href='book_search.php'>update the barcode</a> for this book.";
				header("Location: " . $_SERVER['PHP_SELF'] . "?focus=Number");
				exit();
			}
		} else {// barcode not of a book
			$_SESSION['msg'] = "The barcode is in the wrong format. (Book barcodes start with 'A'): " . $n;
			header("Location: " . $_SERVER['PHP_SELF'] . "?focus=Number");
			exit();
		}
	}

	if(!is_null($bookCopy)){
		/* Get the book for this 'book copy' */ 
		$sql2 = "SELECT * FROM book WHERE bid=" . $bookCopy['bid'];
		$recordset = executeSqlQuery($sql2);
		$rowcount = mysqli_num_rows($recordset);
		if ($rowcount == 0) {
				trigger_error("There is no book with ID =" . $bookCopy['bid'], E_USER_ERROR);
				exit();
		} else if ($rowcount >1) {
				trigger_error("DATA ERROR: There is more than one book with ID =" . $bookCopy['bid'], E_USER_ERROR);
				exit();
		}
		$book = mysqli_fetch_assoc($recordset);    
		
		$msg = "<h1>Checking in the book: [{$bookCopy['access_no']}] {$book['title']}</h1>";
		/* if this copy is notmarked during this inventory taking mark it */
		$sql3 = "SELECT * FROM copy_check WHERE name='2007h08' AND cid=" . $bookCopy['cid'];
		$rs3 = executeSqlQuery($sql3);
		$rowcount3 = mysqli_num_rows($rs3);
		if($rowcount3>0){ // already checked-in
			$r3 = mysqli_fetch_assoc($rs3);
			$msg .= "This book is already checked-in at {$r3['datetime']}<br />";
		} else {
			$current_time = date("Y-m-d G:i:s");
			$sql4 = sprintf("INSERT into copy_check (name, datetime, cid, checked, mid, comments) VALUES ('2007h08','%s',%d,1,%d,'')",
				$current_time, $bookCopy['cid'], $_SESSION['CurrentUser']['mid']);
			$a = executeSqlNonQuery($sql4);
			$rowsUpdated = $a['rows'];
			if($rowsUpdated != 1){
				$msg .= "Failed to update database<br />";
			}else{
				$msg .= "This " . 
					"<a target='_new' href='book_edit.php?ID={$bookCopy['bid']}'>book</a><br />"
					 . " was successfully checked-in!<hr />";
			}
		}
		
		//$msg .= "<input type='button' name='' id='' onclick=\"location.href('ir07-inventory.php');\" />";
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Pusthaka: <?php echo $PageTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/styles.css" rel="stylesheet" type="text/css">
</head>

<body onload="document.getElementById('Number').focus();">
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin">
<table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-eventlog-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top">Current Inventory</td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="ir07-inventory-list.php" class="menuLink">Checked-in (by date) </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="ir07-inventory-list-accessno.php" class="menuLink">Checked-in (by acc#) </a></td>
      </tr>
    </table>
	<table width="100%"  border="0">
      <tr>
        <td align="center" class="marginLogin">
          <?php if (isset($_SESSION['CurrentUser'])){
				echo "Welcome " . $_SESSION['CurrentUser']['title'] . " " . $_SESSION['CurrentUser']['firstnames'] . " " . $_SESSION['CurrentUser']['surname'] . " (" . $_SESSION['CurrentUser']['mid'] . ")<br>";
				echo "<a href='_login.php'>logout</a>";
	 		} ?>
        </td>
      </tr>
    </table>
	<table width="100%"  border="0">
      <tr>
        <td><table width="100%"  border="0">
            <tr>
              <td class="marginHelpTitle">help </td>
            </tr>
            <tr>
              <td class="marginHelp"><strong>To mark a book as present in the library.</strong><br>
  You may:<br>
  1) Scan the barcode on the book.<br>
  2) OR: manually enter the accesss number of the book.</td>
            </tr>
        </table></td>
      </tr>
    </table>
	</td>
    <td valign="top">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
<td class="contents">
<h1>Scan Barcode of Book </h1>
<form action="" method="post" name="ir1" class="formNormal" id="ir1">
                <table border="0" cellspacing="0" cellpadding="0">

          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="Number" type="text" id="Number"><input name="Number2" type="text" id="Number2" size="1" disabled>
              &nbsp;&nbsp;
              <input name="BtnNext" type="submit" value="Check In"></td>
          </tr>
        </table>
            </form>

<h1>Or Enter the Access Number of Book </h1>
<form action="" method="post" name="ir1b" class="formNormal" id="ir1b">
                <table border="0" cellspacing="0" cellpadding="0">

          <tr>
            <td>&nbsp;</td>
            <td><input name="ManualNumber" type="text" id="ManualNumber">
              &nbsp;&nbsp;
              <input name="BtnManualNext" type="submit" value="Check In"> </td>
            <td>&nbsp;</td>
          </tr>
        </table>
</form>
<h1>Inventory Reports</h1>
<ul>
	<li><a href="ir07-inventory-list-full-missing-REALLY.php">The final online Inventory Report- LOOKING FOR THESE</a></li>
	<li><a href="ir07-inventory-list-full-missing-LOAN.php">The final online Inventory Report- CURRENTLY ON LOAN OR SEEN</a></li>
	<li><a href="ir07-inventory-list-full-missing.php">The final online Inventory Report- BOTH</a></li>
	<li> --- </li>
	<li><a href="ir07-inventory-list-accessno.php">All checked in books (by Access No)</a></li>
	<li><a href="ir07-inventory-list.php">The last 100 books that were checked in</a></li>
</ul>
<h1>Reports by Copy Status</h1>
<form action="ir07-reports.php" method="post">
Copy status: 
<select name="copy_status" id="copy_status">				  
<option value="OK">OK (Book is present and not damaged)</option>
<option value="Damaged">Damaged (Book is Damaged)</option>
<option value="L">L (Lost by library)</option>
<option value="S">S (Borrowed by staff)</option>
<option value="SL">SL (Lost by staff)</option>
<option value="S1">S1 (Borrowed by staff on study leave)</option>
<option value="S2">S2 (Borrowed by former staff)</option>
<option value="S3">S3 (Borrowed by students)</option>
<option value="S3L">S3L (Lost by students)</option>
<option value="W">W (Withdrawn)</option>
<option value="TW">TW (Temporarily Withdrawn)</option>
</select>
<input name="BtnGenReport" type="submit" value="Generate Report">
</form>


			</td>
          </tr>
    </table>
<?php if((isset($_SESSION['msg'])) && ($_SESSION['msg'] != "")) { ?>
<table border="0">
  <tr>
    <td class="msg">
		<?php
			echo stripcslashes($_SESSION['msg']);
			unset($_SESSION['msg']);
		?>
	</td>
  </tr>
</table>
<?php } ?>

<?php 
if(strlen($msg)>0) {
	echo $msg;
}
?>

	</td>
  </tr>
</table>
<?php include("../inc/bottom2.php"); ?>
</body>
</html>
