<?php 
//Time Zone Set

  include('../config/setup2.php');



	date_default_timezone_set("Asia/Colombo");
    $date=date("Y-m-d");
    $time=date("H:i:s");
    $datetime=date("d/m/y h:i:s A");
	
//Database Connection
	//include('../inc/util.php');
	//include('../config/setup.php');  
	
if(isset($_GET["dt1"]) && isset($_GET["dt2"]) && $_GET["dt1"] != null && $_GET["dt2"] != null){

$date1 = $_GET["dt1"];
$date2 = $_GET["dt2"]; 

$start = new DateTime($date1);
$end = new DateTime($date2);

//$end->modify('-1 day');

$interval = $end->diff($start);

// total days
$days = $interval->days;
echo "Total Days : ".$days. "<br>";

// create an iterateable period of date (P1D equates to 1 day)
$period = new DatePeriod($start, new DateInterval('P1D'), $end);

// best stored as array, so you can add more than one
//$holidays = array('2016-12-15', '2016-12-16');
		//require('../config/setup.php');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// $db['server'] = "localhost";
		// $db['username'] ="root";
		// $db['password'] = "";
		// $db['database'] = "pusthaka_ucsc";
		
		// $con = mysqli_connect($db['server'],$db['username'],$db['password'],$db['database']);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		//mysqli_select_db($db['database'], $con);
		$sql = "SELECT * FROM holidays";
		$rs = mysqli_query($con, $sql);

		$holidays = array();
		
		$rows = mysqli_num_rows($rs);
        if ($rows != 0) {
			while($holidaysData = mysqli_fetch_array($rs)){
				
				$holidays[] =$holidaysData['days'];
				
			}
		}	

/*

$sql = "SELECT * FROM holidays";

$result = mysqli_query($con, $sql);


if (mysqli_num_rows($result) > 0) {
  
    while($row = mysqli_fetch_assoc($result)) {
		
		$holidays[] =$row['date'];
	}

}
*/

foreach($period as $dt) {
    $curr = $dt->format('D');

    // substract if Saturday or Sunday
    if ($curr == 'Sat' || $curr == 'Sun') {
        $days--;
    }

    // (optional) for the updated question
    elseif (in_array($dt->format('Y-m-d'), $holidays)) {
        $days--;
    }
}


echo "Weekdays : ".$days;
}

else{
	echo "Dates are not set.";
}
?>