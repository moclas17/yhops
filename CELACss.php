	<!-- start: CSS -->
	<?php
		$ConsultaTema = "select Ruta from CelaTema, CelaConfiguraci_on where CelaTema.idCelaTema = CelaConfiguraci_on.Valor and CelaConfiguraci_on.Nombre = 'CelaTema' ";
		$ResultadoTema = $Conexion->query ($ConsultaTema);
		$RenglonTema = $ResultadoTema->fetch_row();
		$Tema = ( strlen($RenglonTema[0])  > 0)  ? $RenglonTema[0]:"bootstrap/css/bootstrap.min.css";
	?>
		<link id="bootstrap-style" href="<?php print  $Tema; ?>" rel="stylesheet">