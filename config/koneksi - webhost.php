<?php
 
class DbConnection {
 
    private $conn;
    private $url;
 
    function connect() {
		$DB_HOST 	 = "localhost";
		$DB_USERNAME = "id14603350_kurosaki15";
		$DB_PASSWORD = "Senjougahara15?";
		$DB_NAMA 	 = "id14603350_antrian";

        $this->conn = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAMA);
        if (mysqli_connect_errno()) {
            echo "Gagal Koneksi ke Database: " . mysqli_connect_error();
        }
	    return $this->conn;
	}

	function url() {
		$this->url = "http://www.unitsimrstraining.000webhostapp.com/epasien";
	    return $this->url;
	}
}
?>