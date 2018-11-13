<?php
	$allow = "ALL";
	$PageTitle = "Add Feedback";
	include('../inc/init.php');
		
/**** Add new record */
if(isset($_REQUEST['BtnAddNew'])){
	$mid = $_REQUEST['mid'];
	$msg = $_REQUEST['msg'];
	$dt = date("Y-m-d G:i:s");
	$name = $_REQUEST['name'];
	$email = $_REQUEST['email'];

	$sql = sprintf("INSERT INTO feedback (mid,msg,dt, name,email) VALUES (%d,'%s','%s','%s','%s')",
		$mid,$msg,$dt,$name,$email);
	$a = executeSqlNonQuery($sql);
	$rowsUpdated = $a['rows'];
	if($rowsUpdated == 1){
		header("Location: feedback.php?msg=New entry added.");
		exit();
	} else {
		header("Location: feedback.php?msg=Failed to add new entry.");
		exit();
	}
}
/**** END: Add new record */

?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin">
      <table width="100%"  border="0">
        <tr>
          <td><img src="images/icon-feedback-add-200x150.jpg"></td>
        </tr>
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
    </table>	  
      <table width="100%"  border="0">
        <tr>
          <td align="center" class="marginLogin">
            <?php if (!isset($_SESSION['CurrentUser'])){ ?>
            <form action="_login.php" method="post" name="login" class="marginLogin" id="login">
              <table width="100%"  border="0">
                <tr>
                  <td>Username</td>
                  <td><input name="Username" type="text" class="marginLoginText" id="Username3" size="10"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td><input name="Password" type="password" class="marginLoginText" id="Password4" size="10"></td>
                  <td><input name="btnLogin" type="submit" class="marginLoginButton" id="btnLogin5" value="Login"></td>
                </tr>
              </table>
            </form>
            New user? Please <a href="#">register</a>.
            <?php } else { 
		echo "Welcome " . $_SESSION['CurrentUser']['title'] . " " . $_SESSION['CurrentUser']['firstnames'] . " " . $_SESSION['CurrentUser']['surname'] . " (" . $_SESSION['CurrentUser']['mid'] . ")<br>";
		echo "<a href='_login.php'>logout</a>";
	 } ?>
          </td>
        </tr>
      </table>      
    </td>
    <td class="content">
	

<?php if((isset($_REQUEST['msg'])) && ($_REQUEST['msg'] != "")) { ?>
<table border="0">
  <tr>
    <td class="msg">
		<?php echo $_REQUEST['msg']; ?>
	</td>
  </tr>
</table>
<?php } ?>
	<h1>Enter your comments/observations</h1>
	<form action="feedback_add.php" method="post" name="AddNew" class="formNormal" id="AddNew">
	<table border="0" cellpadding="0">
  <tr>
    <td>Message</td>
    <td><textarea name="msg" cols="60" rows="4" id="textarea"></textarea></td>
  </tr>
  <tr>
    <td>Your name:&nbsp;</td>
    <td><input name="name" type="text" size="60" maxlength="60"></td>
  </tr>
  <tr>
    <td>Your email:</td>
    <td><input name="email" type="text" size="60"> 
      (optional) </td>
  </tr>
  <tr>
    <td><input type="hidden" name="mid" value='<?php echo $_SESSION['CurrentUser']['mid']; ?>'></td>
    <td><input name="BtnAddNew" type="submit" id="BtnAddNew2" value="Submit Feedback"></td>
  </tr>
</table>
	</form>
<script language="JavaScript">
	var frmvalidator1 = new Validator("AddNew");
	frmvalidator1.addValidation("msg","req","Please enter your feedback.");
	frmvalidator1.addValidation("email","email","Please enter a valid email address.");
</script></td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
