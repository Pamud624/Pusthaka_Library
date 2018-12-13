<?php
//	$allow = "ADMIN;LIBSTAFF";
$allow = "ADMIN";
	$PageTitle = "Edit Feedback";
	include('../inc/init.php');
		
/**** Update record */
if(isset($_REQUEST['BtnEdit'])){
	$id = $_REQUEST['id'];
	$msg = $_REQUEST['msg'];
	$name = $_REQUEST['name'];
	$email = $_REQUEST['email'];

	$sql = sprintf("UPDATE feedback SET msg='%s', name='%s', email='%s' WHERE id=%d",
		$msg,$name,$email,$id);
	$a = executeSqlNonQuery($sql);
	$rowsUpdated = $a['rows'];
	if($rowsUpdated == 1){
		header("Location: feedback.php?msg=Entry updated.");
		exit();
	} else {
		header("Location: feedback.php?msg=Failed to update entry.");
		exit();
	}
}
/**** END: Update record */

/**** Delete record */
if(isset($_REQUEST['BtnDelete'])){
	$id = $_REQUEST['id'];

	$sql = sprintf("DELETE FROM feedback WHERE id=%d",$id);
	$a = executeSqlNonQuery($sql);
	$rowsUpdated = $a['rows'];
	if($rowsUpdated == 1){
		header("Location: feedback.php?msg=Entry deleted.");
		exit();
	} else {
		header("Location: feedback.php?msg=Failed to delete entry.");
		exit();
	}
}
/**** END: Delete record */



/**** Display record */
if(isset($_REQUEST['ID'])){
	$id = $_REQUEST['ID'];
	$sql = sprintf("SELECT * FROM feedback WHERE id=%d",$id);
	$rs = executeSqlQuery($sql);
	$numrows = mysqli_num_rows($rs);
	if($numrows != 1){
		trigger_error("The feedback entry with ID: $id was not found", E_USER_ERROR);
		exit();
	} 
	$row = mysqli_fetch_assoc($rs);
} else { // no id
	header("Location: feedback.php");
	exit();
}
/**** END: Display record */
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin">
      <table width="100%"  border="0">
        <tr>
          <td><img src="images/icon-feedback-200x150.jpg"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>	
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="<?php echo $_SESSION['back'];  $_SESSION['back'] = (isset($_REQUEST['ID']) && $_REQUEST['ID'] !='')? ($_SERVER['PHP_SELF'] . '?ID=' . $_REQUEST['ID']):$_SERVER['PHP_SELF']; ?>" class="menuLink">back</a></td>
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
      <table width="100%"  border="0">
        <tr>
          <td><table width="100%"  border="0">
              <tr>
                <td class="marginHelpTitle">Search tips </td>
              </tr>
              <tr>
                <td class="marginHelp"><ol>
                  <li>Type your search criteria in the Search boxes. (Separate words by a single space) </li>
                  <li>Specify how you want the results to be sorted. (You may choose the default)</li>
                  <li>Press the search button</li>
                </ol></td>
              </tr>
          </table></td>
        </tr>
      </table>      </td>
    <td class="content"><h1>Edit notice</h1>
	  <form action="feedback_edit.php" method="post" name="AddNew" class="formNormal" id="AddNew">
        <table border="0" cellpadding="0">
          <tr>
            <td>Message</td>
            <td><textarea name="msg" cols="60" rows="4" id="textarea"><?php echo $row['msg']; ?></textarea></td>
          </tr>
          <tr>
            <td>Your name:&nbsp;</td>
            <td><input name="name" type="text" size="60" maxlength="60" value='<?php echo $row['name']; ?>'></td>
          </tr>
          <tr>
            <td>Your email:</td>
            <td><input name="email" type="text" size="60" value='<?php echo $row['email']; ?>'>
        (optional) </td>
          </tr>
          <tr>
            <td><input type="hidden" name="mid" value='<?php echo $_SESSION['CurrentUser']['mid']; ?>'>
			<input type="hidden" name="id" value='<?php echo $row['id']; ?>'>
			</td>
            <td><input name="BtnEdit" type="submit" id="BtnEdit" value="Save Changes">
            &nbsp;&nbsp;&nbsp;
            <input name="BtnDelete" type="submit" id="BtnDelete" value="Delete Entry"></td>
          </tr>
        </table>
      </form>  
	  <script language="JavaScript">
	var frmvalidator1 = new Validator("AddNew");
	frmvalidator1.addValidation("msg","req","Please enter your feedback.");
	frmvalidator1.addValidation("email","email","Please enter a valid email address.");
</script>	

<h1>&nbsp;</h1></td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
