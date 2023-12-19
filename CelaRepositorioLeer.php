<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');

if(!isset($_GET['cvetabla']) && !isset($_GET['tabla'])){
	header("Location: Escritorio.php");
}
$Tabla['table']="CelaRepositorio";
if($_GET['tabla']=="CelaRepositorio"){
	$Tabla['columns']="''/*/Descripci_on/*/ (select NombreCompleto from CelaUsuario where CelaUsuario.idUsuario = CelaRepositorio.idUsuario) /as/ idUsuario/*/ FechaDeCreaci_on /*/ idTabla /*/ Tabla";
	$Tabla['order']=" FechaDeCreaci_on desc  ";
	//$Tabla['debug']=1;
}else{
	$Tabla['columns']="''/*/Descripci_on/*/ (select NombreCompleto from CelaUsuario where CelaUsuario.idUsuario = CelaRepositorio.idUsuario) /as/ idUsuario/*/ FechaDeCreaci_on/*/ ''/*/ idTabla /*/ Tabla";
	$Tabla['extra'][4]="version";
	//$Tabla['debug']=1;	
}
$Tabla['index']="idRepositorio";
$Tabla['condition']=" Tabla='".$_GET['tabla']."' AND idTabla='".$_GET['cvetabla']."' AND Estado != 0";
$Tabla['extra'][0]="actions_file";
$Tabla['Privileges']=$Privilegios;

