<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["CelaMen_uInsert"])) && ($_POST["CelaMen_uInsert"] == "CelaMen_uInsert")) {
	$ConsultaInserta = sprintf("INSERT INTO CelaMen_u ( idMenu, Etiqueta, Descripci_on, TipoDeElemento, Referencia, Categor_ia, Icono, Orientaci_on, Prioridad) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s )", 
		GetSQLValueString(NULL, "int"),
		GetSQLValueString($_POST['Etiqueta'], "varchar") ,
		GetSQLValueString($_POST['Descripci_on'], "varchar") ,
		GetSQLValueString($_POST['TipoDeElemento'], "int") ,
		GetSQLValueString($_POST['Referencia'], "varchar") ,
		GetSQLValueString($_POST['Categor_ia']=='NULL'?NULL:$_POST['Categor_ia'], "int") ,
		GetSQLValueString($_POST['Icono'], "int") ,
		GetSQLValueString($_POST['Orientaci_on'], "int"),
		GetSQLValueString($_POST['Prioridad'], "int")  );
		
	if($ResultadoInserta = $Conexion->query($ConsultaInserta)){
		$IdRegistroCelaMen_u = $Conexion->insert_id;
		
		if($_POST['Categor_ia']=='NULL'){
			$consultaActualizaMenu="UPDATE CelaMen_u SET Categor_ia=".$IdRegistroCelaMen_u." WHERE idMenu=".$IdRegistroCelaMen_u."";
			$ResultadoActualizaMenu=$Conexion->query($consultaActualizaMenu);
		}
		//Registramos el privilegio segun los roles.
		if($_POST['Roles'][0]=='all' && !isset($_POST['Roles'][1])){
			$consultaRoles="select idRol from CelaRol";
			$resultadoRoles=$Conexion->query($consultaRoles);
			while($registroRoles=$resultadoRoles->fetch_assoc()){
				$consultaInsertaPrivilegio="insert into CelaPrivilegios (idPrivilegios, Privilegio, Elemento, RolDeUsuario, Tabla) values (NULL,(select idPrivilegio from CelaPrivilegio where Nombre='Menu'),".$IdRegistroCelaMen_u.", ".$registroRoles['idRol'].", 1)";
				$ResultadoInsertaPrivilegio=$Conexion->query($consultaInsertaPrivilegio);
			}
		}else{
			for($i = $_POST['Roles'][0]=='all'?1:0; $i < count($_POST['Roles']); $i++){
				$consultaInsertaPrivilegio="insert into CelaPrivilegios (idPrivilegios, Privilegio, Elemento, RolDeUsuario, Tabla) values (NULL,(select idPrivilegio from CelaPrivilegio where Nombre='Menu'),".$IdRegistroCelaMen_u.", ".$_POST['Roles'][$i].",1)";
				$ResultadoInsertaPrivilegio=$Conexion->query($consultaInsertaPrivilegio);
			}
		}
		
		$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
			GetSQLValueString( "NULL", "int"),
			GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
			GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
			GetSQLValueString( 'CelaMen_u', "varchar"),
			GetSQLValueString( $IdRegistroCelaMen_u, "int"),
			GetSQLValueString( 2, "int"));
		$ResultadoLog=$Conexion->query($ConsultaLog);
		$InsertGoTo = "CelaMen_uLeer.php?".EncodeThis("Status=SuccessC");
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
											<span style="font-size: 18pt;">Creaci&oacute;n de Men&uacute;</span>
										</strong>
									</div>
									<div class="box-icon col-xs-6 text-right">
										<a data-intro="Ayuda general" data-position="left" href="#" class="btn-help btn btn-default" title="Ayuda"><i class="fa fa-question"></i></a>
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
								<form class="form-horizontal form_validate" method="POST" name="CelaMen_u" id="CelaMen_u" action="<?php echo $FormAction; ?>" >
									<fieldset>
										<span class="clearfix"></span>
										<hr />
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Tipo de Elemento: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
														<select name="TipoDeElemento" id="TipoDeElemento" class="form-control e_requerido">
															<option value="1">Opci&oacute;n de men&uacute;</option>
															<option value="2">Men&uacute; o categor&iacute;a de opciones</option>
															<option value="3">Separador</option>
														</select>
													</div>
												</div>
											</div><!-- group-validate -->
										</div>										
										<div class="form-group" id="EtiquetaDiv">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Etiqueta: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
														<input class="form-control focused e_requerido e_longitud" name="Etiqueta" id="Etiqueta" type="text" data-rango='{"minimo":1,"maximo":64,"mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}' />
													</div>
												</div>
											</div><!-- group-validate -->
										</div>
										<div class="form-group" id="Descripci_onDiv">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Descripci&oacute;n: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
														<input class="form-control focused e_longitud e_requerido" name="Descripci_on" id="Descripci_on" type="text" data-rango='{"minimo":1,"maximo":64,"mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}'/>
													</div>
												</div>
											</div><!-- group-validate -->
										</div>
										<div class="form-group" id="ReferenciaDiv">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Referencia o Ruta: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
														<input class="form-control focused e_requerido e_longitud" name="Referencia" id="Referencia" type="text" data-rango='{"minimo":1,"maximo":256,"mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}'/>
													</div>
												</div>
											</div><!-- group-validate -->
										</div>
										<div class="form-group" id="Categor_iaDiv">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Categor&iacute;a: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
													<?php
														$Opc['nombre']="Categor_ia";
														$Opc['clase']="form-control e_requerido";
														$Opc['EmptyMessage']="MISMA CATEGORIA";
														$Opc['EmptyValue']="NULL";
														$Consulta= " select idMenu, Etiqueta from CelaMen_u where TipoDeElemento=2 ";
														print RellenaCombo($Consulta,$Opc,1);
													?>
													</div>
												</div>
											</div><!-- group-validate -->
										</div>
										<div class="form-group" id="IconoDiv">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font>Icono: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
													    <select name="Icono" id="Icono" class="form-control show-tick focused" data-live-search="true">
                                                    <?php
                                                        $ConsultaIcono="SELECT idIcono, NombreDelIcono FROM CelaIcono ORDER BY NombreDelIcono ASC";
                                                        $ResultadoIconos=$Conexion->query($ConsultaIcono);
                                                        while($RegistroIconos=$ResultadoIconos->fetch_assoc()){
                                                    ?>
                                                            <option value="<?php print $RegistroIconos['idIcono']; ?>" data-icon="<?php print $RegistroIconos['NombreDelIcono']; ?>">&nbsp;&nbsp; <?php print $RegistroIconos['NombreDelIcono']; ?></option>
                                                    <?php
                                                        }
                                                        
                                                    ?>
                                                        </select> 
													 <?php
			                                        	/*$Opc['nombre']="Icono";
			                                        	$Opc['clase']="form-control e_requerido";
			                                        	print RellenaCombo("SELECT idIcono, NombreDelIcono FROM CelaIcono ORDER BY NombreDelIcono ASC",$Opc);*/
			                                        ?>
													</div>
												</div>
											</div><!-- group-validate -->
										</div>
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Orientaci&oacute;n: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
														<select name="Orientaci_on" id="Orientaci_on" class="form-control e_requerido">
															<option value="1">Vertical</option>
															<option value="2">Horizontal</option>
														</select>
													</div>
												</div>
											</div><!-- group-validate -->
										</div>
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Prioridad: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
														<input class="form-control focused e_numero e_requerido e_rango" name="Prioridad" id="Prioridad" type="text" data-rango='{"minimo":1,"maximo":2147483647,"mensaje":"Introduce un valor entre 1 y 2147483647"}'/>
													</div>
												</div>
											</div><!-- group-validate -->
										</div>
										<div class="form-group" id="RolesDiv">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Roles que ven este men&uacute;: </label>
												<div class="col-sm-10">
													<div class="col-xs-8 validate">
														<select id="Roles" class="form-control e_requerido" size="5" multiple="multiple" name="Roles[]">
															<option value="all">TODOS LOS ROLES</option>
													<?php
														$Consulta= " select idRol, NombreDelRol from CelaRol";
														$Resultado=$Conexion->query($Consulta);
														while($Registro=$Resultado->fetch_assoc()){
															print '<option value="'.$Registro['idRol'].'">'.$Registro['NombreDelRol'].'</option>';
														}
													?>
														</select>
													</div>
												</div>
											</div><!-- group-validate -->
										</div>
										<input type="hidden" name="CelaMen_uInsert" value="CelaMen_uInsert" />
										<span class="clearfix"></span>
										<hr />
										<div class="form-group">
											<div class="col-md-offset-3 col-md-9">
												<button id="Guardar" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Guardar
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
		<script type="text/javascript" src="bootstrap/js/bootstrap-select.js"></script>
		<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-select.css">
		<script>
			$("#TipoDeElemento").change(function(){
				if($(this).val()==2){
					//Ocultamos Referencia y Categoría
					$("#ReferenciaDiv").slideUp('slow');
					$("#Referencia").val('#');
					$("#Categor_iaDiv").slideUp('slow');
					$("#Categor_ia option[value=NULL]").attr('selected','selected');					
					$("#EtiquetaDiv").slideDown('slow');
					$("#Descripci_onDiv").slideDown('slow');
					$("#IconoDiv").slideDown('slow');
					$("#RolesDiv").slideDown('slow');
				}else{
					if($(this).val()==3){
						$("#EtiquetaDiv").slideUp('slow');
						$("#Etiqueta").val('_');
						$("#Descripci_onDiv").slideUp('slow');
						$("#Descripci_on").val('_');
						$("#ReferenciaDiv").slideUp('slow');
						$("#Referencia").val('#');
						$("#IconoDiv").slideUp('slow');
						$("#Icono option[value=1]").attr('selected','selected');
						$("#RolesDiv").slideUp('slow');
						$("#Roles option[value=all]").attr('selected','selected');
						$("#Categor_iaDiv").slideDown('slow');
					}else{
						$(".form-group").slideDown('slow');
					}
				}
			});
			$('#Icono').selectpicker({
                'selectedText': 'cat',
                'iconBase': 'fa ',
                'tickIcon': 'fa fa-check',
            });
		</script>
	</body>
</html>