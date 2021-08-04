<?php

class DbHandler{
    private $conn;
    private $url;

    function __construct(){
		require_once '../config/koneksi.php';
        $db = new DbConnection();
        $this->conn = $db->connect();
        $this->url = $db->url();
    }
	
	public function login($norm, $no_asuransi){
		$sql = "SELECT * FROM user_pasien WHERE rm='".$norm."' AND no_asuransi='".$no_asuransi."'";
		$result = $this->conn->query($sql);
        if ($result->num_rows == 1) {
            header('Content-Type: application/json');
            $data 	= array();
            $row 	= $result->fetch_assoc();
            $data[]	= $row;

            echo '{ "metadata" : "OK", "response":'.json_encode($data,true).'}';
        } else {
            header('Content-Type: application/json');
            echo '{"metadata" : "GAGAL", "message" : "Nomor Rekam Medis atau password salah"}';
		}
	}

	public function loginKtp($norm, $no_asuransi){
		$sql = "SELECT * FROM user_pasien WHERE rm='".$norm."' AND no_ktp='".$no_asuransi."'";
		$result = $this->conn->query($sql);
        if ($result->num_rows == 1) {
            header('Content-Type: application/json');
            $data 	= array();
            $row 	= $result->fetch_assoc();
            $data[]	= $row;

            echo '{ "metadata" : "OK", "response":'.json_encode($data,true).'}';
        } else {
            header('Content-Type: application/json');
            echo '{"metadata" : "GAGAL", "message" : "Nomor Rekam Medis atau password salah"}';
		}
	}

	public function cekLogin($no_kartux, $kartunya, $rm){
		header('Content-Type: application/json');
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
//		$bridging_alamat='http://dinkes.madiunkota.go.id/rsud/android/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'cek_kartu.php';

		$getjobdetail = '{
			"no_kartux": "'.$no_kartux.'", 
			"kartunya": "'.$kartunya.'"
		}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);		

		$jsonnya = strstr($output,'{');
//		echo $output;

		$hasilx	= json_decode($jsonnya,true);
		$status	= $hasilx['metadata'];

        if($status =="OK"){
            header('Content-Type: application/json');
			$data = array();
			
            foreach($hasilx['response'] as $row){
				$temp['id'] 			= $row['id'];
				$temp['rm']   			= $row['no_rm'];
				$temp['nama']   		= $row['nama'];
				$temp['sebutan']   		= $row['sebutan'];
				$temp['kelamin']   		= $row['kelamin'];
				$temp['alamat']   		= $row['alamat'];
				$temp['desa']   		= $row['desa'];
				$temp['kecamatan']   	= $row['kecamatan'];
				$temp['kabupaten']   	= $row['kabupaten'];
				$temp['provinsi']   	= $row['provinsi'];
				$temp['lahir']   		= $row['lahir'];
				//$temp['foto']   		= $row['foto'];
				$temp['no_telp']   		= $row['hp'];
				$temp['no_ktp']   		= $row['no_ktp'];
				$temp['id_asuransi']   	= $row['id_asuransi'];
				$temp['no_asuransi']   	= $row['no_asuransi'];
				$temp['jenis_asuransi']	= $row['jenis_asuransi'];
				$temp['kelas_jatah']	= $row['kelas_jatah'];
				//$temp['email']   		= $row['email'];
				$data[]             	= $temp;		
			}

			$id						= $temp['id'];
			$norm					= $temp['rm'];
			$nama					= $temp['nama'];
			$sebutan				= $temp['sebutan'];
			$kelamin				= $temp['kelamin'];
			$alamat					= $temp['alamat'];
			$desa					= $temp['desa'];
			$kecamatan				= $temp['kecamatan'];
			$kabupaten				= $temp['kabupaten'];
			$provinsi				= $temp['provinsi'];
			$lahir					= $temp['lahir'];
			$no_telp				= $temp['no_telp'];
			$no_ktp					= $temp['no_ktp'];
			$id_asuransi			= $temp['id_asuransi'];
			$no_asuransi			= $temp['no_asuransi'];
			$jenis_asuransi			= $temp['jenis_asuransi'];
			$kelas_jatah			= $temp['kelas_jatah'];

			if ($no_kartux == $no_asuransi AND $rm == $norm){
				$sql_cek 	= "SELECT * FROM user_pasien WHERE no_asuransi = '".$no_kartux."' AND rm = '".$rm."'";
				$result_cek	= $this->conn->query($sql_cek);
			
				if ($result_cek->num_rows == 0){
					$acak       	= "1933FAasdsk25kwBjakjDlff1988"; 
					$panjang    	= '6';
					$len        	= strlen($acak);
					$start      	= $len-$panjang; 
					$xx         	= rand('0',$start);	
					$yy         	= str_shuffle($acak);
					$token      	= substr($yy, $xx, $panjang);
					$tmplahir   	= "";
					$password		= "";
					
					if ($kelamin=="L"){
					    $foto = "pasien.png";
					} else {
					    $foto = "pasien_women.png";
					}
					
					$email      	= "";
					$aktivasi   	= "T";

					$sql = "INSERT INTO user_pasien 
						(id, rm, nama, sebutan, kelamin, alamat, desa, kecamatan, kabupaten, provinsi, tmp_lahir, 
						lahir, foto, no_telp, no_ktp, id_asuransi, no_asuransi, jenis_asuransi, kelas_jatah, 
						email, password, aktivasi, token) 
						VALUES('".$id."','".$norm."', '".$nama."', '".$sebutan."', '".$kelamin."', '".$alamat."', '".$desa."', 
						'".$kecamatan."', '".$kabupaten."', '".$provinsi."', '".$tmplahir."', '".$lahir."', '".$foto."', 
						'".$no_telp."', '".$no_ktp."', '".$id_asuransi."', '".$no_asuransi."','".$jenis_asuransi."', 
						'".$kelas_jatah."', '".$email."', '".$password."', '".$aktivasi."', '".$token."')";
						
					if ($this->conn->query($sql) == TRUE){
						header('Content-Type: application/json');
						echo '{ "metadata" : "BERHASIL", "response":'.json_encode($data,true).'}';

					} else {
						header('Content-Type: application/json');
						echo '{"message" : "Gagal Login, koneksi terputus, silahkan Login ulang"}';
					}
				} else {
//					header('Content-Type: application/json');
//					echo '{ "metadata" : "BERHASIL", "response":'.json_encode($data,true).'}';
					
					$sqla = "UPDATE user_pasien 
				        SET nama        = '".$nama."',
					        sebutan   	= '".$sebutan."',
					        kelamin    	= '".$kelamin."',
					        no_telp    	= '".$no_telp."',
					        no_ktp      = '".$no_ktp."',
					        kelas_jatah = '".$kelas_jatah."'
				        WHERE 		id 	= '".$id."'";
				        
				    if ($this->conn->query($sqla) == TRUE){
						header('Content-Type: application/json');
						echo '{ "metadata" : "BERHASIL", "response":'.json_encode($data,true).'}';

					} else {
						header('Content-Type: application/json');
						echo '{"message" : "Gagal Login, koneksi terputus, silahkan Login ulang"}';
					}
				}	
			} else {
				header('Content-Type: application/json');
            	echo '{"metadata" : "GAGAL", "message" : "Anda belum terdaftar di RSUD Kota Madiun, Silahkan Melakukan Pendaftaran"}';
			}
		} else {
			header('Content-Type: application/json');
            echo '{"metadata" : "GAGAL", "message" : "Login gagal, cek inputan anda atau mengunjungi admin RS untuk verifikasi data"}';
		}
	}

	public function cekLoginKtp($no_kartux, $kartunya, $rm){
		header('Content-Type: application/json');
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'cek_kartu.php';

		$getjobdetail = '{
			"no_kartux": "'.$no_kartux.'", 
			"kartunya": "'.$kartunya.'"
		}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);		

		$jsonnya = strstr($output,'{');
