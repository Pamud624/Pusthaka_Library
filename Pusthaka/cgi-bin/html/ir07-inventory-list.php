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
<h1>The Last 100 books to be checked in</h1>
<?php
	//$sql="SELECT cc.cid, c.bid, c.access_no, b.title FROM (copy_check cc LEFT JOIN copy c ON cc.cid=c.cid) LEFT JOIN book b ON c.bid = b.bid WHERE NOT c.access_no='' ORDER BY b.title, c.access_no";
$sql="SELECT cc.cid, cc.datetime, c.bid, c.access_no, b.title FROM (copy_check cc LEFT JOIN copy c ON cc.cid=c.cid) LEFT JOIN book b ON c.bid = b.bid WHERE ((NOT c.access_no='') AND cc.name='2007h08') ORDER BY cc.datetime DESC LIMIT 0, 100";
$rs = executeSqlQuery($sql);
$cnt = mysqli_num_rows($rs);
echo "<table border='1'>\n";
echo "<tr>\n";
echo "<td>Date/Time</td><td>Acc No</td><td>Title</td><td>Edit</td>\n";
echo "</tr>\n";

while($r = mysqli_fetch_assoc($rs)){
	echo "<tr>\n";			
	echo "<td>{$r['datetime']}</td><td>{$r['access_no']}</td><td>{$r['title']}</td><td><a href=\"book_edit.php?ID={$r['bid']}\">Edit</a></td>\n";
	echo "</tr>\n";
}	
echo "</table>\n";
?>
</body>
</html>
