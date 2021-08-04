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

/*    switch ($kartunya) {
        case 'Umum/Pribadi':
            # code...
            $kartunya= "1";
        break;
        
        case 'BPJS Kesehatan':
            $kartunya= "2";
        break;

        case 'BPJS Ketenagakerjaan':
            $kartunya= "3";
        break;
    
        case 'JAMINAN COVID-19':
            $kartunya = "4";
        break;

        case 'Jasa Raharja':
            $kartunya = "5";
        break;

        case 'KAI':
            $kartunya = "8";
        break;

        case 'In-Health':
            $kartunya = "9";
        break;

        case 'INKA':
            $kartunya = "10";
        break;
        
        default:
            # code...
            $kartunya = "0";
            break;
    };*/

    $db = new DbHandler();
    $db->getDaftar($noid, $ruang, $no_rujukan, $id_asuransi, $tanggal);
?>