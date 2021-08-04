<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';
	
    $tgl_kunj		= isset ($_POST["tgl_kunj"]) ? $_POST["tgl_kunj"] : 0;
    
    $db = new DbHandler();
    $db->cekKuota($tgl_kunj);
?>