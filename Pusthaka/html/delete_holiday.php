<?php 
    session_start();
    require('../config/setup.php');
	require('../inc/util.php');
    
    $dateId = filter_input(INPUT_GET, 'dateId');
	//echo $dateId;

    //Delete query
	
	
	$sql_delete = sprintf("DELETE FROM holidays WHERE id=%d",$dateId);
	$a = executeSqlNonQuery($sql_delete);
	$rowsUpdated = $a['rows'];
	if($rowsUpdated == 1){
		$_SESSION['success1'] = "Holiday successfully deleted.";
		header("Location: _holidays.php");
		exit();
	} else {
		$_SESSION['error1'] = "Something went wrong. Try again.";
		header("Location: _holidays.php");
	}

?>
