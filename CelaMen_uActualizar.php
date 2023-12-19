<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$UpdateGoTo = "CelaMen_uLeer.php";
if ((isset($_POST["CelaMen_uUpdate"])) && ($_POST["CelaMen_uUpdate"] == "CelaMen_uUpdate")) {
	if(isset($_POST[Encrypt('idMen_u',$_SESSION['CELA_Aleatorio'])])){
		$Clave=Decrypt($_POST[Encrypt('idMen_u',$_SESSION['CELA_Aleatorio'])],$_SESSION['CELA_Aleatorio']);
		$Clave=explode(",",$Clave);
	}else{
		
			else{
			
			$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
		else{
	else{
?>
<!DOCTYPE html>
						<div class="col-sm-12 col-md-12">
							<div class="row">
											<i class="fa fa-question"></i>
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
							}
							else{
						?>
								<form class="form-horizontal form_validate" method="POST" name="CelaMen_u" id="CelaMen_u" action="<?php print $FormAction; ?>" >
									<fieldset>
										<span class="clearfix"></span>
										<hr />	
								<?php
										else{
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Tipo de Elemento: </label>
													</div><!-- group-validate -->
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Etiqueta: </label>
													</div><!-- group-validate -->
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Descripci&oacute;n: </label>
													</div><!-- group-validate -->
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Referencia o Ruta: </label>
													</div><!-- group-validate -->
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Categor&iacute;a: </label>
															<?php
																$Opc['nombre']="Categor_ia".$RegistroCelaMen_u['idMenu'];
																$Opc['clase']="form-control e_requerido";
																$Opc['EmptyMessage']="MISMA CATEGORIA";
																$Opc['EmptyValue']="NULL";
																$Consulta= " select idMenu, Etiqueta from CelaMen_u where TipoDeElemento=2 ";
																print SRellenaCombo($Consulta,$Opc,$RegistroCelaMen_u['Categor_ia'],1);
															?>
															</div>
														</div>
													</div><!-- group-validate -->
												</div>
												<div class="form-group form-group<?php print $RegistroCelaMen_u['idMenu']; ?>" id="IconoDiv<?php print $RegistroCelaMen_u['idMenu']; ?>" <?php print $RegistroCelaMen_u['TipoDeElemento']==3?'hidden="hidden"':''; ?>>
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font>Icono: </label>
        													   <select name="Icono<?php print $RegistroCelaMen_u['idMenu']; ?>" id="Icono<?php print $RegistroCelaMen_u['idMenu']; ?>" class="form-control show-tick focused" data-live-search="true">
                                                                <?php
                                                                    $ConsultaIcono="SELECT idIcono, NombreDelIcono FROM CelaIcono ORDER BY NombreDelIcono ASC";
                                                                    $ResultadoIconos=$Conexion->query($ConsultaIcono);
                                                                    while($RegistroIconos=$ResultadoIconos->fetch_assoc()){
                                                                ?>
                                                                        <option value="<?php print $RegistroIconos['idIcono']; ?>" data-icon="<?php print $RegistroIconos['NombreDelIcono']; ?>" <?php print $RegistroIconos['idIcono']==$RegistroCelaMen_u['Icono']?'selected="selected"':'' ?> >&nbsp;&nbsp; <?php print $RegistroIconos['NombreDelIcono']; ?></option>
                                                                <?php
                                                                    }
                                                                    
                                                                ?>
                                                                </select>
													</div><!-- group-validate -->
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Orientaci&oacute;n: </label>
													</div><!-- group-validate -->
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Prioridad: </label>
														</div>
													</div><!-- group-validate -->
												</div>
												<div class="form-group form-group<?php print $RegistroCelaMen_u['idMenu']; ?>" id="RolesDiv<?php print $RegistroCelaMen_u['idMenu']; ?>" <?php print $RegistroCelaMen_u['TipoDeElemento']==3?'hidden="hidden"':''; ?>>
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Roles que ven este men&uacute;: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<select id="Roles<?php print $RegistroCelaMen_u['idMenu'];?>" class="form-control e_requerido" size="5" multiple="multiple" name="Roles<?php print $RegistroCelaMen_u['idMenu'];?>[]">
																	<option value="all">TODOS LOS ROLES</option>
															<?php
																print $ConsultaRoles="select RolDeUsuario from CelaPrivilegios where Elemento=".$RegistroCelaMen_u['idMenu']." and Tabla=1";
																$ResultadoRoles=$Conexion->query($ConsultaRoles);
																$Roles=array();
																while($RegistroRoles=$ResultadoRoles->fetch_assoc()){
																	$Roles[]=$RegistroRoles['RolDeUsuario'];
																}
																$Consulta= " select idRol, NombreDelRol from CelaRol";
																$Resultado=$Conexion->query($Consulta);
																while($Registro=$Resultado->fetch_assoc()){
																	print '<option value="'.$Registro['idRol'].'" '.(in_array($Registro['idRol'],$Roles)?'selected="selected"':'').'>'.$Registro['NombreDelRol'].'</option>';
																}
															?>
																</select>
															</div>
														</div>
													</div><!-- group-validate -->
												</div>
											</fieldset>
										</div>
								<?php
										$con++;
									}
								?>
										<input type="hidden" name="CelaMen_uUpdate" value="CelaMen_uUpdate">
										<input type="hidden" name="<?php print Encrypt('idMen_u',$_SESSION['CELA_Aleatorio']);?>" value="<?php print Encrypt($ClaveCelaMen_u,$_SESSION['CELA_Aleatorio']); ?>">
										<span class="clearfix"></span>
										<hr />
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-9">
												<button id="Actualiza" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Hacer Cambios
												</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<button type="reset" class="btn btn-default" onclick = "location.href='CelaMen_uLeer.php'" >
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
		<script type="text/javascript" src="bootstrap/js/bootstrap-select.js"></script>
		<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-select.css">
		<script>
	<?php
		foreach($_GET['clave'] as $Valor){
	?>
			$("#TipoDeElemento<?php print $Valor; ?>").change(function(){
				else{
						$("#Categor_iaDiv<?php print $Valor; ?>").slideDown('slow');
					}
					else{
						$(".form-group<?php print $Valor; ?>").slideDown('slow');
					}
				}
			});
			
			$('#Icono<?php print $Valor; ?>').selectpicker({
                'selectedText': 'cat',
                'iconBase': 'fa ',
                'tickIcon': 'fa fa-check',
            });
	<?php
		}
	?>
		</script>
	</body>
</html>