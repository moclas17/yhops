<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');

$Data = ObtenValor("SELECT * FROM QRHeader WHERE Contract IS NULL AND ContractTX IS NOT NULL");
$txid=json_decode($Data['ContractTX']);
#precode($Data,1);
if($Data['result']!="NULL"){
$contratotx = getdatafromtx( $apikey_tatum, $chain,$txid->txId);
$contract = json_decode($contratotx);
#precode($contract,1);
$addminter = add_minter_to_contract($privatekey, $apikey_tatum, $chain, $contract->contractAddress, $publickey);
$jsonminter = json_decode($addminter);
$ConsultaActualiza = sprintf("UPDATE QRHeader SET Contract= %s ,  Minter= %s WHERE id= %s", 
   GetSQLValueString($contract->contractAddress, "varchar"),
   GetSQLValueString($jsonminter->txId, "varchar"),
   GetSQLValueString($Data['id'], "int") );
    $Conexion->query($ConsultaActualiza);  
    echo "Contract saved";
}
?>