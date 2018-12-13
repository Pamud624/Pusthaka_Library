<?php
	$allow = "ALL";
	$PageTitle = "Add Request";
	include('../inc/init.php');
		
/**** Add new record */
if(isset($_REQUEST['BtnAddNew'])){	
	$title = $_REQUEST['title'];
	$authors = $_REQUEST['authors'];
	$publisher = $_REQUEST['publisher'];
	$subjects = $_REQUEST['subjects'];
	$price = $_REQUEST['price'];
	if(!is_numeric($price)) $price=0;
	
	$mid = $_REQUEST['mid'];
	$dt = date("Y-m-d G:i:s");
	$acquisition_status = 'WAITING';

	$sql = sprintf("INSERT INTO prequests (title, authors, publisher, subjects, price, mid, dt, acquisition_status) " . 
		"VALUES ('%s', '%s', '%s', '%s', %d, %d, '%s', '%s')",
		$title, $authors, $publisher, $subjects, $price, $mid, $dt, $acquisition_status);
	$a = executeSqlNonQuery($sql);
	$rowsUpdated = $a['rows'];
	if($rowsUpdated == 1){
		header("Location: purchase_requests.php?msg=New entry added.");
		exit();
	} else {
		header("Location: purchase_requests.php?msg=Failed to add new entry.");
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
          <td><img src="images/icon-PurchaseRequest-add-200x150.jpg" width="200" height="150"></td>
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
	<h1>Add a new book purchase request </h1>
	<a href='purchase_requests.php'>View All Purchase Requests</a>
	<form action="purchase_requests_add.php" method="post" name="AddNew" class="formNormal" id="AddNew">
	<table border="0" cellpadding="0">
  <tr>
    <td>Title</td>
    <td><input name="title" type="text" size="60" maxlength="60"> 
      * </td>
  </tr>
  <tr>
    <td>Author(s)&nbsp;</td>
    <td><input name="authors" type="text" size="60" maxlength="60"> 
      * </td>
  </tr>
  <tr>
    <td>Subjects</td>
    <td><input name="subjects" type="text" size="60" maxlength="60"> 
      * </td>
  </tr>
  <tr>
    <td>Publisher</td>
    <td><input name="publisher" type="text" size="60" maxlength="60"> 
      * </td>
  </tr>
  <tr>
    <td>Price</td>
    <td><input name="price" type="text" size="10" maxlength="10"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;      </td>
  </tr>
  <tr>
    <td><input type="hidden" name="mid" value='<?php echo $_SESSION['CurrentUser']['mid']; ?>'></td>
    <td><input name="BtnAddNew" type="submit" id="BtnAddNew" value="Submit Request"></td>
  </tr>
</table>
	</form>
<script language="JavaScript">
	var frmvalidator1 = new Validator("AddNew");
	frmvalidator1.addValidation("title","req","Please enter the book title (name of the book)");
	frmvalidator1.addValidation("authors","req","Please enter the name of the author(s)");
	frmvalidator1.addValidation("subjects","req","Please enter the subject(s) this book is recommended for");
	frmvalidator1.addValidation("publisher","req","Please enter the name of the publisher (this would make it easy for us to acquire the book)");
</script></td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
