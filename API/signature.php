<?php 
    $data 			= "30700";
    $secretKey 		= "7yXC868F64";
    $ppkPelayanan	= "1308R004";
      // Computes the timestamp
    date_default_timezone_set('UTC');
    $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        // Computes the signature by hashing the salt with the secret key as the key
    $signature = hash_hmac('sha256', $data."&".$tStamp, $secretKey, true);

    // base64 encode…
    $encodedSignature = base64_encode($signature);

    // urlencode…
    // $encodedSignature = urlencode($encodedSignature);

    //echo "X-cons-id: " .$data ." ";
    //echo "X-timestamp:" .$tStamp ." ";
    //echo "X-signature: " .$encodedSignature;
    $headers = array(); 
    $headers[] = 'Content-Type:application-json';   
    $headers[] = "X-cons-id:".$data;
    $headers[] = "X-timestamp:".$tStamp;
    $headers[] = "X-signature:".$encodedSignature;

$alamat_pcare	= "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest";
//$alamat_pcare	= "https://dvlp.bpjs-kesehatan.go.id/VClaim-rest";
?>