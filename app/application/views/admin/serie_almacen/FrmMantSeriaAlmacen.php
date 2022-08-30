<!-- Begin page -->
<script>
var pathController="<?php echo base_url();?>administrador";
</script>
<script type="text/javascript" src="<?php echo base_url_app();?>assets/js/serie_almacen/FrmMantSeriaAlmacen.js?v=<?php echo rand(0,5000);?>"></script>
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
                                        <li class="breadcrumb-item"><a href="#">Serie almacén</a></li>
                                        <li class="breadcrumb-item active">Mantenimiento</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                <div class="card-header bg-success"><h3 class="my-0 text-white"><?php echo ($update==1?'Modificar Serie almacén':'Nueva Serie almacén')?> </h3></div>
                                    <div class="card-body table-responsive">
					 <form id="FormComprasFiltro" action="" method="post" autocomplete="off">
					<input type="hidden" id="t_codigo" name="t_codigo" value="<?php echo ($update==1?$id:'')?>" /> 
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="cod_almacen">Almacen<span style="color:red;">*</span></label>
							<select id="cbo_cod_almacen" class="form-control" name="cbo_cod_almacen"></select>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="tipodoc">Tipo Documento<span style="color:red;">*</span></label>
							<select id="cbo_tipodoc" class="form-control" name="cbo_tipodoc">
								<option value="NI">Nota Ingreso</option>
								<option value="NS">Nota Salida</option>
								<option value="BI">Boleta Ingreso</option>
								<option value="BS">Boleta Salida</option>
							</select>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="serie">Serie<span style="color:red;">*</span></label>
							<input type="text" maxlength="4" id="t_serie" class="form-control" name="t_serie" value="<?php echo ($update==1?$entity['Serie']:''); ?>"  />
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="correlativo">Correlativo<span style="color:red;">*</span></label>
							<input type="text" id="t_correlativo" class="form-control" name="t_correlativo" value="<?php echo ($update==1?$entity['Correlativo']:''); ?>"  />
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<input type="button" class="btn btn-primary" id="btnsave" value="Grabar" />&nbsp;&nbsp;<input type="button" class="btn btn-danger" id="btncancel" value="Cancelar" />
				</form>

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
FrmMantSeriaAlmacen.listar_cbo_cod_almacen("<?php echo trim($entity['cod_almacen']); ?>");
$('#cbo_TipoDoc').val("<?php echo trim($entity['TipoDoc']); ?>");
<?php } else {
 ?>
 FrmMantSeriaAlmacen.listar_cbo_cod_almacen('00');
 <?php }
 ?></script>