//		echo $output;

		$hasilx	= json_decode($jsonnya,true);
		$status	= $hasilx['metadata'];

        if($status =="OK"){
            header('Content-Type: application/json');
			$data = array();
			
            foreach($hasilx['response'] as $row){
				$temp['id'] 			= $row['id'];
				$temp['rm']   			= $row['no_rm'];
				$temp['nama']   		= $row['nama'];
				$temp['sebutan']   		= $row['sebutan'];
				$temp['kelamin']   		= $row['kelamin'];
				$temp['alamat']   		= $row['alamat'];
				$temp['desa']   		= $row['desa'];
				$temp['kecamatan']   	= $row['kecamatan'];
				$temp['kabupaten']   	= $row['kabupaten'];
				$temp['provinsi']   	= $row['provinsi'];
				$temp['lahir']   		= $row['lahir'];
				//$temp['foto']   		= $row['foto'];
				$temp['no_telp']   		= $row['hp'];
				$temp['no_ktp']   		= $row['no_ktp'];
				$temp['id_asuransi']   	= $row['id_asuransi'];
				$temp['no_asuransi']   	= $row['no_asuransi'];
				$temp['jenis_asuransi']	= $row['jenis_asuransi'];
				$temp['kelas_jatah']	= $row['kelas_jatah'];
				//$temp['email']   		= $row['email'];
				$data[]             	= $temp;		
			}

			$id						= $temp['id'];
			$norm					= $temp['rm'];
			$nama					= $temp['nama'];
			$sebutan				= $temp['sebutan'];
			$kelamin				= $temp['kelamin'];
			$alamat					= $temp['alamat'];
			$desa					= $temp['desa'];
			$kecamatan				= $temp['kecamatan'];
			$kabupaten				= $temp['kabupaten'];
			$provinsi				= $temp['provinsi'];
			$lahir					= $temp['lahir'];
			$no_telp				= $temp['no_telp'];
			$no_ktp					= $temp['no_ktp'];
			$id_asuransi			= $temp['id_asuransi'];
			$no_asuransi			= $temp['no_asuransi'];
			$jenis_asuransi			= $temp['jenis_asuransi'];
			$kelas_jatah			= $temp['kelas_jatah'];

			if ($no_kartux == $no_ktp AND $rm == $norm){
				$sql_cek 	= "SELECT * FROM user_pasien WHERE no_ktp = '".$no_kartux."' AND rm = '".$rm."'";
				$result_cek	= $this->conn->query($sql_cek);
			
				if ($result_cek->num_rows == 0){
					$acak       	= "1933FAasdsk25kwBjakjDlff1988"; 
					$panjang    	= '6';
					$len        	= strlen($acak);
					$start      	= $len-$panjang; 
					$xx         	= rand('0',$start);	
					$yy         	= str_shuffle($acak);
					$token      	= substr($yy, $xx, $panjang);
					$tmplahir   	= "";
					$password		= "";
					
					if ($kelamin=="L"){
					    $foto = "pasien.png";
					} else {
					    $foto = "pasien_women.png";
					}
					
					$email      	= "";
					$aktivasi   	= "T";

					$sql = "INSERT INTO user_pasien 
						(id, rm, nama, sebutan, kelamin, alamat, desa, kecamatan, kabupaten, provinsi, tmp_lahir, 
						lahir, foto, no_telp, no_ktp, id_asuransi, no_asuransi, jenis_asuransi, kelas_jatah, 
						email, password, aktivasi, token) 
						VALUES('".$id."','".$norm."', '".$nama."', '".$sebutan."', '".$kelamin."', '".$alamat."', '".$desa."', 
						'".$kecamatan."', '".$kabupaten."', '".$provinsi."', '".$tmplahir."', '".$lahir."', '".$foto."', 
						'".$no_telp."', '".$no_ktp."', '".$id_asuransi."', '".$no_asuransi."','".$jenis_asuransi."', 
						'".$kelas_jatah."', '".$email."', '".$password."', '".$aktivasi."', '".$token."')";
						
					if ($this->conn->query($sql) == TRUE){
						header('Content-Type: application/json');
						echo '{ "metadata" : "BERHASIL", "response":'.json_encode($data,true).'}';

					} else {
						header('Content-Type: application/json');
						echo '{"message" : "Gagal Login, koneksi terputus, silahkan Login ulang"}';
					}
				} else {
//					header('Content-Type: application/json');
//					echo '{ "metadata" : "BERHASIL", "response":'.json_encode($data,true).'}';

                    $sqla = "UPDATE user_pasien 
				        SET nama        = '".$nama."',
					        sebutan   	= '".$sebutan."',
					        kelamin    	= '".$kelamin."',
					        no_telp    	= '".$no_telp."',
					        no_ktp      = '".$no_ktp."',
					        kelas_jatah = '".$kelas_jatah."'
				        WHERE 		id 	= '".$id."'";
				        
				    if ($this->conn->query($sqla) == TRUE){
						header('Content-Type: application/json');
						echo '{ "metadata" : "BERHASIL", "response":'.json_encode($data,true).'}';

					} else {
						header('Content-Type: application/json');
						echo '{"message" : "Gagal Login, koneksi terputus, silahkan Login ulang"}';
					}
				}	
			} else {
				header('Content-Type: application/json');
            	echo '{"metadata" : "GAGAL", "message" : "Anda belum terdaftar di RSUD Kota Madiun, Silahkan Melakukan Pendaftaran"}';
			}
		} else {
			header('Content-Type: application/json');
            echo '{"metadata" : "GAGAL", "message" : "Login gagal, cek inputan anda atau mengunjungi admin RS untuk verifikasi data"}';
		}
	}

	public function cekBpjs($noKartu){
		include "signature.php";
    	$tanggal		= date("Y-m-d");

    	$url = ''.$alamat_pcare.'/Peserta/nokartu/'.$noKartu.'/tglSEP/'.$tanggal.''; 
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL,$url);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 1);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    	//echo "<br>$url";
    	// execute the request
    	$output = curl_exec($ch);
    	curl_close($ch);
    	//echo $output;
    	//menghilangkan respone diatasnya
    	$jsonnya = strstr($output,'{');
    	$jsonnya = str_replace("[","",$jsonnya);
    	$jsonnya = str_replace("]","",$jsonnya);
    	//echo $jsonnya;
    	//mengubah menjadi array
    	$hasilx	= json_decode($jsonnya,true);
    	$pesannya		= $hasilx['metaData']['message'];
//    	echo $pesannya;
    	if($pesannya =="OK"){
	    	$no_asuransi	= $hasilx['response']['peserta']['noKartu'];
	    	$nama			= str_replace("'","`",$hasilx['response']['peserta']['nama']);
	    	//$hubunganKeluarga			= $hasilx['response']['peserta']['hubunganKeluarga'];
	    	$faskes_1		= $hasilx['response']['peserta']['provUmum']['nmProvider'];
	    	$kdProvider		= $hasilx['response']['peserta']['provUmum']['kdProvider'];
	    	$ketAktif		= $hasilx['response']['peserta']['statusPeserta']['keterangan'];
	    	$kelamin		= $hasilx['response']['peserta']['sex'];
	    	$lahir			= $hasilx['response']['peserta']['tglLahir'];
	    	$no_ktp			= $hasilx['response']['peserta']['nik'];
	    	$jenis_asuransi	= $hasilx['response']['peserta']['jenisPeserta']['keterangan'];
	    	$nmKelas		= $hasilx['response']['peserta']['hakKelas']['keterangan'];
	    	$kdKelas		= $hasilx['response']['peserta']['hakKelas']['kode'];
			$prolanisPRB	= $hasilx['response']['peserta']['informasi']['prolanisPRB'];
//			$data[] 		= $hasilx['response']['peserta'];

			if ($ketAktif=="AKTIF"){
				header('Content-Type: application/json');
				echo '{"message" : "AKTIF", 
					"no_asuransi":'.json_encode($no_asuransi).',
					"nama":'.json_encode($nama).',
					"faskes_1":'.json_encode($faskes_1).',
					"kdProvider":'.json_encode($kdProvider).',
					"ketAktif":'.json_encode($ketAktif).', 
					"kelamin":'.json_encode($kelamin).',
					"lahir":'.json_encode($lahir).',
					"no_ktp":'.json_encode($no_ktp).',
					"jenis_asuransi":'.json_encode($jenis_asuransi).',
					"nmKelas":'.json_encode($nmKelas).',
					"kdKelas":'.json_encode($kdKelas).',
					"prolanisPRB":'.json_encode($prolanisPRB).'}';
			} else {
				header('Content-Type: application/json');
				echo '{"message" : "TIDAK AKTIF"}';	
			}			
		} else {	
	    	header('Content-Type: application/json');
			echo '{"message" : "BPJS Belum Terdaftar"}';	
		}
	}

	public function getKtp($no_kartux){
		header('Content-Type: application/json');
//		$bridging_alamat	='http://172.16.6.200/percobaan/daftar_online/umum/';
//		$bridging_alamat='http://dinkes.madiunkota.go.id/rsud/android/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url 				= ''.$bridging_alamat.'cek_ktp.php';	
		
		$getjobdetail = '{
			"no_kartux": "'.$no_kartux.'"
		}';

		$process = curl_init($url); 
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);

		$jsonnya = strstr($output,'{');
