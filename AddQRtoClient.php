<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
$Privilegios=array("Crear"=>1);

$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["QRInsert"])) && ($_POST["QRInsert"] == "QRInsert")) {
	$consSelect="SELECT * FROM QRDetails WHERE idQRHeader=".$_POST['QRHeader']." AND idClient IS NULL LIMIT ".$_POST['Quantity'];
	$exec=$Conexion->query($consSelect);
	while($row = $exec->fetch_assoc()){
		$ConsultaActualiza = sprintf("UPDATE QRDetails SET idClient= %s, isAsigned= %s WHERE id= %s", 
		GetSQLValueString($_POST['idClient'], "varchar") ,
		GetSQLValueString(1, "int") ,
		GetSQLValueString($row['id'], "int"));
		$Conexion->query($ConsultaActualiza);
	}
	
	$UpdateGoTo = "QRPrint.php?".EncodeThis("idClient=".$_POST['idClient']."&QRHeader=".$_POST['QRHeader']);
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
				<div id="main-container" class="<?php print $registrosMenu>0?'col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10':'col-sm-12 col-md-12'; ?> main">
					
					<div class="row">
						<div class="col-sm-12 col-md-12">
						<div class="row panel panel-primary">
							<div class="panel-heading">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header col-xs-6 text-left">
										<strong>
											<span style="font-size: 18pt;">Add QR to Client</span>
										</strong>
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
								<form class="form-horizontal form_validate" method="POST" name="QR" id="QR" action="<?php echo $FormAction; ?>" enctype="multipart/form-data" >
									<fieldset>
										<span class="clearfix"></span>
										
									
										
										<div class="form-group form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Collection: </label>
												<div class="col-sm-10">
													<div class="col-xs-12 validate">
													<?php
														$Opc['nombre']="QRHeader";
														$Opc['clase']="form-control e_requerido";
														$Opc['EmptyMessage']="Select one option";
														$Opc['EmptyValue']="NULL";
														$Consulta= "SELECT id, CONCAT(Name, ' (', (SELECT count(id) FROM QRDetails d WHERE  d.idClient IS NULL AND d.idQRHeader=h.id ), ')') Disponible FROM QRHeader h ";
														print RellenaCombo($Consulta,$Opc,1);
													?>
													</div>
												</div>
											</div><!-- group-validate -->
										</div>
										
										
										
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Quantity: </label>
												<div class="col-sm-10">
													<div class="col-xs-2 validate">
														<input class="form-control focused e_requerido " name="Quantity" id="Quantity" type="text" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div>
										<input type="hidden" name="idClient" value="<?php  echo $_GET['clave'][0]; ?>" />
										<input type="hidden" name="QRInsert" value="QRInsert" />
										<span class="clearfix"></span>
										<hr />										
										<div class="form-group">
											<div class="col-md-offset-3 col-md-9">
												<button id="Guardar" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
													<i class="fa fa-save"></i>&nbsp; Save
												</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<button type="reset" class="btn btn-default" onclick = "location.href='QRLeer.php'" >
													<i class="fa fa-undo"></i>&nbsp; Cancel
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