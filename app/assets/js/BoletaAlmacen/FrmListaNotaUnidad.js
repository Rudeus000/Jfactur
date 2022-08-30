$(document).ready(function(){
	 $('#btnbuscar').click(FrmListaNotaUnidad.Fill);
	 $('#btnnuevo').click(FrmListaNotaUnidad.NewReg);
	 getList(pathController+'/notaunidad/FillAllMotivoRecep','cbo_motivo_recep',{
	 },'TODOS'); 
});
FrmListaNotaUnidad={ 
	Fill:function(){ 
			 $.ajax({
				 url:pathController+'/notaunidad/fillallnotaunidad',
				 type:'post',
				 dataType:'json',
				 data:{
					vp_fecha_notad:$('#t_fecha_notad').val(),
					vp_fecha_notah:$('#t_fecha_notah').val(),
					vp_motivo_recep:$('#cbo_motivo_recep').val(),
					vp_tipo_nota:$('#cbo_tipo_nota').val()
				 },
				 beforeSend:function(){
					 //$.blockUI({ message: '<h2> Buscando datos...</h2>' });	
				 },
				 error: function(jqXHR, exception) { 
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
				 //$.unblockUI();
				 }, 
				 success:function(result){
				 if(result.status==1){ 
					 FrmListaNotaUnidad.PintarDatos(result.data); 
				 }
				 else if(result.status==2){
					 MessageBox(result.msg);
				 }
				 else{
					 FrmListaNotaUnidad.PintarDatos('');
				 }
				 //$.unblockUI();
				 }
			 });
	},//fin Fill
	NewReg:function(){ 
		 location.href=pathController+"/notaunidad/New_notaunidad/"; 
	},//fin NewReg
	Edit:function(id){ 
		 location.href=pathController+"/notaunidad/Findnotaunidad/"+id; 
	},//fin Edit
	Imprimir:function(id){
		location.href=pathController+"/notaunidad/reportePdf/"+id;
	},
	Rmv:function(id){ 
			 if(confirm('Seguro de anular el registro')){
			 $.ajax({
				 url:pathController+'/notaunidad/Rmvnotaunidad',
				 type:'post',
				 dataType:'json',
				 data:{ 
					 vp_id:id
				 },
				 beforeSend:function(){
					 //$.blockUI({ message: '<h2> Eliminando registro...</h2>' });	
				 },
				 error: function(jqXHR, exception) { 
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
				 //$.unblockUI();
				 }, 
				 success:function(result){
				 if(result.status==1){ 
					 $('#tr'+id).remove(); 
				 }
				 else if(result.status==2){
					 MessageBox(result.msg);
				 }
				 else{
					 MessageBox('PROBLEMAS AL EJECUTAR LA TRANSACCION');
				 }
				 //$.unblockUI();
				 }
			 });
			 }
	},//fin Rmv
	PintarDatos:function(data){ 
		 newHtml='';
		 newHtml='<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="tabla">';
		 newHtml+='<thead>';
		 newHtml+='<tr>';
		 newHtml+='<th></th>';
		 //newHtml+='<th>COD_NOTA</th>';
		 newHtml+='<th>Serie</th>';
		 newHtml+='<th>Numero</th>';
		 newHtml+='<th>Tipo</th>';
		 newHtml+='<th>Cliente</th>';
		 newHtml+='<th>Emisión</th>';
		 newHtml+='<th>Motivo</th>';
		 newHtml+='<th>Doc. Ref.</th>';
		 newHtml+='<th>Serie Ref.</th>';
		 newHtml+='<th>Nro. Ref.</th>';
		 newHtml+='</tr>';
		 newHtml+='</thead>';
		 var cont=1;
		 newHtml+='<tbody>';
			 $.each(data,function(key,fila){
				 newHtml+='<tr id="tr'+fila.Cod_Nota+'">';
				 newHtml+='<td><a href="javascript:FrmListaNotaUnidad.Imprimir(\''+fila.Cod_Nota+'\')"><i class="fa fa-print text-success"></i></a></td>';
				 //newHtml+='<td>'+fila.Cod_Nota+'</td>';
				 newHtml+='<td>'+fila.Serie_Nota+'</td>';
				 newHtml+='<td>'+fila.Num_Nota+'</td>';
				 newHtml+='<td>'+fila.Tipo_Nota+'</td>';
				 newHtml+='<td>'+fila.nomb_cliente+'</td>';
				 newHtml+='<td>'+fila.Fecha_Nota+'</td>';
				 newHtml+='<td>'+fila.des_motivo+'</td>';
				 newHtml+='<td>'+fila.nom_tipdocumento+'</td>';
				 newHtml+='<td>'+fila.serie_doc_ref+'</td>';
				 newHtml+='<td>'+fila.num_doc_ref+'</td>';
				 newHtml+='</tr>';
			 });
		 newHtml+='</tbody>';
		 newHtml+='</table>';
	 $('#listado').empty().append(newHtml);
	 oTable=$('#tabla').dataTable();
	}//fin PintarDatos
}//fin clase