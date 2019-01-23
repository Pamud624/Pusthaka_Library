<?php
//fetch.php
  include('../config/setup2.php');

$columns = array('mid','firstnames','email','address');

 //$query = "SELECT lid,member,copy,loaned_by,date_loaned FROM loan WHERE ";

 //CAST(date_loaned AS DATE) AS ldate

   // $query = "SELECT copy_check.cid, copy.bid FROM copy_check LEFT JOIN copy ON copy.cid=copy_check.cid WHERE copy_check.name='201801' AND copy_check.checked= 0 ";
   $query="SELECT mid ,firstnames ,address ,email  FROM member ";



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
 $query .= 'ORDER BY mid DESC ';
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
 $sub_array[] = $row["mid"];
 $sub_array[] = $row["firstnames"];
  $sub_array[] = $row["email"];
   $sub_array[] = $row["address"];
     




  

 $data[] = $sub_array;
 
}


function get_all_data($connect)
{

  //$query ="SELECT copy_check.cid, copy.bid FROM copy_check LEFT JOIN copy ON copy_check.cid=copy.cid ";
  $query="SELECT mid ,firstnames ,address ,email  FROM member";


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