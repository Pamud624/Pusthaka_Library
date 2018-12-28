<?php
//fetch.php
$connect = mysqli_connect("localhost", "root", "", "pusthaka");
$columns = array('cid','bid','access_no','title','authors','isbn');

 //$query = "SELECT lid,member,copy,loaned_by,date_loaned FROM loan WHERE ";

 //CAST(date_loaned AS DATE) AS ldate

   //
// 

    //$query = "SELECT copy.cid, copy.bid,copy_check.checked  FROM copy LEFT JOIN copy_check ON copy.cid=copy_check.cid WHERE  name= '201801'";

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



     // $query="SELECT cid,bid FROM  copy  WHERE cid  NOT IN  (SELECT cid FROM  copy_check  WHERE name= $year)"; 


     $query="SELECT copy.cid,copy.bid ,copy.access_no,book.title, book.authors,book.isbn FROM  copy LEFT JOIN book ON copy.bid=book.bid   WHERE cid  NOT IN  (SELECT cid FROM  copy_check  WHERE name= $year)"; 


// if($_POST["is_date_search"] == "yes")
// {
//  $query .= 'dt BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
// }

// if(isset($_POST["search"]["value"]))
// {
//  $query .= '
//   (cid LIKE "%'.$_POST["search"]["value"].'%" 
//   bid LIKE "%'.$_POST["search"]["value"].'%" 
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
 $query .= 'ORDER BY cid ASC ';
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
 $sub_array[] = $row["cid"];
 $sub_array[] = $row["bid"];
  $sub_array[] = $row["access_no"];

 $sub_array[] = $row["title"];

 $sub_array[] = $row["authors"];
  $sub_array[] = $row["isbn"];


  // $sub_array[] = $row["checked"];

  

 $data[] = $sub_array;
 
}


function get_all_data($connect)
{
  //$query = "SELECT lid,member,copy,loaned_by, CAST(date_loaned AS DATE)  FROM loan";

      // $query =	"SELECT loan.lid, loan.member, loan.copy, loan.date_loaned, member.surname FROM loan LEFT JOIN member ON loan.member= member.mid";




	//$query ="SELECT copy.cid, copy.bid,copy_check.checked  FROM copy LEFT JOIN copy_check ON copy.cid=copy_check.cid WHERE  name= '201801'  ";

	$sql1 = "SELECT * FROM config1 WHERE id =1";
        $recordset = executeSqlQuery($sql1);
        $rowcount = mysqli_num_rows($recordset);
        $row = mysqli_fetch_assoc($recordset);

      $year=$row['value5'];

     $query="SELECT copy.cid,copy.bid ,copy.access_no,book.title, book.authors,book.isbn FROM  copy LEFT JOIN book ON copy.bid=book.bid   WHERE cid  NOT IN  (SELECT cid FROM  copy_check  WHERE name= $year)";  








 $result = mysqli_query($connect, $query);

 //print_r($result);
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