//		echo $output;

		$hasilx	= json_decode($jsonnya,true);
		$data 	= array();
		
		if($hasilx['metadata']=="OK"){
			foreach($hasilx['response'] as $row){
				$temp ['nama']				= $row['NAMA_LGKP'];
				$temp ['kelamin']			= $row['JENIS_KLMIN'];
				$temp ['lahir']				= $row['TGL_LHR'];
				$temp ['NAMA_LGKP_IBU']		= $row['NAMA_LGKP_IBU'];
				$temp ['NAMA_LGKP_AYAH']	= $row['NAMA_LGKP_AYAH'];
				$temp ['alamat']			= $row['ALAMAT'];
				$temp ['RT'] 				= $row['NO_RT'];
				$temp ['RW']				= $row['NO_RW'];
				$temp ['desa']				= $row['NAMA_KEL'];
				$temp ['kecamatan']			= $row['NAMA_KEC'];
				$temp ['NO_KK']				= $row['NO_KK'];
				$temp ['ktp']				= $row['NIK'];
				$temp ['HUBUNGAN']			= $row['HUBUNGAN'];
				$temp ['PERKAWINAN']		= $row['PERKAWINAN'];
				$data[]						= $temp;
			}
			$nama				= $temp['nama'];
			$kelamin			= $temp['kelamin'];
			$lahir				= $temp['lahir'];
			$ibu 				= $temp['NAMA_LGKP_IBU'];
			$ayah 				= $temp['NAMA_LGKP_AYAH'];
			$alamat 			= $temp['alamat'];
			$RT 				= $temp['RT'];
			$RW 				= $temp['RW'];
			$desa 				= $temp['desa'];
			$kecamatan 			= $temp['kecamatan'];
			$NO_KK 				= $temp['NO_KK'];
			$ktp 				= $temp['ktp'];
			$HUBUNGAN 			= $temp['HUBUNGAN'];
			$PERKAWINAN 		= $temp['PERKAWINAN'];

			echo '{"message":"Berhasil", 
				"nama":'.json_encode($nama).',
				"kelamin":'.json_encode($kelamin).',
				"lahir":'.json_encode($lahir).', 
				"ibu":'.json_encode($ibu).',
				"ayah":'.json_encode($ayah).',
				"alamat":'.json_encode($alamat).',
				"RT":'.json_encode($RT).',
				"RW":'.json_encode($RW).',
				"desa":'.json_encode($desa).',
				"kecamatan":'.json_encode($kecamatan).',
				"NO_KK":'.json_encode($NO_KK).',
				"ktp":'.json_encode($ktp).',
				"HUBUNGAN":'.json_encode($HUBUNGAN).',
				"PERKAWINAN":'.json_encode($PERKAWINAN).'}';
		} else {
			header('Content-Type: application/json');
            echo '{"response" : "0"}';
		}
	}
	
	public function cekRegistrasi($no_kartux, $kartunya, $ktp){
	    $sql_bpjs 		= "SELECT * FROM user_pasien WHERE no_asuransi = '".$no_kartux."'";					
		$result_bpjs 	= $this->conn->query($sql_bpjs);

		if ($result_bpjs->num_rows == 0){
			$sql_ktp 	= "SELECT * FROM user_pasien WHERE no_ktp = '".$ktp."'";
			$result_ktp	= $this->conn->query($sql_ktp);
			
			if ($result_ktp->num_rows == 0){
//				$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
				$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
				$url = ''.$bridging_alamat.'cek_kartu.php';

				$getjobdetail = '{
					"no_kartux": "'.$no_kartux.'", 
					"kartunya": "'.$kartunya.'"
				}';

				$process = curl_init($url);
				curl_setopt($process, CURLOPT_TIMEOUT, 30); 
				curl_setopt($process, CURLOPT_POST, 1); 
				curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
				curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

				$output = curl_exec($process); 
				curl_close($process);		

				$jsonnya = strstr($output,'{');
//				echo $output;

				$hasilx	= json_decode($jsonnya,true);
				$status	= $hasilx['metadata'];

        		if($status =="OK"){
            		header('Content-Type: application/json');
					$data = array();
            		foreach($hasilx['response'] as $row){
						$temp['id'] 			= $row['id'];
						$temp['rm']   			= $row['no_rm'];
						$temp['nama']   		= $row['nama'];
						$temp['sebutan']   		= $row['sebutan'];
						$temp['kelamin']   		= $row['kelamin'];
						$temp['alamat']   		= $row['alamat'];
						$temp['desa']   		= $row['desa'];
						$temp['kecamatan']   	= $row['kecamatan'];
						$temp['kabupaten']   	= $row['kabupaten'];
						$temp['provinsi']   	= $row['provinsi'];
						$temp['lahir']   		= $row['lahir'];
						//$temp['foto']   		= $row['foto'];
						$temp['no_telp']   		= $row['hp'];
						$temp['no_ktp']   		= $row['no_ktp'];
						$temp['id_asuransi']   	= $row['id_asuransi'];
						$temp['no_asuransi']   	= $row['no_asuransi'];
						$temp['jenis_asuransi']	= $row['jenis_asuransi'];
						$temp['kelas_jatah']	= $row['kelas_jatah'];
						//$temp['email']   		= $row['email'];
						$data[]             	= $temp;			
					}
					
					header('Content-Type: application/json');
					echo '{"message" : "Gagal Registrasi, Nomor BPJS anda sudah terdaftar"}';
				} else {
					header('Content-Type: application/json');					
					echo '{"message" : "BERHASIL"}';
				}
			} else {
				header('Content-Type: application/json');
				echo '{"message" : "Gagal Registrasi, Nomor KTP sudah terdaftar (userpasien)"}';
			}			
		} else {
			header('Content-Type: application/json');
			echo '{"message" : "Gagal Registrasi, Nomor BPJS sudah pernah didaftarkan(userpasien)"}';
		}
	}

	public function cekRegistrasiKtp($no_kartux, $kartunya){
	    $sql_ktp 		= "SELECT * FROM user_pasien WHERE no_ktp = '".$no_kartux."'";					
		$result_ktp 	= $this->conn->query($sql_ktp);

		if ($result_ktp->num_rows == 0){
//			$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
			$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
			$url = ''.$bridging_alamat.'cek_kartu.php';

			$getjobdetail = '{
				"no_kartux": "'.$no_kartux.'", 
				"kartunya": "'.$kartunya.'"
			}';

			$process = curl_init($url);
			curl_setopt($process, CURLOPT_TIMEOUT, 30); 
			curl_setopt($process, CURLOPT_POST, 1); 
			curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
			curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

			$output = curl_exec($process); 
			curl_close($process);		

			$jsonnya = strstr($output,'{');
//				echo $output;

			$hasilx	= json_decode($jsonnya,true);
			$status	= $hasilx['metadata'];

			if($status =="OK"){
				header('Content-Type: application/json');
				$data = array();
				foreach($hasilx['response'] as $row){
					$temp['id'] 			= $row['id'];
					$temp['rm']   			= $row['no_rm'];
					$temp['nama']   		= $row['nama'];
					$temp['sebutan']   		= $row['sebutan'];
					$temp['kelamin']   		= $row['kelamin'];
					$temp['alamat']   		= $row['alamat'];
					$temp['desa']   		= $row['desa'];
					$temp['kecamatan']   	= $row['kecamatan'];
					$temp['kabupaten']   	= $row['kabupaten'];
					$temp['provinsi']   	= $row['provinsi'];
					$temp['lahir']   		= $row['lahir'];
					//$temp['foto']   		= $row['foto'];
					$temp['no_telp']   		= $row['hp'];
					$temp['no_ktp']   		= $row['no_ktp'];
					$temp['id_asuransi']   	= $row['id_asuransi'];
					$temp['no_asuransi']   	= $row['no_asuransi'];
					$temp['jenis_asuransi']	= $row['jenis_asuransi'];
					$temp['kelas_jatah']	= $row['kelas_jatah'];
					//$temp['email']   		= $row['email'];
					$data[]             	= $temp;			
				}
				header('Content-Type: application/json');
				echo '{"message" : "Gagal Registrasi, Nomor KTP anda sudah terdaftar"}';
			} else {
				header('Content-Type: application/json');
				echo '{"message" : "BERHASIL"}';
			}
		} else {
			header('Content-Type: application/json');
			echo '{"message" : "Gagal Registrasi, Nomor KTP sudah terdaftar (userpasien)"}';
		}
	}

	public function registrasi ($sebutan, $nama, $kelamin, $lahir, $etnis, $alamat, $lokasi, $hp, 
		$no_ktp, $pendidikan, $pekerjaan, $agama, $asuransi, $no_asuransi, $jenis_asuransi){
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'daftar_baru.php';	
		$getjobdetail = '{
			"sebutan": "'.$sebutan.'",
			"nama": "'.$nama.'",
			"kelamin": "'.$kelamin.'",
			"lahir": "'.$lahir.'",
			"etnis": "'.$etnis.'",
			"alamat": "'.$alamat.'",
			"lokasi": "'.$lokasi.'",
			"hp": "'.$hp.'",
			"no_ktp": "'.$no_ktp.'",
			"pendidikan": "'.$pendidikan.'",
			"pekerjaan": "'.$pekerjaan.'",
			"agama": "'.$agama.'",
			"asuransi": "'.$asuransi.'",
			"no_asuransi": "'.$no_asuransi.'",
			"jenis_asuransi": "'.$jenis_asuransi.'"
		}';
		//echo $getjobdetail;
		//echo $url;   
		$process = curl_init($url); 
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);
		//menghilangkan respone diatasnya
		$jsonnya = strstr($output,'{');
		//echo $output;
		$hasilx	= json_decode($jsonnya,true);
		$status	= $hasilx['metadata'];
		
		if($status=="OK"){
//			include "registrasiUser.php";
			header('Content-Type: application/json');
			echo '{"message" : "BERHASIL"}';	
		}else{
			header('Content-Type: application/json');
			echo '{"message" : "Pendaftaran gagal."}';	
		}
	}

	public function registrasiUser($no_kartux, $kartunya){
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'cek_kartu.php';

		$getjobdetail = '{
			"no_kartux": "'.$no_kartux.'", 
			"kartunya": "'.$kartunya.'"
		}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);		

		$jsonnya = strstr($output,'{');
