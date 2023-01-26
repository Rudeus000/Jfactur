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
                            <!-- <h4 class="page-title float-left">Banco</h4> -->
                            <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Configuracion</a></li>
                                <li class="breadcrumb-item"><a href="#">Banco</a></li>
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
                        <div class="card-header bg-primary"><h3 class="my-0 text-white">Administrar bancos<button  class="btn btn-pink waves-effect w-md waves-light float-right" data-toggle="modal" data-target="#ModalAgregarBanco"><i class="fa fa-plus m-r-5"></i>Agregar</button></h3></div> 
                            <div class="card-body table-responsive">
                               <!-- <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group">
                                     <button type="button" class="btn btn-pink" data-toggle="modal" data-target="#ModalAgregarBanco"><i class="fa fa-plus"></i>  Agregar</button>
                                    </div>
                                  </div>
                               </div> -->
                                <form id="FormBanco" action="" method="post" autocomplete="off">
                                  <div class="row">
                                 

                                     <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nombre:</label>
                                            
                                                <input type="text"  name="tb_banco" class="form-control">
                        
                                            
                                        </div>
                                    </div>
                                  </div>
                                </form>
                                <div>
                                    <table id="TableBancoListado" class="table mb-0" cellspacing="0" width="100%">

                                        <thead>
                                            <tr class="bg-primary text-white">
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




  <div id="ModalAgregarBanco" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                                <form id="FormAgregarBanco" action="<?= base_url('administrador/regbanco/agregarBanco') ?>" method="post" autocomplete="off">
                                                <input type="hidden" > 
                                                    <div class="modal-header bg-primary">
                                                        <h4 class="custom-modal text-white"  >Agregar Banco</h4>
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




    <div id="ModalEditarBanco" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarBanco" action="<?= base_url('administrador/regbanco/editBanco') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header bg-primary">
                                                        <h4 class="custom-modal text-white"  >Editar Cuenta</h4>
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