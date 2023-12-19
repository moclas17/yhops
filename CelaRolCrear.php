<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["CelaRolInsert"])) && ($_POST["CelaRolInsert"] == "CelaRolInsert")) {
	$ConsultaInserta = sprintf("INSERT INTO CelaRol (  idRol,NombreDelRol,Siglas,Descripci_onDelRol ) VALUES (  %s, %s, %s, %s)", 
		GetSQLValueString(NULL, "int"),
		GetSQLValueString($_POST['NombreDelRol'], "varchar") ,
		GetSQLValueString($_POST['Siglas'], "varchar") ,
		GetSQLValueString($_POST['Descripci_onDelRol'], "varchar")  );
	if($ResultadoInserta = $Conexion->query($ConsultaInserta)){
		$IdRegistroCelaRol = $Conexion->insert_id;
	
	$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
			GetSQLValueString( "NULL", "int"),
			GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
			GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
			GetSQLValueString( 'CelaRol', "varchar"),
			GetSQLValueString( $IdRegistroCelaRol, "int"),
			GetSQLValueString( 2, "int"));
		$ResultadoLog=$Conexion->query($ConsultaLog);
		$InsertGoTo = "CelaRolLeer.php?".EncodeThis("Status=SuccessC");
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
											<span style="font-size: 18pt;">Creaci&oacute;n de Rol</span>
										</strong>
									</div>
									<div class="box-icon col-xs-6 text-right">
										<a data-intro="Ayuda general" data-position="left" href="#" class="btn-help btn btn-default" title="Ayuda">
											<i class="fa fa-question"></i>
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
								<form class="form-horizontal form_validate" method="POST" name="CelaRol" id="CelaRol" action="<?php echo $FormAction; ?>" >
									<fieldset>
										<span class="clearfix"></span>
										<hr />
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Nombre del Rol: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
														<input class="form-control focused e_requerido" name="NombreDelRol" id="NombreDelRol" type="text" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- group-validate -->
										</div><!-- End form-group -->
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Siglas: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
														<input class="form-control focused e_requerido" name="Siglas" id="Siglas" type="text" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- group-validate -->
										</div><!-- End form-group -->
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Descripci&oacute;n del Rol: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
														<input class="form-control focused e_requerido" name="Descripci_onDelRol" id="Descripci_onDelRol" type="text" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- group-validate -->
										</div><!-- End form-group -->
										<input type="hidden" name="CelaRolInsert" value="CelaRolInsert" />
										<span class="clearfix"></span>
										<hr />
										<div class="form-group last">
											<div class="col-sm-offset-3 col-sm-9">
												<button id="Guardar" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Guardar
												</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<button type="reset" class="btn btn-default" onclick = "location.href='CelaRolLeer.php'" >
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