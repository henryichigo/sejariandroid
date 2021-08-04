<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';
    $noid	    = isset ($_POST["noid"]) ? $_POST["noid"] : 0;
    $ruangy	    = isset ($_POST["ruang"]) ? $_POST["ruang"] : 0;

    $db = new DbHandler();
    $db->hapusDaftar($noid, $ruangy);
?>