<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');

$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$Back=substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER']));
$UpdateGoTo = "CelaRepositorioLeer.php";
if ((isset($_POST["CelaRepositorioUpdate"])) && ($_POST["CelaRepositorioUpdate"] == "CelaRepositorioUpdate")) {
	if(isset($_POST[Encrypt('idRepositorio',$_SESSION['CELA_Aleatorio'])])){
		$Clave=Decrypt($_POST[Encrypt('idRepositorio',$_SESSION['CELA_Aleatorio'])],$_SESSION['CELA_Aleatorio']);
		$Clave=explode(",",$Clave);
	}else{
    	if(isset($_GET['Back'])){
                header(sprintf("Location: %s", substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER']))));
            }else{
               header(sprintf("Location: %s", $UpdateGoTo."?".EncodeThis("Status=SuccessA&tabla=".$_GET['tabla']."&cvetabla=".$_GET['idtabla'])));
            }   
    	}
	$Status=false;
	foreach($Clave as $Valor){
		//Verificamos que el archivo no alla sido modificado.
		if($_FILES['Archivo'.$Valor]['name']!=NULL || $_FILES['Archivo'.$Valor]['name']!= "" ){
			//Obtenemos algunos atributos del archivo original
			$Archivo = $_FILES["Archivo".$Valor]["tmp_name"];
			$Nombre  = $_FILES["Archivo".$Valor]["name"];
			
			$RutaArchivo=CreateEncodeFile($Nombre, $Archivo);
			
			$ConsultaInserta = sprintf("INSERT INTO CelaRepositorio (  Tabla,idTabla,Ruta,Descripci_on,idUsuario,FechaDeCreaci_on,Estado ) VALUES (   %s, %s, %s, %s, %s, %s, %s)", 
				GetSQLValueString('CelaRepositorio', "varchar") ,
				GetSQLValueString($Valor, "varchar") ,
				GetSQLValueString(Decrypt($_POST['Ruta'.$Valor],$_SESSION['CELA_Aleatorio']), "varchar") ,
				GetSQLValueString(Decrypt($_POST['Descripci_onAnt'.$Valor],$_SESSION['CELA_Aleatorio']), "varchar") ,
				GetSQLValueString($_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int") ,
				GetSQLValueString(date("Y-m-d H:i:s"), "timestamp") ,
				GetSQLValueString(1, "tinyint"));
			$ResultadoInserta = $Conexion->query($ConsultaInserta);
			//@unlink(Decrypt($_POST['Ruta'.$Valor],$_SESSION['CELA_Aleatorio']));
		}else{
			$RutaArchivo=Decrypt($_POST['Ruta'.$Valor],$_SESSION['CELA_Aleatorio']);
		}
		
		$ConsultaActualiza = sprintf("UPDATE CelaRepositorio set Ruta=%s, Descripci_on=%s, Estado=%s where idRepositorio = %s",
			GetSQLValueString($RutaArchivo, "varchar"), 
			GetSQLValueString($_POST['Descripci_on'.$Valor], "varchar"), 
			GetSQLValueString($_POST['Estado'.$Valor], "tinyint") , GetSQLValueString($Valor, "int"));

		if($ResultadoActualiza = $Conexion->query($ConsultaActualiza)){
			
			$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAccesos, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
				GetSQLValueString( "NULL", "int"),
				GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
				GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
				GetSQLValueString( 'CelaRepositorio', "varchar"),
				GetSQLValueString( $Valor, "int"),
				GetSQLValueString( 5, "int"));
			$ResultadoLog=$Conexion->query($ConsultaLog);
			$Status=true;	
		}else{
			$Status=false;
		}
	}
	if($Status==true){
	    if(isset($_GET['Back'])){
            header(sprintf("Location: %s", $_POST['Back']));
        }else{
           header(sprintf("Location: %s", $UpdateGoTo."?".EncodeThis("Status=SuccessA&tabla=".$_GET['tabla']."&cvetabla=".$_GET['idtabla'])));
        }	
	}else{
		$Status="Error";
		$Error=$Conexion->error;
	}
}
if ( !isset( $_GET['clave'])  ){
    if(isset($_GET['Back'])){
        header(sprintf("Location: %s", substr($_SERVER['HTTP_REFERER'],strripos($_SERVER['HTTP_REFERER'],"/")+1,strlen($_SERVER['HTTP_REFERER']))));
    }else{
        header(sprintf("Location: %s", $UpdateGoTo."?".EncodeThis("tabla=".$_GET['tabla']."&cvetabla=".$_GET['idtabla'])));
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
										<a data-intro="Ayuda general" data-position="left" href="#" class="btn-help btn btn-default" title="Ayuda"><i class="fa fa-question"></i></a>
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
								<form class="form-horizontal form_validate" method="POST" name="CelaRepositorio" id="CelaRepositorio" action="<?php print $FormAction; ?>" enctype="multipart/form-data">
									<fieldset>
										<span class="clearfix"></span>
										<hr />	
								<?php
									$con=0;
									$ClaveCelaRepositorio='';
									foreach($_GET['clave'] as $Valor){
										$ConsultaCelaRepositorio = "SELECT * FROM CelaRepositorio WHERE idRepositorio =  ".$Valor."";
										$ResultadoCelaRepositorio = $Conexion->query($ConsultaCelaRepositorio);
										$RegistroCelaRepositorio = $ResultadoCelaRepositorio->fetch_assoc();
										if($con==0){
											$ClaveCelaRepositorio=$RegistroCelaRepositorio['idRepositorio'];
										}else{
											$ClaveCelaRepositorio.=",".$RegistroCelaRepositorio['idRepositorio'];
										}
								?>
										<div class="thumbnail" style="background-color: <?php print $con%2==0?'#F9F9F9':'#FFFFF'; ?>">
											<fieldset>
												<legend>Registro <?php print $RegistroCelaRepositorio['idRepositorio']; ?></legend>
												<div class="form-group">
													<div class="group-validate">
					                                    <label class="col-sm-2 control-label" for="focusedInput"> Archivo : </label>
					                                    <div class="col-sm-10">
															<div class="col-xs-8 validate">
					                                        	<input class="focused" name="Archivo<?php print $RegistroCelaRepositorio['idRepositorio']; ?>" id="Archivo<?php print $RegistroCelaRepositorio['idRepositorio']; ?>" type="file" value="" />
															</div><!--End col-xs-8 validate-->
					                                    </div> <!-- End col-sm-10-->
				                                    </div><!-- group-validate -->
				                                </div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
					                                    <label class="col-sm-2 control-label" for="focusedInput">Descripci&oacute;n: </label>
					                                    <div class="col-sm-10">
															<div class="col-xs-8 validate">
																<textarea class="form-control focused e_requerido " name="Descripci_on<?php print $RegistroCelaRepositorio['idRepositorio']; ?>" id="Descripci_on<?php print $RegistroCelaRepositorio['idRepositorio']; ?>" rows="3"><?php print $RegistroCelaRepositorio['Descripci_on']; ?></textarea>
															</div><!--End col-xs-8 validate-->
														</div> <!-- End col-sm-10-->
													</div><!-- group-validate -->
				                                </div><!-- End form-group -->
												<div class="form-group">
													<div class="group-validate">
					                                    <label class="col-sm-2 control-label" for="focusedInput">Estado: </label>
					                                    <div class="col-sm-10">
															<div class="col-xs-8 validate">
																<select class="form-control focused e_requerido e_combo" name="Estado<?php print $RegistroCelaRepositorio['idRepositorio']; ?>" id="Estado<?php print $RegistroCelaRepositorio['idRepositorio']; ?>">
																	<option <?php print $RegistroCelaRepositorio['Estado']==0? "selected=\"selected\"":""; ?> value="0" >Deshabilitado</option>
																	<option <?php print $RegistroCelaRepositorio['Estado']==1? "selected=\"selected\"":""; ?> value="1" >Disponible</option>
																</select>
															</div><!--End col-xs-8 validate-->
					                                    </div> <!-- End col-sm-10-->
				                                    </div><!-- group-validate -->
				                                </div><!-- End form-group -->
												
												<input name="Ruta<?php print $RegistroCelaRepositorio['idRepositorio'];?>" type="hidden"  value="<?php print Encrypt($RegistroCelaRepositorio['Ruta'],$_SESSION['CELA_Aleatorio']); ?>" />
												<input name="Descripci_onAnt<?php print $RegistroCelaRepositorio['idRepositorio'];?>" type="hidden"  value="<?php print Encrypt($RegistroCelaRepositorio['Descripci_on'],$_SESSION['CELA_Aleatorio']); ?>" />
											</fieldset>
										</div>
								<?php
										$con++;
									}
								?>
										<input type="hidden" name="CelaRepositorioUpdate" value="CelaRepositorioUpdate">
										<input type="hidden" name="<?php print Encrypt('idRepositorio',$_SESSION['CELA_Aleatorio']);?>" value="<?php print Encrypt($ClaveCelaRepositorio,$_SESSION['CELA_Aleatorio']); ?>">
                                        <input name="Back" type="hidden" value="<?php print $Back; ?>" >
										
										<span class="clearfix"></span>
										<hr />										
										<div class="form-group last">
											<div class="col-sm-offset-3 col-sm-9">
												<button id="Actualiza" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Hacer Cambios
												</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<button type="reset" class="btn btn-default" onclick = "location.href='<?php  if(isset($_GET['Back'])){ print $Back;; }else{ print print "CelaRepositorioLeer.php?".EncodeThis("tabla=".$_GET['tabla']."&cvetabla=".$_GET['cvetabla']);} ?>'" >
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