//$Tabla['debug']=1;
$Datos=Encrypt(json_encode($Tabla),$_SESSION['CELA_Aleatorio']);
$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( idAccesos, FechaDeAcceso, idUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
	GetSQLValueString( "NULL", "int"),
	GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
	GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
	GetSQLValueString( 'CelaRepositorio', "varchar"),
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
											<span style="font-size: 18pt;">Listado de Repositorios</span>
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
							if(isset($_GET['Status']) && $_GET['Status']=="ErrorE"){
						?>	
								<div class="alert alert-danger fade in alert-msn" role="alert">
									<button class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span>
										<span class="sr-only">Close</span>
									</button>
									<strong><i class="fa fa-times fa-lg"></i>&nbsp; Oops!... Ocurrio un error al eliminar el elemento</strong>&nbsp; <?php print $_GET['Error']; ?>
								</div>
						<?php
							}
							if(isset($_GET['Estatus']) && $_GET['Estatus']=="NotFound"){
						?>	
								<div class="alert alert-warning fade in alert-msn" role="alert">
									<button class="close" data-dismiss="alert" type="button">
										<span aria-hidden="true">&times;</span>
										<span class="sr-only">Close</span>
									</button>
									<strong><i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp; El archivo seleccionado no fue encontrado.</strong>
								</div>
						<?php
							}
						?>
								<div class="col-md-12">
								    <div class="col-md-2 text-left">
    							<?php
    								if (isset($Privilegios['Crear']) && $Privilegios['Crear']==1 && $_GET['tabla']!="CelaRepositorio") {
    							?>
										<a data-intro="Insertar nuevo CelaRepositorio" data-position="top" class="btn btn btn-success" title="Agregar" href="CelaRepositorioCrear.php?<?php print EncodeThis("tabla=".$_GET['tabla']."&cvetabla=".$_GET['cvetabla']); ?>" >
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
											<label class="sr-only" for="Search-CelaRepositorio">Busqueda</label>
											<div>
												<input id="Search-CelaRepositorio" class="form-control DataTableFilter" type="text" placeholder="Buscar..." autocomplete="off">
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive" align="left">
									<table id="CelaRepositorio" class="table table-striped table-bordered hover datatable" data-records="<?php print $Datos; ?>" data-form="<?php print substr(strrchr($_SERVER['PHP_SELF'],"/"),1); ?>" >
										<thead>
											<tr>
												<th width="1%" title="Seleccionar todo"><div align="center"><label><input  type="checkbox" id="All" data-intro="Seleeciona todos los registros de esta p&aacute;gina" data-position="bottom"/></label></div></th>
												<th class="sortable" width="50%"><div align="center"> Descripci&oacute;n</div></th>
												<th class="sortable" width="30%"><div align="center"> Usuario</div></th>
												<th class="sortable" width="20%"><div align="center"> Fecha de Creaci&oacute;n</div></th>
										<?php
											if($_GET['tabla']!="CelaRepositorio"){
										?>
												<th class="sortable" width="20%"><div align="center"> Versiones Anteriores</div></th>
										<?php
											}
										?>

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
							if(isset($Privilegios['VistaPrevia']) && $Privilegios['VistaPrevia']==1){
						?>
								<div class="modal fade bs-example-modal-sm" id="PreViewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
									<div class="modal-dialog modal-sm" id="PreViewModal1">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close closeFile" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h3 class="modal-title" id="myModalLabel">
													Vista Previa de Archivos.
												</h3>
											</div>
											<div class="modal-body">
												<iframe align="middle" style="width:100%; height:400px;" frameborder="0" id="previewframe"></iframe>
												<input type="hidden" id="src" name="src"/>
											</div>
											<div class="modal-footer">
												<button class="btn btn-primary closeFile">Cerrar</button>
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
				Get=Get+"&tabla=<?php print $_GET['tabla']; ?>&idtabla=<?php print $_GET['cvetabla']; ?>";
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
							$("#Descargar").attr('disabled','disabled');
							$("#Aceptar").attr('href',href);
						}else{
							$("#Actualizar").removeAttr('disabled');
							$("#Eliminar").removeAttr('disabled');
							$("#Descargar").removeAttr('disabled');
							
							$("#Actualizar").attr('href','CelaRepositorioActualizar.php?'+data);
							$("#Aceptar").attr('href','CelaRepositorioEliminar.php?.php?'+data);
							$("#Descargar").attr('href',Get);
						}
					});
				}else{
					var href="#";
					$("#Actualizar").attr('disabled','disabled');
					$("#Eliminar").attr('disabled','disabled');
					$("#Descargar").attr('disabled','disabled');
					$("#Aceptar").attr('href',href);
				}
			}
			
			$(document).delegate(".showfile","click", function(){
				var id=$(this).attr("id");
				$(".box-content").prepend('<div class="alert alert-info text-center alert-file" role="alert">'+
											'<strong>'+
												'<i class="fa fa-file-text-o fa-lg"></i>&nbsp; Obteniendo archivo, por favor espere un momento...! &nbsp<img src="bootstrap/img/loading.gif" />'+
											'</strong>'+
										'</div>');
				$.post("ajaxs_functions.php",{
					funcion: 1,
					IdFile: id
				},function(response) {
					if(response.status == "OK"){
						$(".alert-file").remove();
						$("#previewframe").attr("src",response.src);
						$("#src").val(response.file_source);
						$("#caseError").html("");
						$("#PreViewModal1").css({
							'width': function () { 
								return ($(document).width() * .9) + 'px';  
							}
						});
						$("#PreViewModal").modal("show");
					}else{
						$('.alert-file').removeClass('alert-info').addClass('alert-danger').html('<button class="close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button><strong><i class="fa fa-times fa-lg"></i>&nbsp; '+response.error+'</strong>');
						$(".alert-file").fadeOut(6000,function(){
							$(".alert-file").remove();
						});
					}
				},"json");
			});

			$(document).delegate(".closeFile","click", function(){	
				var file=$("#src").val();
				$.post("ajaxs_functions.php", {
					funcion: 2,
					SRCFile: file
				}, function(data) {
					if(data=="OK"){
						$("#src").val("");
						$("#previewframe").attr("src","");
						$("#PreViewModal").modal("hide");
					}
				});
			});
				
			$("#PreViewModal").on("hidden.bs.modal",function(){
				var file=$("#src").val();
				$.post("ajaxs_functions.php", {
					funcion: 2,
					SRCFile: file
				}, function(data) {
					if(data=="OK"){
						$("#src").val("");
						$("#previewframe").attr("src","");
						$("#PreViewModal").modal("hide");
					}
				});
			});
			
			$(document).ready(function(){
				$(".alert-msn").fadeOut(6000);
			});	
		</script>
	</body>
</html>