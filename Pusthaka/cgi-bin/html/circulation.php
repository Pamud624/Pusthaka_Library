<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Circulation";
	include('../inc/init.php');

	// Calculate $noIssuedToday and $noReturnedToday
	$y = date("Y"); $m = date("m"); $d = date("d");		
	$date_start = $y . '-' . $m . '-' . $d . " 00:00:00";
	$date_end =  $y . '-' . $m . '-' . $d . " 23:59:59";

	$sql = sprintf("SELECT count(lid) FROM loan WHERE (date_loaned > '%s' AND date_loaned < '%s')",
		$date_start, $date_end );
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noIssuedToday = $r[0];

	$sql = sprintf("SELECT count(lid) FROM loan WHERE returned=1 AND (date_returned > '%s' AND date_returned < '%s')",
		$date_start, $date_end );
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noReturnedToday = $r[0];
	
	// Calculate $noOnLoan
	$sql = sprintf("SELECT count(lid) FROM loan WHERE returned=0");
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noOnLoan = $r[0];
	
	// Calculate $noOverdue
	$sql = sprintf("SELECT count(lid) FROM loan WHERE returned=0 AND date_due < '%s'", date("Y-m-d G:i:s"));
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noOverdue = $r[0];
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
      <table width="100%"  border="0">
        <tr>
          <td align="center" class="marginLogin">
           &nbsp;
          </td>
        </tr>
      </table>
    </td>
    <td> <!-- Content Area -->
<h1>Issues and Returns </h1>
<div class='contentEm'>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td><a href="circulation_irReport.php?IR=Issues">Books issued today</a></td>
    <td>&nbsp;</td>
    <td><?php echo $noIssuedToday;?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="circulation_irReport.php?IR=Returns">Books returned today</a></td>
    <td>&nbsp;</td>
    <td><?php echo $noReturnedToday;?></td>
  </tr>
</table>

</div>
<h1>Loans</h1>
<div class='contentEm'>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td><a href="circulation_loans.php">Books currently on loan</a></td>
    <td>&nbsp;</td>
    <td><?php echo $noOnLoan; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="circulation_loansOD.php">Overdue books</a></td>
    <td>&nbsp;</td>
    <td><?php echo $noOverdue; ?></td>
  </tr>
</table>
</div>

</td> <!-- END: Content Area -->
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
