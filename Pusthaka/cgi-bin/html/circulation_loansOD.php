<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Loans";
	include('../inc/init.php');
	

	// Calculate $noOverdue
	$sql = sprintf("SELECT count(lid) FROM loan WHERE returned=0 AND date_due < '%s'", date("Y-m-d G:i:s"));
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noOverdue = $r[0];

	// Retrieve the set of members who have overdue books ($rsMembers)
	$sql = sprintf("SELECT distinct(member) AS mid FROM loan WHERE returned=0 AND date_due < '%s'", date("Y-m-d G:i:s"));
	$rsMembers = executeSqlQuery($sql);
	$noMembers = mysqli_num_rows($rsMembers);
/************************************************************************
Returns a HTML string containg a table.
The table has information about overdue books that are with the member $rMember
************************************************************************/
function displayOverdueBooks($mid){
	$sql = 'SELECT * FROM member WHERE mid=' . $mid;
	$rs = executeSqlQuery($sql);
	$rMember = mysqli_fetch_assoc($rs);
	
	$sql = sprintf("SELECT l.*, c.*, b.* FROM " .
		"(loan l LEFT JOIN copy c ON l.copy=c.cid) LEFT JOIN book b ON c.bid=b.bid WHERE l.returned=0 AND l.date_due < '%s' AND l.member=%d",
			date("Y-m-d G:i:s"), $rMember['mid']);
	$rsBooks = executeSqlQuery($sql);
	$noBooks = mysqli_num_rows($rsBooks);
	
	$str1 = 
	'<table>' .
	'<tr>' .
		'<td colspan=3><strong>' . $rMember['title'] . ' ' . $rMember['firstnames'] . ' ' . $rMember['surname'] . '</strong></td>' .
	'</tr>';
	$str2 = '';
	while($rBook = mysqli_fetch_assoc($rsBooks)){
		$str2 = $str2 .
		'<tr>' .
			'<td>' . '[' . $rBook['access_no'] . ']' . '</td>' .
			'<td>' . '(' . date("y-m-d", strtotime($rBook['date_loaned'])) . '&nbsp;&raquo;&nbsp;' . date("y-m-d", strtotime($rBook['date_due'])) . ')' . '</td>' .
			'<td>' .  "<a href='book_view.php?ID=" . $rBook['bid'] . "'>" . $rBook['title'] . '</a>' . '</td>' .			
		'</tr>';	
	}	
	$str3 = '</table>';
	return $str1 . $str2 . $str3;
}	
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
        <td align="center" valign="top"><a href="circulation_loans.php" class="menuLink">All Loans</a></td>
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
<h1>There are <?php echo $noOverdue; ?> overdue books (among <?php echo $noMembers; ?> members)</h1>
<div class='contentEm'>
<a href='?do=email'>Send reminder letters to all members</a>
</div>
<div class='contentEm'>
<?php 
	while($rMember=mysqli_fetch_assoc($rsMembers)){ 
		echo displayOverdueBooks($rMember['mid']);
	}	
?>


</div>
</td> 
<!-- END: Content Area -->
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
