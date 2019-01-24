

<?php 

  include('../config/setup2.php');



function executeSqlNonQuery($sql){
	require('../config/setup.php');
	$con = mysqli_connect($db['server'],$db['username'],$db['password'],$db['database']);
	//$con = mysqli_connect($db['server'],$db['username'],$db['password']);
	//mysqli_select_db($db['database'], $con);
	$rs = mysqli_query($con, $sql);
	if(!$rs){
		trigger_error("Database Error: Failed to execute query<br>$sql", E_USER_ERROR);
		exit();
	} else {
	    return array('rows' => mysqli_affected_rows($con), 'id' => mysqli_insert_id($con));
    }
}

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



if(isset($_REQUEST['first'])){
    $value = $_REQUEST['start_date'];
    $value2 = $_REQUEST['end_date'];
  
      $sql = sprintf("update config1 set start_date='$value',end_date='$value2' WHERE id=1");
      $a = executeSqlQuery($sql);
        header("Location: date.php");


     
}
      





$sql = "SELECT * FROM config1 WHERE id =1";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
       
        $row = mysqli_fetch_assoc($recordset);



if(isset($_POST["export"]))



{
    

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');
$output = fopen("php://output", "w");
fputcsv($output, array('lid', 'member','copy','names','title','date_loaned'));

// $query="SELECT loan.lid, loan.member, loan.copy,CONCAT(member.firstnames, ',', member.surname) AS names,book.title, loan.date_loaned FROM loan LEFT JOIN member ON loan.member= member.mid LEFT JOIN copy ON loan.copy=copy.cid LEFT JOIN book ON copy.bid=book.bid WHERE CAST(date_loaned AS DATE) BETWEEN '2018-12-02' AND '2018-12-03' ";


$query="SELECT loan.lid, loan.member, loan.copy,CONCAT(member.firstnames, ',', member.surname) AS names,book.title, loan.date_loaned FROM loan LEFT JOIN member ON loan.member= member.mid LEFT JOIN copy ON loan.copy=copy.cid LEFT JOIN book ON copy.bid=book.bid WHERE " ;

$query .= 'CAST(date_loaned AS DATE) BETWEEN  "'.$row['start_date'].'" AND "'.$row['end_date'].'"';




$result=mysqli_query($connect , $query );	

while ($row = mysqli_fetch_assoc($result))

 {  
 	fputcsv($output,$row);
}

fclose($output);

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// if(isset($_POST["export"]))
// {

// header('Content-Type: text/csv; charset=utf-8');
// header('Content-Disposition: attachment; filename=data.csv');
// $output = fopen("php://output", "w");
// fputcsv($output, array('lid', 'member','copy','names','title','date_loaned'));

// $query="SELECT loan.lid, loan.member, loan.copy,CONCAT(member.firstnames, ',', member.surname) AS names,book.title, loan.date_loaned FROM loan LEFT JOIN member ON loan.member= member.mid LEFT JOIN copy ON loan.copy=copy.cid LEFT JOIN book ON copy.bid=book.bid WHERE CAST(date_loaned AS DATE) BETWEEN '2018-12-02' AND '2018-12-03' ";



// $result=mysqli_query($connect , $query );	

// while ($row = mysqli_fetch_assoc($result))

//  {  
//  	fputcsv($output,$row);
// }

// fclose($output);


// }



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



?>


