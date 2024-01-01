<?php
require_once('../lib/Conexion.php');
include ('../lib/Funciones.php');


if ((isset($_POST["MintInsert"])) && ($_POST["MintInsert"] == "MintInsert")) {
	
	$data = ObtenValor("SELECT * FROM QRDetails d 
	INNER JOIN QRHeader q ON (q.id=d.idQRHeader)
	WHERE d.uniqueid='".$_POST['id']."'");
	#precode($data,1,1);
	$curl = curl_init();
	
	$payload = array(
  		"chain" => $chain,
  		"to" => $_POST['wallet'],
  		"contractAddress" => $data['Contract'],
  		"tokenId" => $data["idCount"],
  		"url" => $data["Data"],
  		"fromPrivateKey" => $privatekey
	);
	#precode($payload,1);
	curl_setopt_array($curl, [
  	CURLOPT_HTTPHEADER => [
    	"Content-Type: application/json",
    	"x-api-key: ".$apikey_tatum
  	],
  	CURLOPT_POSTFIELDS => json_encode($payload),
  	CURLOPT_URL => "https://api.tatum.io/v3/nft/mint",
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_CUSTOMREQUEST => "POST",
	]);
	
	$response = curl_exec($curl);
	$error = curl_error($curl);
	
	curl_close($curl);
	
	if ($error) {
  	echo "cURL Error #:" . $error;
	} else {
		$ConsUpdate="UPDATE QRDetails SET isMinted=1 WHERE uniqueid='".$_POST['id']."'";
		$Conexion->query($ConsUpdate);
  	echo $response;
	  echo "you can see this NFT on: <a href='https://opensea.io/es/assets/matic/".$data['Contract']."/". $data["idCount"]."'>OpenSea</a> ";
	}
	exit;
}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<!-- Meta, title, CSS, favicons, etc. -->
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="description" content="Sistema de control y seguimiento.">
			<meta name="author" content="3EM&eacute;xico">
			<title>
			Yhops
			</title>
		<!-- end: Meta -->
		<!-- start: Mobile Specific -->
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- end: Mobile Specific -->

		<!-- start: CSS -->
		<?php
			$ConsultaTema = "select Ruta from CelaTema, CelaConfiguraci_on where CelaTema.idCelaTema = CelaConfiguraci_on.Valor and CelaConfiguraci_on.Nombre = 'CelaTema' ";
			$ResultadoTema = $Conexion->query ($ConsultaTema);
			$RenglonTema = $ResultadoTema->fetch_row();
			$Tema = ( strlen($RenglonTema[0])  > 0)  ? $RenglonTema[0]:"bootstrap/css/bootstrap.min.css";
		?>
			<link id="bootstrap-style" href="../<?php print  $Tema; ?>" rel="stylesheet">
			<link rel="stylesheet" type="text/css" href="../bootstrap/css/dashboard.css">
			<link rel="stylesheet" type="text/css" href="../bootstrap/css/dataTables.bootstrap.css">
			<link id="JQueryUI" href="../bootstrap/css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
			<link id="chardin" href="../bootstrap/css/chardinjs.css" rel="stylesheet">
			<link id="jquery_ui" href="../bootstrap/css/jquery-ui.css" rel="stylesheet">
			<link id="font" href="../bootstrap/font/css/font-awesome.css" rel="stylesheet">
			
			<link id="styles" href="../bootstrap/css/CELAStyles.css" rel="stylesheet">
			<link id="scrollbar" href="../bootstrap/scrollbar/perfect-scrollbar-0.4.10.min.css" rel="stylesheet">
			<!--[if lt IE 9]>
				<script src="../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
			<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
			<!--[if lt IE 9]>
				<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
				<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
			<![endif]-->
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div id="main-container" class="'col-sm-12 col-md-12 main">
					<div class="row">
						<div class="col-sm-12 col-md-12">
						<div class="row panel panel-primary">
							<div class="panel-heading">
							<div class="row">
								<div class="col-md-12">
									<div class="box-header col-xs-6 text-left">
										<strong>
											<span style="font-size: 18pt;">Mint NFT</span>
										</strong>
									</div>
									
								</div>
							</div>
							</div>
					<?php
						
					?>
							<div class="box-content panel-body">
						
								<?php 
									
									$query = "SELECT * FROM QRDetails WHERE uniqueid='". $_GET['id']."'";
									$exec = $Conexion->query($query);
									while($row = $exec->fetch_assoc() ){
										#precode($row,1);
										if($row['isMinted']==0){
											
										?>
										<form class="form-horizontal form_validate" method="POST" name="QR" id="QR" action="<?php echo $FormAction; ?>" enctype="multipart/form-data" >
											<fieldset>
												<span class="clearfix"></span>
												
											
												<div class="form-group">
													<div class="group-validate">
														<label class="col-sm-2 control-label" for="focusedInput"><font color="red">*</font>  Wallet: </label>
														<div class="col-sm-10">
															<div class="col-xs-8 validate">
																<input class="form-control focused e_requerido" name="wallet" id="wallet" type="text" />
															</div><!--End col-xs-8 validate-->
															<div class="col-xs-2 validate">
																<input class="btn btn-primary" type="submit" value="Mint" />
															</div>
														</div> <!-- End col-sm-10-->
													</div><!-- End form-group -->
												</div><!-- End form-group -->
												<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
												<input type="hidden" name="MintInsert" value="MintInsert" />
												
												
												<span class="clearfix"></span>
												
												
											</fieldset>
										</form>
										<?php	
										}else{
											$data = ObtenValor("SELECT * FROM QRDetails d 
											INNER JOIN QRHeader q ON (q.id=d.idQRHeader)
											WHERE d.uniqueid='".$_GET['id']."'");
											echo $UpdateGoTo="https://opensea.io/es/assets/matic/".$data['Contract']."/". $data["idCount"];
											
											header(sprintf("Location: %s", $UpdateGoTo));
											
											
										}
									} 
										
								?>
						
							</div>
						
							
						</div>
						</div>
					</div>
					<hr>
					<span class="clearfix"></span>
					<?php include '../CELAPie.php'; ?>
				</div>
			</div>
		</div>
		<?php include '../CELAJavascript.php'; ?>
		
	</body>
</html>