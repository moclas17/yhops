<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$Tabla['table']="CelaUsuario";
$Tabla['columns']="''/*/ NombreCompleto/*/ slogan /*/ logo /*/ PIN ";
$Tabla['index']="idUsuario";
$Tabla['condition']=" EstadoActual=1 AND Rol=4";
$Tabla['extra'][0]="actions_clients";
$Tabla['Privileges']=$Privilegios;
$Datos=Encrypt(json_encode($Tabla),$_SESSION['CELA_Aleatorio']);
$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAcceso, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
	GetSQLValueString( "NULL", "int"),
	GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
	GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
	GetSQLValueString( 'CelaUsuario', "varchar"),
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
						<div class="col-sm-12 col-md-12">
						<div class="row panel panel-primary">
							<div class="panel-heading">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header col-xs-6 text-left">
										<strong>
											<span style="font-size: 18pt;">User's list</span>
										</strong>
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
										<a data-intro="Insertar nuevo CelaUsuario" data-position="top" class="btn btn btn-success" title="Agregar" href="ClientsCrear.php" >
											<i class="fa fa-plus"></i>&nbsp;
											<span>Add</span>
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
											<label class="sr-only" for="Search-CelaUsuario">Search</label>
											<div>
												<input id="Search-CelaUsuario" class="form-control DataTableFilter" type="text" placeholder="Search..." autocomplete="off">
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive" align="left">
									<table id="CelaUsuario" class="table table-striped table-bordered hover datatable" data-records="<?php print $Datos; ?>" data-form="<?php print substr(strrchr($_SERVER['PHP_SELF'],"/"),1); ?>" >
										<thead>
											<tr>
												<th width="1%" title="Seleccionar todo"><div align="center"><label><input  type="checkbox" id="All" data-intro="Seleeciona todos los registros de esta p&aacute;gina" data-position="bottom"/></label></div></th>
												<th class="sortable" width="30%"><div align="center"> Username</div></th>
												<th class="sortable" width="25%"><div align="center"> Slogan</div></th>
												<th class="sortable" width="25%"><div align="center"> Logo</div></th>
												<th class="sortable" width="9%"><div align="center"> PIN</div></th>

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
							$("#Actualizar").attr('href','CelaUsuarioActualizar.php?'+data);
							$("#Aceptar").attr('href','CelaUsuarioEliminar.php?.php?'+data);
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