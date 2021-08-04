<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';
	$no_kartux      = isset ($_POST["no_kartux"]) ? $_POST["no_kartux"] : 0;
    $kartunya       = isset ($_POST["kartunya"]) ? $_POST["kartunya"] : 0;
    $rm			    = isset ($_POST["rm"]) ? $_POST["rm"] : 0;
    
    switch ($kartunya) {
        case 'No Induk Kependudukan (NIK)':
            # code...
            $kartunya= "1";
        break;
        
        case 'BPJS Kesehatan':
            $kartunya= "2";
        break;
        
        default:
            # code...
            $kartunya = "0";
            break;
    };
        
    $db = new DbHandler();
    $db->cekLogin($no_kartux, $kartunya, $rm);
?>