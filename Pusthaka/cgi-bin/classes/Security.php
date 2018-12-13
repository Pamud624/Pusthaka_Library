<?php
class Security{
    /**********************************************************************
    *   If successful, sets $_SESSION['CurrentUser']
    *   Uses $_SESSION['BackLink'], if set, to redirect to the original page from where
    *       a request for restricted content was made.
    **********************************************************************/
    function login($usr,$pwd){
        //[Verify that the inputs are good] ------------------------------
        if($usr == ''){
            $msg = 'Username is blank.<br>Please enter a valid username';
			$title = 'Username is Blank';
			$backlink = 'index.php';
			displayMsg($msg, $title, $backlink);
        }
        if($pwd == ''){
			$msg = 'Password is blank.<br>Please enter a valid password';
			$title = 'Password is Blank';
			$backlink = 'index.php';
			displayMsg($msg, $title, $backlink);
        }

        //[Get the user entry] ------------------------------        
        $sql = "SELECT * FROM member WHERE username = '$usr'";
        $rs = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($rs);

        if ($rowcount==1){
            $row = mysqli_fetch_assoc($rs);
            if($row["password"]==md5($pwd)){ // Passwords match
                $_SESSION['CurrentUser'] = $row; //				
				logEvent('LOGIN',$row['mid'],$row['mid'], addslashes($row['title'] . ' ' . $row['firstnames'] . ' ' . $row['surname']));
                if (isset($_SESSION['BackLink']) && ($_SESSION['BackLink']!="")){
                    header("Location: " . $_SESSION['BackLink']);
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else { // Password doesn't match
                $msg = "Password for the user <strong>$usr</strong> is incorrect.<br>Please try again.";
				$title = 'Incorrect Password';
				$backlink = 'index.php';
				displayMsg($msg, $title, $backlink);
            }
        } elseif($rowcount==0) { // The user was not found
            $msg = "The user <strong>$usr</strong> was not found.<br>Please try again.";
			$title = 'User not Found';
			$backlink = 'index.php';
			displayMsg($msg, $title, $backlink);
        } else {
            trigger_error("DATA INTEGRITY ERROR: while accessing user: <strong>$usr</strong><br>The admin was notified.", E_USER_ERROR);
            exit();
        }
    }

    /**********************************************************************
    *   Clears all session variables. This in effect 'logs-out' a user
    *   Redirects to the application home page
    **********************************************************************/
    function logout(){
		logEvent('LOGOUT', $_SESSION['CurrentUser']['mid'], $_SESSION['CurrentUser']['mid'],
			addslashes($_SESSION['CurrentUser']['title'] . ' ' . $_SESSION['CurrentUser']['firstnames'] . ' ' . $_SESSION['CurrentUser']['surname']));
		foreach($_SESSION as $key => $val){
            unset($_SESSION[$key]);
        }
        		
		$msg = 'Thank you for using Pusthaka.<br>' .
			'We are always looking for ways to improve your experience with Pusthaka ILS and we ' .
			'welcome your suggestions as to how we may do so.<br>' .
			'Please send us your comments by email to ' . "<a href='mailto:admin@pusthaka.org'>admin@pusthaka.org</a><br>";
		$title = 'Thank You';
		$backlink = 'index.php';
		$backlinkTitle = 'Go Back to Pusthaka Home Page';
		displayMsg($msg, $title, $backlink, $backlinkTitle);		
    }
}
?>