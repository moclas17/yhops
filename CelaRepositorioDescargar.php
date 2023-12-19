<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include('lib/Seguridad.php');$TempFile=CreateDecodeTempFile($_GET['clave']);if($TempFile!=false){	$Temp=fopen($TempFile,"rb");	$Nombre=substr($TempFile,strrpos($TempFile,'/')+1);			//Se lanza a descarga el archivo decodificado	header("Content-type: application/octet-stream");	header("Content-Disposition: attachment; filename=\"copy_".$Nombre."");	fpassthru($Temp);			fclose($Temp);	unlink($TempFile);}else{
    if(isset($_GET['Back'])){
        $InsertGoTo = substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER']));
    }else{
        header("Location: CelaRepositorioLeer.php?".EncodeThis("tabla=".$_GET['tabla']."&cvetabla=".$_GET['idtabla']."&Estatus=NotFound"));
    }}?>