<?php
	$allow = "ADMIN;LIBSTAFF;PATRON";
	$PageTitle = " My Info";
	include('../inc/init.php');
	$mem = new Members;

	// Update my_info /////////////////////////////////////////////////////
	if(isset($_REQUEST['BtnUpdate'])){
		$mid = $_REQUEST['mid'];
		$address = $_REQUEST['address'];
		$nic = $_REQUEST['nic'];
		$reg_no = $_REQUEST['reg_no'];
		$phone = $_REQUEST['phone'];
		$email = $_REQUEST['email'];
		$index_no = $_REQUEST['index_no'];
		
	
		  $sql = sprintf("update member set address='%s', nic='%s', reg_no='%s', phone='%s', email='%s', " .
			"index_no='%s' WHERE mid=%d", $address, $nic, $reg_no, $phone, $email, $index_no, $mid);
		  $a = executeSqlNonQuery($sql);
		  $rowcount = $a['rows'];
		  if ($rowcount <> 1) {
			  $_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
			  trigger_error("An error occured while updating member: ID=" . $mid, E_USER_ERROR);
			  exit();
		  } else {
				$_SESSION['msg'] = "Your information was updated.";
				header("Location: my_info.php");
				exit();
		  }
	} //END: update my_info


	// Change Password /////////////////////////////////////////////////////
	if(isset($_REQUEST['BtnChangePassword'])){
		$mid = $_REQUEST['mid'];	
		$password = $_REQUEST['pwd'];
			
		  $sql = sprintf("update member set password='%s' WHERE mid=%d", md5($password), $mid);
		  $a = executeSqlNonQuery($sql);
		  $rowcount = $a['rows'];
		  if ($rowcount <> 1) {
			  $_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
			  trigger_error("An error occured while changing password: ID=" . $mid, E_USER_ERROR);
			  exit();
		  } else {
				$_SESSION['msg'] = "Your password was changed.";
				header("Location: my_info.php");
				exit();
		  }
	} //END: Change Password

	$row = $mem->getByID($_SESSION['CurrentUser']['mid']);
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><img src="images/icon-MyInfo-200x150.jpg" width="200" height="150">
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="my_loans.php" class="menuLink">my loans </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="my_reservations.php" class="menuLink">my reservations </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="my_history.php" class="menuLink">my history </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="book_search.php" class="menuLink">opac</a></td>
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
    </td><td>

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
<h1>My Current Info</h1>
<div class="contentEm">
<div class="bg1">
<?php 
	echo  $row['title'] . " " . $row['firstnames'] . " " . $row['surname'] . "&nbsp;<strong>(" .$row['mem_no'] . ")</strong>"; ?><br>
            <?php echo "<strong>Member#:&nbsp;</strong>" .$row['mid'] . "&nbsp;(Old#:&nbsp;" . $row['mem_no'] . ")|&nbsp;<strong>Type:&nbsp;</strong>" .$row['type'] . "&nbsp;|&nbsp;<strong>Reg#:&nbsp;</strong>" .$row['reg_no'] . "&nbsp;|&nbsp;<strong>Index#:&nbsp;</strong>" .$row['index_no'] . "&nbsp;|&nbsp;<strong>NIC#:&nbsp;</strong>" .$row['nic']; ?><br>
        <?php echo  "<strong>Email:&nbsp;</strong>" . $row['email'] . " | <strong>Phone:&nbsp;</strong>" . $row['phone'] . " | <strong>Address:&nbsp;</strong>" . $row['address']; ?></div>
</div>
<h1>Change My Info</h1>
<form action="my_info.php" method="post" name="my_info" class="formNormal" id="my_info">                
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><span class="emphtext">
      <input name="mid" type="hidden" value="<?php echo $row['mid']; ?>">
    </span></td>
    <td>Address&nbsp;</td>
    <td colspan="5"><input name="address" type="text" id="address" value="<?php echo $row['address']; ?>" size="90"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Phone</td>
    <td>      <input name="phone" type="text" id="phone" value="<?php echo $row['phone']; ?>" size="15"></td>
    <td>&nbsp;&nbsp;Email</td>
    <td colspan="3"><input name="email" type="text" id="email" value="<?php echo $row['email']; ?>" size="53"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>NIC</td>
    <td><input name="nic" type="text" id="nic" value="<?php echo $row['nic']; ?>" size="15"></td>
    <td>&nbsp;&nbsp;Reg#</td>
    <td><input name="reg_no" type="text" id="reg_no" value="<?php echo $row['reg_no']; ?>" size="15"></td>
    <td>Index#</td>
    <td><input name="index_no" type="text" id="index_no" value="<?php echo $row['index_no']; ?>" size="15"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input name="BtnUpdate" type="submit" id="BtnUpdate" value="Save Changes"></td>
    <td>&nbsp;</td>
    <td><input name="BtnReset" type="reset" id="BtnReset" value="Reset"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<script language="JavaScript">
	var frmvalidator1 = new Validator("my_info");
	frmvalidator1.addValidation("email","email","Please enter a valid email address.");
</script>
<h1>Reset My Password </h1>
<form action="my_info.php" method="post" name="resetpwd" class="formNormal" id="resetpwd"  onSubmit="return checkPwd()" >
<table border="0" cellpadding="0">
    <tr>
      <td>New Password </td>
      <td><input name="pwd" type="password" id="pwd"></td>
    </tr>
    <tr>
      <td>Retype New Password&nbsp; </td>
      <td><input name="pwd2" type="password" id="pwd2"></td>
    </tr>
    <tr>
      <td><span class="emphtext">
        <input name="mid" type="hidden" value="<?php echo $row['mid']; ?>">
      </span></td>
      <td><input name="BtnChangePassword" type="submit" id="BtnChangePassword" value="Change Password"></td>
    </tr>
  </table>
</form>

</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
