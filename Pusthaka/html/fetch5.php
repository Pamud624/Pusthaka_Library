<?php

  include('../config/setup2.php');

$columns = array('bid','cid', 'access_no','title','authors','barcode','isbn','publisher','lang');

 //$query = "SELECT lid,member,copy,loaned_by,date_loaned FROM loan WHERE ";

 //CAST(date_loaned AS DATE) AS ldate

   // $query = "SELECT copy_check.cid, copy.bid FROM copy_check LEFT JOIN copy ON copy.cid=copy_check.cid WHERE copy_check.name='201801' AND copy_check.checked= 0 ";
   $query="SELECT b.bid, c.cid, c.access_no, b.title,b.authors, c.barcode, b.isbn, b.publisher, b.lang  FROM `book` b, copy c WHERE (c.bid=b.bid AND  (c.copy_status='LSt' OR c.copy_status='LS' OR c.copy_status='LFS') )  ";



// if(isset($_POST["search"]["value"]))
// {
//  $query .= '
//   (bid LIKE "%'.$_POST["search"]["value"].'%" 
//   OR cid LIKE "%'.$_POST["search"]["value"].'%" 
//  )
//  ';
// }

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY bid ASC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

$result = mysqli_query($connect, $query.$query1 );


$data = array();

while($row = mysqli_fetch_array($result))
	//print_r($sub_array);

{
 $sub_array = array();
 $sub_array[] = $row["bid"];
 $sub_array[] = $row["cid"];
  $sub_array[] = $row["access_no"];
   $sub_array[] = $row["title"];
      $sub_array[] = $row["authors"];
            $sub_array[] = $row["barcode"];

      $sub_array[] = $row["isbn"];

      $sub_array[] = $row["publisher"];

      $sub_array[] = $row["lang"];





  

 $data[] = $sub_array;
 
}


function get_all_data($connect)
{

	//$query ="SELECT copy_check.cid, copy.bid FROM copy_check LEFT JOIN copy ON copy_check.cid=copy.cid ";
	$query="SELECT b.bid, c.cid, c.access_no, b.title,b.authors, c.barcode, b.isbn, b.publisher, b.lang  FROM `book` b, copy c WHERE  (c.bid=b.bid AND  (c.copy_status='LSt' OR c.copy_status='LS' OR c.copy_status='LFS' ) )";


 $result = mysqli_query($connect, $query);
 return mysqli_num_rows($result);

}

$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  get_all_data($connect),
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);
//print_r($output);

?>