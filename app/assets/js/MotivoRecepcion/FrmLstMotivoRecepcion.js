$(document).ready(function(){
	 FrmLstMotivoRecepcion.Fill();
	 $('#btnnuevo').click(FrmLstMotivoRecepcion.NewReg);
	 $('#btndelete').click(FrmLstMotivoRecepcion.Rmv);
});
FrmLstMotivoRecepcion={ 
	Fill:function(){ 
			 $.ajax({
				 url:pathController+'/motivorecepcion/fillallmotivorecepcion',
				 type:'post',
				 dataType:'json',
				 data:{ 

				 },
				 beforeSend:function(){
					 //ShowWait();	
				 },
				 error: function(jqXHR, exception) { 
					 if (jqXHR.status === 0) { 
						 /*HideWait(); 
						 ShowMessage('Error','No se pudo conectar a la direccion destino.');*/ 
					 } else if (jqXHR.status == 404) { 
						 //ShowMessage('Error','Pagina no existe'); 
					 } else if (jqXHR.status == 500) { 
						 //ShowMessage('Error','Error interno en el servidor '); 
					 } else if (exception === 'parsererror') { 
						 //ShowMessage('Error','Requested JSON parse failed.'); 
					 } else if (exception === 'timeout') { 
						 //ShowMessage('Error','Fuera de tiempo de espera.'); 
					 } else if (exception === 'abort') { 
						 //ShowMessage('Error','Consulta abortada.'); 
					 } else { 
						 //ShowMessage('Error','Error desconocido: ' + jqXHR.responseText); 
					 } 
				 }, 
				 success:function(result){
				 //HideWait(); 
				 if(result.CodMsg==1){ 
					 FrmLstMotivoRecepcion.PintarDatos(result.Lst); 
				 }
				 else if(result.CodMsg==2){
					 alert(result.Msg);
				 }
				 else{
					 FrmLstMotivoRecepcion.PintarDatos('');
				 }
				 }
			 });
	},//fin Fill
	NewReg:function(){
		 location.href=pathController+"/motivorecepcion/New_motivorecepcion/"; 
	},//fin NewReg
	Edit:function(id){
		 location.href=pathController+"/motivorecepcion/Findmotivorecepcion/"+id; 
	},//fin Edit
	RmvItem:function(id,msg){
		 ShowDeleteModal(id,'Seguro de eliminar  <strong>'+msg+'</strong>?');
	},
	Rmv:function(){
			 HideDeleteModal();
			 $.ajax({
				 url:pathController+'/motivorecepcion/Rmvmotivorecepcion',
				 type:'post',
				 dataType:'json',
				 data:{ 
					 vp_id:$("#txtiddelete").val()
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
					 $('#tr'+$("#txtiddelete").val()).remove(); 
				 }
				 else if(result.CodMsg==2){
					 ShowMessage('Error',result.Msg);
				 }
				 else{
					 ShowMessage('Error','PROBLEMAS AL EJECUTAR LA TRANSACCION');
				 }
				 }
			 });
	},//fin Rmv
	PintarDatos:function(data){ 
		 newHtml='';
		 newHtml='<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="tabla">';
		 newHtml+='<thead>';
		 newHtml+='<tr>';
		 newHtml+='<th></th>';
		 newHtml+='<th>CODIGO</th>';
		 newHtml+='<th>DESCRIPCION</th>';
		 newHtml+='<th>TIPO</th>';
		 newHtml+='<th>TRANSACCION</th>';
		 newHtml+='</tr>';
		 newHtml+='</thead>';
		 var cont=1;
		 newHtml+='<tbody>';
			 $.each(data,function(key,fila){
				 newHtml+='<tr id="tr'+fila.cod_motivo+'">';
				 newHtml+='<td><a href="javascript:FrmLstMotivoRecepcion.Edit(\''+fila.cod_motivo+'\')">Editar</a>&nbsp;&nbsp;<a href="javascript:FrmLstMotivoRecepcion.RmvItem(\''+fila.cod_motivo+'\')">Eliminar</a></td>';
				 newHtml+='<td>'+fila.cod_motivo+'</td>';
				 newHtml+='<td>'+fila.des_motivo+'</td>';
				 newHtml+='<td>'+fila.tipo_operacion+'</td>';
				 newHtml+='<td>'+fila.cod_transaccion+'</td>';
				 newHtml+='</tr>';
			 });
		 newHtml+='</tbody>';
		 newHtml+='</table>';
	 $('#listado').empty().append(newHtml);
	 oTable=$('#tabla').dataTable({"responsive": true, "lengthChange": false, "autoWidth": false}).buttons().container().appendTo('#tabla_wrapper .col-md-6:eq(0)');
	}//fin PintarDatos
}//fin clase