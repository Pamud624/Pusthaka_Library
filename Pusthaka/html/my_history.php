<?php
	$allow = "PATRON;LIBSTAFF;ADMIN";
	$PageTitle = "My History";
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
			$date_startTS = mktime(0,0,0,1,1,$y);
			$date_endTS = mktime(23,59,59,12,31,$y);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
	}
	
	$sql = sprintf("select * FROM eventlog WHERE (dt > '%s' AND dt < '%s') AND (mid_user=%d OR (mid_patron=%d AND (event='ISSUE' OR event='RETURN')))ORDER BY dt DESC", $date_start, $date_end, $_SESSION['CurrentUser']['mid'],$_SESSION['CurrentUser']['mid']);
	$rs = executeSqlQuery($sql);
	$count = mysqli_num_rows($rs);
	
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-MyHistory-200x150.jpg" width="200" height="150"></td>
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
        <td align="center" valign="top"><a href="my_reservations.php" class="menuLink">my reservations </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="my_info.php" class="menuLink">my info</a> </td>
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
          <input name="period" type="radio" value="Today" <?php if($_REQUEST['period']=='Today'){ echo 'checked';} ?>>
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
          <label><input type="radio" name="period" value="ThisYear" <?php if( !isset($_REQUEST['period']) ||($_REQUEST['period']=='ThisYear')){ echo 'checked';} ?>>This Year</label>
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

<h1>My History <?php echo (substr($date_start,0,10)!=substr($date_end,0,10))?' during [' . substr($date_start,0,10) . ' to ' . substr($date_end,0,10) . ']':' on ' . substr($date_start,0,10); ?> includes <?php echo $count; ?>&nbsp;events</h1>
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
