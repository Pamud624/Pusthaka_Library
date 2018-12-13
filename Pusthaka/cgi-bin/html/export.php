<?php 


if(isset($_POST["export"]))
{

$connect = mysqli_connect("localhost","root","","pusthaka");
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');
$output = fopen("php://output", "w");
fputcsv($output, array('id','allowed','book type','num allowed','days allowd','member type'));
$query="SELECT * FROM lending_settings ORDER BY id desc";
$result=mysqli_query($connect , $query );	

while ($row = mysqli_fetch_assoc($result))

 {  
 	fputcsv($output,$row);
}

fclose($output);


}

?>

