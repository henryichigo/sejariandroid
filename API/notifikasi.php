<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';
	$nama		= isset ($_POST["nama"]) ? $_POST["nama"] : 0;
    $title		= isset ($_POST["title"]) ? $_POST["title"] : 0;
    $text      	= isset ($_POST["text"]) ? $_POST["text"] : 0;
    
    $db = new DbHandler();
    $db->notifikasi($nama, $title, $text);
?>