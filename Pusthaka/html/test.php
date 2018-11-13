<?php

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

       $sql = "SELECT value FROM config1 WHERE id =1";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        // if ($rowcount == 0) {
        //   trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
        //   exit();
        // } else if ( ($rowcount > 1) || ($rowcount<0) ) {
        //   trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
        //   exit();
        // }
        $row = mysqli_fetch_assoc($recordset);

  $abc = $row['value'];
  echo $abc;

?>