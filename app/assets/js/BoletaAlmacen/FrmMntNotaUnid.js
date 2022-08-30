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
				if($('#t_tipo_nota').val()=="S"){
					FrmMntNotaUnid.obtenerSeriesProducto(selectedItemValue.id);				
				}				
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
	obtenerSeriesProducto:function(producto)	
	{		
		var almacen = $('#cbo_cod_almacen').val();
		$.get(path+"administrador/regventas/getSeriesProducto", {producto,almacen},
			function (res, textStatus, jqXHR) {
					$('#t_cnro_lote').empty().trigger("change");
					$('#t_cnro_lote').select2({
						data:res	
					});
					$('#t_cnro_lote').prop('disabled',false);

				if(res.length == 0){
					/*$('input[name=serieCheckProducto]').prop('checked', false);
					$('input[name=serieCheckProducto]').prop('disabled', true);
					$('input[name=cantidadProducto]').prop('disabled', false);*/
					$('#t_cnro_lote').prop('disabled', true);
					$('#t_nund').prop('disabled', false);
				}else{
					/*$('input[name=serieCheckProducto]').prop('checked', true);
					$('input[name=serieCheckProducto]').prop('disabled', false);
					$('input[name=cantidadProducto]').prop('disabled', true);*/
					$('#t_cnro_lote').prop('disabled', false);
					$('#t_nund').prop('disabled', true);
					document.getElementsByName("t_bind_lote")[0].checked=true;
				}
				},
				"JSON"
			);
	},
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
	 listar_cbo_Serie_Nota:function(){ 
		 getList(pathController+'/notaunidad/ListarSerieNotaUnid/'+$('#cbo_cod_almacen').val(),'cbo_serie_nota',{
		 },{ 
		 finish:function(){  }
		 });
	},
	ProxCorrelativo:function(){ 
			 $.ajax({
				 url:pathController+'/notaunidad/ProxCorrelativoUnidad',
				 type:'post',
				 dataType:'json',
				 data:{
					vp_almacen:$('#cbo_cod_almacen').val(),
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
	},
	
	 listar_cbo_Cod_Almacen:function(value){ 
		 getList(pathController+'/notaunidad/ListarAlmacenes','cbo_cod_almacen',{
		 },{ 
		 finish:function(){ $('#cbo_cod_almacen').val(value); }
		 });
	},
	 listar_cbo_tip_doc_ref:function(value){ 
		 getList(pathController+'/notaunidad/ListarTipoDocumento','cbo_tip_doc_ref',{
		 },{ 
		 finish:function(){ $('#cbo_tip_doc_ref').val(value); }
		 });
	},
	Cancel:function(){ 
		 location.href=pathController+"/notaunidad"; 
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
				 url:pathController+'/notaunidad/Cargadet_ALM_Kardex',
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
			 
			 eslote="N";
			 if(document.getElementsByName("t_bind_lote")[0].checked){
				eslote="S"; 
			 }		
			if(eslote=="S" && $.trim($('#t_tipo_nota').val())=="S"){
				
			}
			else{
				if($.trim($('#t_nund').val())==""){
					MessageBox('Ingrese la cantidad');
					return;
				}
			} 
			if(eslote=="S" && $.trim($('#t_tipo_nota').val())=="I"){
				var cantidad = parseInt($('#t_nund').val());
				$('#inputSeries').empty();
				for (i = 1; i <= cantidad; i++) {
					var serie = '<div class="col-md-6"><div class="form-group"><label class="control-label">Serie '+i+'</label><input type="text" name="serie[]"  class="form-control" validate></div></div>';
					$('#inputSeries').append(serie);
				}
				$('#ModalSeries').modal();	
			}	
			else{	
			 $.ajax({
				 url:pathController+'/notaunidad/Adddet_ALM_Kardex',
				 type:'post',
				 dataType:'json',
				 data:{ 
					vp_nund:$('#t_nund').val(),
					vp_ccod_undmed:$('#t_ccod_undmed').val(),
					vp_ccod_art:$('#t_ccod_art').val(),
					vp_cdsc_art:$('#t_cdsc_art').val(),
					vp_bind_lote:eslote,
					vp_cnro_lote:$('#t_cnro_lote').val(),
					vp_serie:''
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
						 $('#t_ccod_art').val('');
						 $('#t_cnro_lote').val('');
					 }
					 else if(result.status==2){
						 MessageBox(result.msg);
					 }
					 else{
						 MessageBox('NO SE PUDO REGISTRAR');
					 }
				 }
			 });
			}
	},//fin save
	AddDet_ALM_KardexSerie:function(){ 
			 //alert($("#form1").serialize());
			 $.ajax({
				 url:pathController+'/notaunidad/Adddet_ALM_Kardex',
				 type:'post',
				 dataType:'json',
				 data:{ 
					vp_nund:$('#t_nund').val(),
					vp_ccod_undmed:$('#t_ccod_undmed').val(),
					vp_ccod_art:$('#t_ccod_art').val(),
					vp_cdsc_art:$('#t_cdsc_art').val(),
					vp_bind_lote:eslote,
					vp_cnro_lote:$('#t_cnro_lote').val(),
					vp_serie:$("#form2 input").serialize()//$('#serie').val()
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
						 $('#ModalSeries').modal('hide');
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
						 $('#cbo_cod_almacen').val(result.cod_almacen);
						 $('#t_fecha_nota').val(result.fecha_emision);
						 FrmMntNotaUnid.PintarDatosdet_ALM_Kardex(result.data); 						 
					 }
					 else if(result.status==2){
						 MessageBox(result.msg);
					 }
					 else{
						 MessageBox('No se encontro el documento');
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
		 /*newHtml+='<th>CCOD_EJE</th>';
		 newHtml+='<th>CCOD_PER</th>';
		 newHtml+='<th>CCOD_ALM</th>';
		 newHtml+='<th>CTIPO_MOV</th>';
		 newHtml+='<th>CCOD_OPER_LOG</th>';
		 newHtml+='<th>CDOC_SERIE</th>';
		 newHtml+='<th>CDOC_NRO</th>';
		 newHtml+='<th>DDOC_FCH</th>';*/
		 newHtml+='<th>Codigo</th>';
		 newHtml+='<th>Descripcion</th>';
		 //newHtml+='<th>Unidad</th>';
		 newHtml+='<th>Cantidad</th>';
		 newHtml+='<th>Es Lote</th>';
		 newHtml+='<th>Lote</th>';
		 
		 
		 /*newHtml+='<th>CCOD_MON</th>';
		 newHtml+='<th>NT_CAMBIO</th>';
		 newHtml+='<th>NCOS_UA_MOF</th>';
		 newHtml+='<th>NCOS_T_MOF</th>';
		 newHtml+='<th>CREF_DOC</th>';
		 newHtml+='<th>CREF_SER</th>';
		 newHtml+='<th>CREF_NRO</th>';*/
		 
		 /*newHtml+='<th>CREF_DOC2</th>';
		 newHtml+='<th>CREF_SER2</th>';
		 newHtml+='<th>CREF_NRO2</th>';
		 newHtml+='<th>COD_NOTA</th>';*/
		 newHtml+='</tr>';
		 newHtml+='</thead>';
		 var cont=1;
		 newHtml+='<tbody>';
			 $.each(data,function(key,fila){
				 newHtml+='<tr id="tr'+key+'">';
				 newHtml+='<td><a href="javascript:FrmMntNotaUnid.RmvDet_ALM_Kardex(\''+key+'\')">Eliminar</a></td>';
				 /*newHtml+='<td>'+fila.ccod_eje+'</td>';
				 newHtml+='<td>'+fila.ccod_per+'</td>';
				 newHtml+='<td>'+fila.ccod_alm+'</td>';
				 newHtml+='<td>'+fila.ctipo_mov+'</td>';
				 newHtml+='<td>'+fila.ccod_oper_log+'</td>';
				 newHtml+='<td>'+fila.cdoc_serie+'</td>';
				 newHtml+='<td>'+fila.cdoc_nro+'</td>';
				 newHtml+='<td>'+fila.ddoc_fch+'</td>';*/
				 newHtml+='<td>'+fila.ccod_art+'</td>';
				 newHtml+='<td>'+fila.cdsc_art+'</td>';
				 //newHtml+='<td>'+fila.ccod_undmed+'</td>';
				 newHtml+='<td>'+fila.nund+'</td>';
				 newHtml+='<td>'+fila.bind_lote+'</td>';
				 newHtml+='<td>';
					 newHtml+='<table>';
					 $.each(fila.series,function(key_serie,fila_serie){
						 newHtml+='<tr><td>'+fila_serie+'&nbsp;&nbsp;</td><td><button onclick="javascript:FrmMntNotaUnid.RmvDet_ALM_KardexSerie(\''+key+'\',\''+fila_serie+'\')" type="button" class="btn btn-danger btn-sm delete-serie"><i class="fa fa-trash"></i></button></td></tr>';
					 });
					 newHtml+='</table>';
				 newHtml+='</td>';
				 /*newHtml+='<td>'+fila.ccod_mon+'</td>';
				 newHtml+='<td>'+fila.nt_cambio+'</td>';
				 newHtml+='<td>'+fila.ncos_ua_mof+'</td>';
				 newHtml+='<td>'+fila.ncos_t_mof+'</td>';
				 newHtml+='<td>'+fila.cref_doc+'</td>';
				 newHtml+='<td>'+fila.cref_ser+'</td>';
				 newHtml+='<td>'+fila.cref_nro+'</td>';*/
				 
				 /*newHtml+='<td>'+fila.cref_doc2+'</td>';
				 newHtml+='<td>'+fila.cref_ser2+'</td>';
				 newHtml+='<td>'+fila.cref_nro2+'</td>';
				 newHtml+='<td>'+fila.cod_nota+'</td>';*/
				 newHtml+='</tr>';
			 });
		 newHtml+='</tbody>';
		 newHtml+='</table>';
	 $('#listado_det_ALM_Kardex').empty().append(newHtml);
	 oTable=$('#tabla_ALM_Kardex').dataTable();
	},//fin PintarDatos
	RmvDet_ALM_Kardex:function(id){ 
			 $.ajax({
				 url:pathController+'/notaunidad/Rmvdet_ALM_Kardex',
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
	RmvDet_ALM_KardexSerie:function(id,serie){ 
			 $.ajax({
				 url:pathController+'/notaunidad/Rmvdet_ALM_KardexSerie',
				 type:'post',
				 dataType:'json',
				 data:{ 
					vp_id:id,
					vp_serie:serie 					
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
				 url:pathController+'/notaunidad/Savenotaunidad',
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
					vp_num_doc_ref:$('#t_num_doc_ref').val()
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
					 location.href=pathController+"/notaunidad"; 
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
		 
	},//fin save
	Edit:function(){ 
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
				 url:pathController+'/notaunidad/Editnotaunidad',
				 type:'post',
				 dataType:'json',
				 data:{ 
					vp_id:$('#t_codigo').val(),
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
					vp_num_doc_ref:$('#t_num_doc_ref').val()
				 },
				 beforeSend:function(){
					 //$.blockUI({ message: '<h2> Actualizando datos...</h2>' });	
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
					 MessageBox('SE ACTUALIZO  SATISFACTORIAMENTE'); 
					 location.href=pathController+"/administrador/notaunidad"; 
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
		 
	} //fin update
}//fin clase