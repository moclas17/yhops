<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$UpdateGoTo = "CelaRolLeer.php";
if ((isset($_POST["CelaRolUpdate"])) && ($_POST["CelaRolUpdate"] == "CelaRolUpdate")) {
	if(isset($_POST[Encrypt('idRol',$_SESSION['CELA_Aleatorio'])])){
		$Clave=Decrypt($_POST[Encrypt('idRol',$_SESSION['CELA_Aleatorio'])],$_SESSION['CELA_Aleatorio']);
		$Clave=explode(",",$Clave);
	}else{
		header(sprintf("Location: %s", $UpdateGoTo));
	}
	$Status=false;
	foreach($Clave as $Valor){ 
		$ConsultaActualiza = sprintf("UPDATE CelaRol SET NombreDelRol=%s, Siglas=%s, Descripci_onDelRol=%s WHERE idRol= %s", 
			GetSQLValueString($_POST['NombreDelRol'.$Valor], "varchar") , 
			GetSQLValueString($_POST['Siglas'.$Valor], "varchar"), 
			GetSQLValueString($_POST['Descripci_onDelRol'.$Valor], "varchar") , GetSQLValueString($Valor, "int")  );
		if($ResultadoActualiza = $Conexion->query($ConsultaActualiza)){
			$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
				GetSQLValueString( "NULL", "int"),
				GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
				GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
				GetSQLValueString( 'CelaRol', "varchar"),
				GetSQLValueString( $Valor, "int"),
				GetSQLValueString( 5, "int"));
			$ResultadoLog=$Conexion->query($ConsultaLog);
			$Status=true;	
		}else{
			$Status=false;
		}
	}
	if($Status==true){
		header(sprintf("Location: %s", $UpdateGoTo."?".EncodeThis("Status=SuccessA")));	
	}else{
		$Status="Error";
		$Error=$Conexion->error;
	}
}
if ( !isset( $_GET['clave'])  ){
	$UpdateGoTo = "CelaRolLeer.php";
	header(sprintf("Location: %s", $UpdateGoTo));
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
						<div class="row col-sm-12 col-md-12" >
						<div class="panel panel-primary" >
							<div class="panel-heading">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header col-md-6 text-left">
										<strong>
											<span style="font-size: 18pt;">Actualizaci&oacute;n de  Rol del Sistema</span>
										</strong>
									</div>
									<div class="box-icon col-md-6 text-right">
										<a data-intro="Ayuda general" data-position="left" href="#" class="btn-help btn btn-default" title="Ayuda"><i class="fa fa-question"></i></a>
									</div>
								</div>
							</div>
							</div>
					<?php
						if(isset($Privilegios['Actualizar']) && $Privilegios['Actualizar']==1){
					?>
							<div class="box-content panel-body">
						<?php
							if(isset($Status) && $Status=="Error"){
						?>
								<div class="alert alert-danger" role="alert">
									<p>
										<i class="fa fa-times fa-lg"></i>&nbsp; Oops!... Ocurrio un error actualizando el elemento, puede <a href="<?php print $FormAction; ?>" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>
									</p>
									<p><?php print $Error; ?></p>
								</div>
						<?php
							}else{
						?>
								<form class="form-horizontal form_validate" method="POST" name="CelaRol" id="CelaRol" action="<?php print $FormAction; ?>" >
									<fieldset>
										<span class="clearfix"></span>
										<hr />	
								<?php
									$con=0;
									$ClaveCelaRol='';
									foreach($_GET['clave'] as $Valor){
										$ConsultaCelaRol = "SELECT * FROM CelaRol WHERE idRol =  ".$Valor."";
										$ResultadoCelaRol = $Conexion->query($ConsultaCelaRol);
										$RegistroCelaRol = $ResultadoCelaRol->fetch_assoc();
										if($con==0){
											$ClaveCelaRol=$RegistroCelaRol['idRol'];
										}else{
											$ClaveCelaRol.=",".$RegistroCelaRol['idRol'];
										}
								?>
										<div class="thumbnail" style="background-color: <?php print $con%2==0?'#F9F9F9':'#FFFFF'; ?>">
											<fieldset>
												<legend>Registro <?php print $RegistroCelaRol['idRol']; ?></legend>
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Nombre del Rol: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido" name="NombreDelRol<?php print $RegistroCelaRol['idRol'];?>" id="NombreDelRol<?php print $RegistroCelaRol['idRol'];?>" type="text" value="<?php print $RegistroCelaRol['NombreDelRol']; ?>"/>
															</div><!--End col-xs-8 validate-->
														</div> <!-- End col-sm-10-->
													</div><!-- group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Siglas: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido" name="Siglas<?php print $RegistroCelaRol['idRol'];?>" id="Siglas<?php print $RegistroCelaRol['idRol'];?>" type="text" value="<?php print $RegistroCelaRol['Siglas']; ?>"/>
															</div><!--End col-xs-8 validate-->
														</div> <!-- End col-sm-10-->
													</div><!-- group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Descripci&oacute;n del Rol: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido" name="Descripci_onDelRol<?php print $RegistroCelaRol['idRol'];?>" id="Descripci_onDelRol<?php print $RegistroCelaRol['idRol'];?>" type="text" value="<?php print $RegistroCelaRol['Descripci_onDelRol']; ?>"/>
															</div><!--End col-xs-8 validate-->
														</div> <!-- End col-sm-10-->
													</div><!-- group-validate -->
												</div><!-- End form-group -->
											</fieldset>
										</div>
								<?php
										$con++;
									}
								?>
										<input type="hidden" name="CelaRolUpdate" value="CelaRolUpdate">
										<input type="hidden" name="<?php print Encrypt('idRol',$_SESSION['CELA_Aleatorio']);?>" value="<?php print Encrypt($ClaveCelaRol,$_SESSION['CELA_Aleatorio']); ?>">
										<span class="clearfix"></span>
										<hr />
										<div class="form-group last">
											<div class="col-sm-offset-3 col-sm-9">
												<button id="Actualiza" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Hacer Cambios
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
							<div class="panel-footer" align="right">
								<a class="btn btn-danger" href="<?php print substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER'])); ?>" title="ir atr&aacute;s" data-intro="Regresa al formulario anterior" data-position="left"><i class="fa fa-arrow-left"></i>&nbsp; ir atr&aacute;s</a>
							</div>
						</div>
						</div>
					</div>
					<hr>
					<span class="clearfix"></span>
					<?php include 'CELAPie.php'; ?>
				</div>
			</div>
			<?php include 'CELAJavascript.php'; ?>
		</div>
	</body>
</html>