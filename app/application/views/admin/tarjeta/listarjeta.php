<!-- Begin page -->
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
                            <h4 class="page-title float-left">Tarjeta</h4>
                            <ol class="breadcrumb float-right">
                               
                                <li class="breadcrumb-item"><a href="#">Tarjeta</a></li>
                                <li class="breadcrumb-item active">Listado</li>
                            </ol>
                   
                        </div>
                    </div>
                </div>
              
                <!-- end row -->

                <!-- Vertical Steps Example -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body table-responsive">
                               <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group">
                                     <button type="button" class="btn btn-pink" data-toggle="modal" data-target="#ModalAgregarTarjeta"><i class="fa fa-plus"></i>  Agregar</button>
                                    </div>
                                  </div>
                               </div>
                                <form id="FormTarjeta" action="" method="post" autocomplete="off">
                                  <div class="row">
                                 

                                     <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nombre:</label>
                                            
                                                <input type="text"  name="tb_tarjeta" class="form-control">
                        
                                            
                                        </div>
                                    </div>
                                  </div>
                                </form>
                                <div>
                                    <table id="TableTarjetaListado" class="table mb-0 table-hover" cellspacing="0" width="100%">

                                        <thead>
                                            <tr class="bg-info text-white">
                                                <th style="text-align: center;">Codigo</th>
                                                <th style="text-align: center;">Banco</th>     
                                                <th style="text-align: center;">Estado</th>
                                                <th style="text-align: center;">Opciones</th>
                                            </tr>
                                        </thead>
                                 
                                    </table>
                                    
                                </div>
                                <!-- End #wizard-vertical -->
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




  <div id="ModalAgregarTarjeta" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                                <form id="FormAgregarTarjeta" action="<?= base_url('administrador/regtarjeta/agregarTarjeta') ?>" method="post" autocomplete="off">
                                                <input type="hidden" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Agregar Tarjeta</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                             <div class="modal-body">
                                                    <div class="row">
                                                     

                                                       <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre:</label>
                                                                <input type="text" name="nombre" class="form-control">
                                                            </div>
                                                       </div>

                                                    </div> 
                                              </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-pink waves-effect" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                                                        <button type="submit" class="btn btn-success waves-effect waves-light" ><i class="fas fa-save"></i> Guardar</button>
                                                    </div>
                                          </form>
                                     </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
         </div><!-- /.modal -->




    <div id="ModalEditarTarjeta" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarTarjeta" action="<?= base_url('administrador/regtarjeta/editTarjeta') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar Tarjeta</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <div class="row">
                                                      
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre:</label>
                                                                <input type="text" name="nombre" class="form-control">
                                                            </div>
                                                        </div>

                                                   
                                                    </div>

                                                       
                                                       
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
                                                    </div>
                                                    </form>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->