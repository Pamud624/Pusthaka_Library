<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Fines";
	include('../inc/init.php');

	if(isset($_REQUEST['act']) && $_REQUEST['act']=='del'){
		$id = $_REQUEST['id'];
		
		$sql = "delete from payment where pid=$id";		
		executeSqlNonQuery($sql);		
		
		header("Location: " . $_SERVER['PHP_SELF'] . "?period" . $_REQUEST['period'] . "&Year=" . $_REQUEST['Year']);
		exit;
	}
	
	

	// Set Time Period
	if(isset($_REQUEST['period'])){
		$p = $_REQUEST['period'];
		if($p == 'Today'){
			$y = date("Y"); $m = date("m"); $d = date("d");		
			$date_start = $y . '-' . $m . '-' . $d . " 00:00:00";
			$date_end =  $y . '-' . $m . '-' . $d . " 23:59:59";
		} elseif($p == 'Yesterday'){
			$y = date("Y"); $m = date("m"); $d = date("d");
			$date_startTS = mktime(0,0,0,$m,$d-1,$y);
			$date_endTS = mktime(23,59,59,$m,$d-1,$y);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} elseif($p == 'ThisMonth'){
			$y = date("Y"); $m = date("m"); $d = date("d");
			$date_startTS = mktime(0,0,0,$m,1,$y);
			$date_endTS = mktime(23,59,59,$m+1,0,$y);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} elseif($p == 'LastMonth'){
			$y = date("Y"); $m = date("m"); $d = date("d");
			$date_startTS = mktime(0,0,0,$m-1,1,$y);
			$date_endTS = mktime(23,59,59,$m,0,$y);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} elseif($p == 'ThisYear'){
			$y = date("Y"); $m = date("m"); $d = date("d");
			$date_startTS = mktime(0,0,0,1,1,$y);
			$date_endTS = mktime(23,59,59,12,31,$y);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} elseif($p == 'InYear'){
			$Year = $_REQUEST['Year'];
			if($Year <2000){$Year = $Year+2000;}
			$date_startTS = mktime(0,0,0,1,1,$Year);
			$date_endTS = mktime(23,59,59,12,31,$Year);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} else {
			$date_start = '1900-01-01';
			$date_end =  '2900-12-31';
		}
	} else {
			$y = date("Y"); $m = date("m"); $d = date("d");		
			$date_start = $y . '-' . $m . '-' . $d . " 00:00:00";
			$date_end =  $y . '-' . $m . '-' . $d . " 23:59:59";
	}
	

	$sql = sprintf("select p.pid, p.mid, p.dt, p.amount, p.reason, concat(m.title,  ' ', m.firstnames, ' ',  m.surname) AS member_name, m.category  " .
		"FROM ( payment p LEFT JOIN  member m ON p.mid = m.mid)" .
		"WHERE (m.category='STUDENT' AND p.dt > '%s' AND p.dt < '%s') ORDER BY p.dt DESC", $date_start, $date_end);	
	$rs2 = executeSqlQuery($sql);	
	$rs2count = mysqli_num_rows($rs2);
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-Circulation-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table>
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="<?php echo $_SESSION['back'];  $_SESSION['back'] = (isset($_REQUEST['ID']) && $_REQUEST['ID'] !='')? ($_SERVER['PHP_SELF'] . '?ID=' . $_REQUEST['ID']):$_SERVER['PHP_SELF']; ?>" class="menuLink">back</a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="eventlog.php" class="menuLink">event log </a></td>
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
<h1>Choose Time Period</h1><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form1" class="formNormal">
    <table border="0" cellpadding="0">
      <tr>
        <td><div class="contentEm">
          <label>
          <input name="period" type="radio" value="Today" <?php if( !isset($_REQUEST['period']) || ($_REQUEST['period']=='Today')){ echo 'checked';} ?>>
  Today</label>
          <label>
          <input type="radio" name="period" value="Yesterday" <?php if($_REQUEST['period']=='Yesterday'){ echo 'checked';} ?>>
  Yesterday</label>
          <label>
          <input type="radio" name="period" value="ThisMonth" <?php if($_REQUEST['period']=='ThisMonth'){ echo 'checked';} ?>>
  This Month</label>
          <label>
          <input type="radio" name="period" value="LastMonth" <?php if($_REQUEST['period']=='LastMonth'){ echo 'checked';} ?>>
  Last Month</label>
          <label>
          <input type="radio" name="period" value="ThisYear" <?php if($_REQUEST['period']=='ThisYear'){ echo 'checked';} ?>>
  This Year</label>
          <label>
          <input type="radio" name="period" value="InYear" <?php if($_REQUEST['period']=='InYear'){ echo 'checked';} ?>>
  In Year</label>                   
          <input name="Year" type="text" id="Year2" size="4" maxlength="2" value='<?php if($_REQUEST['period']=='InYear'){ echo $Year;} ?>'>
		  <input name="BtnOk" type="submit" value="Display Fine Info"> 
        </div></td>
        </tr>
    </table>
</form>

<h1><?php echo $rs2count; ?>&nbsp;fine entries have occured in the period [<?php echo $date_start; ?> to <?php echo $date_end; ?>]</h1>
<div class="contentEm">
  <table border="0" cellpadding="0" cellspacing="0">
    <?php 
	$x = 0;
	while($row2 = mysqli_fetch_assoc($rs2)){ ?>
    <tr class="<?php if( ( $x % 2) == 0 ){ echo "td1"; $x += 1; } else {echo "td2"; $x +=1; }?>">
      <td><?php 
		echo "[" . $row2['dt'] . "]&nbsp;" .  "Rs. " . $row2['amount'] . "/=&nbsp;::&nbsp;" . $row2['reason'] . "&nbsp;by&nbsp;<a target='_blank' href='member_view.php?ID=" . $row2['mid'] ."'>" . $row2['member_name'] . "</a>&nbsp;&nbsp;" . $row2['category'];
		//echo "&nbsp;&nbsp;" . "<a href='" . $_SERVER['PHP_SELF']  . "?act=del&id=" . $row2['pid'] .  "&period" . $_REQUEST['period'] . "&Year=" . $_REQUEST['Year'] . "'>" . "Delete</a>";
		?>
	  </td>
    </tr>
    <?php } ?>
  </table>
</div>

</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
