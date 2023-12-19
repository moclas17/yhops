/* ---------- Additional functions for data table ---------- */
var oTable=[];
var sQuery=[];
/* Default class modification */
$.extend( $.fn.dataTableExt.oStdClasses, {
	"sWrapper": "dataTables_wrapper form-inline",
	"sFilter": "form-group",
	"sLength": "form-group"
});

/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings ){
	return {
		"iStart": oSettings._iDisplayStart,
		"iEnd": oSettings.fnDisplayEnd(),
		"iLength": oSettings._iDisplayLength,
		"iTotal": oSettings.fnRecordsTotal(),
		"iFilteredTotal": oSettings.fnRecordsDisplay(),
		"iPage": oSettings._iDisplayLength === -1 ? 
			0 : Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
		"iTotalPages": oSettings._iDisplayLength === -1 ?
			0 : Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
	};
};

/* Bootstrap style pagination control */
$.extend( $.fn.dataTableExt.oPagination, {
	"bootstrap": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};

			$(nPaging).append(
				'<ul class="pagination" data-intro="Barra de paginaci&oacute;n" data-position="left">'+
					'<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
					'<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
				'</ul>'
			);
			var els = $('a', nPaging);
			$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
		},
		
		"fnUpdate": function ( oSettings, fnDraw ) {
			var iListLength = 5;
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var an = oSettings.aanFeatures.p;
			var i, ien, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);
			
			if ( oPaging.iTotalPages < iListLength) {
				iStart = 1;
				iEnd = oPaging.iTotalPages;
			}else if ( oPaging.iPage <= iHalf ) {
				iStart = 1;
				iEnd = iListLength;
			} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
				iStart = oPaging.iTotalPages - iListLength + 1;
				iEnd = oPaging.iTotalPages;
			} else {
				iStart = oPaging.iPage - iHalf + 1;
				iEnd = iStart + iListLength - 1;
			}
			
			for ( i=0, ien=an.length ; i<ien ; i++ ) {
				// Remove the middle elements
				$('li:gt(0)', an[i]).filter(':not(:last)').remove();
				
				// Add the new list items and their event handlers
				for ( j=iStart ; j<=iEnd ; j++ ) {
					sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
					$('<li '+sClass+'><a href="#">'+j+'</a></li>')
						.insertBefore( $('li:last', an[i])[0] )
							.bind('click', function (e) {
								e.preventDefault();
								oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
								fnDraw( oSettings );
							} );
				}
				// Add / remove disabled classes from the static elements
				if ( oPaging.iPage === 0 ) {
					$('li:first', an[i]).addClass('disabled');
				} else {
					$('li:first', an[i]).removeClass('disabled');
				}
				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
					$('li:last', an[i]).addClass('disabled');
				} else {
					$('li:last', an[i]).removeClass('disabled');
				}
			}
		}
	}
} );

