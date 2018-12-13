<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Current Inventory 2007 Held 2008";
	include('../inc/init.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Pusthaka: <?php echo $PageTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1>Books Currently Checked-in by Acc No</h1>
<?php
$sql="SELECT cc.cid, cc.datetime, c.bid, c.access_no, b.title FROM (copy_check cc LEFT JOIN copy c ON cc.cid=c.cid) LEFT JOIN book b ON c.bid = b.bid WHERE ((NOT c.access_no='') AND cc.name='2007h08') ORDER BY (c.access_no+100000)";
$rs = executeSqlQuery($sql);
$cnt = mysqli_num_rows($rs);
echo "Total: $cnt books<hr />";

echo "<table border='1'>\n";
echo "<tr>\n";
echo "<td>#</td><td>Acc No</td><td>Title</td><td>Edit</td>\n";
echo "</tr>\n";
$x1 = 1;
while($r = mysqli_fetch_assoc($rs)){
	echo "<tr>\n";	
	echo "<td>$x1</td><td>{$r['access_no']}</td><td>{$r['title']}</td><td><a href=\"book_edit.php?ID={$r['bid']}\">Edit</a></td>\n";
	echo "</tr>\n";
	$x1++;
}	
echo "</table>\n";
?>
</body>
</html>
