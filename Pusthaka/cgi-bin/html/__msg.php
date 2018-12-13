<?php
    require('../config/setup.php');
    $allow = "ALL";
	$PageTitle = "Pusthaka Info";
	session_start();
	
	if(!isset($_SESSION['msg'])){
		header('Location: index.php');
		exit();
	}
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-notice-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
      <table width="100%"  border="0">
        <tr>
          <td align="center" class="marginLogin">
		  <?php 
			if (isset($_SESSION['CurrentUser'])){ 
				echo "Welcome " . $_SESSION['CurrentUser']['title'] . " " . $_SESSION['CurrentUser']['firstnames'] . " " . $_SESSION['CurrentUser']['surname'] . " (" . $_SESSION['CurrentUser']['mid'] . ")<br>";
				echo "<a href='_login.php'>logout</a>";
			}
		  ?>
          </td>
        </tr>
      </table>
    </td>
    <td class="content">
<h1><?php echo $_SESSION['msg']['title']; ?></h1>
<?php echo $_SESSION['msg']['msg']; ?><br>
<?php
	if($_SESSION['msg']['backlink'] != '#'){
		echo "<a href='" . $_SESSION['msg']['backlink'] .  "'>" . $_SESSION['msg']['backlinkTitle'] . '</a>';
	}
?>
<hr />
<form name="form1" method="post" action="ir1-inventory.php">
<input name="inventory" type="submit" value="Current Inventory List">
</form>

    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
<?php unset($_SESSION['msg']); ?>