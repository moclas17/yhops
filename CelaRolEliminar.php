<?php
require_once('lib/Conexion.php');
include('lib/Funciones.php');
include('lib/Seguridad.php');

if (isset($_GET['clave']) && $_GET['clave'] != "" && isset($Privilegios['Eliminar']) && $Privilegios['Eliminar']==1) {
	$Status=false;
	foreach($_GET['clave'] as $Valor){
		$ConsultaElimina = sprintf("DELETE FROM CelaRol WHERE idRol = %s", GetSQLValueString($Valor, "int"));
		if($ResultadoElimina = $Conexion->query($ConsultaElimina)){
			$IdRegistro = $Valor;
			$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
				GetSQLValueString( "NULL", "int"),
				GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
				GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
				GetSQLValueString( 'CelaRol', "varchar"),
				GetSQLValueString( $IdRegistro, "int"),
				GetSQLValueString( 3, "int"));
			$ResultadoLog=$Conexion->query($ConsultaLog);
			$Status=true;	
		}else{
			$Status=false;
		}
	}
	if($Status==true){
		header(sprintf("Location: CelaRolLeer.php?".EncodeThis("Status=SuccessE")) );
	}else{
		$Error=$Conexion->error;
		header(sprintf("Location: CelaRolLeer.php?".EncodeThis("Status=ErrorE&Error=".$Error)) );
	}
} else { 
	header(sprintf("Location: CelaRolLeer.php") );
}
?>