//		echo $output;

		$hasilx	= json_decode($jsonnya,true);
		$status	= $hasilx['metadata'];

        if($status =="OK"){
            header('Content-Type: application/json');
			$data = array();
            foreach($hasilx['response'] as $row){
				$temp['id'] 			= $row['id'];
				$temp['rm']   			= $row['no_rm'];
				$temp['nama']   		= $row['nama'];
				$temp['sebutan']   		= $row['sebutan'];
				$temp['kelamin']   		= $row['kelamin'];
				$temp['alamat']   		= $row['alamat'];
				$temp['desa']   		= $row['desa'];
				$temp['kecamatan']   	= $row['kecamatan'];
				$temp['kabupaten']   	= $row['kabupaten'];
				$temp['provinsi']   	= $row['provinsi'];
				$temp['lahir']   		= $row['lahir'];
				//$temp['foto']   		= $row['foto'];
				$temp['no_telp']   		= $row['hp'];
				$temp['no_ktp']   		= $row['no_ktp'];
				$temp['id_asuransi']   	= $row['id_asuransi'];
				$temp['no_asuransi']   	= $row['no_asuransi'];
				$temp['jenis_asuransi']	= $row['jenis_asuransi'];
				$temp['kelas_jatah']	= $row['kelas_jatah'];
						//$temp['email']   		= $row['email'];
				$data[]             	= $temp;			
			}

			$id						= $temp['id'];
			$norm					= $temp['rm'];
			$nama					= $temp['nama'];
			$sebutan				= $temp['sebutan'];
			$kelamin				= $temp['kelamin'];
			$alamat					= $temp['alamat'];
			$desa					= $temp['desa'];
			$kecamatan				= $temp['kecamatan'];
			$kabupaten				= $temp['kabupaten'];
			$provinsi				= $temp['provinsi'];
			$lahir					= $temp['lahir'];
			$no_telp				= $temp['no_telp'];
			$no_ktp					= $temp['no_ktp'];
			$id_asuransi			= $temp['id_asuransi'];
			$no_asuransi			= $temp['no_asuransi'];
			$jenis_asuransi			= $temp['jenis_asuransi'];
			$kelas_jatah			= $temp['kelas_jatah'];

			$acak       	= "1933FAasdsk25kwBjakjDlff1988"; 
			$panjang    	= '6';
			$len        	= strlen($acak);
			$start      	= $len-$panjang; 
			$xx         	= rand('0',$start);	
			$yy         	= str_shuffle($acak);
			$token      	= substr($yy, $xx, $panjang);
			$tmplahir   	= "";
			
			if ($kelamin=="L"){
			    $foto = "pasien.png";
			} else {
				$foto = "pasien_women.png";
			}

			$email      	= "";
			$password		= "";
			$aktivasi   	= "T";

			$sql = "INSERT INTO user_pasien 
				(id, rm, nama, sebutan, kelamin, alamat, desa, kecamatan, kabupaten, provinsi, tmp_lahir, 
				lahir, foto, no_telp, no_ktp, id_asuransi, no_asuransi, jenis_asuransi, kelas_jatah, 
				email, password, aktivasi, token) 
				VALUES('".$id."','".$norm."', '".$nama."', '".$sebutan."', '".$kelamin."', '".$alamat."', '".$desa."', 
				'".$kecamatan."', '".$kabupaten."', '".$provinsi."', '".$tmplahir."', '".$lahir."', '".$foto."', 
				'".$no_telp."', '".$no_ktp."', '".$id_asuransi."', '".$no_asuransi."','".$jenis_asuransi."', 
				'".$kelas_jatah."', '".$email."', '".$password."', '".$aktivasi."', '".$token."')";
						
			if ($this->conn->query($sql) == TRUE){
				header('Content-Type: application/json');
				echo '{"message" : "BERHASIL","rm":'.json_encode($norm, true).'}';
			} else {
				header('Content-Type: application/json');
				echo '{"message" : "Gagal registrasi, silahkan registrasi ulang"}';
			}
		}
	}

	public function registrasiKtp($no_kartux, $kartunya, $rm, $password){
	    $sql_ktp 		= "SELECT * FROM user_pasien WHERE no_ktp = '".$no_kartux."'";					
		$result_ktp 	= $this->conn->query($sql_ktp);
//		echo $result_bpjs->num_rows;

		if ($result_ktp->num_rows == 0){
			$sql_rm 		= "SELECT * FROM user_pasien WHERE rm = '".$rm."'";
			$result_rm	= $this->conn->query($sql_rm);
			
			if ($result_rm->num_rows == 0){
//				$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
				$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
				$url = ''.$bridging_alamat.'cek_kartu.php';

				$getjobdetail = '{
					"no_kartux": "'.$no_kartux.'", 
					"kartunya": "'.$kartunya.'"
				}';

				$process = curl_init($url);
				curl_setopt($process, CURLOPT_TIMEOUT, 30); 
				curl_setopt($process, CURLOPT_POST, 1); 
				curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
				curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

				$output = curl_exec($process); 
				curl_close($process);		

				$jsonnya = strstr($output,'{');
//				echo $output;

				$hasilx	= json_decode($jsonnya,true);
				$status	= $hasilx['metadata'];

        		if($status =="OK"){
            		header('Content-Type: application/json');
					$data = array();
            		foreach($hasilx['response'] as $row){
						$temp['id'] 			= $row['id'];
						$temp['rm']   			= $row['no_rm'];
						$temp['nama']   		= $row['nama'];
						$temp['sebutan']   		= $row['sebutan'];
						$temp['kelamin']   		= $row['kelamin'];
						$temp['alamat']   		= $row['alamat'];
						$temp['desa']   		= $row['desa'];
						$temp['kecamatan']   	= $row['kecamatan'];
						$temp['kabupaten']   	= $row['kabupaten'];
						$temp['provinsi']   	= $row['provinsi'];
						$temp['lahir']   		= $row['lahir'];
						//$temp['foto']   		= $row['foto'];
						$temp['no_telp']   		= $row['hp'];
						$temp['no_ktp']   		= $row['no_ktp'];
						$temp['id_asuransi']   	= $row['id_asuransi'];
						$temp['no_asuransi']   	= $row['no_asuransi'];
						$temp['jenis_asuransi']	= $row['jenis_asuransi'];
						$temp['kelas_jatah']	= $row['kelas_jatah'];
						//$temp['email']   		= $row['email'];
						$data[]             	= $temp;			
					}

					$id						= $temp['id'];
					$norm					= $temp['rm'];
					$nama					= $temp['nama'];
					$sebutan				= $temp['sebutan'];
					$kelamin				= $temp['kelamin'];
					$alamat					= $temp['alamat'];
					$desa					= $temp['desa'];
					$kecamatan				= $temp['kecamatan'];
					$kabupaten				= $temp['kabupaten'];
					$provinsi				= $temp['provinsi'];
					$lahir					= $temp['lahir'];
					$no_telp				= $temp['no_telp'];
					$no_ktp					= $temp['no_ktp'];
					$id_asuransi			= $temp['id_asuransi'];
					$no_asuransi			= $temp['no_asuransi'];
					$jenis_asuransi			= $temp['jenis_asuransi'];
					$kelas_jatah			= $temp['kelas_jatah'];

					if ($rm == $norm){
						$acak       	= "1933FAasdsk25kwBjakjDlff1988"; 
						$panjang    	= '6';
						$len        	= strlen($acak);
						$start      	= $len-$panjang; 
						$xx         	= rand('0',$start);	
						$yy         	= str_shuffle($acak);
						$token      	= substr($yy, $xx, $panjang);
						$tmplahir   	= "";
						$lahirnull		= "";
						
						if ($kelamin=="L"){
					        $foto = "pasien.png";
					    } else {
					        $foto = "pasien_women.png";
					    }
						$email      	= "";
						$aktivasi   	= "T";

						$sql = "INSERT INTO user_pasien 
							(id, rm, nama, sebutan, kelamin, alamat, desa, kecamatan, kabupaten, provinsi, tmp_lahir, 
							lahir, foto, no_telp, no_ktp, id_asuransi, no_asuransi, jenis_asuransi, kelas_jatah, 
							email, password, aktivasi, token) 
							VALUES('".$id."','".$norm."', '".$nama."', '".$sebutan."', '".$kelamin."', '".$alamat."', '".$desa."', 
							'".$kecamatan."', '".$kabupaten."', '".$provinsi."', '".$tmplahir."', '".$lahir."', '".$foto."', 
							'".$no_telp."', '".$no_ktp."', '".$id_asuransi."', '".$no_asuransi."','".$jenis_asuransi."', 
							'".$kelas_jatah."', '".$email."', '".$password."', '".$aktivasi."', '".$token."')";
						
						if ($this->conn->query($sql) == TRUE){
							header('Content-Type: application/json');
							echo '{"message" : "Registrasi berhasil, silahkan login."}';
						} else {
							header('Content-Type: application/json');
							echo '{"message" : "Gagal registrasi, silahkan registrasi ulang"}';
						}
					} else {
						header('Content-Type: application/json');
						echo '{"message" : "nomor Rekam Medis anda salah"}';
					}
				} else {
					header('Content-Type: application/json');
					echo '{"message" : "Periksa kembali Metode Pendaftaran anda"}';
				}
			} else {
				header('Content-Type: application/json');
				echo '{"message" : "Gagal Registrasi, Nomor Rekam Medis sudah pernah didaftarkan"}';
			}			
		} else {
			header('Content-Type: application/json');
			echo '{"message" : "Gagal Registrasi, Nomor KTP sudah pernah didaftarkan"}';
		}
	}
	
	public function selectUser($id){
		header('Content-Type: application/json');
        $sql = "SELECT * FROM user_pasien WHERE id='".$id."'";
		$result = $this->conn->query($sql);
		
        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
            $row = $result->fetch_assoc();
			$temp['metadata']    	= 'OK';
			$temp['id'] 			= $row['id'];
			$temp['rm']   			= $row['rm'];
			$temp['nama']   		= $row['nama'];
			$temp['sebutan']   		= $row['sebutan'];
			$temp['kelamin']   		= $row['kelamin'];
			$temp['alamat']   		= $row['alamat'];
			$temp['desa']   		= $row['desa'];
			$temp['kecamatan']   	= $row['kecamatan'];
			$temp['kabupaten']   	= $row['kabupaten'];
			$temp['provinsi']   	= $row['provinsi'];
			$temp['tmp_lahir']   	= $row['tmp_lahir'];
			$temp['lahir']   		= $row['lahir'];
			$temp['foto']   		= $row['foto'];
			$temp['no_telp']   		= $row['no_telp'];
			$temp['no_ktp']   		= $row['no_ktp'];
			$temp['id_asuransi']   	= $row['id_asuransi'];
			$temp['no_asuransi']   	= $row['no_asuransi'];
			$temp['jenis_asuransi']	= $row['jenis_asuransi'];
			$temp['kelas_jatah']   	= $row['kelas_jatah'];
			$temp['email']   		= $row['email'];
			$temp['password']   	= $row['password'];             
            $temp['aktivasi']   	= $row['aktivasi'];             
            $temp['token']      	= $row['token'];
			$data             		= $temp;	
			
			echo json_encode($data,true);
        } else {
            header('Content-Type: application/json');
            echo '{"metadata" : "GAGAL"}';
        }
	}

	public function notifikasi($nama, $title, $text){
	    $sql = "SELECT * FROM user_pasien INNER JOIN notifikasi WHERE user_pasien.id = notifikasi.nid";
		$result = $this->conn->query($sql);
		$row = mysqli_num_rows($result);
		
		$from 		= "RSUD Kota Madiun";
		$picture	= "logoae.png";
		$isread		= "n";
		
		$sqly = "INSERT INTO notifikasi
				(nid, dari, nama, title, text, picture, waktu, isread)
				VALUES((SELECT id from user_pasien WHERE nama = '".$nama."'), '".$from."', '".$nama."', '".$title."', '".$text."', '".$picture."', CURRENT_TIMESTAMP, '".$isread."')";
					
			if ($this->conn->query($sqly) == TRUE){
				header('Content-Type: application/json');
				echo '{"message" : "notifikasi masuk"}';
			} else {
				header('Content-Type: application/json');
				echo '{"message" : "notifikasi gagal"}';
			}
	} 
	
	public function getNotif($id){
        $sql = "SELECT notifikasi.*, user_pasien.id FROM notifikasi INNER JOIN user_pasien ON user_pasien.id=notifikasi.nid WHERE notifikasi.nid = '".$id."' ORDER BY notifikasi.waktu DESC";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
            $data = array();
            while ($row = $result->fetch_assoc()) {
				$temp['id']			= $row['id_notif'];
				$temp['nid']		= $row['nid'];
				$temp['dari']		= $row['dari'];
				$temp['nama']		= $row['nama'];
				$temp['title']		= $row['title'];
				$temp['text']		= $row['text'];
				$temp['picture']	= $row['picture'];
				$temp['waktu']		= $row['waktu'];
				$temp['isread']		= $row['isread'];
				$data[]				= $temp;
			}
            echo '{"message" : "Berhasil","results":'.json_encode($data).'}';
        } else {
            header('Content-Type: application/json');
			echo '{"message" : "Anda tidak mempunyai pesan"}';
        }
    }
	
	public function selectNotif($id_notif){
		$sql="SELECT * FROM notifikasi WHERE id_notif='".$id_notif."'";
		$result = $this->conn->query($sql);
		
		if ($result->num_rows > 0) {
			header('Content-Type: application/json');
			$row = $result->fetch_assoc();
			$temp['message']    = 'Berhasil';
			$temp['id']			= $row['id_notif'];
			$temp['nid']		= $row['nid'];
			$temp['dari']		= $row['dari'];
			$temp['nama']		= $row['nama'];
			$temp['title']		= $row['title'];
			$temp['text']		= $row['text'];
			$temp['picture']	= $row['picture'];
			$temp['waktu']		= $row['waktu'];
			$temp['isread']		= $row['isread'];
			$data[]				= $temp;
			echo json_encode($data);
		} else {
			header('Content-Type: application/json');
            echo '{"results" : "0"}';
		}
    }
	
	public function updateNotif ($id_notif){
//		$sql="SELECT * FROM notifikasi WHERE id_notif='".$id_notif."'";
/*        $sql = "SELECT * FROM notifikasi WHERE notifikasi.id_notif = '".$id_notif."'";
		$result = $this->conn->query($sql);
		
		header('Content-Type: application/json');
		$data = array();
	
		while ($row = $result->fetch_assoc()) {
			$temp['id']			= $row['id_notif'];
			$temp['nid']		= $row['nid'];
			$temp['dari']		= $row['dari'];
			$temp['nama']		= $row['nama'];
			$temp['title']		= $row['title'];
			$temp['text']		= $row['text'];
			$temp['picture']	= $row['picture'];
			$temp['waktu']		= $row['waktu'];
			$temp['isread']		= $row['isread'];
			$data[]				= $temp;
//			$id                 = $row['id_notif'];
//			$read               = $row['isread'];
		}
		echo '{"result":'.json_encode($data).'}';

		if ($result->num_rows > 0) {*/
		    $isread = "y";
		    
			$sqly = "UPDATE notifikasi 
            SET isread  = '".$isread."' WHERE id_notif='".$id_notif."'";
	
			if ($this->conn->query($sqly) == TRUE){
				header('Content-Type: application/json');
				echo '{"message" : '.json_encode($id_notif,TRUE).'}';
			} else {
				header('Content-Type: application/json');
				echo '{"message" : "notifikasi gagal"}';
			}
/*		} else {
			header('Content-Type: application/json');
				echo '{"message" : "GAGAL"}';
		}*/
	}
	
	public function hapusNotif($id){   
        $sqlcek = "SELECT * FROM notifikasi WHERE notifikasi.id_notif = '".$id."'";
//		$sqlcek = "SELECT notifikasi.*, user_pasien.id FROM notifikasi INNER JOIN user_pasien ON user_pasien.id=notifikasi.nid WHERE notifikasi.nid = '".$id."' ORDER BY notifikasi.waktu DESC";
        
        if ($this->conn->query($sqlcek)->num_rows > 0) {
            $sql = "DELETE FROM notifikasi WHERE id_notif = '".$id."'";
			
			$this->conn->query($sql); 
			
			header('Content-Type: application/json');
			echo '{"message" : "Notifikasi berhasil dihapus"}';
        }else{
            header('Content-Type: application/json');
			echo '{"message" : "Gagal menghapus notifikasi"}';
        }
	}
	
	public function getKartu($no_kartux, $kartunya, $tanggal){		
        header('Content-Type: application/json');
//		$bridging_alamat='http://172.16.6.200/daftar_online/umum/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'cek_kartu.php';

//		$kartunya="2";

		$getjobdetail = '{
			"no_kartux": "'.$no_kartux.'", 
			"kartunya": "'.$kartunya.'",
			"tanggal": "'.$tanggal.'"
		}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);		

		$jsonnya = strstr($output,'{');
