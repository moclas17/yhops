<?phpdate_default_timezone_set(ObtenValor("SELECT Nombre FROM CelaZonaHoraria WHERE idCelaZonaHoraria=(SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='CelaZonaHoraria')","Nombre"));//inicia la variable de sesionif (!isset($_SESSION)) {	session_start();}//verifica la autenticacion del usuarioif ($_SESSION["CELA_Autentificado".$_SESSION['CELA_Aleatorio']] != "SI") {	header("Location: Salir.php");	exit();}if (!isset($_SESSION["CELA_CveUsuario".$_SESSION['CELA_Aleatorio']]) && !isset($_SESSION['CELA_Usuario'.$_SESSION['CELA_Aleatorio']])) {   	header("Location: Salir.php");	exit();}// Verifica si coincide el nombre del sistema entre la sesión y la base de datos$ConsultaSistema="SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = 'NombreSistema' AND  Valor ='".$_SESSION['CELA_NombreSistema'.$_SESSION['CELA_Aleatorio']]."'";$ResultadoSistema = $Conexion->query($ConsultaSistema);if ($ResultadoSistema->num_rows == 0){	session_destroy();	header("Location: Salir.php");}//verificamos si el usuario no ha caducado$ConsultaEstadoActual="SELECT EstadoActual FROM CelaUsuario WHERE idUsuario=".$_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']]."";$ResultadoEstadoActual = $Conexion->query($ConsultaEstadoActual);$RegistroEstadoActual = $ResultadoEstadoActual->fetch_assoc();if($RegistroEstadoActual['EstadoActual']==2){	session_destroy();	header("Location: Salir.php");}//Guardamos el ultimo acceso$FechaGuardada = strtotime($_SESSION["CELA_UltimoAcceso".$_SESSION['CELA_Aleatorio']]);$Ahora = strtotime(date("Y-n-j H:i:s"));$TiempoTranscurrido = $Ahora - $FechaGuardada;$Limite=ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='TiempoDeCaducidadDeLaSesi_on(EnMinutos)'","Valor"); //Se calcula el tiempo de inicio para matar la sesion if($TiempoTranscurrido >= (int)($Limite*60)) { 	session_destroy();	header("Location: Salir.php");}else{	$_SESSION["CELA_UltimoAcceso".$_SESSION['CELA_Aleatorio']] = date("Y-n-j H:i:s");}//Obtiene los privilegios del usuario$FomularioRuta =  substr(strrchr($_SERVER['PHP_SELF'],"/"),1); // Archivo actual$Privilegios = ObtenPrivilegiosFormulario ($FomularioRuta);if($_GET)	DecodeGet($_SERVER["REQUEST_URI"]);$ConsultaPrivilegio="SELECT * FROM CelaPrivilegio";$ResultadoPrivilegio=$Conexion->query($ConsultaPrivilegio);while ($RegistroPrivilegio=$ResultadoPrivilegio->fetch_assoc()) {	$Length=strlen($RegistroPrivilegio['Nombre'])+4;	if(substr($FomularioRuta,-$Length,$Length)==$RegistroPrivilegio['Nombre'].".php"){		$FomularioRuta=substr($FomularioRuta,0,-$Length)."Leer.php";		break;	}}?>