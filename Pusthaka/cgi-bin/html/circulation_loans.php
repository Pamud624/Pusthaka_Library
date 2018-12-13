<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Loans";
	include('../inc/init.php');
	
	$sql = sprintf("SELECT l.*, m.*, c.*, b.* FROM " .
		"((loan l LEFT JOIN member m ON l.member=m.mid) LEFT JOIN copy c ON l.copy=c.cid) LEFT JOIN book b ON c.bid=b.bid WHERE returned=0");
	$rsBooks = executeSqlQuery($sql);
	$noOnLoan = mysqli_num_rows($rsBooks);
	

?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-Circulation-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table>
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="<?php echo $_SESSION['back'];  $_SESSION['back'] = (isset($_REQUEST['ID']) && $_REQUEST['ID'] !='')? ($_SERVER['PHP_SELF'] . '?ID=' . $_REQUEST['ID']):$_SERVER['PHP_SELF']; ?>" class="menuLink">back</a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="circulation_loansOD.php" class="menuLink">overdue loans</a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="eventlog.php" class="menuLink">event log </a></td>
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
    </td>
    <td> <!-- Content Area -->
<h1>There are <?php echo $noOnLoan; ?> books on loan</h1>
<div class='contentEm'>
<table border="0" cellspacing="0" cellpadding="0">
<?php while($rBook=mysqli_fetch_assoc($rsBooks)){ ?>
  <tr>
    <td>[<?php echo $rBook['access_no']; ?>]&nbsp;</td>
    <td>(<?php echo date("y-m-d", strtotime($rBook['date_loaned'])) ; ?>&nbsp;&raquo;&nbsp;<?php echo date("y-m-d", strtotime($rBook['date_due'])) ; ?>)&nbsp;</td>
    <td><a href="book_view.php?ID=<?php echo $rBook['bid'];?>"><?php echo $rBook['title']; ?></a>&nbsp;</td>
    <td><?php echo $rBook['']; ?>&nbsp;</td>
    <td><?php echo $rBook['']; ?>&nbsp;</td>
  </tr>
<?php } ?> 
</table>

</div>
</td> 
<!-- END: Content Area -->
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