$(function(){
	/*Cookie menu hidden*/
	if(typeof $.cookie('hiddenMenu') == 'undefined'){
		$.cookie('hiddenMenu',0);
	}
	
	if($.cookie('hiddenMenu') == 1){
		$("#main-container").removeClass('col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2').addClass('col-sm-11 col-md-12');
			$(".sidebar").hide();
	}
	
	if(! $("#nav-container").length){
		$("#toggle-min").hide();	
	}
	
	/* Initialise the DataTable */
	$(".datatable").each(function(){
		var sorting = [];
		var options={};
		var IdTable=$(this).attr("id");
	
		$("#"+IdTable+" thead th").each(function(){
			/* *
			if($(this).hasClass('sortable') && $(this).hasClass('nosearch') && $(this).hasClass('invisible'))
				sorting.push({ 'bSortable': true, 'bSearchable': false, 'bVisible': false });
			else
				if($(this).hasClass('sortable') && $(this).hasClass('nosearch'))
					sorting.push({ 'bSortable': true, 'bSearchable': false });
				else
					if($(this).hasClass('sortable') && !$(this).hasClass('nosearch'))
						sorting.push({ 'bSortable': true, 'bSearchable': true });
					else
						if(!$(this).hasClass('sortable') && $(this).hasClass('nosearch'))
							sorting.push({ 'bSortable': false, 'bSearchable': false });
						else
							sorting.push({ 'bSortable': false, 'bSearchable': true});
			/* */
			
			if($(this).hasClass('sortable'))
				sorting.push(null);
			else
				sorting.push({"bSortable":false});
			/* */
		});
		
		var auxTable = null;

		if(typeof $("#"+IdTable).data("records") == "undefined"){
			auxTable = $("#"+IdTable).dataTable( {
			"sDom": "<'row'<'col-md-12'r>>t<'row col-md-12'<'col-md-6 text-left'i><'col-md-6 text-right'p>>",
			"sPaginationType": "bootstrap",
			"iDisplayLength" : 20,
			"oLanguage": {
				"sZeroRecords": "No se encontrarón registros",
				"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros totales)",
				"sEmptyTable":"No se encontrarón registros",
				"sLoadingRecords":"Cargando...",
				"sProcessing":"<strong>Procesando . . .</strong>",
				"sSearch":"Buscar:&nbsp;",
				"oPaginate":{
					"sFirst":"Primero",
					"sLast":"Ultimo",
					"sNext":"Sig. Pag.",
					"sPrevious":"Pag. Ant."
				}
			},
			"aoColumns": sorting,
		});
		}else{
			auxTable = $("#"+IdTable).dataTable( {
				"sDom": "<'row'<'col-md-12'r>>t<'row col-md-12'<'col-md-6 text-left'i><'col-md-6 text-right'p>>",
				"sPaginationType": "bootstrap",
				"iDisplayLength" : 20,
				"oLanguage": {
					"sZeroRecords": "No se encontrarón registros",
					"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
					"sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
					"sInfoFiltered": "(filtrado de _MAX_ registros totales)",
					"sEmptyTable":"No se encontrarón registros",
					"sLoadingRecords":"Cargando...",
					"sProcessing":"<strong>Procesando . . .</strong>",
					"sSearch":"Buscar:&nbsp;",
					"oPaginate":{
						"sFirst":"Primero",
						"sLast":"Ultimo",
						"sNext":"Sig. Pag.",
						"sPrevious":"Pag. Ant."
					}
				},
				"aoColumns": sorting,
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": "lib/DataTableServer.php",
				"fnServerData": function ( sSource, aoData, fnCallback ) {
					$(".dataTablePrint").attr('disabled','disabled');
					aoData.push({name:'params', value:$("#"+IdTable).data("records")});
					aoData.push({name:'formulario', value:$("#"+IdTable).data("form")});

					$.ajax({
						"dataType": 'json',
						"type": "POST",
						"url": sSource,
						"data": aoData,
						"success": function(data){
							fnCallback(data.aResponse);
							sQuery[IdTable]=data.sQuery;

							if(data.aResponse['iTotalDisplayRecords']==0){
								$(".dataTablePrint").attr('disabled','disabled');
							}else{
								$(".dataTablePrint").removeAttr('disabled');
							}
						},
						error:function(e,i){
							var out="";
							for (var i in e) { out += i + ": " + e[i] + "\n"; }
							console.log(out);
						}
					});
				}
			});
		}
		oTable[IdTable]=(auxTable);
		sQuery[IdTable]="";
	});

	/* Row hover options */
	$("table").delegate(".dataTableRow","mouseover", function(){
		var id=$(this).attr("id");
		$("#Actions_"+id).show();	
	});

	$("table").delegate(".dataTableRow","mouseleave", function(){
		var id=$(this).attr("id");
		$("#Actions_"+id).hide();	
	});

	
	/* Filter filter if use custom filter*/		
	$('.DataTableFilter').each(function(){
		var Id=$(this).attr("id");
		Id=Id.split("-");
		var Tabla=Id[1];
		
		var Boton='<span class="input-group-btn" title="Buscar"><a class="btn btn-default btn-filter" id="btnSearch-'+Tabla+'"><i class="fa fa-search"></i></span></a>';
		
		var $target = $(this).parent();
		var $contend = $(this).parent();

		$contend.attr("align","right");
		
		$target.addClass("input-group");
		$target.append(Boton);
		
		$("#"+Id[0]+"-"+Id[1]).unbind('keyup')
			.bind('keyup', function(e){
				if ( e.keyCode == 13 ){
					$(this).blur();
					oTable[Tabla].fnFilter($(this).val());
				}else{
					return;
				}
		});
	});

	$('.DataTableFilter').focus(function(){
		 $(this).one('mouseup', function(event){
	        event.preventDefault();
	    }).select();
	});
	
	$(".btn-filter").click(function(){
		var Id=$(this).attr("id");
		Id=Id.split("-");
		var Tabla=Id[1];
		
		var $target = $(this).parent().parent().find('input');
		//alert($target.val());
		oTable[Tabla].fnFilter($target.val());
	});

	$('.DataTableFilter').focusin(function(){
		$(this).select();
		 return false;
	});
	
	/* Processing message style */
	$(".dataTables_processing").each(function(){
		var Id=$(this).attr("id");
		Tabla=Id.replace('_processing','');
		$(this).addClass('alert alert-info col-md-10 text-center');

		$(this).css({
			'top': function (){
				return ( ($("#"+Tabla).height()/2) - ($(this).height()/2) + 50) + 'px';
			},
			'left': function (){
				return ( ($("#"+Tabla).width()/2) - ($(this).width()/2) ) + 'px';
			}
		}); 
	});
	
	/* Default export table*/
	$(document).delegate(".dataTablePrint", "click", function(e){
		e.preventDefault();
		$(this).button('loading');
		$(".dataTablePrint").attr('disabled','disabled');
		var $button=$(this);
		var Tabla=$(this).data('table');
		var query=sQuery[Tabla];
		var Data={};
		var Colums="";
		if( typeof (query) == 'undefined')
			return false;
			
		//Obtenemos el encabezado de la tabla.
		$("#"+Tabla+" thead th").each(function(){
			Colums+=($(this).get(0).outerHTML)+'/*/';
		});
		
		//Ponemos el encabezado que llevará la tabla.
		Data['sColum']=Colums;
		//Ponemos la consulta
		Data['sQuery']=query;
		
		//Mandamos los parametros DataTable para el procesamiento de los Datos.
		Data['params']=$("#"+Tabla).data("records");
		
		if( typeof ($(this).data('header')) == 'undefined')
			Data['sHeader']=Tabla;
		else
			Data['sHeader']=$(this).data('header');
			
		if( typeof ($(this).data('extracols')) == 'undefined')
			Data['bExtraCols']=0;
		else
			Data['bExtraCols']=$(this).data('extracols');

	
		if( typeof ($(this).data('mimetype')) == 'undefined'){
			Data['sMimeType']='application/vnd.ms-excel';
			var type='application/vnd.ms-excel';
		}else{
			Data['sMimeType']=$(this).data('mimetype');
			var type=$(this).data('mimetype');
		}
			
		if( typeof ($(this).data('name')) == 'undefined'){
			Data['sFileName']='Exported_Data_Of_Table_'+Tabla;
			var name = 'Exported_Data_Of_Table_'+Tabla;
		}else{
			Data['sFileName']=$(this).data('name');
			var name = $(this).data('name');
		}

		$.post('ajaxs_functions.php',{
			funcion: 8,
			aData: Data
		},
		function(data){
			if(data.status=="OK"){		
				if(type == 'application/vnd.ms-excel' || type == 'application/pdf'){
					$button.after('<iframe id="downloader" src="'+data.sourceFile+'" style="display:none;"></iframe>');
					setTimeout(function(){
						$.post("ajaxs_functions.php", {
							funcion: 2,
							SRCFile: data.tempFile
						}, function(data) {
							if(data=="OK"){
								$("#downloader").remove();
							}
						});
					}, 10000);
				}
				if(type == 'text/html'){
					$button.after('<div id="downloader" style="display:none;"></div>');
					$("#downloader").html(data.contend);
					var options = { mode : 'iframe', popClose : true };
					$("#downloader").printArea( options );	
					setTimeout(function(){
						$("#downloader").remove();
					}, 10000);
				}
				$('.dataTablePrint').button('reset');
				$(".dataTablePrint").removeAttr('disabled');
			}else{
				console.log("Ocurrio un error generando el archivo: "+data.error);
			}
		},'json');
	});

	/* Tools form buttons */
	$('.btn-close').click(function(e){
		e.preventDefault();
		$(this).parent().parent().parent().parent().fadeOut();
	});

	$('.btn-minimize').click(function(e){
		e.preventDefault();
		var $target = $(this).parent().parent().parent().next('.box-content');
		if($target.is(':visible')) 
			$('i',$(this)).removeClass('fa fa-chevron-up').addClass('fa fa-chevron-down');
		else 
			$('i',$(this)).removeClass('fa fa-chevron-down').addClass('fa fa-chevron-up');
		
		$target.slideToggle();
	});

	$(".btn-help").click(function(e){
		e.preventDefault();
		$('body').chardinJs('start');
	});

	/* Trigger combo */
	/*
		example of the structure to create a combo desecandenado
		$Datos= array(
					'cadena1'=>array(
					'tabla'=>'Localidad',
					'campo'=>'Nombre',
					'filtro'=>'IdMunicipio',
					'hijo'=>'Localidad',
					'indice'=>'IdLocalidad'
				),
				//Only if is multiple 
				'cadena2'=>array(
					'tabla'=>'CodigoPostal',
					'campo'=>'concat_ws(" ",CodigoPostal,NombreAsentamiento)',											'filtro'=>'IdMunicipio',
					'hijo'=>'CodigoPostal',
					'indice'=>'IdCodigo'
				)
			);
			//Parent
			<select name="Localidad" id="Localidad" class="form-control e_requerido combo_padre" data-combo_cadena=\''.json_encode($Datos).'\'>
			</select>
			//Children
			<select name="Localidad" id="Localidad" class="form-control e_requerido Localidad">
			</select>
			//Same Children other item
			<select name="Localidad2" id="Localidad2" class="form-control e_requerido Localidad">
			</select>
			//Other Children only if is multiple
			<select name="CodigoPostal" id="CodigoPostal" class="form-control e_requerido CodigoPostal">
			</select>
	*/
	$(document).delegate(".combo_padre","change", function(){
		var id=$(this).attr("id");
		$.each($("#"+id).data("combo_cadena"), function(indice, valor){
			var Table=valor.tabla;
			var Field=valor.campo;
			var Filter=valor.filtro;
			var Son=valor.hijo;
			var Index=valor.indice;
			var Value=$("#"+id).val();
			$("."+Son).each( function(){
				$(this).hide();
				var $content=$(this).parent();
				$content.append('<img id="loading'+id+'" src="bootstrap/img/loading.gif"/>');
			});
			$.post("ajaxs_functions.php",{
				funcion: 4,
				Tabla: Table,
				Campo: Field,
				Filtro: Filter,
				Indice: Index,
				Valor: Value,
				Vacio: function(){
					if(typeof valor.vacio=='undefined' )
						return '';
					else
						return valor.vacio;
				},
				MensajeVacio: function(){
					if(typeof valor.mensajevacio=='undefined' )
						return '';
					else
						return valor.mensajevacio;
				},
				Condicion:function(){
					if(typeof valor.condicion=='undefined' )
						return '';
					else
						return valor.condicion;
				}
			},function(data){
				$("."+Son).each( function(){
					$(this).html(data);
					$("#loading"+id).remove();
					$(this).show();
				});
			});
		});
	});
	
	$(document).delegate('.alert-msn','mouseover', function(){
		$(".alert-msn").stop().animate({opacity:'100'})
	});
	
	$(document).delegate('.alert-msn','mouseleave', function(){
		$(".alert-msn").fadeOut(10000);
	});
	
	/*Menu toogle*/
	$(".menuOption").click(function(){
		if($(this).next("ul").length){
			if($(this).next("ul").is(':visible')){
				$(this).next("ul").slideToggle('fast', function(){
					$(this).closest('li').removeClass('open');
					$(this).next(".fa").removeClass('fa-caret-up').addClass('fa-caret-right');
				});
			}else{
				$(".menuOption").each(function(){
					if($(this).closest('li').hasClass('open')){
						$(this).next("ul").slideToggle('fast', function(){
							$(this).closest('li').removeClass('open');
							$(this).next(".fa").removeClass('fa-caret-up').addClass('fa-caret-right');
						});
					}
				});
				$(this).next("ul").slideToggle('fast', function(){
					$(this).closest('li').addClass('open');
					$(this).next(".fa").removeClass('fa-caret-right').addClass('fa-caret-up');
				});
			}	
		}			
	});

	/*Hide menu bar*/
	var pull = $('.toggle-min');
	var menu = $('.sidebar');
	menuHeight = menu.height();

	$(pull).on('click', function(e) {
		e.preventDefault();
		if(menu.is(':visible')){
			menu.slideUp(0,function(){
				$.cookie('hiddenMenu',1);
				$("#main-container").removeClass('col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10').addClass('col-sm-12 col-md-12');	
			});
		}else{
			$.cookie('hiddenMenu',0);
			$("#main-container").removeClass('col-sm-12 col-md-12').addClass('col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10');
			menu.slideDown(0);
		}
	});

	/*Responsive menu*/
	$(window).resize(function(){
		var w = $(window).width();
		if(w > 320 && menu.is(':hidden') && $.cookie('hiddenMenu') == 0) {
			menu.removeAttr('style');
		}
	});
	
	$('#nav-container').perfectScrollbar({
		suppressScrollX: true
	});
});

