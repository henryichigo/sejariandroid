<?php

class DbHandler{
    private $conn;
    private $url;

    function __construct()
    {
        require_once '../config/koneksi.php';
        $db = new DbConnection();
        $this->conn = $db->connect();
        $this->url = $db->url();
    }

    public function login($telp,$password)
    {
        $sql = "SELECT * FROM user_pasien WHERE no_telp='".$telp."' AND password='".$password."'";
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
            $temp['bpjs']       = $row['no_bpjs'];
            $temp['email']      = $row['email'];
//			$temp['password']   = $row['password'];			
            $temp['aktivasi']   = $row['aktivasi'];             
            $temp['token']      = $row['token'];            
            $data[]             = $temp;

            echo '{ "message" : "Berhasil" ,"results":'.json_encode($data).'}';
        } else {
            header('Content-Type: application/json');
            echo '{"message" : "Email atau password salah"}';
		}
	}
	
	public function registrasi($nama, $email, $password, $confirm_password){
		if (!empty($email) && $password == $confirm_password){							
			$sql = "SELECT * FROM user_pasien WHERE email='".$email."'";
			$result = $this->conn->query($sql);
			if ($result->num_rows == 0){
				$acak="1933FAasdsk25kwBjakjDlff1988"; $panjang='8'; $len=strlen($acak);
				$start=$len-$panjang; $xx=rand('0',$start);
				$yy=str_shuffle($acak);
				$token=substr($yy, $xx, $panjang);
				$aktivasi = "T";
				$sql = "INSERT INTO user_pasien 
					(nama, email, password, aktivasi, token) 
					VALUES('".$nama."', '".$email."', '".md5($password)."', '".$aktivasi."', '".md5($token)."')";

				if ($this->conn->query($sql) == TRUE){
					header('Content-Type: application/json');
					echo '{"message" : "Registrasi berhasil, silahkan login."}';

				} else {
					header('Content-Type: application/json');
					echo '{"message" : "Gagal registrasi, silahkan registrasi ulang"}';
				}
			} 
			else {
				header('Content-Type: application/json');
				echo '{"message" : "Email sudah terdaftar, gunakan email yang berbeda"}';
			}
		}
	}
	
    public function lupa_password($email)
    {
        $sql = "SELECT * FROM user_pasien WHERE email='".$email."'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            include("../PHPMailer/class.phpmailer.php");
            include("../PHPMailer/class.smtp.php"); // note, this is optional - gets called from main class if not already loaded

            $mail = new PHPMailer();
            $body = "
                <body style='margin: 10px;'>
                <div style='width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;'>
                    Anda baru saja merequest untuk proses lupa password, silahkan klik link berikut, apabila memang Anda menginginkan perubahan tersebut : <br> 
                        <a href='".$this->url."API/passwordBaru.php?id=".$row['token']."'> LAKUKAN AKTIVASI </a>
                </div>
                </body>";

            echo $body;

            $mail->IsSMTP();
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
            $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
            $mail->Port       = 587;                   // set the SMTP port

            $mail->Username   = "";   // GMAIL username
            $mail->Password   = "";  // GMAIL password
            $mail->From       = ""; //GMAIL username
            $mail->FromName   = "Lupa Password";
            $mail->Subject    = "Lupa Password";
            $mail->WordWrap   = 50; // set word wrap

            $mail->MsgHTML($body);
            $mail->AddAddress($row['email']);
            $mail->IsHTML(true); // send as HTML

            if(!$mail->Send()) {
              echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                header('Content-Type: application/json');           
                echo '{ "message" : "Cek Email"}';
            }
        } else {
            header('Content-Type: application/json');
            echo '{"message" : "Email salah"}';
        }
    }

	public function daftarBpjs($tgl_kunj, $nama, $rm, $penjamin, $nmr_kartu, $kliniknya){
		if (!empty ($kliniknya) && !empty($tgl_kunj)){
			$sql = "SELECT urut FROM data WHERE tgl_kunj='".$tgl_kunj."'";
			$result = $this->conn->query($sql);
			$row = mysqli_num_rows($result);
			
			if ($row == 0 ){
				$urut=$row+1;
				$sql = "INSERT INTO data 
					(tgl_kunj, nama, rm, penjamin, nmr_kartu, kliniknya, urut, datetime, waktunya) 
					VALUES('".$tgl_kunj."', '".$nama."', '".$rm."', '".$penjamin."', '".$nmr_kartu."', '".$kliniknya."', '".$urut."', '".$tgl_kunj." 11:59:59', CURRENT_TIMESTAMP)";
						
				if ($this->conn->query($sql) == TRUE){
					header('Content-Type: application/json');
					echo '{"message" : "Pendaftaran Poli rawat jalan berhasil."}';

				} else {
						header('Content-Type: application/json');
						echo '{"message" : "Pendaftaran Poli rawat jalan Gagal, silahkan mendaftar ulang"}';
				}				
			} else {
				$sql1 = "SELECT * FROM data WHERE rm='".$rm."' AND tgl_kunj='".$tgl_kunj."'";
				$hasil = $this->conn->query($sql1);
				if ($hasil->num_rows == 0){
					$urut=$row+1;
					$sql = "INSERT INTO data 
						(tgl_kunj, nama, rm, penjamin, nmr_kartu, kliniknya, urut, datetime, waktunya) 
						VALUES('".$tgl_kunj."', '".$nama."', '".$rm."', '".$penjamin."', '".$nmr_kartu."', '".$kliniknya."', '".$urut."', '".$tgl_kunj." 11:59:59', CURRENT_TIMESTAMP)";
					
					if ($this->conn->query($sql) == TRUE){
						header('Content-Type: application/json');
						echo '{"message" : "Pendaftaran Poli Rawat Jalan berhasil."}';

					} else {
						header('Content-Type: application/json');
						echo '{"message" : "Pendaftaran janji temu Poli Gagal, silahkan mendaftar ulang"}';
					}
				} else {
					header('Content-Type: application/json');
					echo '{"message" : "anda sudah mendaftar di tanggal yang sama, silahkan pilih tanggal yang berbeda"}';
				}
			} 
		} else {
			header('Content-Type: application/json');
			echo '{"message" : "inputan tidak boleh kosong! Silahkan lengkapi inputan anda"}'; 
		}
	}
						
	public function daftarNonBpjs($tgl_kunj, $nama, $rm, $penjamin, $kliniknya){	
		if (!empty ($kliniknya) && !empty($tgl_kunj)){
			$sql = "SELECT urut FROM data WHERE tgl_kunj='".$tgl_kunj."'";
			$result = $this->conn->query($sql);
			$row = mysqli_num_rows($result);
			
			if ($row == 0 ){
				$urut=$row+1;
				$sql = "INSERT INTO data 
					(tgl_kunj, nama, rm, penjamin, kliniknya, urut, datetime, waktunya) 
					VALUES('".$tgl_kunj."', '".$nama."', '".$rm."', '".$penjamin."', '".$kliniknya."', '".$urut."', '".$tgl_kunj." 11:59:59', CURRENT_TIMESTAMP)";
						
				if ($this->conn->query($sql) == TRUE){
					header('Content-Type: application/json');
					echo '{"message" : "Pendaftaran Poli rawat jalan berhasil."}';

				} else {
						header('Content-Type: application/json');
						echo '{"message" : "Pendaftaran Poli rawat jalan Gagal, silahkan mendaftar ulang"}';
				}
			} else {
				$sql1 = "SELECT * FROM data WHERE rm='".$rm."' AND tgl_kunj='".$tgl_kunj."'";
				$hasil = $this->conn->query($sql1);
				if ($hasil->num_rows == 0){
					$urut=$row+1;
					$sql = "INSERT INTO data 
						(tgl_kunj, nama, rm, penjamin, kliniknya, urut, datetime, waktunya) 
						VALUES('".$tgl_kunj."', '".$nama."', '".$rm."', '".$penjamin."', '".$kliniknya."', '".$urut."', '".$tgl_kunj." 11:59:59', CURRENT_TIMESTAMP)";
					
					if ($this->conn->query($sql) == TRUE){
						header('Content-Type: application/json');
						echo '{"message" : "Pendaftaran Poli Rawat Jalan berhasil."}';

					} else {
						header('Content-Type: application/json');
						echo '{"message" : "Pendaftaran janji temu Poli Gagal, silahkan mendaftar ulang"}';
					}
				} else {
					header('Content-Type: application/json');
					echo '{"message" : "anda sudah mendaftar di tanggal yang sama, silahkan pilih tanggal yang berbeda"}';
				}
			} 
		} else {
			header('Content-Type: application/json');
			echo '{"message" : "inputan tidak boleh kosong! Silahkan lengkapi inputan anda"}'; 
		}
	}
	
    public function getUser($id){
        $sql = "SELECT * FROM user_pasien WHERE id!='".$id."'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
            $data = array();
            while ($row = $result->fetch_assoc()) {
				$temp['id']         = $row['id'];
				$temp['rm']       	= $row['rm'];
				$temp['nama']       = $row['nama'];
				$temp['tmplahir']   = $row['tmp_lahir'];
				$temp['tglahir']    = $row['tgl_lahir'];
				$temp['jk']     	= $row['jk'];
				$temp['telp']      	= $row['no_telp'];
				$temp['bpjs']       = $row['no_bpjs'];
				$temp['email']      = $row['email'];             
				$temp['aktivasi']   = $row['aktivasi'];             
				$temp['token']      = $row['token'];             
				$data[]             = $temp;
				}
            echo '{"message" : "Berhasil","results":'.json_encode($data).'}';
        } else {
            header('Content-Type: application/json');
            echo '{"results" : "0"}';
        }
    }

    public function selectUser($id)
    {
        $sql = "SELECT * FROM user_pasien WHERE id='".$id."'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
            $row = $result->fetch_assoc();
            $temp['message']    = 'Berhasil';
            $temp['id']         = $row['id'];
			$temp['rm']       	= $row['rm'];
			$temp['nama']       = $row['nama'];
			$temp['tmplahir']   = $row['tmp_lahir'];
			$temp['tglahir']    = $row['tgl_lahir'];
			$temp['jk']     	= $row['jk'];
			$temp['telp']      	= $row['no_telp'];
			$temp['ktp']       = $row['no_ktp'];
			$temp['bpjs']       = $row['no_bpjs'];
			$temp['email']      = $row['email'];
            $temp['password']   = $row['password'];             
            $temp['aktivasi']   = $row['aktivasi'];             
            $temp['token']      = $row['token'];             
            $data             = $temp;
            
            echo json_encode($data);
        } else {
            header('Content-Type: application/json');
            echo '{"results" : "0"}';
        }
    }
	
	public function getAntri($rm){
		$tgl 		= date('Y-m-d');
		$jam 		= date('H:i:s');
		$tgl_besok	= mktime(0,0,0,date("n"),date("j")+1,date("Y"));
		$tgl2       = date("Y-m-d", $tgl_besok);
		
		if ($jam < '12:00:00'){
			$sql = "SELECT * FROM data WHERE rm='".$rm."' AND tgl_kunj >= '$tgl' ORDER BY tgl_kunj ASC";
		}else{
			$sql = "SELECT * FROM data WHERE rm='".$rm."' AND tgl_kunj >= '$tgl2' ORDER BY tgl_kunj ASC";
		}
		
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
            $row = $result->fetch_assoc();
            $temp['message']    = 'Berhasil';
            $temp['id']         = $row['id'];
			$temp['tgl_kunj']   = $row['tgl_kunj'];
			$temp['nama']       = $row['nama'];
			$temp['rm']   		= $row['rm'];
			$temp['penjamin']	= $row['penjamin'];
			$temp['nmr_kartu']  = $row['nmr_kartu'];
			$temp['kliniknya']  = $row['kliniknya'];
			$temp['urut']      	= $row['urut'];
			$temp['waktunya']   = $row['waktunya'];         
            $data             	= $temp;
            
            echo json_encode($data);
        } else {
            header('Content-Type: application/json');
            echo '{"results" : "0"}';
        }
    }
	
