<?php 
  include('../config/setup2.php');


if(isset($_POST["export"]))
{



	
	
	function executeSqlQuery($sql){
	require('../config/setup.php');
	$con = mysqli_connect($db['server'],$db['username'],$db['password'],$db['database']);
	//mysqli_select_db($db['database'], $con);
	$rs = mysqli_query($con, $sql);
	if(!$rs){
		trigger_error("Database Error: Failed to execute query<br>$sql", E_USER_ERROR);
		exit();
	} else {
	    return $rs;
    }
}


$sql1 = "SELECT * FROM config1 WHERE id =1";
        $recordset = executeSqlQuery($sql1);
        $rowcount = mysqli_num_rows($recordset);
        $row = mysqli_fetch_assoc($recordset);

      $year=$row['value5'];


header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');
$output = fopen("php://output", "w");
fputcsv($output, array('cid','bid','access_no','title','authors','isbn'));
$query="SELECT copy.cid,copy.bid ,copy.access_no,book.title, book.authors,book.isbn FROM  copy LEFT JOIN book ON copy.bid=book.bid   WHERE cid  NOT IN  (SELECT cid FROM  copy_check  WHERE name= $year) ";
$result=mysqli_query($connect , $query );	


while ($row = mysqli_fetch_assoc($result))

 {  
 	fputcsv($output,$row);
}

fclose($output);


}

?>