<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';
	
	$id 	= isset ($_POST["id"]) ? $_POST["id"] : 0;

    $db = new DbHandler();
    $db->selectUser($id);
?>