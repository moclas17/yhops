<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');


$contract_address=ObtenValor("SELECT Contract FROM Collection WHERE id=".$_GET['clave'][0], "Contract");
#precode($contract_address,1,1);
#exit;
$curl = curl_init();

$payload = array(
  "chain" => $chain,
  "to" => "0x0e88AC34917a6BF5E36bFdc2C6C658E58078A1e6",
  "contractAddress" => $contract_address,
  "tokenId" => "1",
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
