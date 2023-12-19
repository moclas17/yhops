<?php 
$UrlSalida = "index.php"; 
if (!isset($_SESSION)) { 
  session_start(); 
} 
session_destroy(); 

unset($_SESSION['CELA_Usuario']); 
unset($_SESSION['CELA_CveUsuario']); 
unset($_SESSION['CELA_Rol']); 
unset($_SESSION['CELA_CveRol']); 
unset($_SESSION['CELA_UltimoAcceso']); 
unset($_SESSION['CELA_Autentificado']);  
unset($_SESSION['CELA_Aleatorio']);  
unset($_SESSION['CELA_NombreSistema']);  

header(sprintf("Location: %s", $UrlSalida)); 
exit; 
?> 
