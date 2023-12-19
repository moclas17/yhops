<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');

$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$Back=substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER']));
if ((isset($_POST["CelaRepositorioInsert"])) && ($_POST["CelaRepositorioInsert"] == "CelaRepositorioInsert")) {
	//Obtenemos algunos atributos del archivo original
	$Archivo = $_FILES["Archivo"]["tmp_name"];
	$Nombre  = $_FILES["Archivo"]["name"];
	
	$RutaArchivo=CreateEncodeFile($Nombre, $Archivo);
	
	$ConsultaInserta = sprintf("INSERT INTO CelaRepositorio (idRepositorio,Tabla,idTabla,Ruta,Descripci_on,idUsuario,FechaDeCreaci_on,Estado ) VALUES (  %s, %s, %s, %s, %s, %s, %s, %s)",
		GetSQLValueString(NULL, "int") ,
		GetSQLValueString(Decrypt($_POST[Encrypt('Tabla',$_SESSION['CELA_Aleatorio'])],$_SESSION['CELA_Aleatorio']), "varchar") ,
		GetSQLValueString(Decrypt($_POST[Encrypt('idTabla',$_SESSION['CELA_Aleatorio'])],$_SESSION['CELA_Aleatorio']), "varchar") ,
		GetSQLValueString($RutaArchivo, "varchar") ,
		GetSQLValueString($_POST['Descripci_on'], "varchar") ,
		GetSQLValueString($_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int") ,
		GetSQLValueString(date("Y-m-d H:i:s"), "timestamp") ,
		GetSQLValueString(1, "tinyint"));
	if($ResultadoInserta = $Conexion->query($ConsultaInserta)){
		$IdRegistroCelaRepositorio = $Conexion->insert_id;
		$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
			GetSQLValueString( "NULL", "int"),
			GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
			GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
			GetSQLValueString( 'CelaRepositorio', "varchar"),
			GetSQLValueString( $IdRegistroCelaRepositorio, "int"),
			GetSQLValueString( 2, "int"));
		$ResultadoLog=$Conexion->query($ConsultaLog);
		
        if(isset($_GET['Back'])){
            $InsertGoTo = $_POST['Back'];
        }else{
            $InsertGoTo = "CelaRepositorioLeer.php?".EncodeThis("tabla=".$_GET['tabla']."&cvetabla=".$_GET['cvetabla']."&Status=SuccessC");    
        }
		
		header(sprintf("Location: %s", $InsertGoTo));
	}else{
		$Status="Error";
		$Error=$Conexion->error;
	}
}
?>
<!DOCTYPE html>
<html lang="es">
	<?php include 'CELAHead.php'; ?>
	<body>
		<?php include 'CELAMenuHorizontal.php'; ?>
		<div class="container-fluid">
			<div class="row">
			<?php include 'CELAMenuVertical.php'; ?>
				<div id="main-container" class="<?php print $registrosMenu>0?'col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10':'col-sm-12 col-md-12'; ?> main">
					<div class="row">
						<?php include 'CELARuta.php'; ?>
					</div>
					<div class="row">
						<div class="col-sm-12 col-md-12">
						<div class="row panel panel-primary">
							<div class="panel-heading">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header col-xs-6 text-left">
										<strong>
											<span style="font-size: 18pt;">Escritorio</span>
										</strong>
									</div>
									<div class="box-icon col-xs-6 text-right">
										<a data-intro="Ayuda general" data-position="left" href="#" class="btn-help btn btn-default" title="Ayuda"><i class="fa fa-question">
</i>
</a>
									</div>
								</div>
							</div>
							</div>
					<?php
						if(isset($Privilegios['Crear']) && $Privilegios['Crear']==1){
					?>
							<div class="box-content panel-body">
						<?php
							if(isset($Status) && $Status=="Error"){
						?>
								<div class="alert alert-danger" role="alert">
									<p>
										<i class="fa fa-times fa-lg"></i>&nbsp; Oops!... Ocurrio un error registrando el elemento, puede <a href="<?php print $FormAction; ?>" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>
									</p>
									<p><?php print $Error; ?></p>
								</div>
						<?php
							}else{
						?>
								<form class="form-horizontal form_validate" method="POST" name="CelaRepositorio" id="CelaRepositorio" action="<?php echo $FormAction; ?>" enctype="multipart/form-data" >
									<fieldset>
										<span class="clearfix"></span>
										<hr />
										<div class="form-group">
											<div class="group-validate">
			                                    <label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Archivo: </label>
			                                    <div class="col-sm-10">
													<div class="col-xs-8 validate">
			                                        	<input class="focused e_requerido" name="Archivo" id="Archivo" type="file" />
													</div><!--End col-xs-8 validate-->
			                                    </div> <!-- End col-sm-10-->
		                                    </div> <!-- group-validate -->
		                                </div><!-- End form-group -->
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Descripci&oacute;n: </label>
			                                    <div class="col-sm-10">
													<div class="col-xs-8 validate">
														<textarea class="form-control focused e_requerido" name="Descripci_on" id="Descripci_on" rows="3"></textarea>
													</div><!--End col-xs-8 validate-->
			                                    </div> <!-- End col-sm-10-->
		                                    </div> <!-- group-validate -->
		                                </div><!-- End form-group -->
		                                <input type="hidden" name="CelaRepositorioInsert" value="CelaRepositorioInsert" />
										<input name="<?php print Encrypt('Tabla',$_SESSION['CELA_Aleatorio']); ?>" type="hidden" value="<?php print Encrypt($_GET['tabla'],$_SESSION['CELA_Aleatorio']); ?>" />
										<input name="<?php print Encrypt('idTabla',$_SESSION['CELA_Aleatorio']); ?>" type="hidden" value="<?php print Encrypt($_GET['cvetabla'],$_SESSION['CELA_Aleatorio']); ?>" >
										<input name="Back" type="hidden" value="<?php print $Back; ?>" >
		                                <span class="clearfix"></span>
										<hr />
										<div class="form-group">
											<div class="col-md-offset-3 col-md-9">
												<button id="Guardar" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Guardar
												</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<button type="reset" class="btn btn-default" onclick = "location.href='<?php  if(isset($_GET['Back'])){ print $Back; }else{ print print "CelaRepositorioLeer.php?".EncodeThis("tabla=".$_GET['tabla']."&cvetabla=".$_GET['cvetabla']);} ?>'" >
													<i class="fa fa-undo"></i>&nbsp; Cancelar
												</button>
											</div>
										</div>
									</fieldset>
								</form>
						<?php
							}
						?>
							</div>
					<?php
						}
					?>
							<div class="panel-footer text-right">
								<a class="btn btn-danger" href="<?php print isset($_SERVER['HTTP_REFERER'])?substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER'])):'Escritorio.php'; ?>" title="ir atr&aacute;s"  data-intro="Regresa al formulario anterior" data-position="left"><i class="fa fa-arrow-left"></i>&nbsp; ir atr&aacute;s</a>
							</div>
						</div>
						</div>
					</div>
					<hr>
					<span class="clearfix"></span>
					<?php include 'CELAPie.php'; ?>
				</div>
			</div>
		</div>
		<?php include 'CELAJavascript.php'; ?>
	</body>
</html>