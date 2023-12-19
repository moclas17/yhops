<?php

function GetSQLValueString($Value, $Type, $DefinedValue = "", $NotDefinedValue = ""){
	$Value =  addslashes($Value) ;
	switch($Type){
		case "text":
		case "binary":
		case "varbinary":
		case "varchar":
		case "date":
		case "timestamp" :
		case "datetime":
		case "time":
			$Value = ($Value != "") ? "'" . $Value . "'" : "NULL";
			break;
		case "long":
		case "bit":
		case "bool":
		case "tinyint":
		case "smallint":
		case "mediumint":
		case "longint":
		case "smallint unsigned":
		case "mediumint unsigned":
		case "longint unsigned":
		case "int":		
		case "long unsigned":
		case "bit unsigned":
		case "bool unsigned":
		case "tinyint unsigned":
		case "int unsigned":
			$Value = ($Value != "") ? intval($Value) : "NULL";
			break;
		case "double":
		case "float":
		case "double unsigned":
		case "float unsigned":
			$Value = ($Value != "") ? "'" . doubleval($Value) . "'" : "NULL";
			break;
		case "defined":
			$Value = ($Value != "") ? $DefinedValue : $NotDefinedValue;
			break;
		default:
			$Value = ($Value != "") ? $DefinedValue : $NotDefinedValue;
			break;
	}
	return $Value;
}

function EncodeThis($String){
	$String = utf8_encode($String);
	$Control = $_SESSION['CELA_Aleatorio']; // genero un llave aleatoria para codificar por sesion...
	$String = $Control.$String.$Control; //concateno la llave para encriptar la cadena
	$String = Encrypt($String,"b5s1i4t5a1316");
	$String = base64_encode($String);//codifico la cadena
	return($String);
}

function DecodeGet($String){
	$_GET=NULL;
	$String = substr(strrchr($String,"?"),1); //Obtener la url desde el ?
	$String = base64_decode($String); //decodifico la cadena
	$String = Decrypt($String,"b5s1i4t5a1316");
	$Control = $_SESSION['CELA_Aleatorio']; //defino la llave con la que fue encriptada la cadena,, cambiarla por la que deseamos usar
	if(substr_count($String,$Control) == 0){
		print "".
			"<head>".
				"<title>Acceso prohibido!</title>".
				"<style type='text/css'>".
				"<!--".
					"body,td,th {".
						"color: #FFFFFF;".
					"}".
					"body {".
						"background-color: #999999;".
					"}".
					".Estilo1 {".
						"font-size: 36px;".
						"font-weight: bold;".
						"font-family: Verdana, Arial, Helvetica, sans-serif;".
					"}".
					".Estilo2 {".
						"font-size: 18px;".
						"font-weight: bold;".
					"}".
					".Estilo3 {".
						"font-size: 18px;".
						"font-weight: bold;".
						"font-family: 'Courier New', Courier, monospace;".
					"}".
				"-->".
				"</style>".
			"</head>".
			"<body>".
				"<p class= 'Estilo1'>&iexcl;Error 403!</p>".
				"<p class= 'Estilo1'>No tienes privilegios para navegar en este formulario.</p>".
				"<p class= 'Estilo1'>Las llaves cifradas no intentes decodificarlas.</p>".
				"<p class= 'Estilo3'>Tus accesos no autorizados est&aacute;n siendo monitoreados.</p>".
				"<p class= 'Estilo3'>Direcci&oacute;n IP: ".$_SERVER['REMOTE_ADDR']." </p>".
				"<p>&nbsp;</p>".
				"<a href='javascript:history.back()'> Volver Atr&aacute;s </a>".
				"<p>&nbsp;</p>".
				"<p>&nbsp;</p>".
			"</body>".
		"</html>";
		exit();
	}
	$String = str_replace($Control, "", "$String"); //quito la llave de la cadena
	//procedo a dejar cada variable en el $_GET
	$GET = preg_split ("[&]",$String); //separo la url por &
	foreach($GET as $Value){
		$GET = preg_split ("[=]",$Value); //asigno los valores al GET
		if(substr_count($GET[0],"[]") == 1){
			//Es un arreglo
			$GET[0]=str_replace("[]","",$GET[0]);

			$_GET[$GET[0]][]=($GET[1]);
		}
		else{
			$_GET[$GET[0]]=($GET[1]);
		}
	}
}

