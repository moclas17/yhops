<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
$publickey="0x2Dc468d7f0ae482A7C009dF3E73F9F5F13c30C86";
$privatekey="f1b20baeaab96af270949d207c478d67bb36fbf15bbb0eb18665b2b21cdf2db0";
$apikey_tatum = "t-64f72e210c34f3d88dec24bd-7e45ce3c5a854d06bfd2539d";
$chain = "MATIC";


$publickey = "0x2Dc468d7f0ae482A7C009dF3E73F9F5F13c30C86";
$privatekey = "f1b20baeaab96af270949d207c478d67bb36fbf15bbb0eb18665b2b21cdf2db0";
$apikey_tatum = "t-64f72e210c34f3d88dec24bd-7e45ce3c5a854d06bfd2539d";
$contract_address = "0x6bf21d0f493b4fcb7c80f447ebebf880b4184640";



$curl = curl_init();

$payload = array(
  "chain" => "MATIC",
  "to" => "0x0e88AC34917a6BF5E36bFdc2C6C658E58078A1e6",
  "contractAddress" => $contract_address,
  "tokenId" => "2",
  "url" => "https://ipfs.io/ipfs/bafybeibnsoufr2renqzsh347nrx54wcubt5lgkeivez63xvivplfwhtpym/metadata.json",
  "fromPrivateKey" => $privatekey
);

curl_setopt_array($curl, [
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
    "x-api-key: ".$apikey_tatum
  ],
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_URL => "https://api.tatum.io/v3/nft/mint",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
]);

$response = curl_exec($curl);
$error = curl_error($curl);

curl_close($curl);

if ($error) {
  echo "cURL Error #:" . $error;
} else {
  echo $response;
}

 