<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Current Inventory";
	include('../inc/init.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Pusthaka: <?php echo $PageTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php include("../inc/top_old.php"); ?>
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
        <td align="center" valign="top"><a href="ir1-inventory.php">Back to Checking</a> </td>		
      </tr>
      <tr>
        <td align="center" valign="top"><a href="ir1-inventory-list.php" class="menuLink">Checked-in (by title) </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="ir1-inventory-list-accessno.php" class="menuLink">Checked-in (by acc#) </a></td>
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
<h1>Books Currently Checked In</h1>
<?php
//	$sql="SELECT cc.cid, c.bid, c.access_no, b.title FROM (copy_check cc LEFT JOIN copy c ON cc.cid=c.cid) LEFT JOIN book b ON c.bid = b.bid WHERE cc.name='201801' AND (NOT c.access_no='') ORDER BY CONCAT(10000-c.access_no,c.access_no) DESC";
$sql="SELECT cc.cid, c.bid, c.access_no, b.title FROM (copy_check cc LEFT JOIN copy c ON cc.cid=c.cid) LEFT JOIN book b ON c.bid = b.bid WHERE cc.name='201901' AND (NOT c.access_no='') ORDER BY c.access_no+0";
	$rs = executeSqlQuery($sql);
	$cnt = mysql_num_rows($rs);
	echo "Total: $cnt books<hr />
	while($r = mysql_fetch_assoc($rs)){
		echo "[{$r['access_no']}] {$r['title']} <a href=\"book_edit.php?ID={$r['bid']}\">Edit</a><br />\n";
	}	
?>
</td>
          </tr>
    </table>
	</td>
  </tr>
</table>
<?php include("../inc/bottom_old.php"); ?>
</body>
</html>
