<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$UpdateGoTo = "CelaFormularioLeer.php";
if ((isset($_POST["CelaFormularioUpdate"])) && ($_POST["CelaFormularioUpdate"] == "CelaFormularioUpdate")) {
	if(isset($_POST[Encrypt('idFormulario',$_SESSION['CELA_Aleatorio'])])){
		$Clave=Decrypt($_POST[Encrypt('idFormulario',$_SESSION['CELA_Aleatorio'])],$_SESSION['CELA_Aleatorio']);
		$Clave=explode(",",$Clave);
	}else{
		header(sprintf("Location: %s", $UpdateGoTo));
	}
	foreach($Clave as $Valor){ 
		$ConsultaActualiza = sprintf("UPDATE CelaFormulario SET Nombre=%s, Descripci_on=%s, Ruta=%s WHERE idFormulario= %s", 
			GetSQLValueString($_POST['Nombre'.$Valor], "varchar") , 
			GetSQLValueString($_POST['Descripci_on'.$Valor], "varchar"), 
			GetSQLValueString($_POST['Ruta'.$Valor], "varchar") , GetSQLValueString($Valor, "int")  );
		if($ResultadoActualiza = $Conexion->query($ConsultaActualiza)){
			$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
				GetSQLValueString( "NULL", "int"),
				GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
				GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
				GetSQLValueString( 'CelaFormulario', "varchar"),
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
	$UpdateGoTo = "CelaFormularioLeer.php";
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
						<div class="col-sm-12 col-md-12">
						<div class="row panel panel-primary">
							<div class="panel-heading">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header col-xs-6 text-left">
										<strong>
											<span style="font-size: 18pt;">Actualizaci&oacute;n de Formulario</span>
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
								<form class="form-horizontal form_validate" method="POST" name="CelaFormulario" id="CelaFormulario" action="<?php print $FormAction; ?>" >
									<fieldset>
										<span class="clearfix"></span>
										<hr />	
								<?php
									$con=0;
									$ClaveCelaFormulario='';
									foreach($_GET['clave'] as $Valor){
										$ConsultaCelaFormulario = "SELECT * FROM CelaFormulario WHERE idFormulario =  ".$Valor."";
										$ResultadoCelaFormulario = $Conexion->query($ConsultaCelaFormulario);
										$RegistroCelaFormulario = $ResultadoCelaFormulario->fetch_assoc();
										if($con==0){
											$ClaveCelaFormulario=$RegistroCelaFormulario['idFormulario'];
										}else{
											$ClaveCelaFormulario.=",".$RegistroCelaFormulario['idFormulario'];
										}
								?>
										<div class="thumbnail" style="background-color: <?php print $con%2==0?'#F9F9F9':'#FFFFF'; ?>">
											<fieldset>
												<legend>Registro <?php print $RegistroCelaFormulario['idFormulario']; ?></legend>
											<div class="form-group">
												<div class="group-validate">
													<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Nombre: </label>
													<div class="col-sm-10">
														<div class="col-xs-8 validate">
															<input class="form-control focused e_requerido" name="Nombre<?php print $RegistroCelaFormulario['idFormulario'];?>" id="Nombre<?php print $RegistroCelaFormulario['idFormulario'];?>" type="text" value="<?php print $RegistroCelaFormulario['Nombre']; ?>"/>
														</div><!--End col-xs-8 validate-->
													</div> <!-- End col-sm-10-->
												</div><!-- group-validate -->
											</div><!-- End form-group -->
											<div class="form-group">
												<div class="group-validate">
													<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Descripci&oacute;n: </label>
													<div class="col-sm-10">
														<div class="col-xs-8 validate">
															<input class="form-control focused e_requerido" name="Descripci_on<?php print $RegistroCelaFormulario['idFormulario'];?>" id="Descripci_on<?php print $RegistroCelaFormulario['idFormulario'];?>" type="text" value="<?php print $RegistroCelaFormulario['Descripci_on']; ?>"/>
														</div><!--End col-xs-8 validate-->
													</div> <!-- End col-sm-10-->
												</div><!-- group-validate -->
											</div><!-- End form-group -->
											<div class="form-group">
												<div class="group-validate">
													<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Ruta: </label>
													<div class="col-sm-10">
														<div class="col-xs-8 validate">
															<input class="form-control focused e_requerido" name="Ruta<?php print $RegistroCelaFormulario['idFormulario'];?>" id="Ruta<?php print $RegistroCelaFormulario['idFormulario'];?>" type="text" value="<?php print $RegistroCelaFormulario['Ruta']; ?>"/>
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
										<input type="hidden" name="CelaFormularioUpdate" value="CelaFormularioUpdate">
										<input type="hidden" name="<?php print Encrypt('idFormulario',$_SESSION['CELA_Aleatorio']);?>" value="<?php print Encrypt($ClaveCelaFormulario,$_SESSION['CELA_Aleatorio']); ?>">
										<span class="clearfix"></span>
										<hr />
										<div class="form-group last">
											<div class="col-sm-offset-3 col-sm-9">
												<button id="Actualiza" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Hacer Cambios
												</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<button type="reset" class="btn btn-default" onclick = "location.href='CelaFormularioLeer.php'" >
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