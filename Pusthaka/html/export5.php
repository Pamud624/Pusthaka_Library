<?php 
  include('../config/setup2.php');



if(isset($_POST["export"]))
{





header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');
$output = fopen("php://output", "w");
fputcsv($output, array('cid','bid','access_no','barcode','Availability','copy_status','title','authors','edition','publisher','published_year','isbn'));
$query="SELECT copy.cid,copy.bid ,copy.access_no,copy.barcode,copy.Availability,copy.copy_status,book.title, book.authors,book.edition,book.publisher,book.published_year,book.isbn FROM  copy LEFT JOIN book ON copy.bid=book.bid WHERE cid NOT IN (SELECT cid FROM copy WHERE ci ) ";
$result=mysqli_query($connect , $query );	


while ($row = mysqli_fetch_assoc($result))

 {  
 	fputcsv($output,$row);
}

fclose($output);


}

?>