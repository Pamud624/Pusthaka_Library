
<?php
 include('../inc/util.php');
$p = $_GET['q'];
echo $p;
/*$p = $_GET['r'];
echo $r;*/


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
			//$Year = $_REQUEST['Year'];
			if($Year <2000){$Year = $Year+2000;}
			$date_startTS = mktime(0,0,0,1,1,$Year);
			$date_endTS = mktime(23,59,59,12,31,$Year);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} else {
			$date_start = '1900-01-01';
			$date_end =  '2900-12-31';
		}
	
	$sql = sprintf("select * FROM eventlog WHERE (dt > '%s' AND dt < '%s') ORDER BY dt DESC", $date_start, $date_end);
	$rs = executeSqlQuery($sql);
	$count = mysqli_num_rows($rs);
	
?>

<h1><?php echo $count; ?>&nbsp;events have occurred <?php echo (substr($date_start,0,10)!=substr($date_end,0,10))?' during [' . substr($date_start,0,10) . ' to ' . substr($date_end,0,10) . ']':' on ' . substr($date_start,0,10); ?></h1>
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
