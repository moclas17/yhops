<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include('lib/Seguridad.php');
    if(isset($_GET['Back'])){
        $InsertGoTo = substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER']));
    }else{
        header("Location: CelaRepositorioLeer.php?".EncodeThis("tabla=".$_GET['tabla']."&cvetabla=".$_GET['idtabla']."&Estatus=NotFound"));
    }