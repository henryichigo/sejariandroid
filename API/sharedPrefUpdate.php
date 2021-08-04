<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';
	
	$rm		= isset ($_POST["rm"]) ? $_POST["rm"] : 0;
        
    $db = new DbHandler();
    $db->sharedPrefUpdate($rm);
?>