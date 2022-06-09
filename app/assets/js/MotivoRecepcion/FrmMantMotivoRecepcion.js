$(document).ready(function(){
	 $('#btncancel').click(FrmMantMotivoRecepcion.Cancel);
	 $.validator.setDefaults({
	 submitHandler: function () {
	 if($.trim($('#t_codigo').val())==""){
	 FrmMantMotivoRecepcion.Save();
	 }
	 else{
	 FrmMantMotivoRecepcion.Edit();
	 }
	 }
	 });
	$('#frmMant').validate({
	rules: {
	#t_des_motivo: {
	required: true
	},
	#cbo_tipo_operacion: {
	required: true
	},
	#t_cod_transaccion: {
	required: true
	},
	},
	messages: {
	#t_des_motivo: "Ingrese #t_des_motivo",
	#cbo_tipo_operacion: "Ingrese #cbo_tipo_operacion",
	#t_cod_transaccion: "Ingrese #t_cod_transaccion",
	},
	errorElement: 'span',
	errorPlacement: function (error, element) {
	error.addClass('invalid-feedback');
	element.closest('.form-group').append(error);
	},
	highlight: function (element, errorClass, validClass) {
	$(element).addClass('is-invalid');
	},
	unhighlight: function (element, errorClass, validClass) {
	$(element).removeClass('is-invalid');
	}
	});
});
FrmMantMotivoRecepcion={ 
	Cancel:function(){ 
		 location.href=pathController+"/motivorecepcion/frmmotivorecepcion"; 
	}, 
	Save:function(){ 
			//validamos el combo tipo_operacion
			 if(parseInt($('#cbo_tipo_operacion').val())==0){
				 ShowMessage('Error','SELECCIONE tipo_operacion');
			 return false;
			}
			 $.ajax({
				 url:pathController+'/motivorecepcion/Savemotivorecepcion',
				 type:'post',
				 dataType:'json',
				 data:{ 
					vp_des_motivo:$('#t_des_motivo').val(),
					vp_tipo_operacion:$('#cbo_tipo_operacion').val(),
					vp_cod_transaccion:$('#t_cod_transaccion').val()
				 },
				 beforeSend:function(){
					 ShowWait();	
				 },
				 error: function(jqXHR, exception) { 
					 HideWait();
					 if (jqXHR.status === 0) { 
						 ShowMessage('Error','No se pudo conectar a la direccion destino.'); 
					 } else if (jqXHR.status == 404) { 
						 ShowMessage('Error','Pagina no existe'); 
					 } else if (jqXHR.status == 500) { 
						 ShowMessage('Error','Error interno en el servidor '); 
					 } else if (exception === 'parsererror') { 
						 ShowMessage('Error','Requested JSON parse failed.'); 
					 } else if (exception === 'timeout') { 
						 ShowMessage('Error','Fuera de tiempo de espera.'); 
					 } else if (exception === 'abort') { 
						 ShowMessage('Error','Consulta abortada.'); 
					 } else { 
						 ShowMessage('Error','Error desconocido: ' + jqXHR.responseText); 
					 } 
				 }, 
				 success:function(result){
				 HideWait();
				 if(result.CodMsg==1){ 
					 location.href=pathController+"/motivorecepcion/frmmotivorecepcion"; 
				 }
				 else if(result.CodMsg==2){
					 ShowMessage('Error',result.Msg);
				 }
				 else{
					 ShowMessage('Error','PROBLEMAS AL EJECUTAR LA TRANSACCION');
				 }
				 }
			 });
	},//fin save
	Edit:function(){ 
			//validamos el combo tipo_operacion
			 if(parseInt($('#cbo_tipo_operacion').val())==0){
				 ShowMessage('Error','SELECCIONE tipo_operacion');
			 return false;
			}
			 $.ajax({
				 url:pathController+'/motivorecepcion/Editmotivorecepcion',
				 type:'post',
				 dataType:'json',
				 data:{ 
					vp_id:$('#t_codigo').val(),
					vp_des_motivo:$('#t_des_motivo').val(),
					vp_tipo_operacion:$('#cbo_tipo_operacion').val(),
					vp_cod_transaccion:$('#t_cod_transaccion').val()
				 },
				 beforeSend:function(){
					 ShowWait();	
				 },
				 error: function(jqXHR, exception) { 
					 HideWait();	
					 if (jqXHR.status === 0) { 
						 ShowMessage('Error','No se pudo conectar a la direccion destino.'); 
					 } else if (jqXHR.status == 404) { 
						 ShowMessage('Error','Pagina no existe'); 
					 } else if (jqXHR.status == 500) { 
						 ShowMessage('Error','Error interno en el servidor '); 
					 } else if (exception === 'parsererror') { 
						 ShowMessage('Error','Requested JSON parse failed.'); 
					 } else if (exception === 'timeout') { 
						 ShowMessage('Error','Fuera de tiempo de espera.'); 
					 } else if (exception === 'abort') { 
						 ShowMessage('Error','Consulta abortada.'); 
					 } else { 
						 ShowMessage('Error','Error desconocido: ' + jqXHR.responseText); 
					 } 
				 }, 
				 success:function(result){
				 HideWait();	
				 if(result.CodMsg==1){ 
					 location.href=pathController+"/motivorecepcion/frmmotivorecepcion"; 
				 }
				 else if(result.CodMsg==2){
					 ShowMessage('Error',result.Msg);
				 }
				 else{
					 ShowMessage('Error','PROBLEMAS AL EJECUTAR LA TRANSACCION');
				 }
				 }
			 });
	} //fin update
}//fin clase