<?php
$allow = "ADMIN;LIBSTAFF";
$PageTitle = "Current Inventory 2007 Held 2008 report 2009 May";
include ('../inc/init.php');
$con = mysqli_connect("localhost", "root", "", "pusthaka_ucsc");
//mysqli_select_db("pusthaka", $con);

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
    	<h1>Inventory Report: as of 2008-07-03</h1>
		<a href='ir07-inventory.php'>Back to Main Inventory Screen</a><br/>
        <?php
        
        // Copies that should be present
//        $sql = "SELECT cid FROM copy WHERE copy_status='OK' ORDER BY cid";
		$sql = "SELECT cid FROM copy WHERE copy_status='OK' AND copy.acquired_on < '2008-07-03' ORDER BY cid";
		
        $rs = mysqli_query($con, $sql) or die (mysqli_error());
        $cnt = mysqli_num_rows($rs);
        // Put all cid's into an array
        while ($r = mysqli_fetch_assoc($rs))
        {
            $expected[$r['cid']] = $r['cid'];
        }
		echo "<h2>Overview</h2>";
		echo "<ul>";
		echo "<li>A total of <strong>" . count($expected) . "</strong> books were expected to be in the library. [All book copies with 'OK' as its 'copy_status']<br/>";
        
        // Copies actually present
        $sql = "SELECT cid FROM copy_check WHERE name='2007h08' ORDER BY cid";
        $rs = mysqli_query($con, $sql) or die (mysqli_error());
        $cnt = mysqli_num_rows($rs);
        // Put all cid's into an array
        while ($r = mysqli_fetch_assoc($rs))
        {
            $present[$r['cid']] = $r['cid'];
        }
        echo "<li>During the inventory taking, <strong>" .count($present) . "</strong> books were checked-in by scanning their barcodes at the inventory screen.<br/>";
        
        // Found copies
        $found = array_diff($present, $expected);

        // The missing copies
        $missing = array_diff($expected, $present);        
		
	
        echo "<li>However out of the " . count($present) . " books <a href='ir07-inventory-list-full-found.php'><strong>" . count($found)."</strong> books were earlier marked as something other than 'OK'</a>.<br/>";
		echo "<li>This leaves us " . count($expected) . " - (" . count($present) . " - " . count($found) . ") = <strong>" . count($missing) . "</strong> books to account for.";    
        echo "</ul>";
		echo "<h2>Accounting for the [" . count($missing) . "] books</h2>";
		echo "<ul>";
		echo "<li>Some of these might have been missed in the inventory taking process, yet might have been actually issued or returned during the inventory start date and today. (<em>Indicated in table</em>)<br/>";		
		echo "<li>Some of these were on loan at the time of the inventory and not-yet returned. (<em>The 'Returned On' column contains all zeros</em>)<br/>";
		echo "<li>The books that are really missing are in the grey colored rows. <br/>";
		echo "<ul>";
		echo "<li>It is possible they are in the library but were not scanned and checked-in.";
		echo "<li>If they are located, scan-them-in  and this report will get automatically updated.";
		echo "</ul>";
        echo "</ul>";
		echo "<table border='1'>\n";
        echo "<tr>\n";
        echo "<td>#</td><td>Access No</td><td>Title</td><td>Authors</td><td>Patron</td><td>Type</td><td>Loaned On</td><td>Returned On</td>\n";
        echo "</tr>\n";
		$c1 = 1;	
		$c2 = 1;	
        foreach ($missing as $cid) {
//            $sql = "select c.cid, c.access_no, b.bid, b.title as booktitle, b.authors, l.lid, l.date_loaned, m.*  from copy c, book b, loan l, member m  where c.cid=$cid AND c.bid=b.bid AND c.cid=l.copy AND l.returned=0 AND l.member=m.mid";
			$sql = "select c.cid, c.access_no, b.bid, b.title as booktitle, b.authors, l.lid, l.date_loaned, l.date_returned, m.*  from copy c, book b, loan l, member m  where c.cid=$cid AND c.bid=b.bid AND c.cid=l.copy AND (l.returned=0 OR l.date_returned > '2008-07-03') AND l.member=m.mid";
            $rs = mysqli_query($con,$sql) or die (mysqli_error());
            $rs = mysqli_query($con, $sql) or die (mysqli_error());
            if ($r = mysqli_fetch_assoc($rs)){ // This book is on loan with someone, or returned after the inventory was taken
            	echo "<tr>\n";	
				echo "<td style='text-align:right'>$c1</td><td><a href='book_view.php?ID={$r['bid']}'>{$r['access_no']}</a></td><td>{$r['booktitle']}</td><td>{$r['authors']}</td><td><a href='member_view.php?ID={$r['mid']}'>{$r['surname']}, {$r['firstnames']} {$r['title']}</a></td><td>[{$r['type']}]</td><td>{$r['date_loaned']}</td><td>{$r['date_returned']}</td>\n";
				echo "</tr>\n";
				$c1++;		 
            } else { // This is indeed missing
				$sql2 = "select c.cid, c.access_no, b.bid, b.title as booktitle, b.authors from copy c, book b where c.cid=$cid AND c.bid=b.bid";
				$rs2 = mysqli_query($con,$sql2) or die (mysqli_error());
				if ($r2 = mysqli_fetch_assoc($rs2)){            	
//            	echo "<tr style='background-color:#CCCCCC; color: #223311'>\n";	
//				echo "<td style='text-align:right'></td><td><a href='book_view.php?ID={$r2['bid']}'>{$r2['access_no']}</a></td><td>{$r2['booktitle']}</td><td>{$r2['authors']}</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style='text-align: right'>$c2</td></td>\n";
//				echo "</tr>\n"; 
				$c2++;
				} else { // some error
					echo "<tr>\n";
					echo "<td colspan='8'> An error ocurred while getting data.</td>\n"; 
					echo "</tr>\n"; 		
				}
            }
        }
		echo "</table>";
		echo "<h2>Totals</h2>";
		echo "<ul>";
		echo "<li>Books that are either currently on loan or were returned after the inventory taking date (2008-07-03): <strong>" . ($c1 -1) . "</strong>"; 
		echo "<li>Books that are really missing: <strong>" . ($c2 - 1) . "</strong>";
		echo "</ul>";
        ?>
    </body>
</html>
