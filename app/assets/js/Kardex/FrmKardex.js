$(document).ready(function(){
	 $('#btnbuscar').click(FrmKardex.FillKardexUnd);
	 $('#btnexportar').click(FrmKardex.ExportarExcel);
	 $('#btnbuscarvalorizado').click(FrmKardex.FillKardexValorizado);
	 $('#btnexportarvalorizado').click(FrmKardex.ExportarExcelValorizado);
	 
	 $('#btncierre').click(function(){
		 Swal.fire({
  title: 'Esta seguro?',
  text: "Cerrar kardex unidades mensual!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si, cerrar!'
}).then((result) => {
	console.log(result.value);
  if (result.value) {
    FrmKardex.CierreKardex();
  }
})
		 
	 });//
	 $('#btnconsulta').click(FrmKardex.ConsultarKardex);
	 
	 
//para el kardex valorizado
	 $('#btncierrevalorizado').click(function(){
		 Swal.fire({
  title: 'Esta seguro?',
  text: "Cerrar kardex valorizado mensual!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si, cerrar!'
}).then((result) => {
	console.log(result.value);
  if (result.value) {
    FrmKardex.CierreKardexValorizado();
  }
})
		 
	 });//
	 $('#btnconsultavalorizado').click(FrmKardex.ConsultarKardexValorizado);
	 
	 
	 
	 
	$("#txtprod").easyAutocomplete({
		minCharNumber: 2,
		url: function (query) {
			return path + "administrador/notaunidad/getProductoBusqueda?producto=" + query
		},
		getValue: function (element) {
			return element.nombre;
		},
		requestDelay: 500,
		list: {
			onSelectItemEvent: function () {
				var selectedItemValue = $("#txtprod").getSelectedItemData();				
				$('#txtidprod').val(selectedItemValue.id);				
			},			
		}
	});
});
FrmKardex={ 
	ExportarExcel:function(){
		fecha=$('#fecha').val();	
		location.href=pathController+"/notaunidad/ExportKdxUnd/"+fecha; 	
	},
	ExportarExcelValorizado:function(){
		fecha=$('#fecha').val();	
		location.href=pathController+"/notavalorizado/ExportKdxVal/"+fecha; 	
	},
	CierreKardex:function(){
			
			 $.ajax({
				 url:pathController+'/kardex/CierreKdxUnd',
				 type:'post',
				 dataType:'json',
				 data:{
					CodProd:$('#txtidprod').val(),
					fecha:$('#fecha').val(),
					almacen:$('#almacen').val()
				 },
				 beforeSend:function(){
					 //$.blockUI({ message: '<h2> Buscando datos...</h2>' });
					$('#listado').empty().append("<h2>Realizando el cierre. Espero por favor....</h2>");					 
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
					$('#listado').empty().append("<h2>"+result.msg+"</h2>");					 
				 //$.unblockUI();
				 }
			 });
	},//fin Fill
	ConsultarKardex:function(){			
			 $.ajax({
				 url:pathController+'/kardex/consultakardexunid',
				 type:'post',
				 dataType:'json',
				 data:{ 
					CodProd:$('#txtidprod').val(),
					fecha:$('#fecha').val(),
					almacen:$('#almacen').val()
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
					 FrmKardex.PintarDatosConsulta(result.data); 
				 }
				 else if(result.status==2){
					 MessageBox(result.msg);
				 }
				 else{
					 FrmKardex.PintarDatosConsulta('');
				 }
				 //$.unblockUI();
				 }
			 });
	},//fin Fill
	PintarDatosConsulta:function(data){ 
		 newHtml='';
		 newHtml='<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="tabla">';
		 newHtml+='<thead>';
		 newHtml+='<tr>';
		 newHtml+='<th>Almacén</th>';
		 newHtml+='<th>Código</th>';
		 newHtml+='<th>Descripción</th>';
		 newHtml+='<th>Unidad</th>';
		 newHtml+='<th>Cantidad</th>';		 
		 newHtml+='</tr>';
		 newHtml+='</thead>';
		 var cont=1;
		 newHtml+='<tbody>';
		$.each(data,function(key,fila){
			newHtml+='<tr>';				 
			newHtml+='<td>'+fila.nomb_almacen+'</td>';
			newHtml+='<td>'+fila.cod_producto+'</td>';
			newHtml+='<td>'+fila.nomb_product+'</td>';
			newHtml+='<td align="center">'+fila.nomb_tipunidad+'</td>';
			newHtml+='<td align="right">'+fila.nund_tot+'</td>';
			newHtml+='</tr>';
		});
		 newHtml+='</tbody>';
		 newHtml+='</table>';
	 $('#listado').empty().append(newHtml);
	 oTable=$('#tabla').dataTable();
	},//fin PintarDatos
	FillKardexUnd:function(){
			if($.trim($('#txtidprod').val())==""){
				MessageBox("Seleccione un producto");
				return;
			}
			 $.ajax({
				 url:pathController+'/notaunidad/fillkardexunid',
				 type:'post',
				 dataType:'json',
				 data:{ 
					CodProd:$('#txtidprod').val(),
					fecha:$('#fecha').val(),
					almacen:$('#almacen').val()
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
					 FrmKardex.PintarDatos(result.data); 
				 }
				 else if(result.status==2){
					 MessageBox(result.msg);
				 }
				 else{
					 FrmKardex.PintarDatos('');
				 }
				 //$.unblockUI();
				 }
			 });
	},//fin Fill
	PintarDatos:function(data){ 
		 newHtml='';
		 newHtml='<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="tabla">';
		 newHtml+='<thead>';
		 newHtml+='<tr>';
		 newHtml+='<th>Fecha</th>';
		 newHtml+='<th>Boleta</th>';
		 newHtml+='<th>Referencia</th>';
		 newHtml+='<th>Nro. Referencia</th>';
		 newHtml+='<th>Operación</th>';
		 newHtml+='<th>Ingreso</th>';
		 newHtml+='<th>Salida</th>';
		 newHtml+='<th>Saldo</th>';
		 newHtml+='</tr>';
		 newHtml+='</thead>';
		 var cont=1;
		 newHtml+='<tbody>';
			 $.each(data,function(key,fila){
				 newHtml+='<tr>';				 
				 newHtml+='<td colspan="8" align="left">'+key+'</td>';
				 
				 newHtml+='</tr>';
				$.each(fila,function(key_f,fila_d){
					newHtml+='<tr>';				 
					newHtml+='<td  colspan="8" align="left">'+key_f+'</td>';
					
					newHtml+='</tr>';
					$.each(fila_d,function(key_data,fila_data){
						newHtml+='<tr>';				 
						newHtml+='<td>'+fila_data.fecha+'</td>';
						newHtml+='<td>'+fila_data.boleta+'</td>';
						newHtml+='<td>'+fila_data.referencia+'</td>';
						newHtml+='<td>'+fila_data.nroreferencia+'</td>';
						newHtml+='<td>'+fila_data.operacion+'</td>';
						newHtml+='<td align="right">'+fila_data.ingreso+'</td>';
						newHtml+='<td align="right">'+fila_data.salida+'</td>';
						newHtml+='<td align="right">'+fila_data.saldo+'</td>';
						newHtml+='</tr>';
					});
				});					
			 });
		 newHtml+='</tbody>';
		 newHtml+='</table>';
	 $('#listado').empty().append(newHtml);
	 oTable=$('#tabla').dataTable();
	},//fin PintarDatos
	FillKardexValorizado:function(){
			if($.trim($('#txtidprod').val())==""){
				MessageBox("Seleccione un producto");
				return;
			}
			 $.ajax({
				 url:pathController+'/notavalorizado/fillkardexvalorizado',
				 type:'post',
				 dataType:'json',
				 data:{ 
					CodProd:$('#txtidprod').val(),
					fecha:$('#fecha').val(),
					almacen:$('#almacen').val()
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
					 FrmKardex.PintarDatosValorizado(result.data); 
				 }
				 else if(result.status==2){
					 MessageBox(result.msg);
				 }
				 else{
					 FrmKardex.PintarDatosValorizado('');
				 }
				 //$.unblockUI();
				 }
			 });
	},//fin Fill
	PintarDatosValorizado:function(data){ 
		 newHtml='';
		 newHtml='<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="tabla">';
		 newHtml+='<thead>';
		 newHtml+='<tr>';
		 newHtml+='<th rowspan="2">Fecha</th>';
		 newHtml+='<th rowspan="2">Boleta</th>';
		 newHtml+='<th rowspan="2">Referencia</th>';
		 newHtml+='<th rowspan="2">Nro. Referencia</th>';
		 newHtml+='<th rowspan="2">Operación</th>';
		 newHtml+='<th colspan="3">Ingreso</th>';
		 newHtml+='<th colspan="3">Salida</th>';
		 newHtml+='<th colspan="3">Saldo</th>';
		 newHtml+='</tr>';
		 newHtml+='<tr>';
		 
		 newHtml+='<th>Cantidad</th>';
		 newHtml+='<th>Costo</th>';
		 newHtml+='<th>Total</th>';
		 
		 newHtml+='<th>Cantidad</th>';
		 newHtml+='<th>Costo</th>';
		 newHtml+='<th>Total</th>';
		
		 newHtml+='<th>Cantidad</th>';
		 newHtml+='<th>Costo</th>';
		 newHtml+='<th>Total</th>';
		 newHtml+='</tr>';
		 newHtml+='</thead>';
		 var cont=1;
		 newHtml+='<tbody>';
			 $.each(data,function(key,fila){
				 newHtml+='<tr>';				 
				 newHtml+='<td colspan="14" align="left">'+key+'</td>';
				 
				 newHtml+='</tr>';
				$.each(fila,function(key_f,fila_d){
					newHtml+='<tr>';				 
					newHtml+='<td  colspan="14" align="left">'+key_f+'</td>';
					
					newHtml+='</tr>';
					$.each(fila_d,function(key_data,fila_data){
						newHtml+='<tr>';
						newHtml+='<td>'+fila_data.fecha+'</td>';
						newHtml+='<td>'+fila_data.boleta+'</td>';
						newHtml+='<td>'+fila_data.referencia+'</td>';
						newHtml+='<td>'+fila_data.nroreferencia+'</td>';
						newHtml+='<td>'+fila_data.operacion+'</td>';
						newHtml+='<td align="right">'+fila_data.cantingreso+'</td>';
						newHtml+='<td align="right">'+fila_data.costoingreso+'</td>';
						newHtml+='<td align="right">'+fila_data.totalingreso+'</td>';
						newHtml+='<td align="right">'+fila_data.cantsalida+'</td>';
						newHtml+='<td align="right">'+fila_data.costosalida+'</td>';
						newHtml+='<td align="right">'+fila_data.totalsalida+'</td>';
						newHtml+='<td align="right">'+fila_data.cntsaldo+'</td>';
						newHtml+='<td align="right">'+fila_data.costosaldo+'</td>';
						newHtml+='<td align="right">'+fila_data.totalsaldo+'</td>';
						newHtml+='</tr>';
					});
				});					
			 });
		 newHtml+='</tbody>';
		 newHtml+='</table>';
	 $('#listado').empty().append(newHtml);
	 oTable=$('#tabla').dataTable();
	},//fin PintarDatos
	//cierre kardex valorizado
	CierreKardexValorizado:function(){
			
			 $.ajax({
				 url:pathController+'/kardex/CierreKdxValorizado',
				 type:'post',
				 dataType:'json',
				 data:{
					CodProd:$('#txtidprod').val(),
					fecha:$('#fecha').val(),
					almacen:$('#almacen').val()
				 },
				 beforeSend:function(){
					 //$.blockUI({ message: '<h2> Buscando datos...</h2>' });
					$('#listado').empty().append("<h2>Realizando el cierre valorizado. Espero por favor....</h2>");					 
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
					$('#listado').empty().append("<h2>"+result.msg+"</h2>");					 
				 //$.unblockUI();
				 }
			 });
	},//fin Fill
	ConsultarKardexValorizado:function(){			
			 $.ajax({
				 url:pathController+'/kardex/consultakardexvalorizado',
				 type:'post',
				 dataType:'json',
				 data:{ 
					CodProd:$('#txtidprod').val(),
					fecha:$('#fecha').val(),
					almacen:$('#almacen').val()
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
					 FrmKardex.PintarDatosConsultaValorizado(result.data); 
				 }
				 else if(result.status==2){
					 MessageBox(result.msg);
				 }
				 else{
					 FrmKardex.PintarDatosConsultaValorizado('');
				 }
				 //$.unblockUI();
				 }
			 });
	},//fin Fill
	PintarDatosConsultaValorizado:function(data){ 
		 newHtml='';
		 newHtml='<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="tabla">';
		 newHtml+='<thead>';
		 newHtml+='<tr>';
		 newHtml+='<th>Almacén</th>';
		 newHtml+='<th>Código</th>';
		 newHtml+='<th>Descripción</th>';
		 newHtml+='<th>Unidad</th>';
		 newHtml+='<th>Cantidad</th>';		 
		 newHtml+='<th>Costo</th>';
		 newHtml+='<th>Total</th>';
		 newHtml+='</tr>';
		 newHtml+='</thead>';
		 var cont=1;
		 newHtml+='<tbody>';
		$.each(data,function(key,fila){
			newHtml+='<tr>';				 
			newHtml+='<td>'+fila.nomb_almacen+'</td>';
			newHtml+='<td>'+fila.cod_producto+'</td>';
			newHtml+='<td>'+fila.nomb_product+'</td>';
			newHtml+='<td align="center">'+fila.nomb_tipunidad+'</td>';
			newHtml+='<td align="right">'+fila.nund_tot+'</td>';
			newHtml+='<td align="right">'+fila.ncosto+'</td>';
			newHtml+='<td align="right">'+fila.totalvalorizado+'</td>';
			newHtml+='</tr>';
		});
		 newHtml+='</tbody>';
		 newHtml+='</table>';
	 $('#listado').empty().append(newHtml);
	 oTable=$('#tabla').dataTable();
	},//fin PintarDatos
}//fin clase