/*	public function getAntri($rm){
		$sql = "SELECT * FROM data WHERE rm='".$rm."' AND tgl_kunj >= CURRENT_DATE() AND datetime >= CURRENT_TIMESTAMP() ORDER BY tgl_kunj ASC";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
            $row = $result->fetch_assoc();
            $temp['message']    = 'Berhasil';
            $temp['id']         = $row['id'];
			$temp['tgl_kunj']   = $row['tgl_kunj'];
			$temp['nama']       = $row['nama'];
			$temp['rm']   		= $row['rm'];
			$temp['penjamin']	= $row['penjamin'];
			$temp['nmr_kartu']  = $row['nmr_kartu'];
			$temp['kliniknya']  = $row['kliniknya'];
			$temp['urut']      	= $row['urut'];
			$temp['waktunya']   = $row['waktunya'];         
            $data             	= $temp;
            
            echo json_encode($data);
        } else {
            header('Content-Type: application/json');
            echo '{"results" : "0"}';
        }
    }*/
	
	public function ubahUser($id, $rm, $nama, $tmplahir, $tglahir, $jk, $telp, $bpjs, $email, $password){
//		if($foto==NULL){
           /* header('Content-Type: application/json');
            echo '{"message" :"Foto tidak ada"}';
    */
		$acak="1933FAasdsk25kwBjakjDlff1988"; $panjang='8'; $len=strlen($acak);
		$start=$len-$panjang; $xx=rand('0',$start);
		$yy=str_shuffle($acak);
		$token=substr($yy, $xx, $panjang);
		
		$sql = "UPDATE user_pasien 
			SET rm     	= '".$rm."',
			nama       	= '".$nama."',
			tmp_lahir   = '".$tmplahir."',
			tgl_lahir 	= '".$tglahir."',
			jk 			= '".$jk."',
			no_telp   	= '".$telp."',
			no_bpjs 	= '".$bpjs."',
			email    	= '".$email."',
			password	= '".md5($password)."',
			token    	= '".md5($token)."'
			WHERE id 		= '".$id."'";

		if ($this->conn->query($sql) == TRUE) {
			header('Content-Type: application/json');
			echo '{"message" : "Berhasil Mengubah"}';
		} else {
			header('Content-Type: application/json');
			echo '{"message" : "Tidak Mengubah"}';
		}
	}

