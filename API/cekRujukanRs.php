<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';

    $no_kartu	= isset ($_POST["no_kartu"]) ? $_POST["no_kartu"] : 0;
    $kodebpjs	= isset ($_POST["kodebpjs"]) ? $_POST["kodebpjs"] : 0;

    $db = new DbHandler();
    $db->cekRujukanRs($no_kartu, $kodebpjs);
?>