<?php
        $allow = "ADMIN;LIBSTAFF";
		$PageTitle = "Add Notice";
        include('../inc/init.php');
		
/**** Add new record */
if(isset($_REQUEST['BtnAddNew'])){
	$mid = $_REQUEST['mid'];
	$msg = $_REQUEST['msg'];
	$dt = date("Y-m-d G:i:s");

	$sql = sprintf("INSERT INTO notice (mid,msg,dt) VALUES (%d,'%s','%s')",
		$mid,$msg,$dt);
	$a = executeSqlNonQuery($sql);
	$rowsUpdated = $a['rows'];
	if($rowsUpdated == 1){
		header("Location: notices.php?msg=New notice added.");
		exit();
	} else {
		header("Location: notices.php?msg=Failed to add new notice.");
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
          <td><img src="images/icon-notices-add-200x150.jpg"></td>
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
	<h1>Post a new notice</h1>
	<form method="post" name="AddNew" class="formNormal" id="AddNew">
      <textarea name="msg" cols="60" rows="4" id="msg"></textarea>
      <input type="hidden" name="mid" value='<?php echo $_SESSION['CurrentUser']['mid']; ?>'>
      <br>
      <input name="BtnAddNew" type="submit" id="BtnAddNew" value="Post Notice">
	</form>
<script language="JavaScript">
	var frmvalidator1 = new Validator("AddNew");
	frmvalidator1.addValidation("msg","req","Please enter a notice to post.");
</script>	
    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
