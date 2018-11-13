
<?php
 include('../inc/util.php');
$p = $_GET['q'];
// echo $p;
	$sql_cs = "";

	/*cs active students */
	switch($p){
		case "cs": 
			$sql = "select * FROM member WHERE (expired='0') AND  ((type LIKE '%UCSC B% CS') OR (type LIKE 'UCSC B% (CS)%') OR (type LIKE '%UCSC B% (CS)') ) ORDER BY type DESC";
			break;
		case "se": 
			$sql = "select * FROM member WHERE ((expired='0') AND  ((type LIKE '%UCSC B% SE') OR (type LIKE '%UCSC B% (SE)%')))ORDER BY type DESC";
			break;
		case "is": 
			$sql = "select * FROM member WHERE ((expired='0') AND  ((type LIKE '%UCSC B% IS') OR (type LIKE '%UCSC B% (IS)%')))ORDER BY type DESC";
			break;
		case "ict": 
			$sql = "select * FROM member WHERE ((expired='0') AND  ((type LIKE '%UCSC B% ICT') OR (type LIKE '%UCSC B% %ICT%')))ORDER BY type DESC";
			break;
		case "mcs": 
			$sql = "select * FROM member WHERE ((expired='0') AND  ((type LIKE '%MSc B% CS') OR (type LIKE '%MSc% CS') OR(type LIKE '%MSc B1') OR (type LIKE '%MSc B2')))ORDER BY type DESC";
			break;
		case "mit": 
			$sql = "select * FROM member WHERE ((expired='0') AND  ((type LIKE '%MSc B% IT') OR (type LIKE '%MSc% IT')))ORDER BY type DESC";
			break;
		case "mis": 
			$sql = "select * FROM member WHERE ((expired='0') AND  (type LIKE '%MIS B%') )ORDER BY type DESC";
			break;
		case "mp": 
			$sql = "select * FROM member WHERE ((expired='0') AND  (type LIKE 'Mphiil') )ORDER BY surname ASC";
			break;
		case "ac": 
			$sql = "select * FROM member WHERE ((expired='0') AND  (type LIKE 'Staff') )ORDER BY surname ASC";
			break;
		case "ad": 
			$sql = "select * FROM member WHERE ((expired='0') AND  ((type LIKE 'Non Academic Staff') AND category = 'ASS_LEC'))ORDER BY type DESC";
			break;
		case "nac": 
			$sql = "select * FROM member WHERE ((expired='0') AND  ((type LIKE 'Non Academic Staff') AND category = 'STUDENT'))ORDER BY type DESC";
			break;
		case "lib": 
			$sql = "select * FROM member WHERE ((expired='0') AND  (type LIKE 'Library Staff') OR (type LIKE 'System Administrator'))ORDER BY type DESC";
			break;
		
	}
	
	$rs = executeSqlQuery($sql);
	$count = mysql_num_rows($rs);
	
?>

<h1><?php echo $count; ?>&nbsp;members are currently active </h1> 
<table  border="1" cellspacing="10" cellpadding="3">
<tr>
<td><strong>Title</strong></td>
<td><strong>First Name</strong></td>
<td><strong>Last Name</strong></td>
<td><strong>Type</strong></td>
<td><strong>nic</strong></td>
<td><strong>reg_no</strong></td>
<td><strong>Address</strong></td>
<!--<td><strong>sex</strong></td>
<td><strong>category</strong></td>
<td><strong>email</strong></td>
<td><strong>barcode</strong></td>-->
</tr>
<?php
while($r=mysql_fetch_assoc($rs)){ 
	echo '<tr>';
		echo '<td>' .  $r['title'] . '</td>';
		echo '<td>' .  $r['firstnames'] . '</td>';
		echo '<td>' .  $r['surname'] . '</td>';
		echo '<td>' .  $r['type'] . '</td>';
		echo '<td>' .  $r['nic'] . '</td>';
		echo '<td>' .  $r['reg_no'] . '</td>';
		echo '<td>' .  $r['address'] . '</td>';
		/*echo '<td>' .  $r['sex'] . '</td>';
		echo '<td>' .  $r['category'] . '</td>';
		echo '<td>' .  $r['email'] . '</td>';
		echo '<td>' .  $r['barcode'] . '</td>';*/
	echo '</tr>';
}
?>
</table>
