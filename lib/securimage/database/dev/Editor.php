<?php
//inicia la variable de sesion
if (!isset($_SESSION)) {
	session_start();
}

//verifica la autenticacion del usuario
if ($_SESSION["CELA_Autentificado"] != "SI") {
	header("Location: ext.php");
	exit();
}
?>
<!doctype html>
<html>
	<header>
		<meta charset="utf-8"/>
		<link rel=stylesheet href="codemirror/doc/docs.css">
		<link rel="stylesheet" href="codemirror/addon/hint/show-hint.css">
		<link rel="stylesheet" href="codemirror/lib/codemirror.css">
		<link rel="stylesheet" href="codemirror/addon/fold/foldgutter.css" >
		<link rel="stylesheet" href="codemirror/addon/dialog/dialog.css">
		
		<script src="codemirror/lib/codemirror.js"></script>
		<script src="codemirror/addon/selection/active-line.js"></script>
		<script src="codemirror/mode/php/php.js"></script>
		
		<script src="codemirror/addon/edit/matchbrackets.js"></script>
		<script src="codemirror/mode/htmlmixed/htmlmixed.js"></script>
		<script src="codemirror/mode/xml/xml.js"></script>
		<script src="codemirror/mode/javascript/javascript.js"></script>
		<script src="codemirror/mode/css/css.js"></script>
		<script src="codemirror/mode/clike/clike.js"></script>
		<script src="codemirror/addon/hint/show-hint.js"></script>
		<script src="codemirror/addon/hint/anyword-hint.js"></script>
		<script src="codemirror/addon/edit/closetag.js"></script>
		<script src="codemirror/addon/hint/javascript-hint.js"></script>
		
		<script src="codemirror/addon/fold/foldcode.js"></script>
		<script src="codemirror/addon/fold/foldgutter.js"></script>
		<script src="codemirror/addon/fold/brace-fold.js"></script>
		<script src="codemirror/addon/fold/xml-fold.js"></script>
		<script src="codemirror/addon/fold/markdown-fold.js"></script>
		<script src="codemirror/addon/fold/comment-fold.js"></script>
		<script src="codemirror/mode/markdown/markdown.js"></script>
		
		<script src="codemirror/mode/css/css.js"></script>
		<script src="http://ajax.aspnetcdn.com/ajax/jshint/r07/jshint.js"></script>
		<script src="https://rawgithub.com/zaach/jsonlint/79b553fb65c192add9066da64043458981b3972b/lib/jsonlint.js"></script>
		<script src="codemirror/addon/search/searchcursor.js"></script>
		<script src="codemirror/addon/search/match-highlighter.js"></script>
		
		<script src="codemirror/addon/fold/xml-fold.js"></script>
		<script src="codemirror/addon/edit/matchtags.js"></script>
		
		<script src="codemirror/addon/dialog/dialog.js"></script>
		<script src="codemirror/addon/search/searchcursor.js"></script>
		<script src="codemirror/addon/search/search.js"></script>
		
		<style type="text/css">
			.CodeMirror {
				border-top: 1px solid black; 
				border-bottom: 1px solid black;
			}
			.breakpoints {
				width: .8em;
			}
			.breakpoint {
				color: #822;
			}
			.CodeMirror {
				border: 1px solid #aaa;
			}
			.CodeMirror-focused .cm-matchhighlight {
				background-position: bottom;
				background-repeat: repeat-x;
			}
			.CodeMirror-matchingtag { background: rgba(255, 150, 0, .3); }
			dt {font-family: monospace; color: #666;}
		</style>	
	</header>
	<body>
		<form>
			<textarea id="code" name="code"><?php print "<?php
require_once('lib/Conexion.php');
include ('lib/Funciones.php');
include ('lib/Seguridad.php');
\$Tabla['table']=\"CelaUsuario\";
\$Tabla['columns']=\"''/*/ NombreCompleto/*/ Usuario/*/ CorreoElectr_onico/*/ ( select Descripci_on from  CelaEstados where CelaEstados.ClaveDeCelaEstado = CelaUsuario.EstadoActual) /as/ EstadoActual /*/ ( select NombreDelRol from  CelaRol where CelaRol.ClaveDelRol = CelaUsuario.ClaveDelRol) /as/ ClaveDelRol \";
\$Tabla['index']=\"ClaveDeUsuario\";
\$Tabla['condition']=\" EstadoActual=1\";
\$Tabla['extra'][0]=\"actions\";
\$Tabla['Privileges']=\$Privilegios;
\$Datos=base64_encode(json_encode(\$Tabla));
\$ConsultaLog = sprintf(\"INSERT INTO CelaAccesos ( ClaveDeAccesos, FechaDeAcceso, ClaveDeUsuario, Tabla, IdTabla, Acci_on ) VALUES ( %s, %s, %s, %s, %s, %s)\",
	GetSQLValueString( \"NULL\", \"int\"),
	GetSQLValueString( date('Y-m-d H:i:s'), \"varchar\"),
	GetSQLValueString( \$_SESSION['CELA_CveUsuario'], \"int\"),
	GetSQLValueString( 'CelaUsuario', \"varchar\"),
	GetSQLValueString( \"NULL\", \"int\"),
	GetSQLValueString( 4, \"int\"));
\$ResultadoLog=\$Conexion->query(\$ConsultaLog);
?>
<!DOCTYPE html>
<html lang=\"es\">
	<?php include 'CELAHead.php'; ?>
	<body>
		<?php include 'CELAMenuHorizontal.php'; ?>
		<div class=\"container\" style=\"width: 99%;\">
			<div class=\"row\">
				<div class=\"col-md-2\">
					<?php include 'CELAMenuVertical.php'; ?>
				</div>
				<div class=\"col-md-10\">
					<div class=\"row\">
						<?php include 'CELARuta.php'; ?>
					</div>
					<div class=\"row\">
						<div class=\"col-md-12 panel panel-primary\" >
							<div class=\"row panel-heading\">
								<div class=\"col-md-12\">
									<div class=\"box-header col-md-6 text-left\">
										<strong>
											<span style=\"font-size: 14pt;\">Listado de Usuarios</span>
										</strong>
									</div>
									<div class=\"box-icon col-md-6 text-right\">
										<a data-intro=\"Ayuda general\" data-position=\"left\" href=\"#\" class=\"btn-help btn btn-default\" title=\"Ayuda\"><i class=\"fa fa-question\"></i></a>
									</div>
								</div>
							</div>
						<?php
							if(\$Privilegios['leer']){
						?>
							<div class=\"box-content panel-body\">
								<div class=\"col-md-12\">
							<?php
								if (\$Privilegios['crear']) {
							?>
									<div class=\"col-md-2 text-left\">
										<a data-intro=\"Insertar nuevo CelaUsuario\" data-position=\"top\" class=\"btn btn btn-success\" title=\"Agregar\" href=\"CelaUsuarioCrear.php\" >
											<i class=\"fa fa-plus\"></i>&nbsp;
											<span>Agregar</span>
										</a>
									</div>
							<?php
								}
							?>
									<div class=\"col-md-6 text-left form-inline\">
								<?php
									if(\$Privilegios['actualizar']){
								?>
										<a data-intro=\"Modifica los datos seleccionados\" data-position=\"bottom\" class=\"btn btn btn-warning\" title=\"Editar seleccionados\" href=\"#\" disabled=\"disabled\" id=\"Actualizar\">
											<i class=\"fa fa-pencil\"></i>&nbsp;
											<span>Editar</span>
										</a>
								<?php
									}
									if(\$Privilegios['eliminar']){
								?>
										<a data-intro=\"Elimina datos todos los seleccionados\" data-position=\"right\" title=\"Eliminar  seleccionados\" href=\"#\" class=\"btn btn btn-danger delete\" disabled=\"disabled\" id=\"Eliminar\">
											<i class=\"fa fa-trash-o\"></i>&nbsp;
											<span>Eliminar</span>
										</a>
								<?php
									}
								?>
										<label>&larr; Para los datos seleccionados</label>
									</div>
									<div class=\"col-md-4 text-right\">
										<div class=\"form-group\" data-intro=\"Busqueda general\" data-position=\"bottom\" >
											<label class=\"sr-only\" for=\"Search-CelaUsuario\">Busqueda</label>
											<div>
												<input id=\"Search-CelaUsuario\" class=\"form-control DataTableFilter\" type=\"text\" placeholder=\"Buscar...\" autocomplete=\"off\">
											</div>
										</div>
									</div>
								</div>
								<div class=\"table-responsive\" align=\"left\">
									<table id=\"CelaUsuario\" class=\"table table-striped table-bordered hover datatable\" data-records=\"<?php print \$Datos; ?>\" data-form=\"<?php print substr(strrchr(\$_SERVER['PHP_SELF'],\"/\"),1); ?>\" >
										<thead>
											<tr>
												<th width=\"1%\" title=\"Seleccionar todo\"><div align=\"center\"><label><input  type=\"checkbox\" id=\"All\" data-intro=\"Seleeciona todos los registros de esta p&aacute;gina\" data-position=\"bottom\"/></label></div></th>
												<th class=\"sortable\" width=\"30%\"><div align=\"center\"> Nombre Completo</div></th>
												<th class=\"sortable\" width=\"25%\"><div align=\"center\"> Usuario</div></th>
												<th class=\"sortable\" width=\"25%\"><div align=\"center\"> Correo Electr&oacute;nico</div></th>
												<th class=\"sortable\" width=\"9%\"><div align=\"center\"> Estado Actual</div></th>
												<th class=\"sortable\" width=\"10%\"><div align=\"center\"> Clave del Rol</div></th>

											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
						<?php
							if(\$Privilegios['eliminar']){
						?>
								<div class=\"modal fade\" id=\"DeleteModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
									<div class=\"modal-dialog\">
										<div class=\"modal-content\">
											<div class=\"modal-header\">
												<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
											</div>
											<div class=\"modal-body\" id=\"Body\">
											</div>
											<div class=\"modal-footer\">
												<a class=\"btn btn-default\" id=\"Cancelar\" data-dismiss=\"modal\">Cancelar</a>
												<a class=\"btn btn-primary\" id=\"Aceptar\" href=\"\"><i class=\"fa fa-trash-o\"></i>&nbsp;Eliminar</a>
											</div>
										</div>
									</div>
								</div>
						<?php
							}
						?>
							</div>
						<?php
							}
						?>
							<div class=\"panel-footer\" align=\"right\">
								<a class=\"btn btn-danger\" href=\"<?php print substr(\$_SERVER['HTTP_REFERER'],strripos(\$_SERVER['HTTP_REFERER'],\"/\")+1,strlen(\$_SERVER['HTTP_REFERER'])); ?>\" title=\"ir atr&aacute;s\"  data-intro=\"Regresa al formulario anterior\" data-position=\"left\"><i class=\"fa fa-arrow-left\"></i>&nbsp; ir atr&aacute;s</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<span class=\"clearfix\"></span>
			<?php include 'CELAPie.php'; ?>
			<?php include 'CELAJavascript.php'; ?>
			<script>
				\$(document).delegate(\".delete\",\"click\",function(e){
					e.preventDefault();
					var mensaje='';

					if(\$(this).attr(\"id\") != \"Eliminar\"){
						mensaje = \"¿Realmente desea eliminar el elemeto seleccionado?\";
						\$(\"#Aceptar\").attr('href',\$(this).attr('href'));
					}else{
						mensaje='¿Realmente desea eliminar los elementos seleccionados?';
					}

					\$(\"#Body\").html(mensaje);
					\$(\"#DeleteModal\").modal('show');
				});

				\$(document).delegate(\".Select\",\"change\",function(){
					GetAllSelect();
				});

				\$(\"#All\").change(function(){
					\$(\".Select\").each(function(){
						\$(this).prop('checked', \$('#All').is(':checked'));
					});
					GetAllSelect();
				});

				function GetAllSelect(){
					var Get='', cont=0;
					\$(\".Select\").each(function(){
						if(\$(this).is(\":checked\")){
							var id=\$(this).attr(\"id\");
							id=id.split(\"_\");
							Get+='clave[]='+id[1]+'&';
							cont++;
						}
					});

					Get=Get.substring(0, Get.length-1);
					if(Get!=''){
						\$.post(\"ajaxs_functions.php\",{
							funcion: 5,
							Post: Get
						},
						function(data){
							if(cont<2){
								var href=\"#\";
								\$(\"#Actualizar\").attr('disabled','disabled');
								\$(\"#Eliminar\").attr('disabled','disabled');
								\$(\"#Aceptar\").attr('href',href);
							}else{
								\$(\"#Actualizar\").removeAttr('disabled');
								\$(\"#Eliminar\").removeAttr('disabled');

								\$(\"#Actualizar\").attr('href','CelaUsuarioActualizar.php?'+data);
								\$(\"#Aceptar\").attr('href','CelaUsuarioEliminar.php?.php?'+data);
							}
						});
					}else{
						var href=\"#\";
						\$(\"#Actualizar\").attr('disabled','disabled');
						\$(\"#Eliminar\").attr('disabled','disabled');
						\$(\"#Aceptar\").attr('href',href);
					}
				}
			</script>
		</div>
	</body>
</html>" ?>
			</textarea>
		</form>
		<script>
			var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
					mode: "application/x-httpd-php",
					styleActiveLine: true,
					lineNumbers: true,
					lineWrapping: true,
					extraKeys: {"Ctrl-Space": "autocomplete", "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }, "Ctrl-J": "toMatchingTag"},
					autoCloseTags: true,
					foldGutter: true,
					gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter","CodeMirror-lint-markers","breakpoints"],
					highlightSelectionMatches: {showToken: /\w/},
					value: "<html>\n  " + document.documentElement.innerHTML + "\n</html>",
					matchTags: {bothTags: true},
				});
			editor.on("gutterClick", function(cm, n) {
				var info = cm.lineInfo(n);
				cm.setGutterMarker(n, "breakpoints", info.gutterMarkers ? null : makeMarker());
			});
			
			function makeMarker() {
				var marker = document.createElement("div");
				marker.style.color = "#822";
				marker.innerHTML = "●";
				return marker;
			}
    		editor.setSize('99%', '100%');
		</script>
	</body>
</html>