<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';

    $id_asuransi	= isset ($_POST["id_asuransi"]) ? $_POST["id_asuransi"] : 0;
    
    $db = new DbHandler();
    $db->cekAsuransi($id_asuransi);
?>