//		echo $output;

		$hasilx	= json_decode($jsonnya,true);
		$status	= $hasilx['metadata'];

        if($status == "OK"){
            header('Content-Type: application/json');
			$data = array();
            foreach($hasilx['response'] as $row){
				$temp['id'] 			= $row['id'];
				$temp['rm']   			= $row['no_rm'];
				$temp['nama']   		= $row['nama'];
				$temp['sebutan']   		= $row['sebutan'];
				$temp['kelamin']   		= $row['kelamin'];
				$temp['alamat']   		= $row['alamat'];
				$temp['desa']   		= $row['desa'];
				$temp['kecamatan']   	= $row['kecamatan'];
				$temp['kabupaten']   	= $row['kabupaten'];
				$temp['provinsi']   	= $row['provinsi'];
				$temp['lahir']   		= $row['lahir'];
				//$temp['foto']   		= $row['foto'];
				$temp['no_telp']   		= $row['hp'];
				$temp['no_ktp']   		= $row['no_ktp'];
				$temp['id_asuransi']   	= $row['id_asuransi'];
				$temp['no_asuransi']   	= $row['no_asuransi'];
				$temp['jenis_asuransi']	= $row['jenis_asuransi'];
				$temp['kelas_jatah']	= $row['kelas_jatah'];
				//$temp['email']   		= $row['email'];
				$data[]             	= $temp;			
			}

			$id					= $temp['id'];
			$rm					= $temp['rm'];
			$nama				= $temp['nama'];
			$sebutan			= $temp['sebutan'];
			$kelamin			= $temp['kelamin'];
			$alamat 			= $temp['alamat'];
			$desa				= $temp['desa'];
			$kecamatan			= $temp['kecamatan'];
			$kabupaten			= $temp['kabupaten'];
			$provinsi 			= $temp['provinsi'];
			$lahir 				= $temp['lahir'];
			$no_telp			= $temp['no_telp'];
			$no_ktp 			= $temp['no_ktp'];
			$id_asuransi 		= $temp['id_asuransi'];
			$no_asuransi		= $temp['no_asuransi'];
			$jenis_asuransi		= $temp['jenis_asuransi'];
			$kelas_jatah 		= $temp['kelas_jatah'];

			header('Content-Type: application/json');
			echo '{"metadata":"OK", 
				"id":'.json_encode($id).',
				"rm":'.json_encode($rm).',
				"nama":'.json_encode($nama).', 
				"sebutan":'.json_encode($sebutan).',
				"kelamin":'.json_encode($kelamin).',
				"alamat":'.json_encode($alamat).',
				"desa":'.json_encode($desa).',
				"kecamatan":'.json_encode($kecamatan).',
				"kabupaten":'.json_encode($kabupaten).',
				"provinsi":'.json_encode($provinsi).',
				"lahir":'.json_encode($lahir).',
				"no_telp":'.json_encode($no_telp).',
				"no_ktp":'.json_encode($no_ktp).',
				"id_asuransi":'.json_encode($id_asuransi).',
				"no_asuransi":'.json_encode($no_asuransi).',
				"jenis_asuransi":'.json_encode($jenis_asuransi).',
				"kelas_jatah":'.json_encode($kelas_jatah).'}';
			
			//echo '{"metadata" : "OK", "message" : "BERHASIL", "results":'.json_encode($data, true).'}';
        } else {
            header('Content-Type: application/json');
            echo '{"message" : "GAGAL", "results" : "0"}';
		}
	}
	
	public function getKamar($id){		
        header('Content-Type: application/json');
//		$data = array();

		$url = 'http://172.16.6.200/bridging/tampilan/status_kamar.php'; 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$output = curl_exec($ch); 
		curl_close($ch);		

//		echo $output;

		$hasilx	= json_decode($output,true);
		$status		= $hasilx['metadata'];

        if($status =="OK"){
            header('Content-Type: application/json');
			$data = array();
            foreach($hasilx['response'] as $row){
//				$temp['metadata']	= 'OK';
				$temp['nama'] 		= $row['nama'];
				$temp['kelas']   	= $row['kelas'];
				$temp['bed']       	= $row['bed'];
				$temp['isi']   		= $row['isi'];
				$temp['sisa']		= $row['sisa'];
				$data[]             = $temp;			
			}
            echo '{"results":'.json_encode($data).'}';
        } else {
            header('Content-Type: application/json');
            echo '{"results" : "0"}';
		}
	}
	
	public function getKlinik($id){		
        header('Content-Type: application/json');
		$data = array();
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
//		$bridging_alamat='http://dinkes.madiunkota.go.id/rsud/android/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'cek_poli.php';
        
        $kode_bpjs 		= "";
        $online 		= "Y";
		$getjobdetail 	= '{"kode_bpjs": "'.$kode_bpjs.'","online": "'.$online.'"}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);		

		$jsonnya = strstr($output,'{');
//		echo $output;

		$hasilx	= json_decode($jsonnya,true);
		$status	= $hasilx['metadata'];

        if($status == "OK"){
			foreach($hasilx['response'] as $row){
				$temp['id'] 		= $row['id'];
				$temp['nama']   	= $row['nama'];
				$temp['kode_bpjs']	= $row['kode_bpjs'];
				$temp['aktif']		= $row['aktif'];
				$data[]            = $temp;			
			}

/*		$aktif = array_filter($data, function($row) {
			return $row['aktif'] != "Y";
		});*/

		echo '{"results":'.json_encode($data, true).'}';
		} else {
            header('Content-Type: application/json');
            echo '{"results" : "0"}';
		}
	}
	
	public function getDaftar($noid, $ruang, $no_rujukan, $id_asuransi, $tanggal){		
        header('Content-Type: application/json');
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'daftar.php';

		$getjobdetail = '{
			"noid": "'.$noid.'",
			"ruang": "'.$ruang.'",
			"no_rujukan": "'.$no_rujukan.'",
			"id_asuransi": "'.$id_asuransi.'",
			"tanggal": "'.$tanggal.'"
		}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);		

		$jsonnya = strstr($output,'{');
