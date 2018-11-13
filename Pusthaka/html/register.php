<?php
	$allow = "ALL";
	$PageTitle = "Register";
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
          <td><img src="images/icon-register-200x150.jpg" width="200" height="150"></td>
        </tr>
      </table>	
      <table width="100%"  border="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="<?php echo $_SESSION['back'];  $_SESSION['back'] = (isset($_REQUEST['ID']) && $_REQUEST['ID'] !='')? ($_SERVER['PHP_SELF'] . '?ID=' . $_REQUEST['ID']):$_SERVER['PHP_SELF']; ?>" class="menuLink">back</a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="_about.php" class="menuLink">about</a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="_rules.php" class="menuLink">rules</a></td>
      </tr>
    </table>	  
    </td>
    <td class="content">
<h1>Registering with Pusthaka </h1>
<div class="contentEm1">
  <p><strong>In order to register with Pusthaka: </strong></p>
  <table border="0">
  <tr>
    <td>&gt;</td>
    <td>You should be eligible for library membership according to the rules set by this organization.</td>
  </tr>
  <tr>
    <td>&gt;</td>
    <td>You should have a valid email address</td>
  </tr>
</table>

  <p><strong>If you satisfy the above criteria, follow these steps:</strong></p>
  <table border="0">
    <tr>
      <td>&gt;</td>
      <td>Visit the library</td>
    </tr>
    <tr>
      <td>&gt;</td>
      <td>Tell the library staff at the front desk that you need to create an account with Pusthaka.</td>
    </tr>
    <tr>
      <td>&gt;</td>
      <td>He/she will request your student ID card</td>
    </tr>
    <tr>
      <td>&gt;</td>
      <td>After verifying your identification, your account will be activated and a library number and a login password will be assigned to you.</td>
    </tr>
  </table>
  </div>
<h1>Why Register </h1>
<div class="contentEm2">
  <p>By registering you will be able to access all the library services online, which includes an Online Public Access Catalog, facility to view book availability information online, reserving of books that are currently not available for loan and a host of other features.  </p>
</div>
 <p>&nbsp;</p></td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
