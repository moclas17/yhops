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
	}else{		header(sprintf("Location: %s", $UpdateGoTo));	}	$Status=false;	foreach($Clave as $Valor){		$ConsultaActualiza = sprintf("UPDATE CelaMen_u SET Etiqueta= %s, Descripci_on= %s, TipoDeElemento= %s, Referencia= %s, Categor_ia= %s, Icono= %s, Orientaci_on= %s, Prioridad= %s WHERE idMenu= %s", 			GetSQLValueString($_POST['Etiqueta'.$Valor], "varchar") ,			GetSQLValueString($_POST['Descripci_on'.$Valor], "varchar") ,			GetSQLValueString($_POST['TipoDeElemento'.$Valor], "int") ,			GetSQLValueString($_POST['Referencia'.$Valor], "varchar") ,			GetSQLValueString($_POST['Categor_ia'.$Valor]=='NULL'?$Valor:$_POST['Categor_ia'.$Valor], "int") ,			GetSQLValueString($_POST['Icono'.$Valor], "int") ,			GetSQLValueString($_POST['Orientaci_on'.$Valor], "int"),			GetSQLValueString($_POST['Prioridad'.$Valor], "int") , GetSQLValueString($Valor, "int"));
				if($ResultadoActualiza = $Conexion->query($ConsultaActualiza)){			//Eliminamos los privilegios anteriores.			$ConsultaEliminaPrivilegos="DELETE FROM CelaPrivilegios where Elemento=".$Valor." and Tabla=1";			$ResultadoEliminaPrivilegios=$Conexion->query($ConsultaEliminaPrivilegos);			//Registramos el privilegio segun los roles.			if($_POST['Roles'.$Valor][0]=='all' && !isset($_POST['Roles'.$Valor][1])){				$consultaRoles="select idRol from CelaRol";				$resultadoRoles=$Conexion->query($consultaRoles);				while($registroRoles=$resultadoRoles->fetch_assoc()){					$consultaInsertaPrivilegio="insert into CelaPrivilegios (idPrivilegios, Privilegio, Elemento, RolDeUsuario, Tabla) values (NULL,(select idPrivilegio from CelaPrivilegio where Nombre='Menu'),".$Valor.", ".$registroRoles['idRol'].", 1)";					$ResultadoInsertaPrivilegio=$Conexion->query($consultaInsertaPrivilegio);				}			}
			else{				for($i = $_POST['Roles'.$Valor][0]=='all'?1:0; $i < count($_POST['Roles'.$Valor]); $i++){					$consultaInsertaPrivilegio="insert into CelaPrivilegios (idPrivilegios, Privilegio, Elemento, RolDeUsuario, Tabla) values (NULL,(select idPrivilegio from CelaPrivilegio where Nombre='Menu'),".$Valor.", ".$_POST['Roles'.$Valor][$i].",1)";					$ResultadoInsertaPrivilegio=$Conexion->query($consultaInsertaPrivilegio);				}			}
			
			$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",				GetSQLValueString( "NULL", "int"),				GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),				GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),				GetSQLValueString( 'CelaMen_u', "varchar"),				GetSQLValueString( $Valor, "int"),				GetSQLValueString( 5, "int"));			$ResultadoLog=$Conexion->query($ConsultaLog);			$Status=true;			}
		else{			$Status=false;		}	}	if($Status==true){		header(sprintf("Location: %s", $UpdateGoTo."?".EncodeThis("Status=SuccessA")));		}
	else{		$Status="Error";		$Error=$Conexion->error;	}}if ( !isset( $_GET['clave'])  ){	$UpdateGoTo = "CelaMen_uLeer.php";	header(sprintf("Location: %s", $UpdateGoTo));}