function RellenaCombo($SQL, $Options, $Empty=0){
	global $Conexion;
	$Select='<select name="'.$Options['nombre'].'" id="'.$Options['nombre'].'" class="'.$Options['clase'].'" '.(isset($Options['extra'])?$Options['extra']:"").'>';
	if($Empty==1)
		$Select.='<option value="'.(isset($Options['EmptyValue'])?$Options['EmptyValue']:"").'">'.(isset($Options['EmptyMessage'])?$Options['EmptyMessage']:"SELECCIONE UNA OPCI&Oacute;N").'</option>';
			
	if(is_array($SQL)){
		foreach ($SQL as $Index => $Value) {
			$Select.='<option value="'.$Index.'">'.($Value).'</option>';
		}
	}else{
		$Result =  $Conexion->query($SQL);
		while($Record = $Result->fetch_row()){			
			$Select.='<option value="'.$Record[0].'">'.($Record[1]).'</option>';
		}
	}
	$Select.='</select>';
	return $Select;
}

function SRellenaCombo($SQL,$Options,$Selected, $Empty=0){
	//print $Selected;
	global $Conexion;
	$Select='<select name="'.$Options['nombre'].'" id="'.$Options['nombre'].'" class="'.$Options['clase'].'" '.(isset($Options['extra'])?$Options['extra']:"").'>';
	
	if($Empty==1)
		$Select.='<option value="'.(isset($Options['EmptyValue'])?$Options['EmptyValue']:"").'">'.(isset($Options['EmptyMessage'])?$Options['EmptyMessage']:"SELECCIONE UNA OPCI&Oacute;N").'</option>';
	
	if(is_array($SQL)){
		foreach ($SQL as $Index => $Value) {
			if($Index==$Selected)
				$Select.='<option value="'.$Index.'" selected="selected">'.($Value)."</option>";
			else
				$Select.='<option value="'.$Index.'">'.($Value).'</option>';
		}
	}else{
		$Result = $Conexion->query($SQL);
		while($Record = $Result->fetch_array()){
			if($Record[0]==$Selected)
				$Select.='<option value="'.$Record[0].'" selected="selected">'.($Record[1])."</option>";
			else
				$Select.='<option value="'.$Record[0].'">'.($Record[1])."</option>";
		}
	}
	$Select.='</select>';
	return $Select;
}


function ObtenValor($SQL, $Value=""){
	//print $SQL;
	global $Conexion;
	if($Value==""){
		if($Result = $Conexion->query($SQL)){
			if( $Result->num_rows == 0 )
				return array('result'=>'NULL');
			
			if($Record = $Result->fetch_assoc())
				$Record['result']='OK';
			else
				$Record['result']="ERROR";
		}else{
			$Record['result']="ERROR";
			$Record['error']=$Conexion->error;
		}
		return $Record;
	}
	else{
		//print "<br/>1.-".$SQL;
		if($Result = $Conexion->query($SQL)){
			//print "<br/>2.-".$SQL;
					
			if( $Result->num_rows == 0 )
				$Value ="NULL";
			else{
				$Record = $Result->fetch_assoc();
				$Value=$Record[$Value];
			}
			return $Value;
		}else{
			return "ERROR-->".$Conexion->error;
		}
	}
	//print_r($Conexion);
	//$Conexion->close();
}


