
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
                                    <!-- <h4 class="page-title float-left">Tipo de nota Almacen</h4> -->
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="#">Almacen</a></li>
                                        <li class="breadcrumb-item active">Listado</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>

                        <!-- Vertical Steps Example -->
                        <div class="row">                   
                                      <div class="col-sm-12">
                                        <div class="card">
                                        <div class="card-header bg-success"><h3 class="my-0 text-white">Lista tipo almacen  <a class="btn btn-rounded btn-pink float-right" data-toggle="modal" data-target="#ModalAgregarTipoAlmacen" tabindex="0" aria-controls="datatable-buttons"><i class="fas fa-plus m-r-5"></i><span>Agregar</span></a> </h3></div>
                                          <div class="card-body table-responsive">
                                                  <!-- Vertical Steps Example -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">                               
                                    <div class="card-body table-responsive">
                                    <div class="row">
                                  
                                    <div class="col-md-12">
                                    <form id="TipAlmacenFormBusqueda" autocomplete="off">
                                         <label class="control-label " >Buscar por descripción:</label>
                                            <div class="input-group">
                                                <input type="text"  name="tb_tipoalmacen" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-effect-ripple btn-success"><i class=" fab fa-earlybirds fa-1x"></i></button>
                                                    </span>
                                            </div>
                                            </form>
                                    </div>  
  
                                        <!-- End #wizard-vertical -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div><!-- End row -->  

                                            <div class="row">
                                              <div class="col-md-12">                                  
                                         
                                                <table id="TableMantenimientoTipAlmacen" class="table  table-striped" cellspacing="0" width="100%" >
                                                     
                                                        <div class="col-sm-12 col-md-6">                                                          
                                                            <a href=""></a>
                                                            <a class="btn btn-rounded btn-primary buttons-excel buttons-html5" tabindex="0" aria-controls="datatable-buttons" href=""><i class="far fa-file-excel"></i><span> Excel</span></a>

                                                            <a class="btn btn-rounded btn-danger buttons-pdf buttons-html5" tabindex="0" aria-controls="datatable-buttons" href=""><i class="far fa-file-pdf"></i><span> PDF</span></a>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div id="datatable-buttons_filter" class="dataTables_filter"></div>
                                                        </div>
                                                
                                                    <br>
                                                    <thead class="bg-success text-white">
                                                        <tr >
                                                            <th class="text-center">Secuencia</th>
                                                            <th  class="text-center">Nombre</th>
                                                            <th  class="text-center">Tipo</th>
                                                             <th  class="text-center">Estado</th>
                                                            <th  class="text-center">Acciones</th>
                                                           
                                                        </tr>
                                                    </thead>
                                             
                                                </table>
                                        <!-- End #wizard-vertical -->
                                     </div>                      
                                </div>              
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


    




     <div id="ModalAgregarTipoAlmacen" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                                <form id="FormTipoAlmacen" action="<?= base_url('administrador/regtipalmacen/agregarTipAlmacen') ?>" method="post" autocomplete="off">
                                                <input type="hidden" > 
                                                    <!-- <div class="modal-header">
                                                        <h4 class="custom-modal"  >Agregar tipo almacen</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div> -->
                                                    <div class="card-header bg-success"><h3 class="my-0 text-white">Agregar tipo almacen<i class="spinner-grow text-warning float-right"></i></h3></diV>
                                             <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre de almacen:</label>
                                                                <input type="text" name="descripcion" class="form-control">
                                                            </div>
                                                       </div>
                                      
                                                         <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label">Para venta:</label>
                                                                  <select class="form-control select2 select2-hidden-accessible" name="tipo" >
                                                                  <option value="I">Ingreso (Aumentar Stock)</option>
                                                                  <option value="S">Salida (Disminuir Stock)</option>
                                                                
                                                                </select>
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




    <div id="ModalEditarTipAlmacen" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarTipo" action="<?= base_url('administrador/regtipalmacen/editTipAlmacen') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar Informacion de Tipo Almacen</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre:</label>
                                                                <input type="text" name="descripcion" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo:</label>
                                                                  <select class="form-control select2" name="tipo" >
                                                                         <option value="I">Ingreso (Aumentar Stock)</option>
                                                                         <option value="S" >Salida (Disminuir Stock)</option>
                                                                
                                                                </select>
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
    
