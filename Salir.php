<?php
require_once('lib/Conexion.php');
require_once('lib/Funciones.php');
date_default_timezone_set(ObtenValor("SELECT Nombre FROM CelaZonaHoraria WHERE idCelaZonaHoraria=(SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='CelaZonaHoraria')","Nombre"));

$UrlSalida = "index.php"; 
if (!isset($_SESSION)) { 
  session_start(); 
} 
session_destroy(); 

unset($_SESSION['CELA_Usuario'.$_SESSION['CELA_Aleatorio']]); 
unset($_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']]); 
unset($_SESSION['CELA_Rol'.$_SESSION['CELA_Aleatorio']]); 
unset($_SESSION['CELA_CveRol'.$_SESSION['CELA_Aleatorio']]); 
unset($_SESSION['CELA_UltimoAcceso'.$_SESSION['CELA_Aleatorio']]); 
unset($_SESSION['CELA_Autentificado'.$_SESSION['CELA_Aleatorio']]); 
unset($_SESSION['CELA_NombreSistema'.$_SESSION['CELA_Aleatorio']]);
unset($_SESSION['CELA_Aleatorio']);   

header(sprintf("Location: %s", $UrlSalida)); 
exit; 
?> 
