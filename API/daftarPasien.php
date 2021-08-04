<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';
	
    $tgl_kunj		= isset ($_POST["tgl_kunj"]) ? $_POST["tgl_kunj"] : 0;
    $nama       	= isset ($_POST["nama"]) ? $_POST["nama"] : 0;
    $rm 			= isset ($_POST["rm"]) ? $_POST["rm"] : 0;
	$penjamin		= isset ($_POST["penjamin"]) ? $_POST["penjamin"] : 0;
	$nmr_kartu    	= isset ($_POST["nmr_kartu"]) ? $_POST["nmr_kartu"] : 0;
    $kliniknya   	= isset ($_POST["kliniknya"]) ? $_POST["kliniknya"] : 0;
    
    $db = new DbHandler();
    $db->daftarPasien($tgl_kunj, $nama, $rm, $penjamin, $nmr_kartu, $kliniknya);
?>