<?php	require_once('lib/Conexion.php');	include ('lib/Funciones.php');		date_default_timezone_set(ObtenValor("SELECT Nombre FROM CelaZonaHoraria WHERE idCelaZonaHoraria=(SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='CelaZonaHoraria')","Nombre"));	class ExceptionThrower{		static $IGNORE_DEPRECATED = true;		static function Start($level = null){	 			if ($level == null){				if (defined("E_DEPRECATED")){					$level = E_ALL & ~E_DEPRECATED ;				}else{					$level = E_ALL;					self::$IGNORE_DEPRECATED = true;				}			}			set_error_handler(array("ExceptionThrower", "HandleError"), $level);		}	 		static function Stop(){			restore_error_handler();		}	 		static function HandleError($code, $string, $file, $line, $context){			// ignore supressed errors			if (error_reporting() == 0) return;			if (self::$IGNORE_DEPRECATED && strpos($string,"deprecated") === true) return true;	 			throw new Exception($string,$code);		}	}	if (!isset($_SESSION)) {
		session_start();
	}
	//Apartado de seguridad.
	if ( ($_SESSION["CELA_Autentificado".$_SESSION['CELA_Aleatorio']] != "SI") && (!isset($_SESSION["CELA_CveUsuario".$_SESSION['CELA_Aleatorio']])) && (!isset($_SESSION['CELA_Usuario'.$_SESSION['CELA_Aleatorio']]))) {
		header("Location: Salir.php");
		exit();
	}
		$Response="";
	switch($_POST['funcion']){
		case 1:
			//Muestra el archivo en temporal.
			$Response=ShowFile($_POST['IdFile']);
			break;
		case 2:
			//Desvincula el archivo temporal.
			$Response=DeleteTempFile($_POST['SRCFile']);
			break;
		case 3:
			//Validamos un campo desde el servidor
			$Response=FieldValidator($_POST['Value'], $_POST['Table'], $_POST['Field'], $_POST['Response'], $_POST['And']);
			break;
		case 4:
			//Rellena un combo desencadenado.
			$Response=TriggerSelect($_POST['Tabla'],$_POST['Indice'],$_POST['Campo'],$_POST['Filtro'],$_POST['Valor'], $_POST['Vacio'], $_POST['MensajeVacio'], $_POST['Condicion']);
			break;
		case 5:
			//Codifica la variable get
			$Response=EncodeThis($_POST['Post']);
			break;
		case 6:
			//Bloqueamos la session del usuario.
			$Response=LockSession();
			break;
		case 7:
			//Desbloqueamos la session del usuario.
			$Response=UnLockSession($_POST['Contrase_na']);
			break;		case 8:			//Exportación por default de registros en tablas.			$Response=DataTableDefaultExported($_POST['aData']);			break;
	}
	echo $Response;
	function ShowFile($IdFile){		$Source=CreateDecodeTempFile($IdFile);		if($Source==false){			$_DATA['status']="Error";			$_DATA['error']="No se encotro el archivo especificado";			return json_encode($_DATA);		}
		$FileSource="".$_SERVER['SERVER_NAME']."".substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/')+1)."".$Source."";		$_DATA['status']="OK";		$_DATA['src']="http://docs.google.com/viewer?url=".$FileSource."&embedded=true";		$_DATA['file_source']=Encrypt($Source,$_SESSION['CELA_Aleatorio']);		return json_encode($_DATA);	}	function DeleteTempFile($FileSource){		$TempFile=Decrypt($FileSource,$_SESSION['CELA_Aleatorio']);		if(file_exists($TempFile)){			unlink($TempFile);			}		return "OK";	}	function FieldValidator($Value, $Table, $Field, $Valid, $And=""){		$Exist = ObtenValor("SELECT ".$Field." FROM ".$Table." WHERE ".$Field."='".$Value."' ".($And==""?'': " AND ".$And)."",$Field);		if($Valid=='false'){			return  $Exist=="NULL"? "true": "false";			}		else{			return  $Exist=="NULL"? "false": "true";		}	}	function TriggerSelect($Table, $Index, $Field, $Filter, $Value, $Empty=0, $EmptyMessage="", $And=""){		global $Conexion;		$Query="SELECT ".$Index.", ".$Field." FROM ".$Table." WHERE ".$Filter."=".$Value." ".($And==""?'': " AND ".$And)."";		$Result=$Conexion->query($Query);		$Options="";		if($Empty==1)			$Options.='<option value="">'.($EmptyMessage==""?$EmptyMessage:"SELECCIONE UNA OPCI&Oacute;N").'</option>';		while($Record=$Result->fetch_assoc()){			$Options.='<option value="'.$Record[$Index].'">'.$Record[$Field].'</option>';		}		return $Options;	}	function EncodeGet($Get){		return EncodeThis($Get);	}
	function LockSession(){		$_SESSION["CELA_Autentificado".$_SESSION['CELA_Aleatorio']]="NO";		if($_SESSION["CELA_Autentificado".$_SESSION['CELA_Aleatorio']]=="NO")			return "OK";		else			return "ERROR";	}	function UnLockSession($Contrase_na){		if(ObtenValor("SELECT NombreCompleto FROM CelaUsuario WHERE idUsuario=".$_SESSION['CELA_CveUsuario'.$_SESSION['CELA_Aleatorio']]." AND Contrase_na='".($Contrase_na)."'","NombreCompleto") != "NULL"){			$_SESSION["CELA_Autentificado".$_SESSION['CELA_Aleatorio']]="SI";			return "OK";			}		else{			return "ERROR";		}	}		function DataTableDefaultExported($aData){
		global $Conexion;
		$Datos="";
		$urlLogo = ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='Logotipo'","Valor");
		$nombreSistema = ObtenValor("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='NombreSistema'","Valor");
		
		$Server=$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
		$ServerName = substr($Server,0,strrpos($Server,'/'));
		ExceptionThrower::Start();
		try{
			//Quitamos el limit a la consulta
			// = ($exp_regular, $cadena_nueva, $cadena);
			$sQuery=preg_replace('/LIMIT [0-9]+, [0-9]+/', '', Decrypt($aData['sQuery'],$_SESSION['CELA_Aleatorio']));
					
			//Obtenemos los campos de el encabezado.
			$aHeader=explode("/*/", substr_replace($aData['sColum'],'',-3));
			
			$rResult = $Conexion->query( $sQuery );
			$tama_noDeHoja="1380px"; //735 ideal vertical; 942 ideal horizontal
			
			$colorDeFondo="#EFEFEF";
			$colorDeTexto="#000000";
			
			$_DATA=json_decode(Decrypt(str_replace(' ','+',$aData['params']),$_SESSION['CELA_Aleatorio']),true);
				
			$myTable = $_DATA['table'];
			$myIndex = $_DATA['index'];
			$mySelect['alias']='';
			$mySelect['where']='';
				
			/**
			* Obtenemos la tabla y las columnas de la consulta.
			**/
			//Separamos las columnas y buscamos si existe alguna consulta de reemplazo.
			$Cols=explode("/*/",$_DATA['columns']);
			$_DATA['columns']= str_replace("/as/"," as",str_replace("/*/",",",$_DATA['columns']));
			
			$found=strripos($Cols[0],'/as/');
			if($found===false){
				//No se encontro consulta de sustitucion.
				$mySelect['alias'].=$Cols[0];
			}
			else{
				//Se encontro una consulta de sustitucion, indexamos el alias como columna.
				$Column=substr($Cols[0],$found+5,strlen($Cols[0]));
				$mySelect['alias'].=$Column;
			}
			
			for($index = 1; $index < count($Cols); $index++){
				$found=strripos($Cols[$index],'/as/');
				if($found===false){
					//No se encontro consulta de sustitucion.
					$mySelect['alias'].="/*/".$Cols[$index];
				}
				else{
					//Se encontro una consulta de sustitucion, indexamos el alias como columna.
					$Column=substr($Cols[$index],$found+5,strlen($Cols[$index]));
					$mySelect['alias'].="/*/".$Column;
				}
			}	
				
			$aColumns = explode("/*/",$mySelect['alias']);
			/* Indice de la tabla (utilizado para una r√°pida y precisa cardinalidad) */
			$sIndexColumn = $myIndex;
			$header='<td width="1%"><div align="center">#</div></td>';
			/* Tabla a ser procesada */
			$sTable = $myTable;
			
			//Verificamos que no haya un alias para el indice.
			if(strpos($sIndexColumn,'.')!==false){
				$sIndexColumn=substr($sIndexColumn,strrpos($sIndexColumn,'.')+1,strlen($sIndexColumn));	
			}
			
			//Verificamos que no haya un alias para la tabla.
			if(strpos($myTable,' ')!==false){
				$myTable=substr($myTable,0,stripos($myTable,' '));
			}
			$columnas=0;
			for ( $i=0 ; $i<count($aHeader) ; $i++ ){
				if(isset($aData['bExtraCols']) && $aData['bExtraCols']==1){
					//Buscamos si tiene es un select o input y extraemos solo el contenido de texto.
					if(stripos($aHeader[$i], '<select')!==false){
						//Obtenemos el contenido html de el select.
						$sSelectElement=substr($aHeader[$i], stripos($aHeader[$i], '<select'),(stripos($aHeader[$i], '</select>')-stripos( $aHeader[$i], '<select')))."</select>";
						//Obtenemos el primer elemento option vacio del select.
						$sOptionElement=str_replace('<option value="">', '', substr($sSelectElement, stripos($sSelectElement, '<option value="">'),(stripos($sSelectElement, '</option>')-stripos( $sSelectElement, '<option value="">'))));
						$header.=str_replace('class="', 'class="br bl bt ', str_replace($sSelectElement, $sOptionElement, $aHeader[$i]));
					}else{
						$header.=str_replace('class="', 'class="br bl bt ', $aHeader[$i]);
					}
					$columnas++;
				}else{
					if ( trim($aColumns[$i]) != '' && trim($aColumns[$i]) !="''"){
						//Buscamos si tiene es un select o input y extraemos solo el contenido de texto.
						if(stripos($aHeader[$i], '<select')!==false){
							//Obtenemos el contenido html de el select.
							$sSelectElement=substr($aHeader[$i], stripos($aHeader[$i], '<select'),(stripos($aHeader[$i], '</select>')-stripos( $aHeader[$i], '<select')))."</select>";
							//Obtenemos el primer elemento option vacio del select.
							$sOptionElement=str_replace('<option value="">', '', substr($sSelectElement, stripos($sSelectElement, '<option value="">'),(stripos($sSelectElement, '</option>')-stripos( $sSelectElement, '<option value="">'))));
							$header.=str_replace('class="', 'class="br bl bt ', str_replace($sSelectElement, $sOptionElement, $aHeader[$i]));
						}else{
							$header.=str_replace('class="', 'class="br bl bt ', $aHeader[$i]);
						}
						$columnas++;
					}
				}
			}
			//print $header;
			$mihtml='
			<!DOCTYPE html>
			<html lang="es">
				<head>
					<meta charset="utf-8">
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<link href="http://'.$ServerName.'/bootstrap/css/bootstrap.min.css" rel="stylesheet">
				</head>
				<body>	
				<div id="exportado">
					<table class="table table-bordered table-striped" id="tabla_exportado"  width="'.$tama_noDeHoja.'" border="0" frame="void">
						<thead>
							<tr>
								<td colspan="'.($columnas+1).'" width="100%" class="encabezado centrado bl br bt">
									<div>
										<div class="izquierda" style="float: left">
											<img style="height:50px;border:none;" src="http://'.$ServerName.'/'.$urlLogo.'" />
										</div>
										<div class="derecha" style="float: right; padding: 15px 0 0 0;">
											<span class="letrapequenia">'.$nombreSistema.'</span>
										</div>
										<div align="center">
											<h2>'.$aData['sHeader'].'</h2>
										</div>
									</div>
								</td>
							</tr>
							<tr class="encabezado-lg">'.$header.'</tr>
						</thead>
						<tbody>';
				$renglon=0;
				while ( $aRow = $rResult->fetch_array(MYSQLI_ASSOC) ){
					if(isset($_DATA['rows'])){
						//Incluimos las columnas con render.
						include("lib/RenderRow.php");
						//Formato para una columna con valor en la tabla pero de algun estilo en especial*
						$renderRow=$Rows[$_DATA['rows']];
						$mihtml .= '<tr class="'.$renderRow.'"><td class="centrado bl br bt">'.($renglon+1).'</td>';
					}else{
						$mihtml .= '<tr><td class="centrado bl br bt">'.($renglon+1).'</td>';
					}
					for ( $i=0 ; $i<count($aHeader); $i++ ){
						if(isset($aColumns[$i])){
							//print $i;
							if(isset($aData['bExtraCols']) && $aData['bExtraCols']==1){
								if (isset($_DATA['extra'][$i]) && trim($aColumns[$i]) == "''"){
									//Incluimos las columnas extras.
									include("lib/ExtraColumns.php");
									//Columna de acciones
									$actionColum=$Extra[$_DATA['extra'][$i]];
									// Formato para una columna que no pertenece a la tabla
									$mihtml .='<td class="br bt">'. $actionColum.'</td>';
								}
								else{
									if(isset($_DATA['render'][$i])){
										//Incluimos las columnas con render.
										include("lib/RenderColumn.php");
										//Formato para una columna con valor en la tabla pero de algun estilo en especial*
										$actionColum=$Render[$_DATA['render'][$i]];
										$mihtml .='<td class="br bt">'.$actionColum.'</td>';
									}
									else{
										if ( trim($aColumns[$i]) != '' && trim($aColumns[$i]) !="''"){
											// Columnas con datos
											$mihtml .='<td class="br bt">'. $aRow[trim($aColumns[$i])].'</td>';
										}
									}
								}
							}else{
								if(isset($_DATA['render'][$i])){
									//Incluimos las columnas con render.
									include("lib/RenderColumn.php");
									//Formato para una columna con valor en la tabla pero de algun estilo en especial*
									$actionColum=$Render[$_DATA['render'][$i]];
									$mihtml .='<td class="br bt">'.$actionColum.'</td>';
								}
								else{
									if ( trim($aColumns[$i]) != '' && trim($aColumns[$i]) !="''"){
										// Columnas con datos
										$mihtml.='<td class="br bt">'.$aRow[trim($aColumns[$i])].'</td>';
									}
								}
							}
						}
					}	
					$mihtml.='</tr>';
					$renglon++;
				}	
			$mihtml.=  '</tbody>
							<tfoot>
								<tr class="encabezado-lg">
									<td colspan="'.($columnas+1).'" class="margin_extra bb bl bt br" style="font-size: 8px;">
										<span class="encabezado-lg">FECHA: '.date('d/m/Y').'
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</body>
			</html>';
			if($aData['sMimeType'] == 'text/html'){
				//Regresamos el archivo html para impresi√≥n.
				$Datos['status']="OK";
				$Datos['contend']=$mihtml;
				return json_encode($Datos);
			}
			if($aData['sMimeType'] == 'application/pdf'){
				//Creamos el archivo pdf para descarga.
				//Creamos el archivo PDF.
				include_once('lib/libPDF.php');
				$NombreOrginal="Documento_".rand(100, 999).".pdf";
				try{
				   $wkhtmltopdf = new Wkhtmltopdf(array('path' =>'repositorio/temporal/', 'orientation'=>'Landscape', 'FooterStyle' => 'Pag. [page] de [toPage]'));
				   $wkhtmltopdf->setHtml($mihtml);
				   $wkhtmltopdf->output(Wkhtmltopdf::MODE_SAVE, $NombreOrginal);
				}
				catch (Exception $e){
				   echo $e->getMessage();
				}
				$NombreFinal=$aData['sFileName'].".pdf";
				$RutaArchivo=CreateEncodeFile($NombreFinal, 'repositorio/temporal/'.$NombreOrginal);
				
				$Datos['sourceFile']="CelaRepositorioDescargar.php?".EncodeThis("clave=".$RutaArchivo);
				
				unlink('repositorio/temporal/'.$NombreOrginal);
				$Datos['status']="OK";
				$Datos['tempFile']=Encrypt($RutaArchivo.".zip", $_SESSION['CELA_Aleatorio']);
			}
			if($aData['sMimeType'] == 'application/vnd.ms-excel'){
				//Creamos el archivo xsl para descarga.
				//Nos aseguramos que existan los temporales.
				$NombreOrginal=$aData['sFileName'].rand(100, 999).".html";
				if(!file_exists("repositorio/temporal/")){
					mkdir("repositorio/temporal/",0755, true);
				}
				$newFile = fopen('repositorio/temporal/'.$NombreOrginal,"wb");
				fwrite($newFile,$mihtml);
				fclose($newFile);
				$Datos['sourceFile']="CelaExportado.php?".EncodeThis("SourceFile=".'repositorio/temporal/'.$NombreOrginal);
				$Datos['status']="OK";
				$Datos['tempFile']=Encrypt('repositorio/temporal/'.$NombreOrginal, $_SESSION['CELA_Aleatorio']);
			}
			return json_encode($Datos);
		} catch (Exception $e) {
			$Datos['status']="ERROR";
			$Datos['error']=$e;
			print_r($e);
			return json_encode($Datos);
		}
		ExceptionThrower::Stop();
	}?>