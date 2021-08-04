<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';

    $no_kartux	= isset ($_POST["no_kartux"]) ? $_POST["no_kartux"] : 0;

    $db = new DbHandler();
    $db->getKtp($no_kartux);
?>