function ObtenPrivilegiosFormulario ($RutaFomulario){
	$ConsultaPrivilegios="";
	$Privilegios=array();
	$Encotrado=0;
	global $Conexion;
	
	$ConsultaPrivilegio="SELECT * FROM CelaPrivilegio";
	$ResultadoPrivilegio=$Conexion->query($ConsultaPrivilegio);
	while ($RegistroPrivilegio=$ResultadoPrivilegio->fetch_assoc()) {
		$Length=strlen($RegistroPrivilegio['Nombre'])+4;
		$Form=substr($RutaFomulario,0,-$Length)."Leer.php";
		if(substr($RutaFomulario,-$Length,$Length)==$RegistroPrivilegio['Nombre'].".php"){
			$ConsultaPrivilegios ="SELECT cp1.Nombre  FROM CelaPrivilegios cp INNER JOIN CelaFormulario cf ON (cp.Elemento=cf.idFormulario) INNER JOIN CelaRol cr ON (cp.RolDeUsuario=cr.idRol) INNER JOIN CelaPrivilegio cp1 ON (cp.Privilegio=cp1.idPrivilegio) WHERE cp.Tabla=2 AND cr.idRol=".$_SESSION['CELA_CveRol'.$_SESSION['CELA_Aleatorio']]." AND cf.Ruta='".$Form."'";
			break;
		}
	}

	if(strlen($ConsultaPrivilegios)>1){
		$ResultadoPrivilegios = $Conexion->query($ConsultaPrivilegios);

		if( $ResultadoPrivilegios->num_rows > 0 ){
			$Encotrado=1;
		}

		while($renglonPrivilegios = $ResultadoPrivilegios->fetch_array()){
			$Privilegios[$renglonPrivilegios[0]]=1;
		}
	}

	if($Encotrado == 0){		
		$ConsultaPrivilegios ="SELECT cp1.Nombre  FROM CelaPrivilegios cp INNER JOIN CelaFormulario cf ON (cp.Elemento=cf.idFormulario) INNER JOIN CelaRol cr ON (cp.RolDeUsuario=cr.idRol) INNER JOIN CelaPrivilegio cp1 ON (cp.Privilegio=cp1.idPrivilegio) WHERE cp.Tabla=2 AND cr.idRol=".$_SESSION['CELA_CveRol'.$_SESSION['CELA_Aleatorio']]." AND cf.Ruta='".$RutaFomulario."'";

		$ResultadoPrivilegios = $Conexion->query($ConsultaPrivilegios);

		if( $ResultadoPrivilegios->num_rows > 0 ){
			$Encotrado=1;
		}

		while($renglonPrivilegios = $ResultadoPrivilegios->fetch_array()){
			$Privilegios[$renglonPrivilegios[0]]=1;
		}
	}

	if($Encotrado==0){
		$Admin=strtoupper(ObtenValor("SELECT * FROM CelaRol WHERE idRol = ".$_SESSION['CELA_CveRol'.$_SESSION['CELA_Aleatorio']]."","NombreDelRol"));
		if($Admin=="ADMINISTRADOR" || $Admin=="ADMINISTRAR"){
			$ConsultaPrivilegios="select Nombre from CelaPrivilegio";
			$ResultadoPrivilegios=$Conexion->query($ConsultaPrivilegios);
			while($renglonPrivilegios = $ResultadoPrivilegios->fetch_array()){
				$Privilegios[$renglonPrivilegios[0]]=1;
			}
		}
	}
	return $Privilegios;
}

