var oForm=[];
$(document).ready(function(){
	$('.form_validate').each(function(){
		var IdForm=$(this).attr("id");
		var auxForm = null;
		auxForm = $("#"+IdForm).validate( {
			//debug: true,
			highlight: function(element){
				var idElement = $(element).attr('id');

				$("#success_"+idElement).remove();
				$("#error_"+idElement).remove();
				
				$(element).closest('.group-validate').removeClass('has-success has-feedback').addClass('has-error has-feedback');
				$(element).closest('.validate').append('<span id="error_'+idElement+'" class="fa fa-times fa-lg form-control-feedback"></span>');
			},
			errorPlacement: function(error, element) {
				var idElement = $(element).attr('id');
				
				$(element).tooltip({
					'template': '<div class="tooltip tooltip-me" id="tooltip_'+idElement+'" role="tooltip">'+
									'<div class="tooltip-arrow" ></div>'+
									'<div class="tooltip-inner tooltip-inner-me"></div>'+
								'</div>',
					'placement':'bottom',
					'title': $(error).html(),
					'trigger': 'manual'
				});

				$(element).tooltip('show');
			},
			unhighlight: function(element) {
				var idElement = $(element).attr('id');

				$("#error_"+idElement).remove();
				$("#success_"+idElement).remove();
				
				$(element).closest('.group-validate').removeClass('has-error has-feedback').addClass('has-success has-feedback');
				$(element).closest('.validate').append('<span id="success_'+idElement+'" class="fa fa-check fa-lg form-control-feedback"></span>');
				$(element).next('label').remove();

				$(element).tooltip('destroy');
			},
			submitHandler: function(form) {
				$(".Save").button('loading');
				form.submit();
			},
			invalidHandler: function(event, validator) {
				$(".Save").button('reset');
			}/*,
			onclick: function(element, event ){
				if ( event.which === 9 && $(element).val() === "" ) {
					return;
				} else if ( element.name in this.submitted || element === this.lastElement ) {
					this.element(element);
				}
				
				if (this.checkForm()) { // checks form for validity
					$(".Save").removeAttr('disabled');        // enables button
				} else {
					$(".Save").attr('disabled','disabled');   // disables button
				}
			},
			onfocusout: function(element, event ){
				if ( event.which === 9 && $(element).val() === "" ) {
					return;
				} else if ( element.name in this.submitted || element === this.lastElement ) {
					this.element(element);
				}
				
				if (this.checkForm()) { // checks form for validity
					$(".Save").removeAttr('disabled');        // enables button
				} else {
					$(".Save").attr('disabled','disabled');   // disables button
				}
			},
			onkeyup: function(element, event ){
				if ( event.which === 9 && $(element).val() === "" ) {
					return;
				} else if ( element.name in this.submitted || element === this.lastElement ) {
					this.element(element);
				}
				
				if (this.checkForm()) { // checks form for validity
					$(".Save").removeAttr('disabled');        // enables button
				} else {
					$(".Save").attr('disabled','disabled');   // disables button
				}
			}*/
		});
		oForm[IdForm]=(auxForm);
		$(".Save").removeAttr('disabled');
	});
			
	jQuery.validator.addMethod(
		"lettersonly",
		function(value, element) {
			return this.optional(element) || /^[a-zA-Z.\xc1\xc9\xcd\xd3\xda\xe1\xe9\xed\xd1\xf1\xf3\xfa\s]+$/i.test(value);
		}, 
		"Letters only please"
	);
	jQuery.validator.addMethod(
		"user",
		function(value, element) {
			return this.optional(element) || /^[0-9a-zA-Z.\x5f]+$/i.test(value);
		}, 
		"Use a valid user name"
	);
	jQuery.validator.addMethod(
		"ipaddress",
		function(value, element) {
			return this.optional(element) || /^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/i.test(value);
		}, 
		"Use a valid ipaddress"
	);
	jQuery.validator.addMethod(
		"alphanumeric",
		function(value, element) {
			return this.optional(element) || /^[0-9A-Z\xc1\xc9\xcd\xd3\xda\xe1\xe9\xed\xf3\xfa\s]+$/i.test(value);
		}, 
		"alphanumeric only please"
	);
		
	jQuery.validator.addMethod(
		"positive",
		function(value, element) {
			return this.optional(element) || /^[0-9]{1,11}$/i.test(value);
		}, 
		"positive number are required"
	);
		
	jQuery.validator.addMethod(
		"valid_date",
		function(value, element){
			var date=new Date();
			var y=date.getFullYear();
			var ms=date.getMonth()+1;
			ms<10?ms="0"+ms:ms=ms;
			var d=date.getDate();
			d<10?d="0"+d:d=d;
			var h=date.getHours()+2;
			var m=date.getMinutes();
			var s=date.getSeconds();
			
			var format=""+y+"-"+ms+"-"+d+" "+h+":"+m+":"+s+"";
			var today = $.datepicker.formatDate(format, new Date());
			var select_date= $.datepicker.formatDate(value, new Date());
			var valid=true;
			if (select_date <= today)
				valid= false;
				return valid;
		},
		"Select a date greater"
	);
	
	jQuery.validator.addMethod(
		"valid_size",
		function(value, element){
			var file = $(element)[0].files[0];
		    if(typeof(file) != 'undefined'){
		        var fileSize = (file.size*1)/1048576;
		        var maxSize = $(element).data('size');
		        var flotante = parseFloat(fileSize);
		        var resultado = Math.round(flotante*Math.pow(10,5))/Math.pow(10,5);
		        if(resultado<=maxSize)
		        	return true;
		        else
		        	return false;
		     }else{
		     	return true;
		     }
		},
		"Select a valid size file"
	);
	
	jQuery.validator.addMethod(
		"valid_repetido",
		function(value, element){
			var actual = value;
			var valid=true;
			$(element).removeClass('unico');
  			$(".unico").each(function(){
  				//console.log(actual+":::"+value);
  				if(actual == $(this).val() ){
  					//console.log('repetido');
  					valid=false;
  					return;
  				}
  			});  				
			$(element).addClass('unico');
			return valid;
		},
		"Select a valid size file"
	);
	
	$.validator.addMethod(
		'required_one',
		function(value, element) {
			var maxCheck;
			var classGroup;
			if(typeof ($(element).data('max-check')) == 'undefined')
		 		maxCheck = 100;
		 	else
		 		maxCheck = $(element).data('max-check');
		 		
		 	if(typeof ($(element).data('class-group')) == 'undefined')
		 		classGroup = 'e_grupo';
		 	else
		 		classGroup = $(element).data('class-group');
		 	
			return ($('.'+classGroup+':checked').size() > 0 && $('.'+classGroup+':checked').size() <= maxCheck? true:false);
    	},
    	'Check an option.'
    );
	
	
	$(".e_unico").each(function (item) {
		$(this).rules("add", {
			valid_repetido: true,
			messages: { 
				valid_repetido: "El orden esta repetido"
			}
		});
	});
	
	jQuery.validator.addMethod(
		"valid_repetido",
		function(value, element){
			var actual = value;
			var valid=true;
			$(element).removeClass('unico');
  			$(".unico").each(function(){
  				//console.log(actual+":::"+value);
  				if(actual == $(this).val() ){
  					//console.log('repetido');
  					valid=false;
  					return;
  				}
  			});  				
			$(element).addClass('unico');
			return valid;
		},
		"Select a valid size file"
	);
	
	$.validator.addMethod(
		'required_one',
		function(value, element) {
			var maxCheck;
			var classGroup;
			if(typeof ($(element).data('max-check')) == 'undefined')
		 		maxCheck = 100;
		 	else
		 		maxCheck = $(element).data('max-check');
		 		
		 	if(typeof ($(element).data('class-group')) == 'undefined')
		 		classGroup = 'e_grupo';
		 	else
		 		classGroup = $(element).data('class-group');
		 	
			return ($('.'+classGroup+':checked').size() > 0 && $('.'+classGroup+':checked').size() <= maxCheck? true:false);
    	},
    	'Check an option.'
    );
	
	
	$(".e_unico").each(function (item) {
		$(this).rules("add", {
			valid_repetido: true,
			messages: { 
				valid_repetido: "El orden esta repetido"
			}
		});
	});
	
	
	$(".e_nombre").each(function (item) {
		$(this).rules("add", {
			minlength:3,
			lettersonly: true,
			messages: { 
				minlength: "Introduzaca mas de 3 caracteres",
				lettersonly: "Deben ser solo letras"
			}
		});
	});
	
	$(".e_requerido").each(function (item) {
		$(this).rules("add", {
			required: true,
			messages: { 
				required: "Campo requerido" 
			}
		});
	});
	
	$(".e_correo").each(function (item) {
		$(this).rules("add", {
			email: true,
			messages: { 
				email: "Introduzca una direcci&oacute;n correcta ejem: nombre@prueba.com"
			}	
		});	
	});
	
	$(".e_usuario").each(function (item) {
		$(this).rules("add", {
			user: true,
			messages: { 
				user: "Utilice un nombre de usuario sin caracteres especiales ni espacios"
			}	
		});	
	});
	$(".e_ip").each(function (item) {
		$(this).rules("add", {
			ipaddress: true,
			messages: { 
				ipaddress: "El valor debe tener un formato xxx.xxx.xxx.xxx"
			}	
		});	
	});
	
	$(".e_direccion").each(function (item) {
		$(this).rules("add", {
			minlength: 10,
			messages: { 
				minlength: "Necesita almenos 10 caracteres"
			}	
		});	
	});
	
	$(".e_telefono").each(function (item) {
		$(this).rules("add", {
			minlength: 10,
			number: true,
			messages: { 
				minlength: "Tel&eacute;fono no v&aacute;lido, almenos 10 caracteres",
				number: "N&uacute;mero de t&eacute;lefono no v&aacute;lido"
			}	
		});	
	});
	
	$(".e_texto").each( function(item){
		$(this).rules("add", {
			lettersonly: true,
			messages: {
				lettersonly: "Solo texto plano"
			}		
		});
	});
	
	$(".e_alfa_numerico").each( function(item){
		$(this).rules("add", {
			alphanumeric: true,
			minlength:5,
			messages: {
				alphanumeric: "Solo texto alfa-n&uacute;merico"
			}		
		});
	});
	
	$(".e_numero").each( function(item){
		//console.log($(this).attr('id'));
		$(this).rules("add", {
			number: true,
			messages: {
				number: "Solo n&uacute;meros",
			}		
		});
	});
	
	$(".e_grupo").each( function(item){
		var maxCheck;
		if(typeof ($(this).data('max-check')) == 'undefined')
		 	maxCheck = 100;
		 else
		 	maxCheck = $(this).data('max-check');
		 	
		$(this).rules("add", {
			required_one: true,
			messages: {
				required_one: "Seleccione entre un una y "+maxCheck+" opciones.",
			}		
		});
	});
	
	$(".e_combo").each( function(item){
		$(this).rules("add", {
			positive: true,
			messages: {
				positive: "Seleccione una Opci&oacute;n"
			}		
		});
	});
	
	$(".e_solo_si").each( function(item){
		var check=$(this).data("check");
		var flag=$(this).data("flag");
		$(this).rules("add", {
			required: function(){
					if(flag==true){
						return "#"+check+":not(:checked)";
					}else{
						return "#"+check+":checked"
					}
			},
			messages: { 
				required: "Campo requerido"
			}	
		});
	});
	
	$(".e_positivo").each( function(item){
		$(this).rules("add", {
			positive: true,
			messages: {
				positive: "Se requiere un n&uacute;mero positivo"
			}		
		});
	});
	$(".e_url").each( function(item){
		$(this).rules("add", {
			url: true,
			messages: {
				url: "Se requiere una URL valida que inicie con http://"
			}		
		});
	});
	
	$(".e_remoto").each( function(item){
		var id=$(this).attr("id");
		$(this).rules("add", {
			remote: {
				url: "ajaxs_functions.php",
				type: "post",
				data: {
					Value: function(){
						return $("#"+id).val();
					},
					Table: function(){
						return $("#"+id).data("remote").tabla;
					},
					Field: function(){
						return $("#"+id).data("remote").campo;
					},
					funcion: 3,
					Response: function(){
						if(typeof $("#"+id).data("remote").response == 'undefined'){
							return 'false';
						}else{
							return $("#"+id).data("remote").response;
						}
					},
					And: function(){
						if(typeof $("#"+id).data("remote").condition == 'undefined'){
							return '';
						}else{
							return $("#"+id).data("remote").condition;
						}
					}
				}
			},
			messages:{
				remote: function(){
					if(typeof $("#"+id).data("remote").message == 'undefined'){
						return "Este valor ya existe, intente con algun otro";
					}else{
						return $("#"+id).data("remote").message;
					}
				}
			}
		});
	});
	
	$(".e_fecha").each( function(item){
		$(this).rules("add", {
			minlength: 10,
			date: true,
			messages: {
				date: "Formato no v&aacute;lido: yyyy-mm-dd",
				minlength: "Minimo 10 caracteres"
			}		
		});
	});
	
	$(".e_fecha_hora").each( function(item){
		$(this).rules("add", {
			minlength: 19,
			messages: {
				date: "Formato no v&aacute;lido: yyyy-mm-dd 00:00:00",
				minlength: "Minimo 19 caracteres",
			}		
		});
	});
	
	$(".e_fecha_hora_mas").each( function(item){
		$(this).rules("add", {
			minlength: 19,
			valid_date: true,
			messages: {
				date: "Formato no v&aacute;lido: yyyy-mm-dd 00:00:00",
				minlength: "Minimo 19 caracteres",
				valid_date: "Seleccione una fecha pr&oacute;xima"
			}		
		});
	});
	
	$(".e_igual").each(function(){
		var id=$(this).attr("id");
		$(this).rules("add", {
			equalTo: "#"+$("#"+id).data('igual_a'),
			messages: {
				equalTo: "El valor no coincide con el de "+$("#"+id).data('igual_a')
			}
		});
	});
	
	$(".e_rango").each(function(){
		var id=$(this).attr("id");
		$(this).rules("add",{
			range: [$("#"+id).data('rango').minimo,$("#"+id).data('rango').maximo],
			messages:{
				range:$("#"+id).data('rango').mensaje
			}
		});
	});
	
	$(".e_longitud").each(function(){
		var id=$(this).attr("id");
		$(this).rules("add",{
			rangelength: [$("#"+id).data('rango').minimo,$("#"+id).data('rango').maximo],
			messages:{
				rangelength:$("#"+id).data('rango').mensaje
			}
		});
	});
	
	$(".e_archivo").each(function(){
        var id=$(this).attr("id");
        $(this).rules("add",{
            extension: $("#"+id).data('extension'),
            valid_size: true,
            messages:{
                extension: "Seleccione un archivo con extensi&oacute;n "+$("#"+id).data('extension'),
                valid_size: "Seleccione un archivo no mayor a "+$("#"+id).data('size')+" MB de tama&ntilde;o"
            }
        });
    });
	
	$( ".e_fecha_hora" ).each( function(){
		var id=$(this).attr("id");
		$(this).datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'HH:mm:ss',
			monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Augosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
			dayNamesShort: ["Dom","Lun","Mar","Mie","Jue","Vie","Sab"],
			dayNames: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
			dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
			currentText: "Ahora",
			closeText: "Listo",
			timeText: 'Hora Seleccionada',
				hourText: 'Hora',
				minuteText: 'Minuto',
				secondText: 'Segundo',
			prevText: "Anterior",
			nextText: "Siguiente",
			changeMonth: true,
			changeYear: true,
			minDate: $("#"+id).data('rango').minimo,
			maxDate: $("#"+id).data('rango').maximo,
			firstDay: 1,
			yearRange: '1900:2999'
		});	
	});
	
	$(".e_fecha").each( function(){
		var id=$(this).attr("id");
		$(this).datepicker({
			monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Augosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
			dayNamesShort: ["Dom","Lun","Mar","Mie","Jue","Vie","Sab"],
			dayNames: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
			dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
			dateFormat: "yy-mm-dd",
			prevText: "Anterior",
			nextText: "Siguiente",
			changeMonth: true,
			changeYear: true,
			minDate: $("#"+id).data('rango').minimo,
			maxDate: $("#"+id).data('rango').maximo,
			firstDay: 1,
			yearRange: '1900:2999'
		});	
	});
	
	$(".e_hora").each( function(){
		var id=$(this).attr("id");
		var minimo=$("#"+id).data('rango').minimo;
		var maximo=$("#"+id).data('rango').maximo;
		minimo=minimo.split(':');
		maximo=maximo.split(':');
		$(this).timepicker({
			timeFormat: "HH:mm:ss",
			currentText: "Ahora",
			closeText: "Listo",
			timeOnlyTitle: 'Selecciona la Hora',
			hourText: 'Hora',
			minuteText: 'Minuto',
			secondText: 'Segundo',
			hourMin: parseInt(minimo[0]),
			minuteMin: parseInt(minimo[1]),
			secondMin: parseInt(minimo[2]),
			hourMax: parseInt(maximo[0]),
			minuteMax: parseInt(maximo[1]),
			secondMax: parseInt(maximo[2])
		});
	});
}); // end document.ready