?>
<!DOCTYPE html><html lang="es">	<?php include 'CELAHead.php'; ?>	<body>		<?php include 'CELAMenuHorizontal.php'; ?>		<div class="container-fluid">			<div class="row">			<?php include 'CELAMenuVertical.php'; ?>				<div id="main-container" class="<?php print $registrosMenu>0?'col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10':'col-sm-12 col-md-12'; ?> main">					<div class="row">						<?php include 'CELARuta.php'; ?>					</div>					<div class="row">
						<div class="col-sm-12 col-md-12">						<div class="row panel panel-primary">							<div class="panel-heading">
							<div class="row">								<div class="col-md-12">									<div class="box-header col-xs-6 text-left">										<strong>											<span style="font-size: 18pt;">Escritorio</span>										</strong>									</div>									<div class="box-icon col-xs-6 text-right">										<a data-intro="Ayuda general" data-position="left" href="#" class="btn-help btn btn-default" title="Ayuda">
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
								<?php									$con=0;									$ClaveCelaMen_u='';									foreach($_GET['clave'] as $Valor){										$ConsultaCelaMen_u = "SELECT * FROM CelaMen_u WHERE idMenu =  ".$Valor."";										$ResultadoCelaMen_u = $Conexion->query($ConsultaCelaMen_u);										$RegistroCelaMen_u = $ResultadoCelaMen_u->fetch_assoc();										if($con==0){											$ClaveCelaMen_u=$RegistroCelaMen_u['idMenu'];										}
										else{											$ClaveCelaMen_u.=",".$RegistroCelaMen_u['idMenu'];										}								?>										<div class="thumbnail" style="background-color: <?php print $con%2==0?'#F9F9F9':'#FFFFF'; ?>">											<fieldset>												<legend>Registro <?php print $RegistroCelaMen_u['idMenu']; ?></legend>												<div class="form-group form-group<?php print $RegistroCelaMen_u['idMenu']; ?>">													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Tipo de Elemento: </label>														<div class="col-sm-10">															<div class="col-xs-8 validate">																<select name="TipoDeElemento<?php print $RegistroCelaMen_u['idMenu'];?>" id="TipoDeElemento<?php print $RegistroCelaMen_u['idMenu'];?>" class="form-control e_requerido">																	<option value="1" <?php print $RegistroCelaMen_u['TipoDeElemento']==1?'selected="selected"':'';?>>Opci&oacute;n de men&uacute;</option>																	<option value="2" <?php print $RegistroCelaMen_u['TipoDeElemento']==2?'selected="selected"':'';?>>Men&uacute; o categor&iacute;a de opciones</option>																	<option value="3" <?php print $RegistroCelaMen_u['TipoDeElemento']==3?'selected="selected"':'';?>>Separador</option>																</select>															</div>														</div>
													</div><!-- group-validate -->												</div>																						<div class="form-group form-group<?php print $RegistroCelaMen_u['idMenu']; ?>" id="EtiquetaDiv<?php print $RegistroCelaMen_u['idMenu']; ?>" <?php print $RegistroCelaMen_u['TipoDeElemento']==3?'hidden="hidden"':''; ?>>													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Etiqueta: </label>														<div class="col-sm-10">															<div class="col-xs-8 validate">																<input class="form-control focused e_requerido e_longitud" name="Etiqueta<?php print $RegistroCelaMen_u['idMenu'];?>" id="Etiqueta<?php print $RegistroCelaMen_u['idMenu'];?>" type="text" data-rango='{"minimo":1,"maximo":64,"mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}' value="<?php print $RegistroCelaMen_u['Etiqueta']; ?>" />															</div>														</div>
													</div><!-- group-validate -->												</div>												<div class="form-group form-group<?php print $RegistroCelaMen_u['idMenu']; ?>" id="Descripci_onDiv<?php print $RegistroCelaMen_u['idMenu']; ?>" <?php print $RegistroCelaMen_u['TipoDeElemento']==3?'hidden="hidden"':''; ?>>													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Descripci&oacute;n: </label>														<div class="col-sm-10">															<div class="col-xs-8 validate">																<input class="form-control focused e_longitud e_requerido" name="Descripci_on<?php print $RegistroCelaMen_u['idMenu'];?>" id="Descripci_on<?php print $RegistroCelaMen_u['idMenu'];?>" type="text" data-rango='{"minimo":1,"maximo":64,"mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}' value="<?php print $RegistroCelaMen_u['Descripci_on'];?>"/>															</div>														</div>
													</div><!-- group-validate -->												</div>												<div class="form-group form-group<?php print $RegistroCelaMen_u['idMenu']; ?>" id="ReferenciaDiv<?php print $RegistroCelaMen_u['idMenu']; ?>" <?php print $RegistroCelaMen_u['TipoDeElemento']==2?'hidden="hidden"':''; ?>>													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Referencia o Ruta: </label>														<div class="col-sm-10">															<div class="col-xs-8 validate">																<input class="form-control focused e_requerido e_longitud" name="Referencia<?php print $RegistroCelaMen_u['idMenu'];?>" id="Referencia<?php print $RegistroCelaMen_u['idMenu'];?>" type="text" data-rango='{"minimo":1,"maximo":256,"mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}' value="<?php print $RegistroCelaMen_u['Referencia'];?>"/>															</div>														</div>
													</div><!-- group-validate -->												</div>												<div class="form-group form-group<?php print $RegistroCelaMen_u['idMenu']; ?>" id="Categor_iaDiv<?php print $RegistroCelaMen_u['idMenu']; ?>" <?php print $RegistroCelaMen_u['TipoDeElemento']==2?'hidden="hidden"':''; ?>>													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Categor&iacute;a: </label>														<div class="col-sm-10">															<div class="col-xs-8 validate">
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
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font>Icono: </label>														<div class="col-sm-10">															<div class="col-xs-8 validate">
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
                                                                </select>															 <?php					                                        	/*$Opc['nombre']="Icono".$RegistroCelaMen_u['idMenu'];					                                        	$Opc['clase']="form-control e_requerido";					                                        	print SRellenaCombo("SELECT idIcono, NombreDelIcono FROM CelaIcono ORDER BY NombreDelIcono ASC",$Opc,$RegistroCelaMen_u['Icono']);*/					                                        ?>															</div>														</div>
													</div><!-- group-validate -->												</div>												<div class="form-group form-group<?php print $RegistroCelaMen_u['idMenu']; ?>">													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Orientaci&oacute;n: </label>														<div class="col-sm-10">															<div class="col-xs-8 validate">																<select name="Orientaci_on<?php print $RegistroCelaMen_u['idMenu'];?>" id="Orientaci_on<?php print $RegistroCelaMen_u['idMenu'];?>" class="form-control e_requerido">																	<option value="1" <?php print $RegistroCelaMen_u['Orientaci_on']==1?'selected="selected"':'';?>>Vertical</option>																	<option value="2" <?php print $RegistroCelaMen_u['Orientaci_on']==2?'selected="selected"':'';?>>Horizontal</option>																</select>															</div>														</div>
													</div><!-- group-validate -->												</div>												<div class="form-group form-group<?php print $RegistroCelaMen_u['idMenu']; ?>">													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"> <font color="red">*</font> Prioridad: </label>														<div class="col-sm-10">															<div class="col-xs-8 validate">																<input class="form-control focused e_numero e_requerido e_rango" name="Prioridad<?php print $RegistroCelaMen_u['idMenu'];?>" id="Prioridad<?php print $RegistroCelaMen_u['idMenu'];?>" type="text" data-rango='{"minimo":1,"maximo":2147483647,"mensaje":"Introduce un valor entre 1 y 2147483647"}' value="<?php print $RegistroCelaMen_u['Prioridad'];?>"/>															</div>
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
			$("#TipoDeElemento<?php print $Valor; ?>").change(function(){				if($(this).val()==2){					//Ocultamos Referencia y Categor?					$("#ReferenciaDiv<?php print $Valor; ?>").slideUp('slow');					$("#Referencia<?php print $Valor; ?>").val('#');					$("#Categor_iaDiv<?php print $Valor; ?>").slideUp('slow');					$("#Categor_ia<?php print $Valor; ?> option[value=NULL]").attr('selected','selected');										$("#EtiquetaDiv<?php print $Valor; ?>").slideDown('slow');					$("#Descripci_onDiv<?php print $Valor; ?>").slideDown('slow');					$("#IconoDiv<?php print $Valor; ?>").slideDown('slow');					$("#RolesDiv<?php print $Valor; ?>").slideDown('slow');				}
				else{					if($(this).val()==3){						$("#EtiquetaDiv<?php print $Valor; ?>").slideUp('slow');						$("#Etiqueta<?php print $Valor; ?>").val('_');						$("#Descripci_onDiv<?php print $Valor; ?>").slideUp('slow');						$("#Descripci_on<?php print $Valor; ?>").val('_');						$("#ReferenciaDiv<?php print $Valor; ?>").slideUp('slow');						$("#Referencia<?php print $Valor; ?>").val('#');						$("#IconoDiv<?php print $Valor; ?>").slideUp('slow');						$("#Icono<?php print $Valor; ?> option[value=1]").attr('selected','selected');						$("#RolesDiv<?php print $Valor; ?>").slideUp('slow');						$("#Roles<?php print $Valor; ?> option[value=all]").attr('selected','selected');
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