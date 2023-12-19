<?php
//inicia la variable de sesion
if (!isset($_SESSION)) {
	session_start();
}

//verifica la autenticacion del usuario
if ($_SESSION["CELA_Autentificado"] != "SI") {
	header("Location: Salir.php");
	exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Developer 1.0</title>
		
		<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" type="text/css" media="screen" href="elfinder/css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="elfinder/css/theme.css">
		
		<link rel=stylesheet href="codemirror/doc/docs.css">
		<link rel="stylesheet" href="codemirror/addon/hint/show-hint.css">
		<link rel="stylesheet" href="codemirror/lib/codemirror.css">
		<link rel="stylesheet" href="codemirror/addon/fold/foldgutter.css" >
		<link rel="stylesheet" href="codemirror/addon/dialog/dialog.css">

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
			dt {
				font-family: monospace; color: #666;
			}
			.elfinder-dialog{
				top: 0px !important;
			}
		</style>	
	</head>
	<body>
		<div id="elfinder"></div>
	</body>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
	
	<script type="text/javascript" src="elfinder/js/elfinder.min.js"></script>
	<script type="text/javascript" src="elfinder/js/i18n/elfinder.es.js"></script>
	
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
	
	<script type="text/javascript" src="dialog/build/jquery.dialogextend.js"></script>
	
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			var areaEditor=[];
			var last=[];
			var elf = $('#elfinder').elfinder({
				url : 'elfinder/php/connector.php',
				lang: 'es',
				height: $(document).height()-2,
				resizable: false,
				contextmenu : {
					files  : [
						'getfile', '|',
						'edit', 'download', '|',
						'copy', 'cut', 'paste', 'duplicate', '|',
						'rm',  'rename', '|',
						'archive', 'extract'
					]
				},
				uiOptions: {
					toolbar : [
						['home', 'up'],
						['back', 'forward'],
						['reload'],
						['mkdir', 'mkfile', 'upload'],
						['edit', 'rename', 'rm', 'duplicate', 'resize'],
						['download', 'getfile'],
						['copy', 'cut', 'paste'],
						['extract', 'archive'],
						['view']
					]
				},
				commandsOptions: {
				 	edit : {
				 		mimes : ['text/plain', 'text/html', 'text/javascript', 'text/css', 'text/x-php'],
				 		editors : [{
				 			mimes : ['text/plain', 'text/html', 'text/javascript', 'text/css', 'text/x-php'], 
				 			load : function(textarea) {
				 				/*last[textarea.id]=$("#"+textarea.id).dialog({
				 					"resizable" :true,
				 					"draggable" : true
				 				}).dialogExtend({
				 					"closable" : true,
				 					"maximizable" : true,
				 					"minimizable" : true,
				 					"minimizeLocation" : 'left',
				 					"collapsable" : true,
				 					"dblclick" : 'maximize',
				 					"titlebar" : ''
				 				});*/
				 				areaEditor[textarea.id] = CodeMirror.fromTextArea(document.getElementById(textarea.id), {
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
								areaEditor[textarea.id].on("gutterClick", function(cm, n) {
									var info = cm.lineInfo(n);
									cm.setGutterMarker(n, "breakpoints", info.gutterMarkers ? null : makeMarker());
								});
								areaEditor[textarea.id].setSize('100%', $(document).height()-77);
								$(".CodeMirror").css('font-size',"10pt");
				 			},
				 			close : function(textarea) {
				 				areaEditor[textarea.id] = null;
				 				//last[textarea.id].remove();
				 			},
				 			save : function(textarea) {
				 				textarea.value = areaEditor[textarea.id].getValue();
				 				//areaEditor[textarea.id] = null;
				 				//last[textarea.id].dialog('open');
				 			}
				 		}]
				 	}
				 },
				defaultView: 'list',
				rememberLastDir: false,
				docked: false,
				dialog: { width: 400, modal: true },
				closeOnEditorCallback: false
			});
			function makeMarker() {
				var marker = document.createElement("div");
				marker.style.color = "#822";
				marker.innerHTML = "●";
				return marker;
			}
		});
	</script>
</html>