function CleanerString($String){
	$String = trim($String);
	$String = strtr($String,"√Ä√Å√Ç√É√Ñ√Ö√†√°√¢√£√§√•√í√ì√î√ï√ñ√ò√≤√≥√¥√µ√∂√∏√à√â√ä√ã√®√©√™√´√á√ß√å√ç√é√è√¨√≠√Æ√Ø√ô√ö√õ√ú√π√∫√ª√º√ø√ë√±","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
	$String = strtr($String,"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz");
	$String = preg_replace('#([^.a-z0-9]+)#i', '_', $String);
	$String = preg_replace('#-{2,}#','_',$String);
	$String = preg_replace('#-$#','',$String);
	$String = preg_replace('#^-#','', $String);
	return $String;
}
	  
function DecodeString($String){
	$Letra['n']="&ntilde;";	
	$Letra['N']="&Ntilde;";	
	$Letra['a']="&aacute;";	
	$Letra['e']="&eacute;";	
	$Letra['i']="&iacute;";	
	$Letra['o']="&oacute;";	
	$Letra['u']="&uacute;";	
	$Letra['A']="&Aacute;";
	$Letra['E']="&Eacute;";	
	$Letra['I']="&Iacute;";	
	$Letra['O']="&Oacute;";	
	$Letra['U']="&Uacute;";
	for($i = 0; $i < strlen($String) ; $i++){
		if($String[$i]=="_"){
			$String=str_replace($String[$i].$String[$i+1],$Letra[$String[$i+1]], $String);			
		}
		if(ctype_upper($String[$i]) && ctype_lower($String[$i>0?$i-1:$i])){
			$String= str_replace($String[$i],' '.$String[$i],$String);
		}
	}
	
	$Buscar = ' De '; // Reemplazar De
	$Reemplazar = ' de ';
	$String = str_replace($Buscar, $Reemplazar, $String);
	return $String;
}

function CreateEncodeFile($Nombre, $Ubicacion){	
	//Vemos si la hubicacion del archivo existe.
	if ( $Ubicacion != "none" ){
		//Leemos el archivo.
		$Fp = fopen($Ubicacion, "rb");
		$FileContents = fread($Fp, filesize($Ubicacion));
		fclose($Fp); 
		
		$FalseName = str_replace(' ', '_', date("ymdHis")."_".$Nombre);
		$FalseName = str_replace('-','_',$FalseName);
		
		//Codificamos el archivo
		$Nf=Encrypt($FalseName,"b5s1i4t5a1316");
		$EncodeString = Encrypt($FileContents,"b5s1i4t5a1316");
		
		//Comprobamos y creamos el arbol de directorios para los archivos.
		$Year=date("Y");
		$Month=date("F");
		
		$Source="repositorio/".$Year."/".$Month;
		
		//nos aseguramos que exista la ruta del archivo
		if(!file_exists($Source)){
			mkdir("repositorio/".$Year."/".$Month."", 0755, true);
		}
		
		if(!file_exists($Source."/index.php")){
			create_index_file($Source.'/');
		}
		
		$Source="repositorio/".$Year."/".$Month."/".str_replace("/","CELA",$Nf);		
		
		//Escribimos el archivo codificado.
		$NewFile = fopen($Source,"wb");
		fwrite($NewFile,$EncodeString);
		fclose($NewFile);
		
		//Se comprimie el archivo en zip
		$zip = new ZipArchive();
		if($zip->open($Source.".zip", ZIPARCHIVE::OVERWRITE ) === true) {
			$zip->addFile($Source);
			$zip->close();

			unlink($Source);
			return $Source;
		}
		else {
			return false;
		}
	}
}

function CreateDecodeTempFile($IdFile){
	//Buscamos el archivo en la base de datos.
	if(! is_numeric($IdFile)){
		$Source=$IdFile;
	}
	else{
		$Source=ObtenValor("SELECT Ruta FROM CelaRepositorio WHERE idRepositorio=".$IdFile."","Ruta");	
	}
	//decodificamos el nombre del archivo.
	$Level=strrpos($Source,'/')+1;
	$Nombre=Decrypt(substr(str_replace("CELA","/",$Source),$Level),"b5s1i4t5a1316");
		
	//Nos aseguramos que existan los temporales.
	if(!file_exists("repositorio/temporal/")){
		mkdir("repositorio/temporal/",0755, true);
	}
	if(!file_exists("repositorio/temporal/index.php")){
		create_index_file("repositorio/temporal/");
	}
	
	$TempFile = "repositorio/temporal/".$Nombre;
	
	$Cont=0;
	while(true){
		if(!file_exists($Source.".zip")){
			$Source.="=";
			if($Cont==5){
				return false;
			}
			$Cont++;	
		}
		else{
			break;
		}
	}
	
	//Descomprimimos el archivo zip
	$zip = new ZipArchive();
	if( $zip->open($Source.".zip") && $zip->extractTo("repositorio/temporal/")){
		$zip->close();

		//Decodificamos el contenido del archivo.
		$Fp=fopen("repositorio/temporal/".$Source, "rb");
		$Temp=fopen($TempFile,"wb");
		if(filesize("repositorio/temporal/".$Source)>0){
			$Content = fread($Fp, filesize("repositorio/temporal/".$Source));
			$String = Decrypt($Content,"b5s1i4t5a1316"); 

			//Se crea un archivo temporal.
			fwrite($Temp,$String);
			fclose($Temp);
			fclose($Fp);
			
			rrmdir("repositorio/temporal/".substr($Source,0,stripos($Source,"/")));
			
			return $TempFile;
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
}

function Decrypt($String, $Key) {
	$Result = '';
	$String = base64_decode($String);
	for($i=0; $i<strlen($String); $i++) {
		$Char = substr($String, $i, 1);
		$KeyChar = substr($Key, ($i % strlen($Key))-1, 1);
		$Char = chr(ord($Char)-ord($KeyChar));
		$Result.=$Char;
	}
	return $Result;
}

function Encrypt($String, $Key) {
   $Result = '';
   for($i=0; $i<strlen($String); $i++) {
      $Char = substr($String, $i, 1);
      $KeyChar = substr($Key, ($i % strlen($Key))-1, 1);
      $Char = chr(ord($Char)+ord($KeyChar));
      $Result.=$Char;
   }
   return base64_encode($Result);
}

function MysqlClear($string){
    $string = trim($string);
    $string = str_replace(
        array('√°', '√†', '√§', '√¢', '¬™', '√Å', '√Ä', '√Ç', '√Ñ', '√©', '√®', '√´', '√™', '√â', '√à', '√ä', '√ã', '√≠', '√¨', '√Ø', '√Æ', '√ç', '√å', '√è', '√é', '√≥', '√≤', '√∂', '√¥', '√ì', '√í', '√ñ', '√î', '√∫', '√π', '√º', '√ª', '√ö', '√ô', '√õ', '√ú', '√±', '√ë', '√ß', '√á', "\\", "¬®", "¬∫", "-", "~", "#", "@", "|", "!", "\"", "¬∑", "$", "%", "&", "/", "(", ")", "?", "'", "¬°", "¬ø", "[", "^", "`", "]", "+", "}", "{", "¬®", "¬¥", ">", "< ", ";", ",", ":", "."),
        '%',
        $string
    );
    
    return $string;
}

function create_index_file($ruta){
	$contenido="<head><title>Acceso prohibido!</title><style type='text/css'><!--body,td,th {color: #FFFFFF;}body {background-color: #999999;}.Estilo1 {font-size: 36px;font-weight: bold;font-family: Verdana, Arial, Helvetica, sans-serif;}.Estilo2 {font-size: 18px;font-weight: bold;}.Estilo3 {font-size: 18px; font-weight: bold; font-family: 'Courier New', Courier, monospace; }	-->	</style></head>	 <body>
            <p class=\"Estilo1\">&iexcl;Error 403!</p>
            <p class=\"Estilo3\">No tienes privilegios para navegar en esta ubicaci&oacute;n.</p>
            <p class=\"Estilo2\">Tus accesos no autorizados est&aacute;n siendo monitoreados.</p>
            <p class=\"Estilo2\">Direcci&oacute;n IP:".$_SERVER['REMOTE_ADDR']."</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            </body></body></html>";
	
	$ruta_actual=$ruta;
	for($i = 0; $i < substr_count($ruta,'/') ; $i++){
		$ruta_actual=substr($ruta_actual,0,strrpos($ruta_actual,'/'));
		if(!file_exists($ruta_actual."/index.php")){
			//Escribimos el archivo.
			$newFile = fopen($ruta_actual."/index.php","wb");
			fwrite($newFile,$contenido);
			fclose($newFile);
		}
	}
}

function rrmdir($dir){
	if(is_dir($dir)){
		$objects = scandir($dir);
		foreach($objects as $object){
			if($object !="."&& $object !=".."){
				if(filetype($dir."/".$object)=="dir") 
					rrmdir($dir."/".$object);
				else
					unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}



function precode($imprime, $return=false, $exit=false){
		if($return)
			echo "<pre>".print_r($imprime, true )."</pre>";
		else
			return "<pre>".print_r($imprime, true )."</pre>";
		
		if($exit)
			exit;

	

}


function add_filetoipfs(){
	/** create curl file */
		$cFile = curl_file_create($_FILES['logo']['tmp_name'], $_FILES['logo']['type'], $_FILES['logo']['name']);
	  /** metakey and meta values */
	  $keyvalues = [
	    'company' => 'BDTASK',
	    'background' => '100% Trait',
	    'Color' => 'RED',
	  ];
	  /** metadata array */
	  $metadata = [
	    'name' => 'This is test file',
	    'keyvalues' => $keyvalues,
	  ];
	
	  /** post data array */
	  $post = array(
	    'file' => $cFile,
	    'pinataMetadata' => json_encode($metadata)
	  );
	
	  /** header info pinata jwt authentication */
	  $headers = array();
	  $headers[] = 'Authorization: Bearer pinata-jwt';
	
	  $url = "https://api.pinata.cloud/pinning/pinFileToIPFS";
	
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_POST, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	  $result = curl_exec($ch);
	  if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	  }
	  curl_close($ch);
	  print_r($result); /** Found IPFS CID in here */
  }


 function add_minter_to_contract($privatekey, $apikey_tatum, $chain, $contract_address, $publickey){
	$curl = curl_init();
	 $payload = array(
	   "chain" => $chain,
	   "contractAddress" => $contract_address,
	   "minter" => $publickey,
	   "fromPrivateKey" => $privatekey
	 );
	 
	 curl_setopt_array($curl, [
	   CURLOPT_HTTPHEADER => [
		"Content-Type: application/json",
		"x-api-key: ".$apikey_tatum
	   ],
	   CURLOPT_POSTFIELDS => json_encode($payload),
	   CURLOPT_URL => "https://api.tatum.io/v3/nft/mint/add",
	   CURLOPT_RETURNTRANSFER => true,
	   CURLOPT_CUSTOMREQUEST => "POST",
	 ]);
	 
	 $response = curl_exec($curl);
	 $error = curl_error($curl);
	 
	 curl_close($curl);
	 
	 if ($error) {
	   echo "cURL Error #:" . $error;
	 } else {
	   echo $response;
	 }
	 return $response;
 }
 
 

function create_colecction($privatekey,$apikey_tatum, $chain, $name, $symbol){
	$curl = curl_init();
	
	$payload = array(
	  "chain" => $chain,
	  "name" => $name,
	  "symbol" => $symbol,
	  "fromPrivateKey" => $privatekey
	);
	
	curl_setopt_array($curl, [
	  CURLOPT_HTTPHEADER => [
	    "Content-Type: application/json",
	    "x-api-key: ".$apikey_tatum
	  ],
	  CURLOPT_POSTFIELDS => json_encode($payload),
	  CURLOPT_URL => "https://api.tatum.io/v3/nft/deploy",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_CUSTOMREQUEST => "POST",
	]);
	
	$response = curl_exec($curl);
	$error = curl_error($curl);
	
	curl_close($curl);
	
	if ($error) {
	  echo "cURL Error #:" . $error;
	} else {
	  echo $response;
	}
	return $response;
}

function getdatafromtx( $apikey_tatum, $chain, $tx_hash){
	$curl = curl_init();
	
	curl_setopt_array($curl, [
	  CURLOPT_HTTPHEADER => [
	    "x-api-key: " . $apikey_tatum
	  ],
	  CURLOPT_URL => "https://api.tatum.io/v3/blockchain/sc/address/" . $chain . "/" . $tx_hash,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_CUSTOMREQUEST => "GET"
	]);
	
	$response = curl_exec($curl);
	$error = curl_error($curl);
	
	curl_close($curl);
	
	if ($error) {
	  echo "cURL Error #:" . $error;
	} else {
	  echo $response;
	}
	return $response;
}


?>