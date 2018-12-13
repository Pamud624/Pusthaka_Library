<?php
	$allow = "ALL";
	$PageTitle = "About Pusthaka";
	require('../inc/init.php');

	//Calculate the stats on books
	$sql = "SELECT count(cid) FROM copy";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noCopies = $r[0];

	$sql = "SELECT count(bid) FROM book";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noTitles = $r[0];

	$sql = "SELECT count(mid) FROM member";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noMembers = $r[0];

	$sql = "SELECT count(mid) FROM member WHERE NOT(category='STUDENT')";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noMembersStaff = $r[0];

	$sql = "SELECT count(mid) FROM member WHERE category='STUDENT'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noMembersStudent = $r[0];

	$sql = "SELECT count(lid) FROM loan WHERE returned=0";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noLoans = $r[0];


?>

<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin">
      <table width="100%"  border="0">
        <tr>
          <td><img src="images/icon-Members-200x150.jpg" width="200" height="150"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="100%"  border="0">
        <tr>
          <td><table width="100%"  border="0">
              <tr>
                <td class="marginHelpTitle">Library Stats </td>
              </tr>
              <tr>
                <td class="marginHelp">Books: <strong><?php echo $noCopies; ?></strong> (<strong><?php echo $noTitles; ?></strong> titles) <br>
                  Members: <strong><?php echo $noMembers; ?></strong><br>
                  Books on loan: <strong><?php echo $noLoans; ?></strong><br>
                &nbsp;<br></td>
              </tr>
          </table></td>
        </tr>
      </table>      </td>
    <td class="content"><h1>About Pusthaka 1.0</h1>
<div class="contentEm2">
  <p>Pusthaka, is a web based ILS (Integrated Library System).</p>
</div>
 <p>&nbsp;</p></td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
