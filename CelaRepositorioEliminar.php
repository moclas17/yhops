<?php
require_once('lib/Conexion.php');
include('lib/Funciones.php');
include('lib/Seguridad.php');

if (isset($_GET['clave']) && $_GET['clave'] != "" && $Privilegios['Eliminar'] && $Privilegios['Eliminar']==1) {
	$Status=false;
	foreach($_GET['clave'] as $Valor){
		$ConsultaElimina = sprintf("UPDATE CelaRepositorio SET Estado=0 WHERE idRepositorio = %s", GetSQLValueString($Valor, "int"));
		if($ResultadoElimina = $Conexion->query($ConsultaElimina)){
			$IdRegistro = $Valor;
			$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idDeAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
				GetSQLValueString( "NULL", "int"),
				GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
				GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
				GetSQLValueString( 'CelaRepositorio', "varchar"),
				GetSQLValueString( $IdRegistro, "int"),
				GetSQLValueString( 3, "int"));
			$ResultadoLog=$Conexion->query($ConsultaLog);
		$Status=true;	
		}else{
			$Status=false;
		}
	}
	if($Status==true){
	    if(isset($_GET['Back'])){
            header(sprintf("Location: %s", substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER']))));
        }else{
           header(sprintf("Location: CelaRepositorioLeer.php?".EncodeThis("tabla=".$_GET['tabla']."&cvetabla=".$_GET['idtabla']."&Status=SuccessE")) );
        }
	}else{
		$Error=$Conexion->error;
        if(isset($_GET['Back'])){
            header(sprintf("Location: %s", substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER']))));
        }else{
           header(sprintf("Location: CelaRepositorioLeer.php?".EncodeThis("tabla=".$_GET['tabla']."&cvetabla=".$_GET['idtabla']."&Status=ErrorE&Error=".$Error)) );
        }
	}
} else {
    if(isset($_GET['Back'])){
        header(sprintf("Location: %s", substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER']))));
    }else{
        header(sprintf("Location: CelaRepositorioLeer.php".EncodeThis("tabla=".$_GET['tabla']."&cvetabla=".$_GET['idtabla']) ));
    } 
}
?>