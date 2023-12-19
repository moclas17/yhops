<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$FormAction = $_SERVER['PHP_SELF'];
if(isset($_SERVER['QUERY_STRING'])){
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$UpdateGoTo = "ServidorLeer.php";
if((isset($_POST["ServidorUpdate"])) && ($_POST["ServidorUpdate"] == "ServidorUpdate")){
	if(isset($_POST[Encrypt('id',$_SESSION['CELA_Aleatorio'])])){
		$Clave = Decrypt($_POST[Encrypt('id',$_SESSION['CELA_Aleatorio'])],$_SESSION['CELA_Aleatorio']);
		$Clave = explode(",",$Clave);
	}else{
		header(sprintf("Location: %s", $UpdateGoTo));
	}
	$Status = false;
	foreach($Clave as $Valor){
		$ConsultaActualiza = sprintf("UPDATE Servidor SET `Servidor`=%s, `IP`=%s, `Usuario`=%s, `Contrase_na`=%s, `ns1`=%s, `ns2`=%s, `dominioPrincipal`=%s, `Proveedor`=%s WHERE id = %s", 
			GetSQLValueString($_POST['Servidor'.$Valor], "varchar") , 
			GetSQLValueString($_POST['IP'.$Valor], "varchar"), 
			GetSQLValueString($_POST['Usuario'.$Valor], "varchar"), 
			GetSQLValueString($_POST['Contrase_na'.$Valor], "varchar"), 
			GetSQLValueString($_POST['ns1'.$Valor], "varchar"), 
			GetSQLValueString($_POST['ns2'.$Valor], "varchar"), 
			GetSQLValueString($_POST['dominioPrincipal'.$Valor], "varchar"), 
			GetSQLValueString($_POST['Proveedor'.$Valor], "int unsigned") , GetSQLValueString($Valor, "int")  );
		if($ResultadoActualiza = $Conexion->query($ConsultaActualiza)){
			$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
				GetSQLValueString( "NULL", "int"),
				GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
				GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
				GetSQLValueString( 'Servidor', "varchar"),
				GetSQLValueString( $Valor, "int"),
				GetSQLValueString( 5, "int"));
			$ResultadoLog= $Conexion->query($ConsultaLog);
			$Status      = true;
		}else{
			$Status = false;
		}
	}
	if($Status == true){
		header(sprintf("Location: %s", $UpdateGoTo."?".EncodeThis("Status=SuccessA")));
	}else{
		$Status = "Error";
		$Error  = $Conexion->error;
	}
}
if( !isset( $_GET['clave'])  ){
	$UpdateGoTo = "ServidorLeer.php";
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
				<div id="main-container" class="<?php print $registrosMenu > 0?'col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10':'col-sm-12 col-md-12'; ?> main">
					<div class="row">
						<?php include 'CELARuta.php'; ?>
					</div>
					<div class="row">
						<div class="col-sm-12 col-md-12">
						<div class="row panel panel-primary">
							<div class="panel-heading">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header col-xs-11 text-left">
										<strong>
											<span style="font-size: 18pt;">
												Actualizaci&oacute;n de  Servidor
											</span>
										</strong>
									</div>
									<div class="box-icon col-xs-1 text-right">
										<a data-intro="Ayuda general" data-position="left" href="#" class="btn-help btn btn-default" title="Ayuda">
											<i class="fa fa-question"></i>
										</a>
									</div>
								</div>
							</div>
							</div>
					<?php
						if( isset($Privilegios['Actualizar']) && $Privilegios['Actualizar'] == 1){
					?>
							<div class="box-content panel-body">
						<?php
							if(isset($Status) && $Status == "Error"){
						?>
								<div class="alert alert-danger" role="alert">
									<p>
										<i class="fa fa-times fa-lg">
										</i>&nbsp; Oops!... Ocurrio un error actualizando el elemento, puede
										<a href="<?php print $FormAction; ?>" class="alert-link">
											volver a intentar
										</a> &oacute;
										<a href="Escritorio.php" class="alert-link">
											ir al escritorio
										</a>
									</p>
									<p>
										<?php print $Error; ?>
									</p>
								</div>
						<?php
							}else{
						?>
								<form class="form-horizontal form_validate" method="POST" name="Form_Servidor" id="Form_Servidor" action="<?php print $FormAction; ?>" >
									<fieldset>
										<span class="clearfix"></span>
										<hr />
								<?php
									$con    = 0;
									$ClaveServidor = '';
									foreach($_GET['clave'] as $Valor){
										$ConsultaServidor = "SELECT * FROM Servidor WHERE id =  $Valor";
										$ResultadoServidor = $Conexion->query($ConsultaServidor);
										$RegistroServidor = $ResultadoServidor->fetch_assoc();
										if($con == 0){
											$ClaveServidor = $RegistroServidor['id'];
										}else{
											$ClaveServidor .= ",".$RegistroServidor['id'];
										}
								?>
										<div class="thumbnail" style="background-color: <?php print $con % 2 == 0?'#F9F9F9':'#FFFFF'; ?>">
											<fieldset>
												<legend>
													Registro <?php print $RegistroServidor['id']; ?>
												</legend>
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Servidor: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido  e_longitud" name="Servidor<?php print $RegistroServidor['id'];?>" id="Servidor<?php print $RegistroServidor['id'];?>" type="text" value="<?php print $RegistroServidor['Servidor']; ?>" data-rango='{"minimo":1,"maximo":128,"mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}'/>
															</div><!--End col-xs-8-->
														</div> <!-- End col-sm-10-->
													</div><!-- End group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> I P: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido  e_longitud" name="IP<?php print $RegistroServidor['id'];?>" id="IP<?php print $RegistroServidor['id'];?>" type="text" value="<?php print $RegistroServidor['IP']; ?>" data-rango='{"minimo":1,"maximo":128,"mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}'/>
															</div><!--End col-xs-8-->
														</div> <!-- End col-sm-10-->
													</div><!-- End group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Usuario: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido  e_longitud" name="Usuario<?php print $RegistroServidor['id'];?>" id="Usuario<?php print $RegistroServidor['id'];?>" type="text" value="<?php print $RegistroServidor['Usuario']; ?>" data-rango='{"minimo":1,"maximo":128,"mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}'/>
															</div><!--End col-xs-8-->
														</div> <!-- End col-sm-10-->
													</div><!-- End group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Contrase&ntilde;a: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido  e_longitud" name="Contrase_na<?php print $RegistroServidor['id'];?>" id="Contrase_na<?php print $RegistroServidor['id'];?>" type="text" value="<?php print $RegistroServidor['Contrase_na']; ?>" data-rango='{"minimo":1,"maximo":128,"mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}'/>
															</div><!--End col-xs-8-->
														</div> <!-- End col-sm-10-->
													</div><!-- End group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font>ns1: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido  e_longitud" name="ns1<?php print $RegistroServidor['id'];?>" id="ns1<?php print $RegistroServidor['id'];?>" type="text" value="<?php print $RegistroServidor['ns1']; ?>" data-rango='{"minimo":1,"maximo":128,"mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}'/>
															</div><!--End col-xs-8-->
														</div> <!-- End col-sm-10-->
													</div><!-- End group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font>ns2: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido  e_longitud" name="ns2<?php print $RegistroServidor['id'];?>" id="ns2<?php print $RegistroServidor['id'];?>" type="text" value="<?php print $RegistroServidor['ns2']; ?>" data-rango='{"minimo":1,"maximo":128,"mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}'/>
															</div><!--End col-xs-8-->
														</div> <!-- End col-sm-10-->
													</div><!-- End group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font>dominio Principal: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido  e_longitud" name="dominioPrincipal<?php print $RegistroServidor['id'];?>" id="dominioPrincipal<?php print $RegistroServidor['id'];?>" type="text" value="<?php print $RegistroServidor['dominioPrincipal']; ?>" data-rango='{"minimo":1,"maximo":128,"mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}'/>
															</div><!--End col-xs-8-->
														</div> <!-- End col-sm-10-->
													</div><!-- End group-validate -->
												</div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Proveedor: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido  e_rango" name="Proveedor<?php print $RegistroServidor['id'];?>" id="Proveedor<?php print $RegistroServidor['id'];?>" type="text" value="<?php print $RegistroServidor['Proveedor']; ?>" data-rango='{"minimo":0,"maximo":4294967295,"mensaje":"Introduce un valor entre 0 y 4294967295"}'/>
															</div><!--End col-xs-8-->
														</div> <!-- End col-sm-10-->
													</div><!-- End group-validate -->
												</div><!-- End form-group -->
											</fieldset>
										</div>
								<?php
										$con++;
									}
								?>
										<input type="hidden" name="ServidorUpdate" value="ServidorUpdate">
										<input type="hidden" name="<?php print Encrypt('id',$_SESSION['CELA_Aleatorio']);?>" value="<?php print Encrypt($ClaveServidor,$_SESSION['CELA_Aleatorio']); ?>">
										<span class="clearfix"></span>
										<hr />
										<div class="form-group last">
											<div class="col-sm-offset-3 col-sm-9">
												<button id="Actualiza" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Hacer Cambios
												</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<button type="reset" class="btn btn-default" onclick = "location.href='ServidorLeer.php'" >
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
								<a class="btn btn-danger" href="<?php print isset($_SERVER['HTTP_REFERER'])?substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/") + 1,strlen($_SERVER['HTTP_REFERER'])):"Escritorio.php"; ?>" title="ir atr&aacute;s"  data-intro="Regresa al formulario anterior" data-position="left">
									<i class="fa fa-arrow-left">
									</i>&nbsp; ir atr&aacute;s
								</a>
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