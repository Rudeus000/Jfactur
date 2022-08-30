$(document).ready(function(){
	 $('#btncancel').click(FrmMantSeriaAlmacen.Cancel);
	 $('#btnsave').click(FrmMantSeriaAlmacen.Register);	 
});
FrmMantSeriaAlmacen={ 
	listar_cbo_cod_almacen:function(value){ 
		 getList(pathController+'/serie_almacen/FillAllAlmacen','cbo_cod_almacen',{
		 },{ 
		 finish:function(){ $('#cbo_cod_almacen').val(value); }
		 });
	},
	Register:function(){
		if($.trim($('#t_codigo').val())==""){
			 FrmMantSeriaAlmacen.Save();
		}
		else{
			 FrmMantSeriaAlmacen.Edit();
		}
	},
	Cancel:function(){ 
		 location.href=pathController+"/serie_almacen"; 
	}, 
	Save:function(){ 
			//validamos el combo cod_almacen
			if(parseInt($('#cbo_cod_almacen').val())==0){
				 MessageBox('Seleccione el almacen');
			 return false;
			}
			if($.trim($('#t_serie').val())==""){
				 MessageBox('Ingrese el numero de serie');
			 return false;
			}
			if($.trim($('#t_correlativo').val())==""){
				 MessageBox('Ingrese el numero correlativo');
			 return false;
			}
			 $.ajax({
				 url:pathController+'/serie_almacen/Saveserie_almacen',
				 type:'post',
				 dataType:'json',
				 data:{ 
					vp_cod_almacen:$('#cbo_cod_almacen').val(),
					vp_tipodoc:$('#cbo_tipodoc').val(),
					vp_serie:$('#t_serie').val(),
					vp_correlativo:$('#t_correlativo').val()
				 },
				 beforeSend:function(){
					 //ShowWait();	
				 },
				 error: function(jqXHR, exception) { 
					 //HideWait();
					 if (jqXHR.status === 0) { 
						 MessageBox('No se pudo conectar a la direccion destino.'); 
					 } else if (jqXHR.status == 404) { 
						 MessageBox('Pagina no existe'); 
					 } else if (jqXHR.status == 500) { 
						 MessageBox('Error interno en el servidor '); 
					 } else if (exception === 'parsererror') { 
						 MessageBox('Requested JSON parse failed.'); 
					 } else if (exception === 'timeout') { 
						 MessageBox('Fuera de tiempo de espera.'); 
					 } else if (exception === 'abort') { 
						 MessageBox('Consulta abortada.'); 
					 } else { 
						 MessageBox('Error desconocido: ' + jqXHR.responseText); 
					 } 
				 }, 
				 success:function(result){
				 //HideWait();
				 if(result.status==1){ 
					 location.href=pathController+"/serie_almacen"; 
				 }
				 else if(result.status==2){
					 MessageBox(result.Msg);
				 }
				 else{
					 MessageBox('PROBLEMAS AL EJECUTAR LA TRANSACCION');
				 }
				 }
			 });
	},//fin save
	Edit:function(){ 
			//validamos el combo cod_almacen
			 if(parseInt($('#cbo_cod_almacen').val())==0){
				 MessageBox('SELECCIONE cod_almacen');
			 return false;
			}
			//validamos el combo tipodoc
			 if(parseInt($('#cbo_tipodoc').val())==0){
				 MessageBox('SELECCIONE tipodoc');
			 return false;
			}
			 $.ajax({
				 url:pathController+'/serie_almacen/Editserie_almacen',
				 type:'post',
				 dataType:'json',
				 data:{ 
					vp_id:$('#t_codigo').val(),
					vp_cod_almacen:$('#cbo_cod_almacen').val(),
					vp_tipodoc:$('#cbo_tipodoc').val(),
					vp_serie:$('#t_serie').val(),
					vp_correlativo:$('#t_correlativo').val()
				 },
				 beforeSend:function(){
					 ShowWait();	
				 },
				 error: function(jqXHR, exception) { 
					 HideWait();	
					 if (jqXHR.status === 0) { 
						 MessageBox('No se pudo conectar a la direccion destino.'); 
					 } else if (jqXHR.status == 404) { 
						 MessageBox('Pagina no existe'); 
					 } else if (jqXHR.status == 500) { 
						 MessageBox('Error interno en el servidor '); 
					 } else if (exception === 'parsererror') { 
						 MessageBox('Requested JSON parse failed.'); 
					 } else if (exception === 'timeout') { 
						 MessageBox('Fuera de tiempo de espera.'); 
					 } else if (exception === 'abort') { 
						 MessageBox('Consulta abortada.'); 
					 } else { 
						 MessageBox('Error desconocido: ' + jqXHR.responseText); 
					 } 
				 }, 
				 success:function(result){
				 HideWait();	
				 if(result.CodMsg==1){ 
					 location.href=pathController+"/serie_almacen/frmserie_almacen"; 
				 }
				 else if(result.CodMsg==2){
					 MessageBox(result.Msg);
				 }
				 else{
					 MessageBox('PROBLEMAS AL EJECUTAR LA TRANSACCION');
				 }
				 }
			 });
	} //fin update
}//fin clase