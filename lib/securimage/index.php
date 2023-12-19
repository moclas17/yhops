<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/funciones.php');
if(isset($_SESSION['MM_Username']) && isset($_SESSION['cveusuario']) && $_SESSION['cveusuario']>0){
 $usr=$_SESSION['cveusuario'];
 $msj="[".$_SESSION['MM_Username']." | Acceso denegado a <".$_SERVER['HTTP_HOST'].">]";
}
else{
 $usr="0";
 $msj="[An&oacute;nimo | Acceso denegado a <".$_SERVER['HTTP_HOST'].">]";
}

accesos($usr, $msj);
 ?>
<label>
<div align="center">
  <img src="<?php echo "http://".$_SERVER['HTTP_HOST']."/img/";?>denegado.png" />
</div>
</label>
<p align="center" style="font-weight: bold;">Acceso denegado.</p>
<p align="center">Tus <span style="font-weight: bold;">accesos</span> est&aacute;n
      siendo <span style="font-weight: bold;">monitoreados</span>.</p>
<p align="center">Direcci&oacute;n ip: <?php echo '<br>'.$_SERVER['REMOTE_ADDR'];?> </p>
<p align="center">Conexi&oacute;n a internet:<?php echo '<br>'.gethostbyaddr($_SERVER['REMOTE_ADDR']); ?> </p>
<p align="center">Navegador:<?php echo '<br>'. $_SERVER[HTTP_USER_AGENT]; ?> </p>
<p align="center"><?php echo '<br>'. $_SERVER[REMOTE_USER]; ?> </p>
<p align="center"><?php echo '<br>'.getenv($_SERVER['HTTP_CLIENT_IP']);?> </p>
<p align="center"> <?php echo '<br>'. $_SERVER['HTTP_X_FORWARDED_FOR']; ?> </p>
<p align="center"> <?php echo '<br>'. $_SERVER[HTTP_VIA]; ?> </p>
