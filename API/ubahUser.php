<?php
   /* error_reporting(-1);
    ini_set('display_errors', 'On');
   */ date_default_timezone_set('Asia/Jakarta');

    require_once 'DbHandler.php';
    $id       	= $_POST["id"];
	$tmplahir	= $_POST["tmp_lahir"];
    $telp 		= $_POST["no_telp"];
    $email    	= $_POST["email"];
//	$password	= $_POST["password"];

    if($_FILES!=NULL){
        $foto     = $_FILES['foto']['name'];
        $foto_temp= $_FILES['foto']['tmp_name'];
    }else{
        $foto=NULL;
        $foto_temp=NULL;
    }

    $db = new DbHandler();
    $db->ubahUser($id, $tmplahir, $foto, $telp, $email, $foto_temp);
?>