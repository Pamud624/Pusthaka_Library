<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Event Log";
	include('../inc/init.php');

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
	
	$sql = sprintf("select * FROM eventlog WHERE (dt > '%s' AND dt < '%s') ORDER BY dt DESC", $date_start, $date_end);
	$rs = executeSqlQuery($sql);
	$count = mysqli_num_rows($rs);
	
	$sql_e1 = sprintf("select * FROM eventlog WHERE ((dt > '%s' AND dt < '%s') & (event LIKE 'ISSUE')) ORDER BY dt DESC", $date_start, $date_end);
	$rs_e1 = executeSqlQuery($sql_e1);
	$count_e1 = mysqli_num_rows($rs_e1);
	
	$sql_e2 = sprintf("select * FROM eventlog WHERE ((dt > '%s' AND dt < '%s') & (event LIKE 'RETURN')) ORDER BY dt DESC", $date_start, $date_end);
	$rs_e2 = executeSqlQuery($sql_e2);
	$count_e2 = mysqli_num_rows($rs_e2);
	
	$sql_e3 = sprintf("select * FROM eventlog WHERE ((dt > '%s' AND dt < '%s') & (event LIKE 'RESERVATION_ADDED')) ORDER BY dt DESC", $date_start, $date_end);
	$rs_e3 = executeSqlQuery($sql_e3);
	$count_e3 = mysqli_num_rows($rs_e3);
	
	$sql_e4 = sprintf("select * FROM eventlog WHERE (dt > '%s' AND dt < '%s') & (event LIKE 'RESERVATION_FULFILLED') ORDER BY dt DESC", $date_start, $date_end);
	$rs_e4 = executeSqlQuery($sql_e4);
	$count_e4 = mysqli_num_rows($rs_e4);
	
	$sql_e5 = sprintf("select * FROM eventlog WHERE ((dt > '%s' AND dt < '%s') & (event LIKE 'LOGIN')) ORDER BY dt DESC", $date_start, $date_end);
	$rs_e5 = executeSqlQuery($sql_e5);
	$count_e5 = mysqli_num_rows($rs_e5);
	
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-eventlog-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table>
      <table width="100%"  border="0">
        <tr>
          <td align="center" class="marginLogin">
            &nbsp;
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
<h1>Choose Time Period</h1><form method="post" name="form1" class="formNormal">
    <table border="0" cellpadding="0">
      <tr>
        <td colspan="2"><div class="contentEm">
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
          <label><input type="radio" name="period" value="ThisYear" <?php if($_REQUEST['period']=='ThisYear'){ echo 'checked';} ?>>This Year</label>
          <label><input type="radio" name="period" value="InYear" <?php if($_REQUEST['period']=='InYear'){ echo 'checked';} ?>>In Year</label>
          <input name="Year" type="text" id="Year" size="4" maxlength="4" value='<?php if($_REQUEST['period']=='InYear'){ echo $Year;} ?>'>
        </div></td>
        </tr>
      <tr>
        <td>	
		</td>
        <td><input name="BtnOk" type="submit" value="Display"></td>
      </tr>
    </table>
</form>

<h1> <?php echo $count; ?>&nbsp; Number of events occured during 
<?php echo (substr($date_start,0,10)!=substr($date_end,0,10))?'[' . substr($date_start,0,10) . ' to ' . substr($date_end,0,10) . ']':' on ' . substr($date_start,0,10); ?></h1> 


<table>
<tr>
<td width='160'><strong>Date/Time</strong></td>
<td><strong>Event</strong></td>
<td><strong>Description</strong></td>
</tr>
<?php
while($r=mysqli_fetch_assoc($rs)){ 
	echo '<tr>';
		echo '<td>[' .  $r['dt'] . ']</td>';
		echo '<td><strong>' .  $r['event'] . '</strong></td>';
		echo '<td>' .  $r['description'] . '</td>';
	echo '</tr>';
}
?>
</table>
    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
