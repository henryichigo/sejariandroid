<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';

    $noid	        = isset ($_POST["noid"]) ? $_POST["noid"] : 0;
    $tanggal	    = isset ($_POST["tanggal"]) ? $_POST["tanggal"] : 0;

    $db = new DbHandler();
    $db->cekDaftarLama($noid, $tanggal);
?>