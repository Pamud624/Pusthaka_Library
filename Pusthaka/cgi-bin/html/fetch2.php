<?php
//fetch.php
$connect = mysqli_connect("localhost", "root", "", "pusthaka");
$columns = array('pid','names','dt', 'amount');

 //$query = "SELECT lid,member,copy,loaned_by,date_loaned FROM loan WHERE ";

 //CAST(date_loaned AS DATE) AS ldate

    $query = "SELECT payment.pid ,CONCAT(member.firstnames, ' ', member.surname, '(',payment.mid,')') AS names,payment.dt,payment.amount FROM payment LEFT JOIN member ON payment.mid=member.mid WHERE ";

if($_POST["is_date_search"] == "yes")
{
 $query .= 'dt BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
}

if(isset($_POST["search"]["value"]))
{
 $query .= '
  (pid LIKE "%'.$_POST["search"]["value"].'%"  
  OR amount LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY pid DESC ';
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
 $sub_array[] = $row["pid"];
  $sub_array[] = $row["names"];

  $sub_array[] = $row["dt"];

 $sub_array[] = $row["amount"];

 $data[] = $sub_array;
 
}


function get_all_data($connect)
{
  //$query = "SELECT lid,member,copy,loaned_by, CAST(date_loaned AS DATE)  FROM loan";

      // $query =	"SELECT loan.lid, loan.member, loan.copy, loan.date_loaned, member.surname FROM loan LEFT JOIN member ON loan.member= member.mid";
	$query ="SELECT payment.pid ,CONCAT(member.firstnames, ' ', member.surname, '(',payment.mid,')') AS names,payment.dt,payment.amount FROM payment LEFT JOIN member ON payment.mid=member.mid";

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