//		echo $output;

		$hasilx	= json_decode($jsonnya,true);
		$status	= $hasilx['metadata'];

        if($status =="OK"){
			$antrian		= $hasilx['response']['antrian'];
			$waktu_mulai	= $hasilx['response']['waktu_mulai'];
			$menit_periksa	= $hasilx['response']['menit_periksa'];
			$nama_pasien	= $hasilx['response']['nama_pasien'];
			$rm				= $hasilx['response']['no_rm'];
			$ruang			= $hasilx['response']['ruang'];
			$asuransi		= $hasilx['response']['asuransi'];
			$tanggal		= $hasilx['response']['tanggal'];
			$id_detail		= $hasilx['response']['id_detail'];
			$waktu_input	= $hasilx['response']['waktu_input'];
			$kode_daftar	= $hasilx['response']['kode_daftar'];
			$data			= $hasilx['response'];

			$sql = "INSERT INTO antrian 
					(noid, antrian, waktu_mulai, menit_periksa, nama_pasien, no_rm, ruang, asuransi, 
					tanggal, id_detail, waktu_input, kode_daftar) 
					VALUES('".$noid."', '".$antrian."', '".$waktu_mulai."', '".$menit_periksa."', '".$nama_pasien."', 
						'".$rm."', '".$ruang."', '".$asuransi."', '".$tanggal."', '".$id_detail."', 
						'".$waktu_input."', '".$kode_daftar."')";
						
			if ($this->conn->query($sql) == TRUE){
				header('Content-Type: application/json');
//				echo '{"metadata" : "BERHASIL", "message" : "Pendaftaran klinik rawat jalan berhasil", "results":'.json_encode($data, true).'}';
				echo '{"metadata" : "BERHASIL", "message" : "Pendaftaran klinik rawat jalan berhasil",
					"antrian":'.json_encode($antrian).',
					"waktu_mulai":'.json_encode($waktu_mulai).',
					"menit_periksa":'.json_encode($menit_periksa).', 
					"nama_pasien":'.json_encode($nama_pasien).',
					"no_rm":'.json_encode($rm).',
					"ruang":'.json_encode($ruang).',
					"asuransi":'.json_encode($asuransi).',
					"tanggal":'.json_encode($tanggal).',
					"id_detail":'.json_encode($id_detail).',
					"waktu_input":'.json_encode($waktu_input).',
					"kode_daftar":'.json_encode($kode_daftar).'}';					
			} else {
				header('Content-Type: application/json');
				echo '{"metadata" : "GAGAL", "message" : "Pendaftaran GAGAL. Cek Ulang pendaftaran anda"}';
			}		

        } else {
			header('Content-Type: application/json');
			echo '{"message" : "Pendaftaran klinik rawat jalan gagal", "results" : "0"}';
		}
	}
	
	public function cekDaftar($noid, $ruang, $no_rujukan, $id_asuransi, $tanggal){		
        header('Content-Type: application/json');
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'cek_daftar.php';

		$getjobdetail = '{
			"noid": "'.$noid.'",
			"ruang": "'.$ruang.'",
			"no_rujukan": "'.$no_rujukan.'",
			"id_asuransi": "'.$id_asuransi.'",
			"tanggal": "'.$tanggal.'"
		}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);		

		$jsonnya = strstr($output,'{');
//		echo $output;

		$hasilx	= json_decode($jsonnya,true);
		$status	= $hasilx['metadata'];

		if ($status =="OK"){
			$antrian		= $hasilx['response']['antrian'];
			$waktu_mulai	= $hasilx['response']['waktu_mulai'];
			$menit_periksa	= $hasilx['response']['menit_periksa'];
			$libur			= $hasilx['response']['libur'];

			echo '{"metadata" : "BERHASIL",
				"antrian":'.json_encode($antrian).',
				"waktu_mulai":'.json_encode($waktu_mulai).',
				"menit_periksa":'.json_encode($menit_periksa).', 
				"libur":'.json_encode($libur).'}';	

/*            if ($antrian == 0){
				header('Content-Type: application/json');
				echo '{"metadata" : "BERHASIL", 
					"antrian":'.json_encode($antrian).',
					"waktu_mulai":'.json_encode($waktu_mulai).',
					"menit_periksa":'.json_encode($menit_periksa).', 
					"libur":'.json_encode($libur).'}';
			}else{
				header('Content-Type: application/json');
				echo '{"metadata" : "BERHASIL ELSE", 
					"antrian":'.json_encode($antrian).',
					"waktu_mulai":'.json_encode($waktu_mulai).',
					"menit_periksa":'.json_encode($menit_periksa).', 
					"libur":'.json_encode($libur).'}';
			}*/
        } else {
			header('Content-Type: application/json');
			echo '{"message" : "Pendaftaran klinik rawat jalan gagal", "results" : "0"}';
		}
	}

	public function cekDaftarPoli($noid, $ruang){		
        header('Content-Type: application/json');
//		$data = array();
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'cek_daftar_poli.php';

		$getjobdetail = '{
		  "noid": "'.$noid.'",
		  "ruang": "'.$ruang.'"
		}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);	
//		echo $output;	

		$jsonnya = strstr($output,'{');
		$hasilx		= json_decode($jsonnya,true);
		$status		= $hasilx['metadata'];
		
        if($status =="OK"){	
			$asuransi		= $hasilx['response']['asuransi'];
			$ruang			= $hasilx['response']['ruang'];
			$antrian		= $hasilx['response']['antrian'];
			$tgl_daftar		= $hasilx['response']['tgl_daftar'];
			$waktu_mulai	= $hasilx['response']['waktu_mulai'];
			$menit_periksa	= $hasilx['response']['menit_periksa'];
//			$data[]     = $hasilx['response'];

            if ($antrian == 0){
				header('Content-Type: application/json');
				echo '{"metadata" : "OK", 
					"asuransi":'.json_encode($asuransi).',
					"ruang":'.json_encode($ruang).',
					"antrian":'.json_encode($antrian).',
					"tgl_daftar":'.json_encode($tgl_daftar).',
					"waktu_mulai":'.json_encode($waktu_mulai).', 
					"menit_periksa":'.json_encode($menit_periksa).'}';
			}else{
				header('Content-Type: application/json');
				echo '{"metadata" : "GAGAL", 
					"asuransi":'.json_encode($asuransi).',
					"ruang":'.json_encode($ruang).',
					"antrian":'.json_encode($antrian).',
					"tgl_daftar":'.json_encode($tgl_daftar).',
					"waktu_mulai":'.json_encode($waktu_mulai).', 
					"menit_periksa":'.json_encode($menit_periksa).'}';
			}
        } else {
			header('Content-Type: application/json');
			echo '{"message" : "Pendaftaran klinik rawat jalan gagal", "results" : "0"}';
		}
	}

	public function cekDaftarLama($noid, $tanggal){		
        header('Content-Type: application/json');
//		$data = array();
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'cek_daftar_lama.php';

		$getjobdetail = '{
		  "noid": "'.$noid.'",
		  "tanggal": "'.$tanggal.'"
		}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output 	= curl_exec($process); 
		curl_close($process);
