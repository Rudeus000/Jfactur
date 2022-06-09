        <!-- Begin page -->
		<script>
		var pathController="<?php echo base_url();?>administrador";
		</script>
		<script type="text/javascript" src="<?php echo base_url_app();?>assets/js/NotaAlmacen/FrmListaNotaAlmacen.js?v=<?php echo rand(0,5000);?>"></script>
		
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
                                        <li class="breadcrumb-item"><a href="#">Nota almacén</a></li>
                                        <li class="breadcrumb-item active">Listado</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                <div class="card-header bg-success"><h3 class="my-0 text-white">Lista de notas almacén <a class="btn btn-rounded btn-pink float-right" id="btnnuevo" tabindex="0" aria-controls="datatable-buttons"><i class="fas fa-plus m-r-5"></i><span>Agregar</span></a> </h3></div>
                                    <div class="card-body table-responsive">
								<fieldset>
                  <legend>Filtro</legend>
                  <form id="FormComprasFiltro" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Emisión Desde</label>
                          <div class="input-group">
                            <input type="text" name="t_fecha_notad" id="t_fecha_notad" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
							
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Emisión hasta</label>
                          <div class="input-group">
                            <input type="text" name="t_fecha_notah" id="t_fecha_notah" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
							
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Motivo Recepción</label>
                          <select class="form-control" id="cbo_motivo_recep"></select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Tipo Movimiento</label>
                          <select class="form-control" id="cbo_tipo_nota">
							<option value="T">TODOS</option>
							<option value="I">Ingreso</option>
							<option value="S">Salida</option>
						</select>	
                        </div>
                      </div>
                      <!--<div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Estado</label>
                          <select name="estado" class="form-control">
                            <option value="1">Activo</option>
                            <option value="2">Anulado</option>
                          </select>
                        </div>
                      </div>-->
                      <div class="col-md-2">
                        <button class="btn btn-success waves-effect waves-light" id="btnbuscar" style="margin-top: 29px"><i class="fa fa-search"></i> Buscar</button>
                      </div>
                    </div>
                  </form>
                </fieldset>	
					
				<div id="listado"></div>
                        <?php if ($this->session->flashdata('sa-success')): ?>
                         <script type="text/javascript">
                          $(function(){
                           swal.fire(
                             'Guardo Exitosamente!',
                             'Grupo usuario!',
                              'sa-success'
                            )
                            });
                            </script>
                         <?php endif ?> 
                        <!-- end row -->
             
           
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
        <!-- END wrapper -->
		