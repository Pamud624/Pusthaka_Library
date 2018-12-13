<?php
$allow = "ADMIN;LIBSTAFF";
$PageTitle = "Inventory Reports Check 2007 Held 2008";
include('../inc/init.php');

$con = mysqli_connect("localhost","root","","pusthaka_ucsc");
//mysql_select_db("pusthaka", $con);

$copy_status = $_REQUEST['copy_status'];

/*
// Books not returned
$sql = "select c.access_no, b.title, l.date_loaned,  concat(m.surname, ', ', m.firstnames, ' (', m.title, ')') as fullname, m.type from loan l, copy c, book b, member m where l.copy = c.cid AND c.bid = b.bid AND l.member = m.mid  AND l.date_loaned < '2007-02-20' and l.returned=0";
$filename = "not-returned.txt";
*/

$sql = "SELECT c.access_no, b.title,  b.authors FROM copy  c LEFT JOIN book b ON c.bid = b.bid where c.copy_status='$copy_status' ORDER BY (c.access_no + 100000);";

$rs = mysqli_query($con,$sql) or die(mysqli_error());
$n = mysqli_num_rows($rs);


/*
// Generate a file
$filename = "$copy_status.txt";
if(is_writable($filename)) {
	unlink($filename);
}
$f1 = fopen($filename,"a+");
if($n==0){ // Not found
	fwrite($f1,"No records");
} else{ // Multiple entries
	while($r = mysqli_fetch_assoc($rs)) {
		//$str = "{$r['access_no']}|{$r['title']}|{$r['authors']}\n";
		$str = "{$r['access_no']}|{$r['title']}|{$r['date_loaned']}|{$r['fullname']}|{$r['type']}\n";
		fwrite($f1,$str);
	}
}
fclose($f1);
*/


?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Pusthaka: <?php echo $PageTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/styles.css" rel="stylesheet" type="text/css">
</head>

<body onload="document.getElementById('Number').focus();">
<h1><?= "There are $n books with status = '$copy_status'"; ?> </h1>
<a href="ir07-inventory.php">Back to Inventory Home</a>
<?php
if($n==0){ // Not found
	echo("No books");
} else { // Multiple entries
	echo "<table border='1'>\n";
	echo "<tr>\n";
	echo "<td>#</td><td>Acc No</td><td>Title</td><td></td>\n";
	//echo "<td>#</td><td>Access No:</td><td>Title</td><td>Date Loaned</td><td>Loaned By</td><td>Mem Type</td>\n";
	echo "</tr>\n";
	$cnt1 = 1;
	while($r = mysqli_fetch_assoc($rs)) {
		echo "<tr>\n";		
		echo "<td>$cnt1</td><td>{$r['access_no']}</td><td>{$r['title']}</td><td>{$r['authors']}</td>\n";
		//echo "{$r['access_no']}|{$r['title']}|{$r['date_loaned']}|{$r['fullname']}|{$r['type']}\n<br/>";		
		echo "</tr>\n";
		$cnt1++;
	}
	echo "</table>\n";
}
?>


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

<?php 
if(strlen($msg)>0) {
	echo $msg;
}
?>

</body>
</html>