//		echo $output;

		$jsonnya 	= strstr($output,'{');
		$hasilx		= json_decode($jsonnya,true);
		$status		= $hasilx['metadata'];

		if($status =="OK"){	
			$asuransi		= $hasilx['response']['asuransi'];
			$ruang			= $hasilx['response']['ruang'];
			$antrian		= $hasilx['response']['antrian'];
			$waktu_mulai	= $hasilx['response']['waktu_mulai'];
			$menit_periksa	= $hasilx['response']['menit_periksa'];

            if ($antrian == 0){
				header('Content-Type: application/json');
				echo '{"metadata" : "OK", 
					"asuransi":"0",
					"ruang":"0",
					"antrian":'.json_encode($antrian).', 
					"waktu_mulai":"0",
					"menit_periksa":"0"}';
/*				echo '{"metadata" : "OK", 
					"asuransi":'.json_encode($asuransi).',
					"ruang":'.json_encode($ruang).',
					"antrian":'.json_encode($antrian).', 
					"waktu_mulai":'.json_encode($waktu_mulai).',
					"menit_periksa":'.json_encode($menit_periksa).'}';*/
			}else{
				header('Content-Type: application/json');
				echo '{"metadata" : "GAGAL", 
					"asuransi":'.json_encode($asuransi).',
					"ruang":'.json_encode($ruang).',
					"antrian":'.json_encode($antrian).', 
					"waktu_mulai":'.json_encode($waktu_mulai).',
					"menit_periksa":'.json_encode($menit_periksa).'}';
			}
        } else {
			header('Content-Type: application/json');
			echo '{"message" : "Pendaftaran klinik rawat jalan gagal", "results" : "0"}';
		}
	}

	public function cekAsuransi($id_asuransi){		
        header('Content-Type: application/json');
//		$data = array();
//		$bridging_alamat='http://dinkes.madiunkota.go.id/rsud/umum/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
		$url = ''.$bridging_alamat.'cek_asuransi.php';

		$getjobdetail = '{
			"id_asuransi": "'.$id_asuransi.'"
		}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process);
		curl_close($process);		

		$jsonnya = strstr($output,'{');
//		echo $output;

		$hasilx	= json_decode($jsonnya,true);
