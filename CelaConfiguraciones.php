<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');

$FormAction = $_SERVER['PHP_SELF']; 
if ((isset($_POST["CelaConfiguraci_onUpdate"])) && ($_POST["CelaConfiguraci_onUpdate"] == "CelaConfiguraci_onUpdate")) {
	while($RegistroConfiguraciones = $ResultadoConfiguraciones->fetch_assoc()){
				$Valor=$ruta;
			if($RegistroConfiguraciones['Tipo']=='checkbox'){
			$ConsultaActualiza = sprintf("UPDATE CelaConfiguraci_on SET Valor=%s WHERE Nombre=%s",
			$ResultadoActualiza = $Conexion->query($ConsultaActualiza);
	$UpdateGoTo = "CelaConfiguraciones.php"; 
	header(sprintf("Location: %s", $UpdateGoTo)); 
}
$ConsultaConfiguraciones = "SELECT * FROM CelaConfiguraci_on"; 
$ResultadoConfiguraciones = $Conexion->query($ConsultaConfiguraciones);
$RegistroConfiguraciones = $ResultadoConfiguraciones->fetch_assoc();
?>
<!DOCTYPE html>
						<div class="col-sm-12 col-md-12">
							<div class="row">
											<i class="fa fa-question"></i>
										</a>
							</div>
												<?php print DecodeString($RegistroConfiguraciones['Nombre']); ?></label>
											<div class="col-sm-10">
												<div class="col-xs-8">
												<?php 
													switch($RegistroConfiguraciones['Tipo']){
														case 'file':
															print '<input type="'.$RegistroConfiguraciones['Tipo'].'" class="focused" name="'.$RegistroConfiguraciones['Nombre'] .'" id="'.$RegistroConfiguraciones['Nombre'].'" value="" />';
															print '<input type="hidden" name="'.$RegistroConfiguraciones['Nombre'].'_ant" value="'.$RegistroConfiguraciones['Valor'].'" />';
															break;
														case 'checkbox':
															print '<input class="checkbox focused" name="'.$RegistroConfiguraciones['Nombre'] .'" id="'.$RegistroConfiguraciones['Nombre'].'" type="'.$RegistroConfiguraciones['Tipo'].'" '.($RegistroConfiguraciones['Valor']=='1'?'checked="checked"':"").' value="1" />';
															break;
														case 'password':
															print '<input class="form-control focused" name="'.$RegistroConfiguraciones['Nombre'] .'" id="'.$RegistroConfiguraciones['Nombre'].'" type="'.$RegistroConfiguraciones['Tipo'].'" value="" />';
															break;
														case 'select':
															$Opciones['nombre']=$RegistroConfiguraciones['Nombre'];
															$Opciones['clase']="form-control";
															print SRellenaCombo("SELECT id".$RegistroConfiguraciones['Nombre'].", Nombre FROM ".$RegistroConfiguraciones['Nombre']."",$Opciones,$RegistroConfiguraciones['Valor']);
															break;
														case 'textarea':
															print '<textarea class="form-control focused" name="'.$RegistroConfiguraciones['Nombre'] .'" id="'.$RegistroConfiguraciones['Nombre'].'" rows="8" >'.$RegistroConfiguraciones['Valor'].'</textarea>';
															break;
														default:
						</div>