//        }elseif($foto!=NULL){
            /*header('Content-Type: application/json');
            echo '{"message" :"Foto ada"}';*/
    
/*            $acak="1933FAasdsk25kwBjakjDlff1988"; $panjang='8'; $len=strlen($acak);
            $start=$len-$panjang; $xx=rand('0',$start);
            $yy=str_shuffle($acak);
            $token=substr($yy, $xx, $panjang);

            $sql = "UPDATE user_pasien 
                    SET rm     		= '".$rm."',
                        nama       	= '".$nama."',
                        tmp_lahir   = '".$tmplahir."',
                        tgl_lahir 	= '".$tglahir."',
						jk 			= '".$jk."',
                        no_telp   	= '".$telp."',
						no_bpjs 	= '".$bpjs."',
                        email    	= '".$email."',
                        token    	= '".md5($token)."'
                    WHERE id 		= '".$id."'";
                if ($this->conn->query($sql) == TRUE) {
                    move_uploaded_file($foto_temp, '../foto/'.$foto); //upload file
                    header('Content-Type: application/json');
                    echo '{"message" : "Berhasil Mengubah"}';
                } else {
                    header('Content-Type: application/json');
                    echo '{"message" : "Tidak Mengubah"}';
                }
        }
    }*/

    public function hapusUser($id){   
        $sqlcek = "SELECT * FROM user_pasien WHERE id = '".$id."'";
        
        if ($this->conn->query($sqlcek)->num_rows > 0) {
            $sql = "DELETE FROM user_pasien WHERE id = '".$id."'";
            $this->conn->query($sql);

            header('Content-Type: application/json');
            echo '{"message" : "Berhasil Terhapus"}';
        }else{
            header('Content-Type: application/json');
            echo '{"message" : "Tidak Menghapus"}';
        }
    }

	public function profilUser($id,$rm,$nama,$tmplahir,$tglahir,$jk,$telp,$bpjs,$email,$passwordLama,$passwordBaru,$passwordUlang)
    {    
        $sql    = "SELECT * FROM user_pasien WHERE id='".$id."'";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
		
		if (/*$foto==NULL &&*/ $passwordLama==NULL) {
            $acak="1933FAasdsk25kwBjakjDlff1988"; $panjang='8'; $len=strlen($acak);
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
							no_bpjs 	= '".$bpjs."',
                            email 		= '".$email."',
                            token 		= '".md5($token)."'
                    WHERE id 			= '".$id."'";
                    
            if ($this->conn->query($sql) == TRUE) {
                header('Content-Type: application/json');
                echo '{"message" : "Profil Berhasil Diubah"}';
            } else {
                header('Content-Type: application/json');
                echo '{"message" : "Profil Gagal Diubah"}';
            }
/*        }elseif($foto!=NULL && $passwordLama==NULL){
            $acak="1933FAasdsk25kwBjakjDlff1988"; $panjang='8'; $len=strlen($acak);
            $start=$len-$panjang; $xx=rand('0',$start);
            $yy=str_shuffle($acak);
            $token=substr($yy, $xx, $panjang);
            $aktivasi = "T";
            $sql = "UPDATE pegawai
                        SET pegawai_nama = '".$nama."',
                            pegawai_jk   = '".$jk."',
                            pegawai_keahlian = '".$keahlian."', 
                            pegawai_agama = '".$agama."', 
                            pegawai_kontak = '".$kontak."', 
                            pegawai_email = '".$email."',
                            pegawai_foto = '".$foto."',
                            pegawai_token = '".md5($token)."'
                    WHERE pegawai_id = '".$id."'";
                    
            if ($this->conn->query($sql) == TRUE) {
                move_uploaded_file($foto_temp, '../foto/'.$foto); //upload file
                header('Content-Type: application/json');
                echo '{"message" : "Profil Berhasil Diubah"}';
            } else {
                header('Content-Type: application/json');
                echo '{"message" : "Profil Gagal Diubah"}';
            }*/
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