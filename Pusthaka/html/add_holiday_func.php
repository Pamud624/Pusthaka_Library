<?php 
    session_start();
	ini_set('date.timezone', 'Asia/Colombo');
    require('../config/setup.php');
	require('../inc/util.php');
	
	$con = mysqli_connect($db['server'],$db['username'],$db['password'],$db['database']);
	//mysql_select_db($db['database'], $con);
    
    $date = filter_input(INPUT_POST, 'date');
	$year = date('Y', strtotime($date));
	$month = date('M', strtotime($date));
	$day = date('l', strtotime($date));
	
	//generate id
	$id = rand(10,10000);
	while(mysqli_num_rows(mysqli_query($con, "SELECT * FROM `holidays` WHERE ID = '$id'")))
	{
		$id = rand(10,10000);
	}
	
	//check whether already exixts
	
	if(mysqli_num_rows(mysqli_query($con, "SELECT * FROM `holidays` WHERE Date = '$date'")))
	{
		$_SESSION['error'] = "Date already added.";
	}
	elseif(date('w', strtotime($date)) == 6 || date('w', strtotime($date)) == 0)
	{
		$_SESSION['error'] = "Weekends are already counted as holidays.";
	}
	else
	{
		//Insert query
		$sql_insert="INSERT INTO `holidays` (`ID`,`Date`,`Year`,`Month`, `Day`) VALUES ('".$id."','".$date."','".$year."','".$month."','".$day."')";
		mysqli_query($con, $sql_insert);
		
		if(mysqli_affected_rows()>0)
		{
			$_SESSION['success1'] = "Holiday added sucessfully.";
		}
		else
		{
			$_SESSION['error1'] = "Something went wrong. Try again.";
		}
	}
	header('Location: _add_holiday.php');

?>
