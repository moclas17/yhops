<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
include('lib/phpqrcode/qrlib.php');
$Privilegios=array("Crear"=>1);

$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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
						<div class="col-sm-12 col-md-12">
						<div class="row panel panel-primary">
							<div class="panel-heading">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header col-xs-6 text-left">
										<strong>
											<span style="font-size: 18pt;">View QR</span>
										</strong>
									</div>
									
								</div>
							</div>
							</div>
							<div class="box-content panel-body">
								<table class="table table-striped table-bordered" >
									
									<tr >
										<th>Collection</th>										
										<th>Symbol</th>
										<th>Unique id</th>
										<th>QR</th>
										<th>Client</th>
									</tr>
								
								<?php 
									$Query="SELECT * , (SELECT Usuario FROM CelaUsuario WHERE CelaUsuario.idUsuario=d.idClient ) Client
									FROM QRDetails d 
									INNER JOIN QRHeader h ON (h.id=d.idQRHeader)
									WHERE h.id=".$_GET['clave'][0];
									#precode($Query,1);
									$exec = $Conexion->query($Query); 
									while($qr = $exec -> fetch_assoc()){
									
									?>
										<tr>
											<td><?php echo $qr["Name"]; ?> </td>
											<td><?php echo $qr["Symbol"]; ?> </td>
											<td><?php echo $qr["uniqueid"]; ?> </td>
											<td><img width="150px" src="qrgenerate.php?id=<?php echo $row['uniqueid'] ?>" /></td>
											<td><?php echo $qr["Client"]; ?> </td>
										</tr>
									<?php 	
										
									}
									
								?>
									</table>
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