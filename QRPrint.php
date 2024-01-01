<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
include('lib/phpqrcode/qrlib.php');
    
   $query="SELECT * FROM QRDetails WHERE idClient=".$_GET['idClient']." AND idQRHeader=".$_GET['QRHeader'];
   $exec=$Conexion->query($query);
   while($row = $exec->fetch_assoc()){
	   #precode($row,1);
	   echo '<br /><img width="150px" src="qrgenerate.php?id='.$row['uniqueid'].'" />';
   }
   
#precode($_GET,1,1);
?>