<?php
	$allow = "ALL";
	$PageTitle = "Feedback";
	include('../inc/init.php');
		
/**** Browse records */
$LimitN = $_REQUEST['LimitN']; // Number of records per page
$LimitI = $_REQUEST['LimitI']; // Current index

if (!($LimitN > 0)) $LimitN = 20;
if (!($LimitI >= 0)) $LimitI = 0;

/// Retrieve a recordset from the database //////////////////////////////
// Calculate $TotalRecords
if (!isset($_REQUEST['TotalRecords'])){
		$sqlTotal = sprintf("SELECT COUNT(id) FROM feedback");
		$recordsetTotal = executeSqlQuery($sqlTotal);
		$rowTotal = mysqli_fetch_array($recordsetTotal);
		$TotalRecords = $rowTotal[0];
} else {
		$TotalRecords = $_REQUEST['TotalRecords'];
}

/// Recordset Paging
// Navigate records: Set the start index from which to return records
if(isset($_REQUEST['BtnFirst'])){
		$LimitI = 0;
} else if (isset($_REQUEST['BtnLast'])) {
		$LimitI = floor($TotalRecords / $LimitN) * $LimitN;
} else if (isset($_REQUEST['BtnPrevious'])) {
		$LimitI = $LimitI - $LimitN;
		if($LimitI <0) $LimitI = 0;
} else if (isset($_REQUEST['BtnNext'])) {
		$LimitI = $LimitI + $LimitN;
		if($LimitI >= $TotalRecords) $LimitI = 0; // Wrap around to the first page
}

// Retrieve records
$sql = sprintf("SELECT * FROM feedback ORDER BY dt DESC LIMIT %d, %d",
		$LimitI, $LimitN);
$recordset = executeSqlQuery($sql);
$rowcount = mysqli_num_rows($recordset);

// Calculate: $TotalPages, $CurrentPage, $SearchCriteria
$TotalPages = ceil($TotalRecords / $LimitN);
if($LimitI == 0){
		$CurrentPage = 1;
} else{
		$CurrentPage = floor($LimitI/$LimitN) +1;
}
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
<h1>Browse Settings </h1>
<?php include('feedback_inc1.php'); ?>
<h1>Feedback</h1>
---&nbsp;<a href="feedback_add.php">Add Your Feedback</a>&nbsp;---
<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
      <?php
  $x = 0;
  while ($row = mysqli_fetch_assoc($recordset)) { ?>
      <tr align="left" class="<?php if( ( $x % 2) == 0 ){ echo "td1"; $x += 1; } else {echo "td2"; $x +=1; }?>">
        <td valign="top">&nbsp;</td>
        <td valign="top">		
			<strong><?php echo $row['name']; ?></strong><?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ ?>&nbsp;(<?php echo $row['email']; ?>)<?php } ?>&nbsp;on&nbsp;<?php echo $row['dt']; ?></strong>&nbsp;
				<?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ ?>
				<?php if($row['mid']>0) { ?><a href='member_view.php?ID=<?php echo $row['mid']; ?>'>view author</a>&nbsp;<?php } ?><a href='feedback_edit.php?ID=<?php echo $row['id']; ?>'>edit</a>
				<?php } ?><br>
			<?php echo $row['msg']; ?>
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
