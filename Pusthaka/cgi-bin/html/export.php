<?php 

  include('../config/setup2.php');


if(isset($_POST["export"]))
{

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');
$output = fopen("php://output", "w");
fputcsv($output, array('bid','cid', 'access_no','title','authors','barcode','isbn','publisher','lang'));

$query="SELECT b.bid, c.cid, c.access_no, b.title,b.authors, c.barcode, b.isbn, b.publisher, b.lang  FROM `book` b, copy c WHERE
(c.bid=b.bid AND  (c.copy_status='LSt' OR c.copy_status='LS' OR c.copy_status='LFS') )";



$result=mysqli_query($connect , $query );	

while ($row = mysqli_fetch_assoc($result))

 {  
 	fputcsv($output,$row);
}

fclose($output);


}

?>

