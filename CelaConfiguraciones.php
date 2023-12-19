<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');

$FormAction = $_SERVER['PHP_SELF']; if (isset($_SERVER['QUERY_STRING'])) {	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']); }
if ((isset($_POST["CelaConfiguraci_onUpdate"])) && ($_POST["CelaConfiguraci_onUpdate"] == "CelaConfiguraci_onUpdate")) {	$ConsultaConfiguraciones = "SELECT * FROM CelaConfiguraci_on"; 	$ResultadoConfiguraciones = $Conexion->query($ConsultaConfiguraciones);
	while($RegistroConfiguraciones = $ResultadoConfiguraciones->fetch_assoc()){		if($RegistroConfiguraciones['Tipo']!="hidden"){			if(isset($_POST[$RegistroConfiguraciones['Nombre']]))				$Valor=$_POST[$RegistroConfiguraciones['Nombre']];			else				$Valor=$RegistroConfiguraciones['Valor'];			if($RegistroConfiguraciones['Tipo']=="file" && $_POST[''.$RegistroConfiguraciones['Nombre'].'_ant']!=$_FILES[$RegistroConfiguraciones['Nombre']]['name'] && ($_FILES[$RegistroConfiguraciones['Nombre']]['name']!=NULL || $_FILES[$RegistroConfiguraciones['Nombre']]['name']!= "")){				if(!file_exists("repositorio/configuracion/")){					mkdir("repositorio/configuracion/", 0755, true);				}						$ruta ="repositorio/configuracion/".$_FILES[$RegistroConfiguraciones['Nombre']]['name']."";				move_uploaded_file($_FILES[$RegistroConfiguraciones['Nombre']]['tmp_name'],"".$ruta);				if($_POST[''.$RegistroConfiguraciones['Nombre'].'_ant']!="" || $_POST[''.$RegistroConfiguraciones['Nombre'].'_ant']!=NULL)					@unlink($_POST[''.$RegistroConfiguraciones['Nombre'].'_ant']);
				$Valor=$ruta;			}			if($RegistroConfiguraciones['Tipo']=='password'){				$Valor=md5($_POST[$RegistroConfiguraciones['Nombre']]);				if($_POST[$RegistroConfiguraciones['Nombre']]==NULL || $_POST[$RegistroConfiguraciones['Nombre']]== ""){					$Valor=$RegistroConfiguraciones['Valor'];					}			}
			if($RegistroConfiguraciones['Tipo']=='checkbox'){				$Valor=(isset($_POST[$RegistroConfiguraciones['Nombre']]) && $_POST[$RegistroConfiguraciones['Nombre']]==1) ?1:0;			}
			$ConsultaActualiza = sprintf("UPDATE CelaConfiguraci_on SET Valor=%s WHERE Nombre=%s",				GetSQLValueString($Valor, "varchar"),				GetSQLValueString($RegistroConfiguraciones['Nombre'], "varchar") );
			$ResultadoActualiza = $Conexion->query($ConsultaActualiza);		}	}
	$UpdateGoTo = "CelaConfiguraciones.php"; 
	header(sprintf("Location: %s", $UpdateGoTo)); 
}
$ConsultaConfiguraciones = "SELECT * FROM CelaConfiguraci_on"; 
$ResultadoConfiguraciones = $Conexion->query($ConsultaConfiguraciones);
$RegistroConfiguraciones = $ResultadoConfiguraciones->fetch_assoc();
?>
<!DOCTYPE html><html lang="es">	<?php include 'CELAHead.php'; ?>	<body>		<?php include 'CELAMenuHorizontal.php'; ?>		<div class="container-fluid">			<div class="row">			<?php include 'CELAMenuVertical.php'; ?>				<div id="main-container" class="<?php print $registrosMenu>0?'col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10':'col-sm-12 col-md-12'; ?> main">					<div class="row">						<?php include 'CELARuta.php'; ?>					</div>					<div class="row">
						<div class="col-sm-12 col-md-12">						<div class="row panel panel-primary">							<div class="panel-heading">
							<div class="row">								<div class="col-md-12">									<div class="box-header col-xs-6 text-left">										<strong>											<span style="font-size: 18pt;">Configuraciones del sistema</span>										</strong>									</div>									<div class="box-icon col-xs-6 text-right">										<a data-intro="Ayuda general" data-position="left" href="#" class="btn-help btn btn-default" title="Ayuda">
											<i class="fa fa-question"></i>
										</a>									</div>								</div>
							</div>							</div>							<div class="box-content panel-body">								<form class="form-horizontal form_validate" method="post" name="CelaConfiguraci_on" enctype="multipart/form-data" id="CelaConfiguraci_on" action="<?php echo $FormAction; ?>" role="form">									<fieldset>										<div class="clearfix"></div>										<hr />							<?php 								do{									if($RegistroConfiguraciones['Tipo']!="hidden"){							?>										<div class="form-group">											<label for="<?php print $RegistroConfiguraciones['Nombre']; ?>" class="col-sm-2 control-label">
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
														default:															print '<input type="text" class="form-control focused" name="'.$RegistroConfiguraciones['Nombre'] .'" id="'.$RegistroConfiguraciones['Nombre'].'" value="'.$RegistroConfiguraciones['Valor'].'" />';															break;													}												?>												</div>											</div>										</div>							<?php									}								}while($RegistroConfiguraciones=$ResultadoConfiguraciones->fetch_assoc());							?>										<div class="clearfix"></div>										<hr />										<input type="hidden" name="CelaConfiguraci_onUpdate" value="CelaConfiguraci_onUpdate"/>										<div class="form-group last">											<div class="col-sm-offset-3 col-sm-9">												<button id="Guardar" type="submit" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">													<i class="fa fa-save"></i>&nbsp; Guardar												</button>												&nbsp;&nbsp;&nbsp;&nbsp;												<button type="reset" class="btn btn-default" onclick = "location.href='Escritorio.php'" >													<i class="fa fa-undo"></i>&nbsp; Cancelar												</button>											</div>										</div>									</fieldset>								</form>							</div>							<div class="panel-footer text-right">								<a class="btn btn-danger" href="<?php print isset($_SERVER['HTTP_REFERER'])?substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER'])):'Escritorio.php'; ?>" title="ir atr&aacute;s"  data-intro="Regresa al formulario anterior" data-position="left"><i class="fa fa-arrow-left"></i>&nbsp; ir atr&aacute;s</a>							</div>						</div>
						</div>					</div>					<hr>					<span class="clearfix"></span>					<?php include 'CELAPie.php'; ?>				</div>			</div>		</div>		<?php include 'CELAJavascript.php'; ?>	</body></html>