//		$status	= $hasilx['metadata'];
	}

	public function cekRujukanRs ($no_kartu, $kodebpjs){
	    header('Content-Type: application/json');
	    
	    include "signature.php";
		$url = ''.$alamat_pcare.'/Rujukan/List/Peserta/'.$no_kartu.''; 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        // execute the request
        $output = curl_exec($ch);
        curl_close($ch);
        
//        echo $output;

        //menghilangkan respone diatasnya
        $jsonnya = strstr($output,'{');

        //echo $jsonnya;

        //mengubah menjadi array
        $hasilx		= json_decode($jsonnya,true);
        $codenya	= $hasilx['metaData']['code'];
        $result     = $hasilx['response']['rujukan'];

        if($codenya ==200){
//	        $ix = 0;
            $data = array();
	        foreach($hasilx['response']['rujukan'] as $row){
	            $temp['diag_masuk']     = $row['diagnosa']['kode'];
				$temp['no_rujukan']   	= $row['noKunjungan'];
				$temp['nama']   	    = $row['peserta']['nama'];
				$temp['nik']   	        = $row['peserta']['nik'];
				$temp['noKartu']   	    = $row['peserta']['noKartu'];
				$temp['poliRujukan']   	= $row['poliRujukan']['kode'];
				$temp['poliRujukanNm']  = $row['poliRujukan']['nama'];
				$temp['provPerujuk']   	= $row['provPerujuk']['kode'];
				$temp['nmProvider']   	= $row['provPerujuk']['nama'];
				$temp['tglKunjungan']   = $row['tglKunjungan'];
				$data[]                 = $temp;
			}
			
			$arrayfilter = array_filter($data, function ($item) use ($kodebpjs) {
				$now = date("Y-m-d");
				$d = strtotime("-90 Days");
				$nowmin90 = date("Y-m-d", $d);

				return $item['poliRujukan'] === $kodebpjs AND $item['tglKunjungan'] >= $nowmin90 AND $item['tglKunjungan'] <= $now;
			});

			foreach($arrayfilter as $row){
				$temp['diag_masuk']     = $row['diag_masuk'];
				$temp['no_rujukan']   	= $row['no_rujukan'];
				$temp['nama']   	    = $row['nama'];
				$temp['nik']   	        = $row['nik'];
				$temp['noKartu']   	    = $row['noKartu'];
				$temp['poliRujukan']   	= $row['poliRujukan'];
				$temp['poliRujukanNm']  = $row['poliRujukanNm'];
				$temp['provPerujuk']   	= $row['provPerujuk'];
				$temp['nmProvider']   	= $row['nmProvider'];
				$temp['tglKunjungan']   = $row['tglKunjungan'];
				$datafilter[]           = $temp;
			}

//			$diag_masuk     = $arrayfilter['diag_masuk'];
			$no_rujukan   	= $temp['no_rujukan'];
//			$nama   	    = $arrayfilter['nama'];
//			$nik   	        = $arrayfilter['nik'];
//			$noKartu   	    = $arrayfilter['noKartu'];
			$poliRujukan   	= $temp['poliRujukan'];
//			$poliRujukanNm  = $arrayfilter['poliRujukanNm'];
//			$provPerujuk   	= $arrayfilter['provPerujuk'];
//			$nmProvider   	= $arrayfilter['nmProvider'];
			$tglKunjungan   = $temp['tglKunjungan'];

			header('Content-Type: application/json');
			echo '{"metadata": "BERHASIL", 
				"no_rujukan":'.json_encode($no_rujukan).',
				"poliRujukan":'.json_encode($poliRujukan).',
				"tglKunjungan":'.json_encode($tglKunjungan).'}';
	    } else {
            header('Content-Type: application/json');
            echo '{"metadata" : "GAGAL"}';
	    }
	}
	
    public function cekRujukanRsx ($no_kartu){
	    header('Content-Type: application/json');
	    
	    include "signature.php";
		$url = ''.$alamat_pcare.'/Rujukan/List/Peserta/'.$no_kartu.''; 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        // execute the request
        $output = curl_exec($ch);
        curl_close($ch);
        
//        echo $output;

        //menghilangkan respone diatasnya
        $jsonnya = strstr($output,'{');

        //echo $jsonnya;

        //mengubah menjadi array
        $hasilx		= json_decode($jsonnya,true);
        $codenya	= $hasilx['metaData']['code'];
        $result     = $hasilx['response']['rujukan'];

        if($codenya ==200){
//	        $ix = 0;
            $data = array();
	        foreach($hasilx['response']['rujukan'] as $row){
	            $temp['diag_masuk']     = $row['diagnosa']['kode'];
				$temp['no_rujukan']   	= $row['noKunjungan'];
				$temp['nama']   	    = $row['peserta']['nama'];
				$temp['nik']   	        = $row['peserta']['nik'];
				$temp['noKartu']   	    = $row['peserta']['noKartu'];
				$temp['poliRujukan']   	= $row['poliRujukan']['kode'];
				$temp['poliRujukanNm']  = $row['poliRujukan']['nama'];
				$temp['provPerujuk']   	= $row['provPerujuk']['kode'];
				$temp['nmProvider']   	= $row['provPerujuk']['nama'];
				$temp['tglKunjungan']   = $row['tglKunjungan'];
				$data[]                 = $temp;
			}
			echo '{"results": '.json_encode($data, true).'}';
	    } else {
            header('Content-Type: application/json');
            echo '{"results" : "0"}';
	    }
	}


	public function hapusDaftar($noid, $ruangy){		
        header('Content-Type: application/json');
//		$data = array();
//		$bridging_alamat='http://172.16.6.200/percobaan/daftar_online/umum/';
		$bridging_alamat='http://35.240.153.75/sogaten/android_terima/';
		$url = ''.$bridging_alamat.'hapus_daftar.php';

		$getjobdetail = '{
		  "noid": "'.$noid.'",
		  "ruang": "'.$ruangy.'"
		}';

		$process = curl_init($url);
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		curl_setopt($process, CURLOPT_POST, 1); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $getjobdetail); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$output = curl_exec($process); 
		curl_close($process);		

		$jsonnya = strstr($output,'{');
		echo $output;

		$hasilx	= json_decode($jsonnya,true);
		$status	= $hasilx['metadata'];
	}
	
	public function getAntri($no_rm){
		$tanggal 		= date('Y-m-d');
		$jam 			= date('H:i:s');
		$tgl_besok		= mktime(0,0,0,date("n"),date("j")+1,date("Y"));
		$tanggal_besok  = date("Y-m-d", $tgl_besok);
		
		if ($jam < '12:00:00'){
			$sql = "SELECT antrian.* FROM antrian WHERE antrian.no_rm='".$no_rm."' AND antrian.tanggal >= '".$tanggal."' ORDER BY antrian.tanggal ASC";
		}else{
			$sql = "SELECT antrian.* FROM antrian WHERE antrian.no_rm='".$no_rm."' AND antrian.tanggal >= '".$tanggal_besok."' ORDER BY antrian.tanggal ASC";
		}
		
		$result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
			$data = array();
            while ($row = $result->fetch_assoc()){
				$temp['message']    	= 'Berhasil';
				$temp['id']         	= $row['id'];
				$temp['antrian']   		= $row['antrian'];
				$temp['waktu_mulai']    = $row['waktu_mulai'];
				$temp['menit_periksa']  = $row['menit_periksa'];
				$temp['nama_pasien']	= $row['nama_pasien'];
				$temp['no_rm']  		= $row['no_rm'];
				$temp['ruang']  		= $row['ruang'];
				$temp['asuransi']      	= $row['asuransi'];
				$temp['tanggal']   		= $row['tanggal'];
				$temp['id_detail']		= $row['id_detail'];
				$temp['waktu_input']	= $row['waktu_input'];
				$temp['kode_daftar']	= $row['kode_daftar'];
				$data[]             	= $temp;			
			}
            //echo json_encode($data);
            echo '{"message" : "Berhasil","results":'.json_encode($data).'}';
        } else {
            header('Content-Type: application/json');
            echo '{"results" : "0"}';
        }
	}
	
	public function getRiwayat($no_rm){
		$tanggal 		= date('Y-m-d');
		$jam 			= date('H:i:s');
		$tgl_besok		= mktime(0,0,0,date("n"),date("j")+1,date("Y"));
		$tanggal_besok  = date("Y-m-d", $tgl_besok);
		
		$sql = "SELECT antrian.* FROM antrian WHERE antrian.no_rm='".$no_rm."' AND antrian.tanggal < '".$tanggal."' ORDER BY antrian.tanggal DESC";
		
/*		if ($jam < '12:00:00'){
			$sql = "SELECT antrian.* FROM antrian WHERE antrian.no_rm='".$no_rm."' AND antrian.tanggal >= '".$tanggal."' ORDER BY antrian.tanggal ASC";
		}else{
			$sql = "SELECT antrian.* FROM antrian WHERE antrian.no_rm='".$no_rm."' AND antrian.tanggal >= '".$tanggal_besok."' ORDER BY antrian.tanggal ASC";
		}*/
		
		$result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
			$data = array();
            while ($row = $result->fetch_assoc()){
				$temp['message']    	= 'Berhasil';
				$temp['id']         	= $row['id'];
				$temp['antrian']   		= $row['antrian'];
				$temp['waktu_mulai']    = $row['waktu_mulai'];
				$temp['menit_periksa']  = $row['menit_periksa'];
				$temp['nama_pasien']	= $row['nama_pasien'];
				$temp['no_rm']  		= $row['no_rm'];
				$temp['ruang']  		= $row['ruang'];
				$temp['asuransi']      	= $row['asuransi'];
				$temp['tanggal']   		= $row['tanggal'];
				$temp['id_detail']		= $row['id_detail'];
				$temp['waktu_input']	= $row['waktu_input'];
				$temp['kode_daftar']	= $row['kode_daftar'];
				$data[]             	= $temp;			
			}
            //echo json_encode($data);
            echo '{"message" : "Berhasil","results":'.json_encode($data).'}';
        } else {
            header('Content-Type: application/json');
            echo '{"results" : "0"}';
        }
	}
    
    public function sharedPrefUpdate($rm){
        $sql = "SELECT * FROM user_pasien WHERE rm='".$rm."'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
            $data = array();
            $row = $result->fetch_assoc();
            $temp['id']         = $row['id'];
            $temp['rm']       	= $row['rm'];
            $temp['nama']       = $row['nama'];
            $temp['tmplahir']   = $row['tmp_lahir'];
            $temp['tglahir']    = $row['tgl_lahir'];
            $temp['jk']     	= $row['jk'];
            $temp['telp']      	= $row['no_telp'];
			$temp['ktp']      	= $row['no_ktp'];
            $temp['bpjs']       = $row['no_bpjs'];
            $temp['email']      = $row['email'];
//			$temp['password']   = $row['password'];			
            $temp['aktivasi']   = $row['aktivasi'];             
            $temp['token']      = $row['token'];            
            $data[]             = $temp;

            echo '{ "message" : "Berhasil" ,"results":'.json_encode($data).'}';
        } else {
            header('Content-Type: application/json');
            echo '{"message" : "tidak berhasil"}';
		}
	}
	
	public function ubahUser($id, $tmplahir, $foto, $telp, $email, $foto_temp){
		if($foto==NULL){
			$acak="1933FAasdsk25kwBjakjDlff1988"; 
			$panjang='8'; $len=strlen($acak);
			$start=$len-$panjang; 
			$xx=rand('0',$start);
			$yy=str_shuffle($acak);
			$token=substr($yy, $xx, $panjang);
		
			$sql = "UPDATE user_pasien 
				SET tmp_lahir   = '".$tmplahir."',
					no_telp   	= '".$telp."',
					email    	= '".$email."',
					token    	= '".$token."'
				WHERE 		id 	= '".$id."'";

			if ($this->conn->query($sql) == TRUE) {
				header('Content-Type: application/json');
				echo '{"message" : "Berhasil Mengubah"}';
			} else {
				header('Content-Type: application/json');
				echo '{"message" : "Tidak Mengubah"}';
			}
			
		}elseif($foto!=NULL){
			$acak="1933FAasdsk25kwBjakjDlff1988"; 
			$panjang='8'; $len=strlen($acak);
			$start=$len-$panjang; 
			$xx=rand('0',$start);
			$yy=str_shuffle($acak);
			$token=substr($yy, $xx, $panjang);
		
			$sql = "UPDATE user_pasien 
				SET 
				tmp_lahir   = '".$tmplahir."',
				foto 		= '".$foto."',
				no_telp   	= '".$telp."',
				email    	= '".$email."',
				token    	= '".$token."'
				WHERE id 	= '".$id."'";

			if ($this->conn->query($sql) == TRUE) {
				move_uploaded_file($foto_temp, '../foto/'.$foto); //upload file
				header('Content-Type: application/json');
				echo '{"message" : "Berhasil Mengubah"}';
			} else {
				header('Content-Type: application/json');
				echo '{"message" : "Tidak Mengubah"}';
			}
		}
	}

	public function profilUser($id, $rm, $nama, $tmplahir, $tglahir, $jk, $telp, $ktp, $bpjs, $email, $passwordLama, $passwordBaru, $passwordUlang){    
        $sql    = "SELECT * FROM user_pasien WHERE id='".$id."'";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
		
		if (/*$foto==NULL &&*/ $passwordLama==NULL) {
            $acak="1933FAasdsk25kwBjakjDlff1988"; $panjang='6'; $len=strlen($acak);
            $start=$len-$panjang; $xx=rand('0',$start);
            $yy=str_shuffle($acak);
            $token=substr($yy, $xx, $panjang);
            $aktivasi = "T";			
            $sql = "UPDATE user_pasien
                        SET rm 			= '".$rm."',
                            nama   		= '".$nama."',
                            tmp_lahir 	= '".$tmplahir."', 
							tgl_lahir 	= '".$tglahir."',
                            jk 			= '".$jk."', 
                            no_telp 	= '".$telp."', 
							no_ktp		= '".$ktp."',
							no_bpjs 	= '".$bpjs."',
                            email 		= '".$email."',
                            token 		= '".$token."'
                    WHERE id 			= '".$id."'";
                    
            if ($this->conn->query($sql) == TRUE) {
                header('Content-Type: application/json');
                echo '{"message" : "Profil Berhasil Diubah"}';
            } else {
                header('Content-Type: application/json');
                echo '{"message" : "Profil Gagal Diubah"}';
            }
        }elseif( /*$foto!=NULL &&*/ $passwordLama!=NULL){
            if ($passwordBaru!=$passwordUlang) {
                header('Content-Type: application/json');
                echo '{"message" : "Password Baru dan Ulangi Password Berbeda"}';
            }elseif (md5($passwordLama)!=$row['password']) {
                header('Content-Type: application/json');
                echo '{"message" : "Password Lama Berbeda"}';
            }else{

                $acak="1933FAasdsk25kwBjakjDlff1988"; $panjang='8'; $len=strlen($acak);
                $start=$len-$panjang; $xx=rand('0',$start);
                $yy=str_shuffle($acak);
                $token=substr($yy, $xx, $panjang);
                $aktivasi = "T";
                $sql = "UPDATE user_pasien 
					SET rm 	= '".$rm."',
					nama   		= '".$nama."',
					tmp_lahir 	= '".$tmplahir."', 
					tgl_lahir 	= '".$tglahir."',
					jk 			= '".$jk."', 
					no_telp 	= '".$telp."', 
					no_bpjs 	= '".$bpjs."',
					email 		= '".$email."',
                    token 		= '".md5($token)."',
                    password 	= '".md5($passwordBaru)."'            
                    WHERE id 	= '".$id."'";
                        
                if ($this->conn->query($sql) == TRUE) {
//                    move_uploaded_file($foto_temp, '../foto/'.$foto); //upload file
                    header('Content-Type: application/json');
                    echo '{"message" : "logout"}';
                } else {
                    header('Content-Type: application/json');
                    echo '{"message" : "Profil Gagal Diubah"}';
                }
            }
		}
	}
}
?>