<?php
$allow = "ADMIN;LIBSTAFF";
$PageTitle = "Current Inventory 2007 Held 2008 report 2009 May";
include ('../inc/init.php');
$con = mysqli_connect("localhost", "root", "toor321");
mysqli_select_db("pusthaka", $con);

?>
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>Pusthaka: 
            <?php
            echo $PageTitle;
            ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="css/styles.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    	<a href='ir07-inventory-list-full-missing.php'>Back to Inventory Report</a><br/>
        <?php
        
        // Copies that should be present
        $sql = "SELECT cid FROM copy WHERE copy_status='OK' ORDER BY cid";
        $rs = mysqli_query($sql, $con) or die (mysqli_error());
        $cnt = mysqli_num_rows($rs);
        // Put all cid's into an array
        while ($r = mysqli_fetch_assoc($rs))
        {
            $expected[$r['cid']] = $r['cid'];
        }
//        echo "copiesExpected: ".count($expected)."<br/>";
        
        // Copies actually present
        $sql = "SELECT cid FROM copy_check WHERE name='2007h08' ORDER BY cid";
        $rs = mysqli_query($con, $sql) or die (mysqli_error());
        $cnt = mysqli_num_rows($rs);
        // Put all cid's into an array
        while ($r = mysqli_fetch_assoc($rs))
        {
            $present[$r['cid']] = $r['cid'];
        }
//        echo "copies checked-in: ".count($present)."<br/>";
        
        
        // The missing copies
        $missing = array_diff($expected, $present);
//        echo count($missing)." books missing<br/>";
        
        // Found copies
        $found = array_diff($present, $expected);
        echo "<h2>Books found. (these were marked as something other than 'OK' at last inventory)  [".count($found)."]</h2>";
        echo "<table border='1'>\n";
        echo "<tr>\n";
        echo "<td>#</td><td>Access No</td><td>Title</td><td>Authors</td><td>Status</td>\n";
        echo "</tr>\n";
        $c1 = 1;
        foreach ($found as $cid){
            $sql2 = "select c.cid, c.access_no, c.copy_status, b.bid, b.title as booktitle, b.authors from copy c, book b where c.cid=$cid AND c.bid=b.bid";
            $rs2 = mysqli_query($con, $sql2) or die (mysqli_error());
            if ($r2 = mysqli_fetch_assoc($rs2)){
                echo "<tr>\n";
                echo "<td style='text-align:right'>$c1</td><td><a href='book_view.php?ID={$r2['bid']}'>{$r2['access_no']}</a></td><td>{$r2['booktitle']}</td><td>{$r2['authors']}</td><td>{$r2['copy_status']}</td></td>\n";
                echo "</tr>\n";
				$c1++;	
            } else {
            	echo "<tr style='background-color:#CCCCCC; color: #223311'>\n";
                echo "<td style='text-align:right'></td><td><a href='book_copy_edit.php?ID=$cid'>CID = $cid</a></td><td>Can't get data</td><td>&nbsp;</td><td>&nbsp;</td>\n";
                echo "</tr>\n";
            }
			
        }
        echo "</table>";
		echo ($c1-1) . " books were found." 
        ?>
    </body>
</html>
