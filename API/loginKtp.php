<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';
	
	$norm	        = isset ($_POST["rm"]) ? $_POST["rm"] : 0;
	$no_asuransi	= isset ($_POST["no_ktp"]) ? $_POST["no_ktp"] : 0;
        
    $db = new DbHandler();
    $db->loginKtp($norm, $no_asuransi);
?>