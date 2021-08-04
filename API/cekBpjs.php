<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';

	$noKartu	= isset ($_POST["noKartu"]) ? $_POST["noKartu"] : 0;

    $db = new DbHandler();
    $db->cekBpjs($noKartu);
?>