<?php
//fetch.php
$connect = mysqli_connect("localhost", "root", "", "pusthaka");
$columns = array('lid', 'member','copy','names','title','date_loaned' );

 //$query = "SELECT lid,member,copy,loaned_by,date_loaned FROM loan WHERE ";

 //CAST(date_loaned AS DATE) AS ldate

    // $query = "SELECT loan.lid, loan.member, loan.copy,member.surname, loan.date_loaned FROM loan LEFT JOIN member ON loan.member= member.mid WHERE ";

// $query = "SELECT loan.lid, loan.member, loan.copy,CONCAT(member.firstnames, ',', member.surname) AS names, loan.date_loaned FROM loan LEFT JOIN member ON loan.member= member.mid WHERE ";

$query = "SELECT loan.lid, loan.member, loan.copy,CONCAT(member.firstnames, ',', member.surname) AS names,book.title, loan.date_loaned FROM loan LEFT JOIN member ON loan.member= member.mid LEFT JOIN copy ON loan.copy=copy.cid LEFT JOIN book ON copy.bid=book.bid WHERE ";



if($_POST["is_date_search"] == "yes")
{
 $query .= 'CAST(date_loaned AS DATE) BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
}

if(isset($_POST["search"]["value"]))
{
 $query .= '
  (lid LIKE "%'.$_POST["search"]["value"].'%" 
  OR member LIKE "%'.$_POST["search"]["value"].'%" 
  OR copy LIKE "%'.$_POST["search"]["value"].'%" 
  OR surname LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY lid DESC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

$result = mysqli_query($connect, $query . $query1);


$data = array();

while($row = mysqli_fetch_array($result))
	//print_r($sub_array);

{
 $sub_array = array();
 $sub_array[] = $row["lid"];
 $sub_array[] = $row["member"];
 $sub_array[] = $row["copy"];
 $sub_array[] = $row["names"];
  $sub_array[] = $row["title"];
 $sub_array[] = $row["date_loaned"];
 $data[] = $sub_array;
 
}


function get_all_data($connect)
{
  //$query = "SELECT lid,member,copy,loaned_by, CAST(date_loaned AS DATE)  FROM loan";

      // $query =	"SELECT loan.lid, loan.member, loan.copy, loan.date_loaned, member.surname FROM loan LEFT JOIN member ON loan.member= member.mid";
	// $query ="SELECT loan.lid, loan.member, loan.copy,member.surname, loan.date_loaned FROM  loan LEFT JOIN member ON loan.member= member.mid ";
      
   $query ="SELECT loan.lid, loan.member, loan.copy,CONCAT(member.firstnames, ',', member.surname) AS names,book.title, loan.date_loaned FROM loan LEFT JOIN member ON loan.member= member.mid LEFT JOIN copy ON loan.copy=copy.cid LEFT JOIN book ON copy.bid=book.bid ";

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