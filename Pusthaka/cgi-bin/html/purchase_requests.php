<?php
	$allow = "ALL";
	$PageTitle = "Purchase Requests";
	include('../inc/init.php');

// Retrieve records
$sql = "SELECT * FROM prequests ORDER BY title, dt";
$recordset = executeSqlQuery($sql);
$rowcount = mysqli_num_rows($recordset);

?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin">
      <table width="100%"  border="0">
        <tr>
          <td><img src="images/icon-PurchaseRequests-200x150.jpg" width="200" height="150"></td>
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
<h1>There are <?php echo $rowcount; ?> Purchase Requests</h1>
---&nbsp;<a href="purchase_requests_add.php">Add a new Purchase Request</a>&nbsp;---
<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
      <?php
  $x = 0;
  while ($row = mysqli_fetch_assoc($recordset)) { ?>
      <tr align="left" class="<?php if( ( $x % 2) == 0 ){ echo "td1"; $x += 1; } else {echo "td2"; $x +=1; }?>">
        <td valign="top">&nbsp;</td>
        <td valign="top">		
			[<?php echo substr($row['dt'],0,10);; ?>]&nbsp;<strong><?php echo $row['title']; ?></strong>&nbsp;|&nbsp;<?php echo $row['authors']; ?>&nbsp;|&nbsp;<?php echo $row['subjects']; ?><br>		
			<?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ 
				echo '&nbsp;&nbsp;Subjects:&nbsp;' . $row['subjects'] . '&nbsp;Publisher:&nbsp;' . $row['publisher'] .
					'&nbsp;Price:&nbsp;' . $row['price'] . '&nbsp;Status:&nbsp;' . $row['acquisition_status'] . '<br>';				
		   } ?>
		  </td>
      </tr>
      <tr align="left">
        <td colspan="2" valign="top"><hr></td>
      </tr>
      <?php } ?>
</table>
</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
