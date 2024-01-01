<?phprequire_once('lib/Conexion.php');require_once('lib/Funciones.php');@session_start(); //Obtenemos la configuración para el capcha.$captcha=ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='Captcha'","Valor");if($captcha==1){	include_once 'lib/securimage/securimage.php';	$securimage = new Securimage();	$Length = (int)ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='Tama_noCaptcha'", "Valor");}$NombreSistema=ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='NombreSistema'",'Valor');$SloganSistema=ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='Slogan'",'Valor');$LogotipoSistema=ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='LogoPantallaInicial(RecomendadoUtilizarImagen246x52SinColorDeFondo)'",'Valor');$ImagenSistema=ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='ImagenPantallaInicial'",'Valor');$IconoSistema=ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='IconoDeSistema'",'Valor');?><!DOCTYPE html><html lang="es">	<head>		<meta charset="utf-8">		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">		<title> <?php print $NombreSistema; ?> </title>		<meta name="description" content="">		<meta name="author" content="">		<meta name="HandheldFriendly" content="True">		<meta name="MobileOptimized" content="320">		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">		<!-- Basic Styles -->		<link rel="stylesheet" type="text/css" media="screen" href="landing/css/bootstrap.min.css">			<link rel="stylesheet" type="text/css" media="screen" href="landing/css/font-awesome.min.css">		<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->		<link rel="stylesheet" type="text/css" media="screen" href="landing/css/smartadmin-production.css">		<link rel="stylesheet" type="text/css" media="screen" href="landing/css/smartadmin-skins.css">					<!-- SmartAdmin RTL Support is under construction			<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.css"> -->				<link rel="stylesheet" type="text/css" media="screen" href="landing/css/demo.css">		<!-- FAVICONS -->		<link rel="shortcut icon" href="<?php print $IconoSistema; ?>" type="image/x-icon">		<link rel="icon" href="<?php print $IconoSistema; ?>" type="image/x-icon">		<!-- GOOGLE FONT -->		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">		<style>			label.error {				font-weight: bold;				color: red;				padding: 2px 8px;				margin-top: 2px;			}		</style>	</head>	<body id="login" class="animated fadeInDown">		<header id="header">			<div id="logo-group">							</div>		</header>		<div id="main" role="main">			<div id="content" class="container">				<div class="row">					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">						<br />						<br />						<br />						<h1>							<img class="pull-right display-image" width="100%" src="<?php print $ImagenSistema; ?>">						</h1>						<br />						<br />						<br />						<div class="row">							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">								<br />								<br />								<hr />								<h4 class="about-heading">									<?php print $NombreSistema; ?>								</h4>								<br />								<p>								<?php									include 'CELAPie.php';								?>								</p>							</div>													</div>					</div>					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">						<div class="well no-padding">							<form action="Accesar.php" id="form1" class="smart-form client-form form_validate" method="post">								<header>									Login								</header>								<fieldset>									<section>									<?php										if(isset($_SESSION['CaptchaFail']) && $_SESSION['CaptchaFail']==1){									?>											<label class="error">El c&oacute;digo de acceso no coincide, vuelva a intentarlo</label>									<?php										}										if(isset($_SESSION['UserLock']) && $_SESSION['UserLock']==1){									?>											<label class="error">Este usuario se encuentra bloquedo, pongase en contancto con el administrador</label>									<?php										}if(isset($_SESSION['LoginFail']) && $_SESSION['LoginFail']==1){									?>											<label class="error">Error al iniciar sesi&oacute;n, verifique el nombre de usuario y la contrase&ntilde;a</label>									<?php										}									?>									</section>									<section>										<label class="label">User</label>										<label class="input"> <i class="icon-append fa fa-user"></i>											<input type="text" name="txtusuario" class="e_requerido e_usuario">																				</section>									<section>										<label class="label">Password</label>										<label class="input"> <i class="icon-append fa fa-lock"></i>											<input type="password" name="txtcontrasena" class="e_requerido" autocomplete="off">																				</section>							<?php								if($captcha==1){							?>									<section>										<label class="label">Captcha</label>										<label class="input"> <i class="icon-append fa fa-chain-broken"></i>											<input type="text" name="txtcaptcha" class="e_requerido" autocomplete="off" maxlength="<?php print $Length; ?>">											<b class="tooltip tooltip-top-right"><i class="fa fa-chain-broken txt-color-teal"></i>&nbsp; Ingrese el texto de la imagen</b> </label>									</section>									<div align="center">										<img class="img-polaroid" id="captcha" src="lib/securimage/securimage_show.php?Length=<?php print $Length; ?>" alt="Codigo de seguridad" width="200" height="80" />										<a class="text-center new-account" href="#" onclick="document.getElementById('captcha').src = 'lib/securimage/securimage_show.php?Length=<?php print $Length; ?>&' + Math.random(); return false"><br />[ Otra imagen ] / [ Different Image ]</a>									</div>							<?php								}								session_destroy(); 								unset($_SESSION['CaptchaFail']);  								unset($_SESSION['UserLock']);  								unset($_SESSION['LoginFail']); 							?>								</fieldset>								<footer>									<input type="hidden" name="CELA_insertar" value="Formulario1">									<button type="submit" class="btn btn-primary">										Enter									</button>								</footer>							</form>						</div>											</div>				</div>			</div>		</div>		<?php include 'CELAJavascript.php'?>	    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local ->	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>		<script>			if(!window.jQuery){				document.write('<script src="landing/js/libs/jquery-2.0.2.min.js"><\/script>');			}		</script>		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>		<script>			if(!window.jQuery.ui){				document.write('<script src="landing/js/libs/jquery-ui-1.10.3.min.js"><\/script>');			}		</script-->		<!--[if IE 7]>			<h1>				Your browser is out of date, please update your browser by going to www.microsoft.com/download			</h1>		<![endif]-->	</body></html>