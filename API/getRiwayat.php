<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';

	$no_rm	= isset ($_POST["no_rm"]) ? $_POST["no_rm"] : 0;

    $db = new DbHandler();
    $db->getRiwayat($no_rm);
?>