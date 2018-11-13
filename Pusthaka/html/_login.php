<?php
	$allow = "ALL";
	include('../inc/init.php');

    require('../classes/Security.php');
    $sec = new Security;

	if(isset($_REQUEST['btnLogin'])){
		$sUsername = addslashes($_REQUEST["Username"]);
		$sPassword = $_REQUEST["Password"];

        $sec->login($sUsername, $sPassword);
	} else { // Called directly, so logout!
        $sec->logout();
	}
?>