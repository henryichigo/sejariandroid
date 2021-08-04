<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';

    $id	        = isset ($_POST["id"]) ? $_POST["id"] : 0;
//    $kode_bpjs	= isset ($_POST["kode_bpjs"]) ? $_POST["kode_bpjs"] : 0;
//    $online	    = isset ($_POST["online"]) ? $_POST["online"] : 0;

//    $online = "Y";

    $db = new DbHandler();
    $db->getKlinik($id);
?>