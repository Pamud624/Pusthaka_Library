<?php
// Logs an event to the application database
function logEvent($event, $mid_user=0, $mid_patron=0, $description=''){
	$dt = date("Y-m-d G:i:s");
	$sql = sprintf("INSERT INTO eventlog (mid_user, mid_patron, dt, event, description) VALUES (%d, %d, '%s', '%s', '%s')",
		$mid_user, $mid_patron, $dt, $event, $description);
	$a = executeSqlNonQuery($sql);
	$rows = $a['rows'];
	if($rows != 1){ // Not logged
		//
	}
}

function displayMsg($msg, $title, $backlink='#', $backlinkTitle='Back'){
    $_SESSION['msg']['msg'] = $msg;
    $_SESSION['msg']['title'] = $title;
    $_SESSION['msg']['backlink'] = $backlink;
	$_SESSION['msg']['backlinkTitle'] = $backlinkTitle;
    header('Location: __msg.php');
    exit();
}

function displayMsgInSamePage($msg, $id=0, $title='Error'){
    $_SESSION['msg']['msg'] = $msg;
    $_SESSION['msg']['title'] = $title;
	///[Save POST data in SESSION] -------------------
	// Get the page name
	$arr = explode('/',$_SERVER['PHP_SELF']);
	$index = count($arr) -1;
	$page =  $arr[$index];
	$page = substr($page,0,count($page)- 5);
	if(isset($_SESSION['page_state'][$page])) unset($_SESSION['page_state'][$page]);
	foreach($_POST as $key=>$val){
		$_SESSION['page_state'][$page][$key] = $val;
	}
	header('Location: ' . $_SERVER['PHP_SELF'] . '?ID=' . $id);
    exit();
}
function echoDisplayMsgInSamePage(){
	if((isset($_SESSION['msg'])) && ($_SESSION['msg'] != "")) { 
		echo '<h1>' . $_SESSION['msg']['title'] . '</h1>';
		echo '<table border="0">' .
			'<tr>' .
			'<td class="msg">' .
			stripcslashes($_SESSION['msg']['msg']);
		if($_SESSION['msg']['backlink']!=''){ 
			echo '<br>---------------------------------------------';
			echo "<a href='" . $_SESSION['msg']['backlink'] . "'>Back</a>";
		}
		echo '</td>' .
			'</tr>' .
			'</table>';
		unset($_SESSION['msg']);
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

function printRecordsetToHtmlTable($rs){
	if(!$rs){
		return 'Invalid recordset';
	}

	$numFields = mysqli_num_fields($rs);
	//[Start table tag]---------------
	$table =
		'<table border=1>' .
			'<tr>';
	//[print header row]--------------------
	$i=0;
	while($i < $numFields){
		$fieldInfo = mysqli_fetch_field($rs,$i);
		$fieldName = $fieldInfo->name;
		$table .= '<td><strong>' . $fieldName . '</strong></td>';
		$i++;
	}

	//[print content rows]---------------
	while($row = mysqli_fetch_array($rs)){
		$table .= '<tr>';
		$j = 0;
		while($j < $numFields){
			$table .= '<td>' . $row[$j] . '</td>';
			$j++;
		}
		$table .= '</tr>';
	}

	//[Complete table]---------------
	$table .= '</tr></table>';
	return $table;
}

function getDateTimeLongNumber($timestamp){
	return date("Y-m-d G:i:s",$timestamp);
}
function getDateTimeLongText($timestamp){
	return 'TODO';
	//return date("Y-m-d G:i:s",$timestamp);
}
/*
	Builds the WHERE clause (without the word 'WHERE') for a SQL query
	given the field name, whether to do a 'OR search' or an 'AND search', and a 
	string of keywords each separated by only one single space
*/
function BuildSearchCriteriaString($str,$fld,$connector){
// Assumes one and only one 'space' separates words
// $fld must be a character field
// $connector = AND | OR
	$strA = explode(" ",$str);
	$crit = "";
	foreach($strA as $val){
		$crit = $crit . " " . $fld . " LIKE '%" . $val . "%' " . $connector;
	}		
	// remove the last AND
	$crit = substr($crit,0,strlen($crit)-3);
	return $crit;

}
?>