<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$UpdateGoTo = "CelaUsuarioLeer.php";
if ((isset($_POST["CelaUsuarioUpdate"])) && ($_POST["CelaUsuarioUpdate"] == "CelaUsuarioUpdate")) {
	if(isset($_POST[Encrypt('idUsuario',$_SESSION['CELA_Aleatorio'])])){
		$Clave=Decrypt($_POST[Encrypt('idUsuario',$_SESSION['CELA_Aleatorio'])],$_SESSION['CELA_Aleatorio']);
		$Clave=explode(",",$Clave);
	}else{
		header(sprintf("Location: %s", $UpdateGoTo));
	}
	$Status=false;
	foreach($Clave as $Valor){
		if( $_POST['Contrase_na'.$Valor] == "" ||  $_POST['Contrase_na'.$Valor] == NULL)
	        $Contrasena=Decrypt($_POST[Encrypt('Contrase_naant'.$Valor,$_SESSION['CELA_Aleatorio'])],$_SESSION['CELA_Aleatorio']);
	    else
	        $Contrasena=md5($_POST['Contrase_na'.$Valor]);
	        
		$ConsultaActualiza = sprintf("UPDATE CelaUsuario SET NombreCompleto=%s, Usuario=%s, Contrase_na=%s, CorreoElectr_onico=%s, EstadoActual=%s, Rol=%s WHERE idUsuario= %s", 
			GetSQLValueString($_POST['NombreCompleto'.$Valor], "varchar") , 
			GetSQLValueString($_POST['Usuario'.$Valor], "varchar"), 
			GetSQLValueString($Contrasena, "varchar"), 
			GetSQLValueString($_POST['CorreoElectr_onico'.$Valor], "varchar"), 
			GetSQLValueString($_POST['EstadoActual'.$Valor], "int"), 
			GetSQLValueString($_POST['Rol'.$Valor], "int") , GetSQLValueString($Valor, "int")  );
		
		if($ResultadoActualiza = $Conexion->query($ConsultaActualiza)){
			$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
				GetSQLValueString( "NULL", "int"),
				GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
				GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
				GetSQLValueString( 'CelaUsuario', "varchar"),
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
	$UpdateGoTo = "CelaUsuarioLeer.php";
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
											<span style="font-size: 18pt;">Actualizaci&oacute;n de  Usuario</span>
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
								<form class="form-horizontal form_validate" method="POST" name="CelaUsuario" id="CelaUsuario" action="<?php print $FormAction; ?>" >
									<fieldset>
										<span class="clearfix"></span>
										<hr />	
								<?php
									$con=0;
									$ClaveCelaUsuario='';
									foreach($_GET['clave'] as $Valor){
										$ConsultaCelaUsuario = "SELECT * FROM CelaUsuario WHERE idUsuario =  ".$Valor."";
										$ResultadoCelaUsuario = $Conexion->query($ConsultaCelaUsuario);
										$RegistroCelaUsuario = $ResultadoCelaUsuario->fetch_assoc();
										if($con==0){
											$ClaveCelaUsuario=$RegistroCelaUsuario['idUsuario'];
										}else{
											$ClaveCelaUsuario.=",".$RegistroCelaUsuario['idUsuario'];
										}
								?>
										<div class="thumbnail" style="background-color: <?php print $con%2==0?'#F9F9F9':'#FFFFF'; ?>">
											<fieldset>
												<legend>Registro <?php print $RegistroCelaUsuario['idUsuario']; ?></legend>
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Nombre Completo: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido e_nombre " name="NombreCompleto<?php print $RegistroCelaUsuario['idUsuario'];?>" id="NombreCompleto<?php print $RegistroCelaUsuario['idUsuario'];?>" type="text" value="<?php print $RegistroCelaUsuario['NombreCompleto']; ?>"/>
															</div><!--End col-xs-8 validate-->
														</div> <!-- End col-sm-10-->
													</div><!-- group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Usuario: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_usuario e_requerido" name="Usuario<?php print $RegistroCelaUsuario['idUsuario'];?>" id="Usuario<?php print $RegistroCelaUsuario['idUsuario'];?>" type="text" value="<?php print $RegistroCelaUsuario['Usuario']; ?>"/>
															</div><!--End col-xs-8 validate-->
														</div> <!-- End col-sm-10-->
													</div><!-- group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Contrase&ntilde;a: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused " name="Contrase_na<?php print $RegistroCelaUsuario['idUsuario'];?>" id="Contrase_na<?php print $RegistroCelaUsuario['idUsuario'];?>" type="password" />
															</div><!--End col-xs-8 validate-->
														</div> <!-- End col-sm-10-->
													</div><!-- group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Correo Electr&oacute;nico: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido " name="CorreoElectr_onico<?php print $RegistroCelaUsuario['idUsuario'];?>" id="CorreoElectr_onico<?php print $RegistroCelaUsuario['idUsuario'];?>" type="text" value="<?php print $RegistroCelaUsuario['CorreoElectr_onico']; ?>"/>
															</div><!--End col-xs-8 validate-->
														</div> <!-- End col-sm-10-->
													</div><!-- group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Estado Actual: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
															<?php
																$Opc['nombre']="EstadoActual".$RegistroCelaUsuario['idUsuario'];
																$Opc['clase']="form-control e_requerido  e_combo";
																$Consulta= " select idEstado, Descripci_on from CelaEstado ";
																print SRellenaCombo($Consulta,$Opc,$RegistroCelaUsuario['EstadoActual']);
															?>
															</div><!--End col-xs-8 validate-->
														</div> <!-- End col-sm-10-->
													</div><!-- group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput">  Clave del Rol: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
															<?php
																$Opc['nombre']="Rol".$RegistroCelaUsuario['idUsuario'];
																$Opc['clase']="form-control  e_combo";
																$Consulta= " select idRol, NombreDelRol from CelaRol ";
																print SRellenaCombo($Consulta,$Opc,$RegistroCelaUsuario['Rol']);
															?>
															</div><!--End col-xs-8 validate-->
														</div> <!-- End col-sm-10-->
													</div><!-- group-validate -->
												</div><!-- End form-group -->
												<input type="hidden" name="<?php print Encrypt('Contrase_naant'.$RegistroCelaUsuario['idUsuario'],$_SESSION['CELA_Aleatorio']);?>" value="<?php print Encrypt($RegistroCelaUsuario['Contrase_na'],$_SESSION['CELA_Aleatorio']); ?>">
											</fieldset>
										</div>
								<?php
										$con++;
									}
								?>
										<input type="hidden" name="CelaUsuarioUpdate" value="CelaUsuarioUpdate">
										<input type="hidden" name="<?php print Encrypt('idUsuario',$_SESSION['CELA_Aleatorio']);?>" value="<?php print Encrypt($ClaveCelaUsuario,$_SESSION['CELA_Aleatorio']); ?>">
										<span class="clearfix"></span>
										<hr />
										<div class="form-group last">
											<div class="col-sm-offset-3 col-sm-9">
												<button id="Actualiza" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Hacer Cambios
												</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<button type="reset" class="btn btn-default" onclick = "location.href='CelaUsuarioLeer.php'" >
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
								<a class="btn btn-danger" href="<?php print substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER'])); ?>" title="ir atr&aacute;s"  data-intro="Regresa al formulario anterior" data-position="left"><i class="fa fa-arrow-left"></i>&nbsp; ir atr&aacute;s</a>
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