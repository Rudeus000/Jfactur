<!-- Begin page -->
<script>
var pathController="<?php echo base_url();?>administrador";
</script>
<script type="text/javascript" src="<?php echo base_url_app();?>assets/js/BoletaAlmacen/FrmMntNotaUnid.js?v=<?php echo rand(0,5000);?>"></script>
        <div id="wrapper">

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                
                <div class="content">
                    <div class="container-fluid">
                   
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <!-- <h4 class="page-title float-left">Almacen</h4> -->
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="#">Boleta almacen</a></li>
                                        <li class="breadcrumb-item active">Mantenimiento</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                <div class="card-header bg-success"><h3 class="my-0 text-white">Boleta almacen <a class="btn btn-rounded btn-pink float-right" id="btnnuevo" tabindex="0" aria-controls="datatable-buttons"><i class="fas fa-plus m-r-5"></i><span>Agregar</span></a> </h3></div>
                                    <div class="card-body table-responsive">
					 <!--<form id="FormComprasFiltro" action="" method="post" autocomplete="off">				-->
					<input type="hidden" id="t_codigo" value="<?php echo ($update==1?$id:'')?>" /> 			
                  <div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="cod_almacen">Almacen</label>
							<select id="cbo_cod_almacen" onchange="javascript:FrmMntNotaUnid.listar_cbo_Serie_Nota();" class="form-control"></select>
						</div>
					</div>
					<div class="col-md-3">
						<!--<div class="form-group">
							<label for="tipo_nota">Tipo</label>
							<select id="t_tipo_nota" class="form-control">
								<option value="I">Ingreso</option>
								<option value="S">Salida</option>
							</select>
						</div>-->
						<div class="form-group">
							<label for="motivo_recep">Motivo Recepción</label>
							<select id="cbo_motivo_recep" onchange="javascript:FrmMntNotaUnid.TipoMovimiento()" class="form-control"></select>
							<input type="hidden" id="t_tipo_nota" class="form-control" value=""  />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="serie_nota">Serie<span style="color:red;">*</span></label>
							<select id="cbo_serie_nota" onchange="javascript:FrmMntNotaUnid.ProxCorrelativo()" class="form-control"></select>
						</div>
						
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="num_nota">Numero<span style="color:red;">*</span></label>
							<input type="text" readonly id="t_num_nota" class="form-control" value="<?php echo ($update==1?$entity['Num_Nota']:''); ?>"  />
						</div>
					</div>
				</div>
				<!--<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="serie_nota">Serie<span style="color:red;">*</span></label>
							<select id="cbo_serie_nota" class="form-control"></select>
						</div>
						
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="num_nota">Numero<span style="color:red;">*</span></label>
							<input type="text" id="t_num_nota" class="form-control" value="<?php echo ($update==1?$entity['Num_Nota']:''); ?>"  />
						</div>
					</div>
				</div>-->
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="ruc_cliente">Cliente</label>
							<input type="hidden" id="t_ruc_cliente" class="form-control" value=""  />
							<input type="text" id="t_nom_cliente" class="form-control" value=""  />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="fecha_nota">Fecha Emision</label>
							<input type="text" id="t_fecha_nota" class="form-control datepicker" value="<?php echo ($update==1?$entity['Fecha_Nota']:''); ?>"  />
						</div>
					</div>
				</div>
				<!--<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="fecha_nota">Fecha Emision</label>
							<input type="text" id="t_fecha_nota" class="form-control" value="<?php echo ($update==1?$entity['Fecha_Nota']:''); ?>"  />
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>-->
				<div class="row">
					<!--<div class="col-md-4">
						<div class="form-group">
							<label for="motivo_recep">Motivo Recepcion</label>
							<select id="cbo_motivo_recep" class="form-control"></select>
						</div>
					</div>-->
					<div class="col-md-5">
						
							<div class="form-group">
								<label for="tip_doc_ref">Tipo Doc. Ref.</label>
								<select id="cbo_tip_doc_ref" class="form-control"></select>
							</div>
					</div>
					<div class="col-md-3">
							<div class="form-group">
								<label for="serie_doc_ref">Serie Doc. Ref.</label>
								<input type="text" id="t_serie_doc_ref" class="form-control" value="<?php echo ($update==1?$entity['serie_doc_ref']:''); ?>"  />
							</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="num_doc_ref">Num Doc. Ref.</label>
							<input type="text" id="t_num_doc_ref" class="form-control" value="<?php echo ($update==1?$entity['num_doc_ref']:''); ?>"  />							
						</div>
					</div>
					<div class="col-md-1">												
							<button type="button" class="btn btn-effect-ripple btn-success" onclick="javascript:FrmMntNotaUnid.FindByDocRefNum();">
								<i class=" ion ion-md-search">
							</i></button>
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="obs_nota">Observacion</label>
							<textarea cols="5" rows="3" id="t_obs_nota" class="form-control" ><?php echo ($update==1?$entity['obs_Nota']:''); ?></textarea>
						</div>
					</div>
				</div>				
					
					<form id="form1" method="post" name="form1" >
					<fieldset><legend>Detalle</legend>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="ccod_art">Codigo Art.</label>
							<input class="form-control" type="hidden" id="t_ccod_art" name="t_ccod_art" value=""  />
							<input class="form-control" type="text" id="t_cdsc_art" name="t_cdsc_art" value=""  />
							<input class="form-control" type="hidden" id="t_ccod_undmed" name="t_ccod_undmed" value=""  />
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="nund">Cantidad</label>
							<input class="form-control" type="text" id="t_nund" name="t_nund" value=""  />
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label for="cnro_lote">Nro. Lote<input type="checkbox" onclick="javascript:FrmMntNotaUnid.CheckLote(this)" id="t_bind_lote" name="t_bind_lote" value=""  /></label>
							<input class="form-control" readonly type="text" id="t_cnro_lote" name="t_cnro_lote" value=""  />
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							
						</div>
					</div>
					<input type="button" class="btn btn-primary" id="btnadddet_ALM_Kardex" value="Agregar item" />
				</div>
						
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<div id="listado_det_ALM_Kardex"></div><!--listado del detalle--> 
					</fieldset>
					
					

					
					
					
					
					</form>
					<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
                <input type="button" class="btn btn-primary" id="btnsave" value="Guardar" />&nbsp;&nbsp;
				<input type="button" class="btn btn-primary" id="btncancel" value="Cancelar" />
				<!--</form>-->
                        
             
           
					 </div>
                                </div>
                            </div>              
                     </div>
                    </div> <!-- container -->         
                </div> <!-- content -->
            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


        </div>
		
