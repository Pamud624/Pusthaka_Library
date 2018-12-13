<?php
	$allow = "ADMIN;LIBSTAFF;PATRON";
	$PageTitle = " My Reservations";
	include('../inc/init.php');
	include('../classes/Reservations.php');

	/** Cancel reservation */
	if($_REQUEST['do'] == "cancel"){
		$re = new Reservations;
		$rs = $re->GetReservationByRID($_REQUEST['rid']);
		
		if (mysqli_num_rows($rs)==0){ // Invalid rid
			$_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
			trigger_error("System error: invalid RID", E_USER_ERROR);
			exit();
		}
		$row = mysqli_fetch_assoc($rs);
				
		if( ($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type']=="LIBSTAFF") || ($_SESSION['CurrentUser']['mid']==$row['mid']) ){ // Is this operation allowed
			$result = $re->CancelReservation($_REQUEST['rid']);
			header("Location: my_reservations.php");
			exit();					
		} else {
			$_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
			trigger_error("You are not allowed to perform this operation.", E_USER_ERROR);
			exit();		
		}
	}

	$re = new Reservations;
	$rs = $re->GetReservedBooks_byMID($_SESSION['CurrentUser']['mid']);
	$rsNo = mysqli_num_rows($rs);
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-MyReservations-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table>
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="my_loans.php" class="menuLink">my loans </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="my_info.php" class="menuLink">my info</a> </td>
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
    </td>
    <td>

<?php if((isset($_REQUEST['msg'])) && ($_REQUEST['msg'] != "")) { ?>
<table border="0">
  <tr>
    <td class="msg">
		<?php echo stripcslashes($_REQUEST['msg']); ?>
	</td>
  </tr>
</table>
<?php } ?>
<h1>You have&nbsp;<?php echo $rsNo; ?>&nbsp;reservations </h1>
<div class="contentEm">
<div class="bg2">
<?php
if($rsNo <> 0){
	while($book = mysqli_fetch_assoc($rs)){
			echo "[" . $book['dt_start'] . "]&nbsp;<strong>" . $book['status'] . 
				"</strong>&nbsp;<a href='reservations_book.php?ID=" . $book['bid'] ."'>" . $book['title'] . 
				"</a>&nbsp;<a href='my_reservations.php?do=cancel&rid=" . $book['rid'] . "'>Cancel</a>" . "<br>";
	}
} else {
	echo "<strong>There are no reservations.";
}
?>		
</div>
</div>
</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