$(document).ready(function(){
	if($("#LockModal").length){
		var Transcurrido = 0;
		setInterval(function(){
			Transcurrido++;
			if(Transcurrido==Limite){
				$.post("ajaxs_functions.php",{
					funcion: 6
				},function(data){
					if(data=="OK"){
						$("#LockModal").modal("show");
					}
				});
			}
		},1000);
	}
	$(document).on("click mousemove keypress",function(){
		if($("#LockModal").length){
			Transcurrido = 0;
		}
	});

	$("#UnLock").click(function(){
		UnLockSession();
	});

	$("#txtcontrasena").unbind('keyup').bind('keyup', function(e){
		if ( e.keyCode == 13 ){
			UnLockSession();
		}else{
			return;
		}
	});

	function UnLockSession(){
		if($("#LockModal").length){
			$.post("ajaxs_functions.php",{
				funcion: 7,
				Contrase_na: CryptoJS.MD5($("#txtcontrasena").val()).toString()
			},function(data){
				if(data=="OK"){
					$("#LockModal").modal("hide");
					$("#Message").html("");
					$("#txtcontrasena").val("");
				}else{
					if(data=="ERROR")
						$("#Message").html("Contrase&ntilde;a incorrecta.");
					else
						$("#Message").html('La sesi&oacute;n ha caducado.<br />Presione la tecla F5 <br />&Oacute; haga click <a href="Salir.php" >aqu&iacute;</a> para iniciar.');
				}						
			});	
		}
	}
	$("input:text:visible:first").focus();
});