<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';

    $noid	        = isset ($_POST["noid"]) ? $_POST["noid"] : 0;
    $ruang	        = isset ($_POST["ruang"]) ? $_POST["ruang"] : 0;
    $no_rujukan	    = isset ($_POST["no_rujukan"]) ? $_POST["no_rujukan"] : 0;
    $id_asuransi	= isset ($_POST["id_asuransi"]) ? $_POST["id_asuransi"] : 0;
    $tanggal	    = isset ($_POST["tanggal"]) ? $_POST["tanggal"] : 0;

    $db = new DbHandler();
    $db->cekDaftar($noid, $ruang, $no_rujukan, $id_asuransi, $tanggal);
?>