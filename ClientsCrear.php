<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["CelaUsuarioInsert"])) && ($_POST["CelaUsuarioInsert"] == "CelaUsuarioInsert")) {
	$ConsultaInserta = sprintf("INSERT INTO CelaUsuario (  idUsuario,NombreCompleto,Usuario,Contrase_na,CorreoElectr_onico,EstadoActual,Rol ) VALUES (  %s, %s, %s, %s, %s, %s, %s)", 
		GetSQLValueString(NULL, "int"),
		GetSQLValueString($_POST['NombreCompleto'], "varchar") ,
		GetSQLValueString($_POST['Usuario'], "varchar") ,
		GetSQLValueString(md5($_POST['Contrase_na']), "varchar") ,
		GetSQLValueString($_POST['CorreoElectr_onico'], "varchar") ,
		GetSQLValueString($_POST['EstadoActual'], "int") ,
		GetSQLValueString($_POST['Rol'], "int")  );
	if($ResultadoInserta = $Conexion->query($ConsultaInserta)){
		$IdRegistroCelaUsuario = $Conexion->insert_id;
		$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
			GetSQLValueString( "NULL", "int"),
			GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
			GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
			GetSQLValueString( 'CelaUsuario', "varchar"),
			GetSQLValueString( $IdRegistroCelaUsuario, "int"),
			GetSQLValueString( 2, "int"));
		$ResultadoLog=$Conexion->query($ConsultaLog);
		$InsertGoTo = "CelaUsuarioLeer.php?".EncodeThis("Status=SuccessC");
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
						<div class="col-sm-12 col-md-12">
						<div class="row panel panel-primary">
							<div class="panel-heading">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header col-xs-6 text-left">
										<strong>
											<span style="font-size: 18pt;">Add Client</span>
										</strong>
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
								<form class="form-horizontal form_validate" method="POST" name="CelaUsuario" id="CelaUsuario" action="<?php echo $FormAction; ?>" >
									<fieldset>
										<span class="clearfix"></span>
										<hr />
										<div class="form-group">
											
										</div><!-- End form-group -->
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Username: </label>
												<div class="col-sm-10">
													<div class="col-xs-4 validate">
														<input class="form-control focused e_usuario e_remoto e_requerido" name="Usuario" id="Usuario" type="text" data-remote='{"tabla":"CelaUsuario","campo":"Usuario"}'/>
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div><!-- End form-group -->
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Slogan: </label>
												<div class="col-sm-10">
													<div class="col-xs-12 validate">
														<input class="form-control focused " name="slogan" id="slogan" type="text" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div>
										
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Logo: </label>
												<div class="col-sm-10">
													<div class="col-xs-4 validate">
														<input class="form-control focused  " name="logo" id="logo" type="file" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div>
										
										
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  PIN: </label>
												<div class="col-sm-10">
													<div class="col-xs-2 validate">
														<input class="form-control focused e_requerido" name="pin" id="pin" type="password" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div><!-- End form-group -->
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Confirm PIN: </label>
												<div class="col-sm-10">
													<div class="col-xs-2 validate">
														<input class="form-control focused e_requerido e_igual" name="pinr" id="pinr" type="password" data-igual_a="pin" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div>
										
										
										<input type="hidden" name="CelaUsuarioInsert" value="CelaUsuarioInsert" />
										<span class="clearfix"></span>
										<hr />										
										<div class="form-group">
											<div class="col-md-offset-3 col-md-9">
												<button id="Guardar" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Save
												</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<button type="reset" class="btn btn-default" onclick = "location.href='CelaUsuarioLeer.php'" >
													<i class="fa fa-undo"></i>&nbsp; Cancel
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
								<a class="btn btn-danger" href="<?php print substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER'])); ?>" title="ir atr&aacute;s"  data-intro="Regresa al formulario anterior" data-position="left"><i class="fa fa-arrow-left"></i>&nbsp; Back</a>
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