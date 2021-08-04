<?php
 
class DbConnection {
 
    private $conn;
    private $url;
 
    function connect() {
		$DB_HOST 	 = "localhost";
		$DB_USERNAME = "id16669842_henryichigo";
		$DB_PASSWORD = "Senjougahara-85";
		$DB_NAMA 	 = "id16669842_sejari";

        $this->conn = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAMA);
        if (mysqli_connect_errno()) {
            echo "Gagal Koneksi ke Database: " . mysqli_connect_error();
        }
	    return $this->conn;
	}

	function url() {
		$this->url = "https://sejari.000webhostapp.com/sejariandroid/";
	    return $this->url;
	}
}
?>