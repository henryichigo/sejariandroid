<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';
	
//    $no_kartux      = isset ($_POST["no_kartux"]) ? $_POST["no_kartux"] : 0;
 //   $kartunya       = isset ($_POST["kartunya"]) ? $_POST["kartunya"] : 0;
 //   $rm             = isset ($_POST["rm"]) ? $_POST["rm"] : 0;
 //   $password       = isset ($_POST["password"]) ? $_POST["password"] : 0;

    $sebutan        = isset ($_POST["sebutan"]) ? $_POST["sebutan"] : 0;
    $nama           = isset ($_POST["nama"]) ? $_POST["nama"] : 0;
    $kelamin        = isset ($_POST["kelamin"]) ? $_POST["kelamin"] : 0;
    $lahir          = isset ($_POST["lahir"]) ? $_POST["lahir"] : 0;
    $etnis          = isset ($_POST["etnis"]) ? $_POST["etnis"] : 0;
    $alamat         = isset ($_POST["alamat"]) ? $_POST["alamat"] : 0;
    $lokasi         = isset ($_POST["lokasi"]) ? $_POST["lokasi"] : 0;
    $hp             = isset ($_POST["hp"]) ? $_POST["hp"] : 0;
    $no_ktp         = isset ($_POST["no_ktp"]) ? $_POST["no_ktp"] : 0;
    $pendidikan     = isset ($_POST["pendidikan"]) ? $_POST["pendidikan"] : 0;
    $pekerjaan      = isset ($_POST["pekerjaan"]) ? $_POST["pekerjaan"] : 0;
    $agama          = isset ($_POST["agama"]) ? $_POST["agama"] : 0;
    $asuransi       = isset ($_POST["asuransi"]) ? $_POST["asuransi"] : 0;
    $no_asuransi    = isset ($_POST["no_asuransi"]) ? $_POST["no_asuransi"] : 0;
    $jenis_asuransi = isset ($_POST["jenis_asuransi"]) ? $_POST["jenis_asuransi"] : 0;

/*    switch ($kartunya) {
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
    };*/

    $db = new DbHandler();
    $db->registrasi($sebutan, $nama, $kelamin, $lahir, $etnis, $alamat, 
        $lokasi, $hp, $no_ktp, $pendidikan, $pekerjaan, $agama, $asuransi, 
        $no_asuransi, $jenis_asuransi);
?>