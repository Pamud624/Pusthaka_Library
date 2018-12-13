<?php
ini_set('date.timezone', 'Asia/Colombo');
session_start();
require('../config/setup.php');
require('../inc/util.php');
require('../classes/Books.php');
require('../classes/Members.php');
set_error_handler("ErrorHandler");

//[Restrict Access to Page] ------------------------------
if ($allow != "ALL"){
	$found = false;
	$arr = explode(";",$allow);

	foreach($arr as $val){
		if( isset($_SESSION['CurrentUser']) && ($_SESSION['CurrentUser']['login_type'] == $val)){
			$found = true;
		}
	}

	if (!$found) {
		$_SESSION['BackLink'] = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
		$msg = 'You tried to access a page that requires you to login.<br>' . 
			'Please go to the <a href=index.php>home page</a> and login.<br>' . 
			'(you will be automatically redirected to the original page)';
		$title = 'Login Required';
		displayMsg($msg, $title);
	}
}

// Custom Error Handler
function ErrorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
    //[Settings] ------------------------------
    $email_addr = "jinadikd@gmail.com";
    $log_file = "../logs/error.log";

    // configuaration flags
    $email = false;
    $log = true;

    //[Error depending processing] --------------------
    $notify = true;
    $halt_script = true;
    switch($errno) {
        case E_USER_NOTICE:
        case E_NOTICE:
            $halt_script = false;
            $type = "Notice";
            break;
        case E_USER_WARNING:
        case E_COMPILE_WARNING:
        case E_CORE_WARNING:
        case E_WARNING:
			$halt_script = false;
            $type = "Warning";
            break;
        case E_USER_ERROR:
        case E_COMPILE_ERROR:
        case E_CORE_ERROR:
        case E_ERROR:
            $type = "Fatal Error";
            break;
        case E_PARSE:
            $type = "Parse Error";
            break;
        default:
            $type = "Unknown Error";
            break;
    }

    //[Notify] -------------------------
    if($notify) {
        $errfile_pathinfo = pathinfo($errfile);
        $error_msg = "[" . getDateTimeLongNumber(time()) . ']' . $type . '(' . $errno . ')' . $errfile_pathinfo['basename'] . ':' . $errline . '(' . $errstr . ')' . "\r\n";
        if($email){
            $email_msg = $error_msg . '';
            error_log($email_msg, 1, $email_addr);
        }
        if($log) {
            if($log_file == "") {
                error_log($error_msg, 0);
            } else {
                error_log($error_msg, 3, $log_file);
            }
        }
    }

    //[Terminate Script, if needed] --------------------
    //Save error info in session, to be used in the error page
    $_SESSION['error']['type'] = $type;
	$_SESSION['error']['no'] = $errno;
    $_SESSION['error']['msg'] = $errstr;
    $_SESSION['error']['file'] = $errfile;
    $_SESSION['error']['line'] = $errline;
    $_SESSION['error']['context'] = $errcontext;
    $_SESSION['error']['backlink'] = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
    $_SESSION['error']['helplink'] = '#';

    if($halt_script){
        header("Location: __error.php");
        exit();
    }
}


?>