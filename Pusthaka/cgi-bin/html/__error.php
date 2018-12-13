<?php
    require('../config/setup.php');
    $allow = "ALL";
	$PageTitle = "Pusthaka: Error";
	session_start();
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-Error-200x150.jpg" width="200" height="150"></td>
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
<h1>Sorry, something unexpected happened! </h1>
<?php if(isset($_SESSION['error'])) { ?>
<?php echo $_SESSION['error']['msg']; ?><br>
<a href="<?php echo $_SESSION['error']['backlink']; ?>">Go Back</a><br>
<?php if($DEBUG==true){ ?>
<hr>
<h1>Error Info</h1>
<table border="0">
	<tr>
		<td>Error Type/No:&nbsp;</td>
		<td><?php echo '[' . $_SESSION['error']['type'] . '] #' . $_SESSION['error']['no']; ?></td>
	</tr>

	<tr>
		<td>Error Message:&nbsp;</td>
		<td><strong><?php echo $_SESSION['error']['msg']; ?></strong></td>
	</tr>
		<td>Source Page:&nbsp;</td>
		<td><?php echo $_SESSION['error']['file']; ?></td>
	</tr>
		<td>Source Line:&nbsp;</td>
		<td><?php echo $_SESSION['error']['line']; ?></td>
</table>
<hr>
<h1>Context of the error</h1>
<table border="0">
  <tr>
    <td><strong>Variable&nbsp;</strong></td>
    <td><strong>Value&nbsp;</strong></td>
  </tr>
  <?php foreach($_SESSION['error']['context'] as $key => $val){
  			if(!is_array($val)){
  	 ?>
  <tr>
    <td><?php echo  $key; ?></td>
    <td><?php echo  $val; ?></td>
  </tr>
  <?php } } ?>
</table>
<?php } // if(DEBUG===true)?> 
<?php } // if(isset($_SESSION['error'])) ?>
	</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
