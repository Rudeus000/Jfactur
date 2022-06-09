$(document).ready(function(){
	 $('#btnsave').click(FrmMntNotaUnid.Register);
	 $('#btncancel').click(FrmMntNotaUnid.Cancel);
	 $('#btnadddet_ALM_Kardex').click(FrmMntNotaUnid.AddDet_ALM_Kardex);
	 $("#t_cdsc_art").easyAutocomplete({
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
				var selectedItemValue = $("#t_cdsc_art").getSelectedItemData();				
				$('#t_ccod_art').val(selectedItemValue.id);				
				$('#t_ccod_undmed').val(selectedItemValue.codund);				
			},			
		}
	});
	
	 $("#t_nom_cliente").easyAutocomplete({
		minCharNumber: 2,
		url: function (query) {
			return path + "administrador/notaunidad/ListSearchEntities?entidad=" + query
		},
		getValue: function (element) {
			return element.nombre;
		},
		requestDelay: 500,
		list: {
			onSelectItemEvent: function () {
				var selectedItemValue = $("#t_nom_cliente").getSelectedItemData();				
				$('#t_ruc_cliente').val(selectedItemValue.codigo);				
			},			
		}
	});
});

FrmMntNotaUnid={ 
	TipoMovimiento:function(){
		x=$('#cbo_motivo_recep').val();
		if(x==1 || x==2 || x==3 || x==8 || x==11 || x==12 || x==13 || x==19 || x==20 || x==21 || x==22) {
			$('#t_tipo_nota').val('I');
		}
		else{
			$('#t_tipo_nota').val('S');
		}
	},
	CheckLote:function(obj){
		if(obj.checked){
			$('#t_cnro_lote').attr('readonly',false);
		}
		else{
			$('#t_cnro_lote').val('');
			$('#t_cnro_lote').attr('readonly',true);
		}
	},
	listar_cbo_Serie_NotaVal:function(){ 
		 getList(pathController+'/notaunidad/ListarSerieNotaVal','cbo_serie_nota',{
		 },{ 
		 finish:function(){  }
		 });
	},
	ProxCorrelativoVal:function(){ 
			 $.ajax({
				 url:pathController+'/notaunidad/ProxCorrelativoVal',
				 type:'post',
				 dataType:'json',
				 data:{
					vp_serie:$('#cbo_serie_nota').val()
				 },
				 beforeSend:function(){
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
				 }, 
				 success:function(result){
				 if(result.status==1){  
					 $('#t_num_nota').val(result.prox);					 
				 }
				 else if(result.status==2){
					 MessageBox(result.msg);
				 }
				 else{
					 MessageBox('NO SE PUDO CARGAR EL DETALLE');
				 }
				 }
			 });
	},
	listar_cbo_Motivo_Recep:function(value){ 		 
		 getList(pathController+'/notaunidad/FillAllMotivoRecep','cbo_motivo_recep',{
		 },{ 
		 finish:function(){ $('#cbo_motivo_recep').val(value); }
		 }); 
	},/*
	
	listar_cbo_Cod_Almacen:function(value){ 
		 getList(pathController+'/notaunidad/ListarAlmacenes','cbo_cod_almacen',{
		 },{ 
		 finish:function(){ $('#cbo_cod_almacen').val(value); }
		 });
	},*/
	 listar_cbo_tip_doc_ref:function(value){ 
		 getList(pathController+'/notaunidad/ListarTipoDocumento','cbo_tip_doc_ref',{
		 },{ 
		 finish:function(){ $('#cbo_tip_doc_ref').val(value); }
		 });
	},
	Cancel:function(){ 
		 location.href=pathController+"/notavalorizado"; 
	}, 
	Register:function(){ 
		if($.trim($('#t_codigo').val())==""){
			 FrmMntNotaUnid.Save();
		}
		else{
			 FrmMntNotaUnid.Edit();
		}
	},
	CargaDet_ALM_Kardex:function(){ 
			 $.ajax({
				 url:pathController+'/notavalorizado/Cargadet_ALM_Kardex',
				 type:'post',
				 dataType:'json',
				 data:{ 
				 },
				 beforeSend:function(){
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
				 }, 
				 success:function(result){
				 if(result.status==1){ 
					 FrmMntNotaUnid.PintarDatosdet_ALM_Kardex(result.data); 
				 }
				 else if(result.status==2){
					 MessageBox(result.msg);
				 }
				 else{
					 MessageBox('NO SE PUDO CARGAR EL DETALLE');
				 }
				 }
			 });
	},//fin Cargadet
	AddDet_ALM_Kardex:function(){
			 if($.trim($('#t_ccod_art').val())==""){
				 MessageBox('Ingrese el codigo de articulo');
							return;
			 }
			 if($.trim($('#t_nund').val())==""){
				 MessageBox('Ingrese la cantidad');
				return;
			 }
			 if($.trim($('#t_costo').val())==""){
				 MessageBox('Ingrese el costo');
				return;
			 }
			 eslote="N";
			 if(document.getElementsByName("t_bind_lote")[0].checked){
				eslote="S"; 
			 }
			 $.ajax({
				 url:pathController+'/notavalorizado/Adddet_ALM_Kardex',
				 type:'post',
				 dataType:'json',
				 data:{ 
					
					vp_nund:$('#t_nund').val(),
					vp_ccod_undmed:$('#t_ccod_undmed').val(),
					vp_ccod_art:$('#t_ccod_art').val(),
					vp_cdsc_art:$('#t_cdsc_art').val(),
					vp_costo:$('#t_costo').val(),					
					vp_bind_lote:eslote,
					vp_cnro_lote:$('#t_cnro_lote').val()					
				 },
				 beforeSend:function(){
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
				 }, 
				 success:function(result){
					 if(result.status==1){
						 FrmMntNotaUnid.PintarDatosdet_ALM_Kardex(result.data); 
					 }
					 else if(result.status==2){
						 MessageBox(result.msg);
					 }
					 else{
						 MessageBox('NO SE PUDO REGISTRAR');
					 }
				 }
			 });
		 
	},//fin save
	FindByDocRefNum:function(){ 
			 if($.trim($('#cbo_tip_doc_ref').val())=="00"){
				 MessageBox('Seleccione el documento de referencia');
							return;
			 }
			 if($.trim($('#t_num_doc_ref').val())==""){
				 MessageBox('Ingrese el numero de documento de referencia');
				return;
			 }
			 if($.trim($('#t_ruc_cliente').val())==""){
				 MessageBox('Seleccione la entidad');
				return;
			 }
			 
			 $.ajax({
				 url:pathController+'/notaunidad/FindDocRefNum',
				 type:'post',
				 dataType:'json',
				 data:{ 			
					vp_motivo_recep:$('#cbo_motivo_recep').val(),
					vp_tip_doc_ref:$('#cbo_tip_doc_ref').val(),
					vp_serie_doc_ref:$('#t_serie_doc_ref').val(),
					vp_num_doc_ref:$('#t_num_doc_ref').val(),
					vp_num_ruc:$('#t_ruc_cliente').val()
				 },
				 beforeSend:function(){
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
				 }, 
				 success:function(result){
					 if(result.status==1){
						 //$('#cbo_cod_almacen').val(result.cod_almacen);
						 $('#t_fecha_nota').val(result.fecha_emision);
						 FrmMntNotaUnid.PintarDatosdet_ALM_Kardex(result.data); 
						 //indicamos si es ingreso o salida
						 switch ($('#cbo_motivo_recep').val()) {
						  case 1:
						  case 2:
						  case 3:
						  case 8:
						  case 11:
						  case 12:
						  case 13:
						  case 19:
						  case 20:
						  case 21:
						  case 22:
							$('#t_tipo_nota').val('I');
							break;
						  default:
							$('#t_tipo_nota').val('S');
						}
					 }
					 else if(result.status==2){
						 MessageBox(result.msg);
					 }
					 else{
						 MessageBox('NO SE PUDO REGISTRAR');
					 }
				 }
			 });
		 
	},//fin save
	PintarDatosdet_ALM_Kardex:function(data){ 
		 newHtml='';
		 newHtml='<table class="table table-striped table-bordered" cellspacing="0"  width="100%"  id="tabla_ALM_Kardex">';
		 newHtml+='<thead>';
		 newHtml+='<tr>';
		 newHtml+='<th></th>';
		 newHtml+='<th>Codigo</th>';
		 newHtml+='<th>Descripcion</th>';
		 newHtml+='<th>Cantidad</th>';
		 newHtml+='<th>Costo</th>';
		 newHtml+='<th>Es Lote</th>';
		 newHtml+='<th>Lote</th>';
		 newHtml+='<th>Total</th>';
		 newHtml+='</tr>';
		 newHtml+='</thead>';
		 var cont=1;
		 newHtml+='<tbody>';
			 $.each(data,function(key,fila){
				 newHtml+='<tr id="tr'+key+'">';
				 newHtml+='<td><a href="javascript:FrmMntNotaUnid.RmvDet_ALM_Kardex(\''+key+'\')">Eliminar</a></td>';
				 
				 newHtml+='<td>'+fila.ccod_art+'</td>';
				 newHtml+='<td>'+fila.cdsc_art+'</td>';
				 newHtml+='<td>'+fila.nund+'</td>';
				 newHtml+='<td>'+fila.ncosto+'</td>';
				 newHtml+='<td>'+fila.bind_lote+'</td>';
				 newHtml+='<td>'+fila.cnro_lote+'</td>';
				 newHtml+='<td>'+fila.nsubtotal+'</td>';
				 
				 newHtml+='</tr>';
			 });
		 newHtml+='</tbody>';
		 newHtml+='</table>';
	 $('#listado_det_ALM_Kardex').empty().append(newHtml);
	 oTable=$('#tabla_ALM_Kardex').dataTable();
	},//fin PintarDatos
	RmvDet_ALM_Kardex:function(id){ 
			 $.ajax({
				 url:pathController+'/notavalorizado/Rmvdet_ALM_Kardex',
				 type:'post',
				 dataType:'json',
				 data:{ 
					vp_id:id 
				 },
				 beforeSend:function(){
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
				 }, 
				 success:function(result){
				 if(result.status==1){ 
					 FrmMntNotaUnid.PintarDatosdet_ALM_Kardex(result.data) 
				 }
				 else if(result.status==2){
					 MessageBox(result.msg);
				 }
				 else{
					 MessageBox('PROBLEMAS AL EJECUTAR LA TRANSACCION');
				 }
				 }
			 });
	},//fin RmvDet
	Save:function(){ 
			if($.trim($('#cbo_serie_nota').val())==""){
				MessageBox('Seleccione la serie de la boleta','Advertencia!','error');
				return;
			}
			if(parseInt($('#cbo_motivo_recep').val())==0){
				MessageBox('Seleccione la motivo de recepcion','Advertencia!','error');
				return;
			}
			if(parseInt($('#cbo_cod_almacen').val())==0){
				MessageBox('Seleccione el almacen','Advertencia!','error');
				return;
			}
			if(parseInt($('#cbo_tip_doc_ref').val())==0){
				MessageBox('Seleccione el documento de referencia','Advertencia!','error');
				return;
			}
			 
			 $.ajax({
				 url:pathController+'/notavalorizado/Savenotavalorizado',
				 type:'post',
				 dataType:'json',
				 data:{ 
					vp_serie_nota:$('#cbo_serie_nota').val(),
					vp_num_nota:$('#t_num_nota').val(),
					vp_tipo_nota:$('#t_tipo_nota').val(),
					vp_ruc_cliente:$('#t_ruc_cliente').val(),
					vp_fecha_nota:$('#t_fecha_nota').val(),
					vp_motivo_recep:$('#cbo_motivo_recep').val(),
					vp_obs_nota:$('#t_obs_nota').val(),
					vp_cod_almacen:$('#cbo_cod_almacen').val(),
					vp_tip_doc_ref:$('#cbo_tip_doc_ref').val(),
					vp_serie_doc_ref:$('#t_serie_doc_ref').val(),
					vp_num_doc_ref:$('#t_num_doc_ref').val(),
					vp_cbo_moneda:$('#cbo_moneda').val(),
					vp_tipocambio:$('#t_tipocambio').val()
				 },
				 beforeSend:function(){
					 //$.blockUI({ message: '<h2> Guardando datos...</h2>' });	
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
					 MessageBox('SE GRABO  SATISFACTORIAMENTE','','success'); 
					 location.href=pathController+"/notavalorizado"; 
				 }
				 else if(result.status==2){
					 //MessageBox(result.msg);
				 }
				 else{
					 MessageBox('PROBLEMAS AL EJECUTAR LA TRANSACCION');
				 }
				 //$.unblockUI();
				 }
			 });
		 
	}
}//fin clase