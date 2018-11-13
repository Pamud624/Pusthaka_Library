<?php
$allow = "ADMIN;LIBSTAFF";
$PageTitle = "Current Inventory 2007 Held 2008 report 2009 May";
include ('../inc/init.php');
$con = mysqli_connect("localhost", "root", "","pusthaka_ucsc");
//mysql_select_db("pusthaka", $con);

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
    	<a href='ir07-inventory.php'>Back to Main Inventory Screen</a><br/>
        <?php

		?>
    </body>
</html>
