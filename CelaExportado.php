<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include('lib/Seguridad.php');
$Content = file_get_contents($_GET['SourceFile']);
$Level=strrpos($_GET['SourceFile'],'/')+1;
$Name=str_replace('html','xls',substr($_GET['SourceFile'],$Level));

header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
header ('Content-Type: application/vnd.ms-excel');
header ('Content-Disposition: attachment; filename='.$Name);
header ('Content-Transfer-Encoding: binary');

echo $Content;
?>