<script>
<?php if($update==1){
?>
//FrmMntNotaUnid.listar_cbo_Serie_Nota('<?php echo trim($entity['Serie_Nota']); ?>');
FrmMntNotaUnid.listar_cbo_Motivo_Recep('<?php echo trim($entity['Motivo_Recep']); ?>');
FrmMntNotaUnid.listar_cbo_Cod_Almacen('<?php echo trim($entity['Cod_Almacen']); ?>');
FrmMntNotaUnid.listar_cbo_tip_doc_ref('<?php echo trim($entity['tip_doc_ref']); ?>');
FrmMntNotaUnid.CargaDet_ALM_Kardex();
<?php } else {
	?>
//FrmMntNotaUnid.listar_cbo_Serie_Nota('');
FrmMntNotaUnid.listar_cbo_Motivo_Recep('');
FrmMntNotaUnid.listar_cbo_Cod_Almacen('');
FrmMntNotaUnid.listar_cbo_tip_doc_ref('');	
FrmMntNotaUnid.PintarDatosdet_ALM_Kardex('');
<?php }
 ?></script>
<div class="modal fade" id="ModalSeries" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<form id="form2" method="post" name="form2" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Series</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>      
        <div class="modal-body">
          <div class="row inputSeries" id="inputSeries">
            
          </div>
		  <div  id="divmsg">
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="javascript:FrmMntNotaUnid.AddDet_ALM_KardexSerie();"><i class="fa fa-save"></i> Guardar</button>
        </div>      
    </div>
  </div>
  </form>
</div>