<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$Tabla['table']="CelaAccesos";
$Tabla['columns']="FechaDeAcceso/*/(select NombreCompleto from CelaUsuario where CelaUsuario.idUsuario=CelaAccesos.idUsuario) /as/ idUsuario/*/ Tabla/*/ IdTabla/*/ ( select Acci_on from  CelaAcci_on where CelaAcci_on.idAcci_on = CelaAccesos.Acci_on) /as/ Acci_on ";
$Tabla['index']="idAcceso";
$Tabla['condition']="";
$Tabla['Privileges']=$Privilegios;

$Datos=Encrypt(json_encode($Tabla),$_SESSION['CELA_Aleatorio']);
$ConsultaLog = sprintf("INSERT INTO CelaAccesos ( ClaveDeAccesos, FechaDeAcceso, ClaveDeUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)",
	GetSQLValueString( "NULL", "int"),
	GetSQLValueString( date('Y-m-d H:i:s'), "varchar"),
	GetSQLValueString( $_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
	GetSQLValueString( 'CelaAccesos', "varchar"),
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
											<span style="font-size: 18pt;">Log del Sistema.</span>
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
								<div class="col-md-12">
									<div class="col-md-8 text-left"></div>
									<div class="col-md-4 text-right">
										<div class="form-group" data-intro="Busqueda general" data-position="bottom" >
											<label class="sr-only" for="Search-CelaAccesos">Busqueda</label>
											<div>
												<input id="Search-CelaAccesos" class="form-control DataTableFilter" type="text" placeholder="Buscar..." autocomplete="off">
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive" align="left">
									<table id="CelaAccesos" class="table table-striped table-bordered hover datatable" data-records="<?php print $Datos; ?>" data-form="<?php print substr(strrchr($_SERVER['PHP_SELF'],"/"),1); ?>" >
										<thead>
											<tr>
												<th class="sortable" width="20%"><div align="center"> Fecha/Hora</div></th>
												<th class="sortable" width="40%"><div align="center"> Usuario</div></th>
												<th class="sortable" width="20%"><div align="center"> Tabla</div></th>
												<th class="sortable" width="10%"><div align="center"> Registro</div></th>
												<th class="sortable"  width="10%"><div align="center"> Acci&oacute;n</div></th>

											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
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