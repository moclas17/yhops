<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$Tabla['table']="CelaFormulario";
$Tabla['columns']="''/*/ Nombre/*/ Descripci_on/*/ Ruta";
$Tabla['index']="idFormulario";
$Tabla['condition']="";
$Tabla['extra'][0]="actions";
$Tabla['Privileges']=$Privilegios;

//$Tabla['debug']=1;
$Datos=Encrypt(json_encode($Tabla),$_SESSION['CELA_Aleatorio']);
$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
	GetSQLValueString( "NULL", "int"),
	GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
	GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
	GetSQLValueString( 'CelaFormulario', "varchar"),
	GetSQLValueString( "NULL", "int"),
	GetSQLValueString( 4, "int"));
$ResultadoLog=$Conexion->query($ConsultaLog);
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
											<span style="font-size: 18pt;">Listado de Formularios</span>
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
						if(isset($Privilegios['Leer']) && $Privilegios['Leer']==1){
					?>
							<div class="box-content panel-body">
						<?php
							if(isset($_GET['Status']) && $_GET['Status']!="ErrorE"){
						?>	
								<div class="alert alert-success fade in alert-msn" role="alert">
									<button class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span>
										<span class="sr-only">Close</span>
									</button>
									
									<?php print ($_GET['Status']=="SuccessC"?'<strong><i class="fa fa-check fa-lg"></i>&nbsp; Registro exitoso!</strong>&nbsp; El nuevo elemento se registr&oacute; correctamente.':'').($_GET['Status']=="SuccessA"?'<strong><i class="fa fa-check fa-lg"></i>&nbsp; Actualizaci&oacute;n exitosa!</strong>&nbsp; El elemento se actualiz&oacute; correctamente.':'').($_GET['Status']=="SuccessE"?'<strong><i class="fa fa-check fa-lg"></i>&nbsp; Eliminaci&oacute;n correcta!</strong>&nbsp; El elemento se eliminado correctamente.':'');  ?>
								</div>
						<?php
							}
						?>
						<?php
							if(isset($_GET['Status']) && $_GET['Status']=="ErrorE"){
						?>	
								<div class="alert alert-danger fade in alert-msn" role="alert">
									<button class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span>
										<span class="sr-only">Close</span>
									</button>
									<strong><i class="fa fa-times fa-lg"></i>&nbsp; Oops!... Ocurrio un error al eliminar el elemento</strong><?php print $_GET['Error']; ?>
								</div>
						<?php
							}
						?>
								<div class="col-md-12">
								    <div class="col-md-2 text-left">
								<?php
								    if (isset($Privilegios['Crear']) && $Privilegios['Crear']==1) {
								?>
								        <a data-intro="Insertar nuevo CelaFormulario" data-position="top" class="btn btn btn-success" title="Agregar" href="CelaFormularioCrear.php" >
											<i class="fa fa-plus"></i>&nbsp;
											<span>Agregar</span>
										</a>
    							<?php
    								}
    							?>
    							    </div>
									<div class="col-md-6 text-left form-inline">
								<?php
								    $Label=false;
									if(isset($Privilegios['Actualizar']) && $Privilegios['Actualizar']==1){
								?>
										<a data-intro="Modifica los datos seleccionados" data-position="bottom" class="btn btn btn-warning" title="Editar seleccionados" href="#" disabled="disabled" id="Actualizar">
											<i class="fa fa-pencil"></i>&nbsp;
											<span>Editar</span>
										</a>
								<?php
								        $Label=true;
									}
									if(isset($Privilegios['Eliminar']) && $Privilegios['Eliminar']==1){
								?>
										<a data-intro="Elimina datos todos los seleccionados" data-position="right" title="Eliminar  seleccionados" href="#" class="btn btn btn-danger delete" disabled="disabled" id="Eliminar">
											<i class="fa fa-trash-o"></i>&nbsp;
											<span>Eliminar</span>
										</a>
								<?php
								        $Label=true;
									}
                                    if($Label==true){
								?>
										<label>&larr; Para los datos seleccionados</label>
								<?php
                                    }
								?>
									</div>
									<div class="col-md-4 text-right">
										<div class="form-group" data-intro="Busqueda general" data-position="bottom" >
											<label class="sr-only" for="Search-CelaFormulario">Busqueda</label>
											<div>
												<input id="Search-CelaFormulario" class="form-control DataTableFilter" type="text" placeholder="Buscar..." autocomplete="off">
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive" align="left">
									<table id="CelaFormulario" class="table table-striped table-bordered hover datatable" data-records="<?php print $Datos; ?>" data-form="<?php print substr(strrchr($_SERVER['PHP_SELF'],"/"),1); ?>" >
										<thead>
											<tr>
												<th width="1%" title="Seleccionar todo"><div align="center"><label><input  type="checkbox" id="All" data-intro="Seleeciona todos los registros de esta p&aacute;gina" data-position="bottom"/></label></div></th>
												<th class="sortable" width="35%"><div align="center"> Nombre</div></th>
												<th class="sortable" width="45%"><div align="center"> Descripci&oacute;n</div></th>
												<th class="sortable" width="19%"><div align="center"> Ruta </div></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
						<?php
							if(isset($Privilegios['Eliminar']) && $Privilegios['Eliminar']==1){
						?>
								<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											</div>
											<div class="modal-body" id="Body">
											</div>
											<div class="modal-footer">
												<a class="btn btn-default" id="Cancelar" data-dismiss="modal">Cancelar</a>
												<a class="btn btn-primary" id="Aceptar" href=""><i class="fa fa-trash-o"></i>&nbsp;Eliminar</a>
											</div>
										</div>
									</div>
								</div>
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
		<script>
			$(document).delegate(".delete","click",function(e){
				e.preventDefault();
				var mensaje='';

				if($(this).attr("id") != "Eliminar"){
					mensaje = "¿Realmente desea eliminar el elemento seleccionado?";
					$("#Aceptar").attr('href',$(this).attr('href'));
				}else{
					mensaje='¿Realmente desea eliminar los elementos seleccionados?';
				}

				$("#Body").html(mensaje);
				$("#DeleteModal").modal('show');
			});

			$(document).delegate(".Select","change",function(){
				GetAllSelect();
			});

			$("#All").change(function(){
				$(".Select").each(function(){
					$(this).prop('checked', $('#All').is(':checked'));
				});
				GetAllSelect();
			});

			function GetAllSelect(){
				var Get='', cont=0;
				$(".Select").each(function(){
					if($(this).is(":checked")){
						var id=$(this).attr("id");
						id=id.split("_");
						Get+='clave[]='+id[1]+'&';
						cont++;
					}
				});

				Get=Get.substring(0, Get.length-1);
				if(Get!=''){
					$.post("ajaxs_functions.php",{
						funcion: 5,
						Post: Get
					},
					function(data){
						if(cont<2){
							var href="#";
							$("#Actualizar").attr('disabled','disabled');
							$("#Eliminar").attr('disabled','disabled');
							$("#Aceptar").attr('href',href);
						}else{
							$("#Actualizar").removeAttr('disabled');
							$("#Eliminar").removeAttr('disabled');

							$("#Actualizar").attr('href','CelaFormularioActualizar.php?'+data);
							$("#Aceptar").attr('href','CelaFormularioEliminar.php?.php?'+data);
						}
					});
				}else{
					var href="#";
					$("#Actualizar").attr('disabled','disabled');
					$("#Eliminar").attr('disabled','disabled');
					$("#Aceptar").attr('href',href);
				}
			}
			$(document).ready(function(){
				$(".alert-msn").fadeOut(6000);
			});
		</script>
	</body>
</html>