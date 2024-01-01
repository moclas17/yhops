<?php
	$Servidor = 'localhost';
	$Base = 'criptochingaderas_mezcal';
	$Usuario = 'criptochingaderas_mezcal';
	$Contrasena = 'kkjCGgl6LNMvNOc4';
try{
	@$Conexion = new mysqli($Servidor, $Usuario, $Contrasena);

	if ($Conexion->connect_errno) {
	print 'conection error';
	exit();
	}
	$Conexion->select_db($Base);
	}catch (Exception $e) {
	print_r($e);
}