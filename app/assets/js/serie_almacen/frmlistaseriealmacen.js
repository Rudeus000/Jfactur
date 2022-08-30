$(document).ready(function(){
	 frmlistaseriealmacen.Fill();
	 $('#btnnuevo').click(frmlistaseriealmacen.NewReg);
	 $('#btndelete').click(frmlistaseriealmacen.Rmv);
});
frmlistaseriealmacen={ 
	Fill:function(){ 
			 $.ajax({
				 url:pathController+'/serie_almacen/fillallseriealmacen',
				 type:'post',
				 dataType:'json',
				 data:{ 

				 },
				 beforeSend:function(){
					 //ShowWait();	
				 },
				 error: function(jqXHR, exception) { 
					 if (jqXHR.status === 0) { 
						 HideWait(); 
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
				 if(result.status==1){ 
					 frmlistaseriealmacen.PintarDatos(result.data); 
				 }
				 else if(result.status==2){
					 MessageBox(result.Msg);
				 }
				 else{
					 frmlistaseriealmacen.PintarDatos('');
				 }
				 }
			 });
	},//fin Fill
	NewReg:function(){ 
		 location.href=pathController+"/serie_almacen/New_serie_almacen/"; 
	},//fin NewReg
	Edit:function(id){ 
		 location.href=pathController+"/serie_almacen/Findserie_almacen/"+id; 
	},//fin Edit
	RmvItem:function(id){
		 //ShowDeleteModal(id,'Seguro de eliminar  <strong>'+msg+'</strong>?');
		Swal.fire({
		  title: 'Estas seguro de eliminar?',
		  text: "¡No podrás revertir esto!",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Si, eliminar!'
		}).then((result) => {
			console.log(result.value);
		  if (result.value) {
			frmlistaseriealmacen.Rmv(id);
		  }
		})
	},
	Rmv:function(id){ 
			 //HideDeleteModal();
			 $.ajax({
				 url:pathController+'/serie_almacen/Rmvserie_almacen',
				 type:'post',
				 dataType:'json',
				 data:{ 
					 vp_id:id
				 },
				 beforeSend:function(){
					 //ShowWait();
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
				 //HideWait();
				 if(result.CodMsg==1){ 
					 $('#tr'+id).remove(); 
				 }
				 else if(result.CodMsg==2){
					 MessageBox(result.Msg);
				 }
				 else{
					 MessageBox('PROBLEMAS AL EJECUTAR LA TRANSACCION');
				 }
				 }
			 });
	},//fin Rmv
	PintarDatos:function(data){ 
		 newHtml='';
		 newHtml='<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="tabla">';
		 newHtml+='<thead>';
		 newHtml+='<tr>';		 
		 newHtml+='<th>ID</th>';
		 newHtml+='<th>ALMACEN</th>';
		 newHtml+='<th>TIPO DOCUMENTO</th>';
		 newHtml+='<th>SERIE</th>';
		 newHtml+='<th>CORRELATIVO</th>';
		 newHtml+='<th>ACCION</th>';
		 newHtml+='</tr>';
		 newHtml+='</thead>';
		 var cont=1;
		 newHtml+='<tbody>';
			 $.each(data,function(key,fila){
				 newHtml+='<tr id="tr'+fila.IDAlmacenSerie+'">';				 
				 newHtml+='<td>'+fila.IDAlmacenSerie+'</td>';
				 newHtml+='<td>'+fila.cod_almacen+'</td>';
				 newHtml+='<td>'+fila.TipoDoc+'</td>';
				 newHtml+='<td>'+fila.Serie+'</td>';
				 newHtml+='<td>'+fila.Correlativo+'</td>';
				 newHtml+='<td><a href="javascript:frmlistaseriealmacen.Edit(\''+fila.IDAlmacenSerie+'\')"><i class="fas fa-edit text-success"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:frmlistaseriealmacen.RmvItem(\''+fila.IDAlmacenSerie+'\')"><i class="fas fa-trash-alt text-pink"></i></a></td>';
				 newHtml+='</tr>';
			 });
		 newHtml+='</tbody>';
		 newHtml+='</table>';
	 $('#listado').empty().append(newHtml);
	 oTable=$('#tabla').dataTable({"responsive": true, "lengthChange": false, "autoWidth": false}).buttons().container().appendTo('#tabla_wrapper .col-md-6:eq(0)');
	}//fin PintarDatos
}//fin clase