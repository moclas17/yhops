<?php
include_once 'securimage/securimage.php';

$securimage = new Securimage();
$Length = 4 ;

$editFormAction = $_SERVER['PHP_SELF'];
 
if((isset($_POST['CELA_insertar'])) && ($_POST['CELA_insertar'] == 'Formulario1')){
	//Si esta incluida la libreria de captcha, se evalua.
	$success=$securimage->check($_POST['txtcaptcha']);
	if( $success == false){ 
		echo "<script type=\"text/javascript\"> alert(\"El código de acceso no coincide. \");</script>";   
	}else{ 
		$usuario = ucfirst( strtolower($_POST['txtusuario'] ) );  
		$contrasena = md5($_POST['txtcontrasena']); 
		$MM_redirectLoginSuccess = "dev.php";          
		$MM_redirectLoginFailed = "ext.php";      
		
		if( $usuario == 'Admin' && $contrasena == md5('admin') ){ // Si el susuario esta habilitado
			@session_start(); 
			$_SESSION['CELA_UltimoAcceso'] = date("Y-n-j H:i:s");  
			$_SESSION['CELA_Autentificado'] = 'SI';  
			$_SESSION['CELA_Aleatorio'] = rand(100,999);
			header("Location: " . $MM_redirectLoginSuccess ); 
		}else{
			header("Location: " . $MM_redirectLoginFailed ); 
		}
	} 
} 
?> 
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" type="text/css" media="screen" href="bootstrap.min.css">
		<style>
			.form-signin{
			    max-width: 330px;
			    padding: 15px;
			    margin: 0 auto;
			}
			.form-signin .form-signin-heading, .form-signin .checkbox{
			    margin-bottom: 10px;
			}
			.form-signin .checkbox{
			    font-weight: normal;
			}
			.form-signin .form-control{
			    position: relative;
			    font-size: 16px;
			    height: auto;
			    padding: 10px;
			    -webkit-box-sizing: border-box;
			    -moz-box-sizing: border-box;
			    box-sizing: border-box;
			}
			.form-signin .form-control:focus{
			    z-index: 2;
			}
			.form-signin input[type="text"]{
			    margin-bottom: -1px;
			    border-bottom-left-radius: 0;
			    border-bottom-right-radius: 0;
			}
			.form-signin input[type="password"]{
			    margin-bottom: 10px;
			    border-top-left-radius: 0;
			    border-top-right-radius: 0;
			}
			.account-wall{
			    margin-top: 20px;
			    padding: 40px 0px 20px 0px;
			    background-color: #f7f7f7;
			    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
			    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
			    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
			}
			.login-title{
			    color: #555;
			    font-size: 18px;
			    font-weight: 400;
			    display: block;
			}
			.profile-img{
			    width: 96px;
			    height: 96px;
			    margin: 0 auto 10px;
			    display: block;
			    -moz-border-radius: 50%;
			    -webkit-border-radius: 50%;
			    border-radius: 50%;
			}
			.need-help{
			    margin-top: 10px;
			}
			.new-account{
			    display: block;
			    margin-top: 10px;
			}
		</style>
	</head>
	<body>
		<div class="container">
		    <div class="row">
		        <div class="col-sm-6 col-md-4 col-md-offset-4">
		            <h1 class="text-center login-title">Accesar/Login</h1>
		            <div class="account-wall">
		                <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
		                    alt="">
		                <form class="form-signin" id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			                <input type="text" class="form-control" name="txtusuario" id="txtusuario" type="text" placeholder="Usuario" autofocus />
			                <input type="password" class="form-control" name="txtcontrasena" id="txtcontrasena" type="password" autocomplete="off" placeholder="Contrase&ntilde;a" />
							<input type="password" class="form-control" name="txtcaptcha" id="txtcaptcha" type="text"  placeholder="C&oacute;digo de seguridad" autocomplete="off" maxlength="<?php print $Length; ?>" />
							<div align="center">
								<img class="img-polaroid" id="captcha" src="securimage/securimage_show.php?Length=<?php print $Length; ?>" alt="Codigo de seguridad" width="200" height="80" />
								<a class="text-center new-account" href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?Length=<?php print $Length; ?>&' + Math.random(); return false"><br />[ Otra imagen ] / [ Different Image ]</a>
							</div>
							<input type="hidden" name="CELA_insertar" value="Formulario1">
							<hr />
							<span class="clearfix"></span>
			                <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
			                <span class="clearfix"></span>
		                </form>
		            </div>
		        </div>
		    </div>
			<hr />
		</div>
	</body>
</html>