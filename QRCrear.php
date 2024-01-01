<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');


$FormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["QRInsert"])) && ($_POST["QRInsert"] == "QRInsert")) {
	#precode($_POST,1,1);
	//create collection
	$collection = create_colecction($privatekey,$apikey_tatum, $chain, $_POST['Name'], $_POST['Symbol']);
	//get contract from tx
	$contratotx = getdatafromtx( $apikey_tatum, $chain, $collection->txId);
	$jsoncontract = json_decode($contratotx);
	$contrato = $jsoncontract->contractAddress;
	//add wallet to contract for able to mint 
	$addminter = add_minter_to_contract($privatekey, $apikey_tatum, $chain, $contrato, $publickey);
	$jsonminter = json_decode($addminter);
	$ConsultaInserta = sprintf("INSERT INTO QRHeader (  id, adminOwner, Name, Supply, Contract, Minter, Symbol, ContractTX ) VALUES (  %s, %s, %s, %s, %s, %s, %s, %s)", 
		GetSQLValueString(NULL, "int"),
		GetSQLValueString($_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']], "int"),
		GetSQLValueString($_POST['Name'], "varchar"),
		GetSQLValueString($_POST['Quantity'], "varchar") ,
		GetSQLValueString($contrato, "varchar")  ,
		GetSQLValueString($jsonminter->txId, "varchar"),
		GetSQLValueString($_POST['Symbol'], "varchar") ,
		GetSQLValueString($collection, "varchar"));
	if($ResultadoInserta = $Conexion->query($ConsultaInserta)){
		$IdRegistroQR = $Conexion->insert_id;
		for($i=1;$i<=$_POST['Quantity']; $i++){
			$bytes = random_bytes(8);
			$ConsultaInsertaDetail = sprintf("INSERT INTO QRDetails (  id, idQRHeader, idCount, uniqueid ) VALUES (  %s, %s, %s, %s)", 
			GetSQLValueString(NULL, "int"),
			GetSQLValueString($IdRegistroQR, "int"),
			GetSQLValueString($i, "int"),
			GetSQLValueString(bin2hex($bytes), "varchar") );
			$ResultadoLog=$Conexion->query($ConsultaInsertaDetail);
		}
	}
	
	$rutaimg=upload_image($_FILES['Image'],  "img_".$IdRegistroQR);
	#precode($rutaimg,1,1);
     $rutaipfs="https://ipfs.io/ipfs/".$rutaimg;
	$attrs=array();
	for($i=0; $i<count($_POST['Type']) ; $i++){
		$attr=array();
		$attr['trait_type'] = $_POST['Type'][$i];
		$attr['value'] = $_POST['Value'][$i];
		$attrs[]= ($attr);
	}
	$rutanft=ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='UrlSitio'","Valor")."public/";
	
	$array= array();
	$array['name']= $_POST['Name'];
	$array['description']= $_POST['Description'];	
	$array['image']= $rutaipfs;
	$array['external_url'] = $rutanft;
	
	$array['attributes']=$attrs;
	$myjson = json_encode($array);
	#precode($myjson,1);
	$pinatafile = upload_json(str_replace(' ', '', $_POST['Name']), $array);
	$txpinatafile=json_decode($pinatafile);
	$rutaipfsfile="https://ipfs.io/ipfs/".$txpinatafile->IpfsHash;
	 $ConsultaActualiza = sprintf("UPDATE QRHeader SET Image= %s, Metadata= %s, Data= %s WHERE id= %s", 
	GetSQLValueString($rutaipfs, "varchar") ,
	GetSQLValueString($myjson, "varchar"),
	GetSQLValueString($rutaipfsfile, "varchar"),
	GetSQLValueString($IdRegistroQR, "int"));
	$Conexion->query($ConsultaActualiza);
	$UpdateGoTo = "QRLeer.php";
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
											<span style="font-size: 18pt;">Add QR Collection</span>
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
										
									<h3>NFT Details</h3>		
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Name: </label>
												<div class="col-sm-10">
													<div class="col-xs-4 validate">
														<input class="form-control focused e_requerido" name="Name" id="Name" type="text" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div><!-- End form-group -->
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Symbol: </label>
												<div class="col-sm-10">
													<div class="col-xs-4 validate">
														<input class="form-control focused e_requerido" name="Symbol" id="Symbol" type="text" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div><!-- End form-group -->
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Description: </label>
												<div class="col-sm-10">
													<div class="col-xs-12 validate">
														<input class="form-control focused " name="Description" id="Description" type="text" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div>
										
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Image: </label>
												<div class="col-sm-10">
													<div class="col-xs-4 validate">
														<input class="form-control focused  " name="Image" id="Image" type="file" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div>
										
										
										
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Quantity: </label>
												<div class="col-sm-10">
													<div class="col-xs-2 validate">
														<input class="form-control focused e_requerido " name="Quantity" id="Quantity" type="text" data-igual_a="pin" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->
										</div>
										<h3>Metadata</h3>	
										<?php for($i=0; $i<10; $i++){ ?>
										<div class="form-group">
											<div class="group-validate">
												<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font> Trait Type: </label>
												<div class="col-sm-4">
													<div class="col-xs-12 validate">
														<input class="form-control focused e_requerido " name="Type[]" id="Type" type="text" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
												<label class="col-sm-1 control-label" for="focusedInput"><font color="red">*</font> Value: </label>
												<div class="col-sm-4">
													<div class="col-xs-12 validate">
														<input class="form-control focused e_requerido " name="Value[]" id="Value" type="text" />
													</div><!--End col-xs-8 validate-->
												</div> <!-- End col-sm-10-->
											</div><!-- End form-group -->									
										</div>
										<?php } ?>
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