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
                                    <!-- <h4 class="page-title float-left">Almacen</h4> -->
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="#">Almacen</a></li>
                                        <li class="breadcrumb-item active">Listado</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>
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
             
           
                        <!-- Vertical Steps Example -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                <div class="card-header bg-success"><h3 class="my-0 text-white">Lista de almacen <a class="btn btn-rounded btn-pink float-right" data-toggle="modal" data-target="#ModalAgregarAlmacen" tabindex="0" aria-controls="datatable-buttons"><i class="fas fa-plus m-r-5"></i><span>Agregar</span></a> </h3></div>
                                    <div class="card-body table-responsive">
                                              <!-- Vertical Steps Example -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">                               
                                    <div class="card-body table-responsive"> 
                                       <div class="row">
                                            <!-- <div class="col-md-12">
               
                                             <div class="form-group">
                                                  <div class="dt-buttons btn-group" data-toggle="modal" data-target="#ModalAgregarAlmacen">
                                                     <a class="btn btn-pink" tabindex="0" aria-controls="datatable-buttons"><span><i class="fa fa-plus"></i> Agregar</span></a>
                                                </div>
                                            </div>
                  
                                            </div>
                                        <br>
                                        <br> -->
                                    
                                    <div class="col-md-6">
                                    <form id="AlmacenFormBusqueda" autocomplete="off">
                                         <label class="control-label " >Buscar por descripción:</label>
                                            <div class="input-group">
                                                <input type="text"  name="tb_almacen" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-effect-ripple btn-success"><i class="fab fa-earlybirds"></i></button>
                                                    </span>
                                            </div>
                                            </form>
                                    </div>  
                                </div>
  
                                        <!-- End #wizard-vertical -->
                                    </div>
                                </div>
                            </div>
                        </div><!-- End row -->  
                                    <div class="row">                          
                                  
                                         <div class="col-md-12">
                                            <div class="form-group">
                                                <table id="TableMantenimientoAlmacen" class="table  table-striped" cellspacing="0" width="100%">                                                  
                                                                                                    
                                                
                                                   
                                                    <thead class="bg-success text-white">
                                                        <tr>
                                                            <th class="text-center">Secuencia</th>
                                                            <th  class="text-center">Descripcion</th>
                                                            <th  class="text-center">Disponible venta</th>
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
                     </div>
                    </div> <!-- container -->         
                </div> <!-- content -->
            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->
    

       



     <div id="ModalAgregarAlmacen" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                                <form id="FormAlmacen" action="<?= base_url('administrador/regalmacen/agregarAlmacen') ?>" method="post" autocomplete="off">
                                                <input type="hidden" > 
                                                    <!-- <div class="modal-header">
                                                        <h4 class="custom-modal"  >Agregar almacen</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div> -->
                                                    <div class="card-header bg-success"><h3 class="my-0 text-white">Agregar almacen<i class="spinner-grow text-warning float-right"></i></h3></diV>
                                             <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre de almacen:</label>
                                                                <input type="text" name="descripcion" class="form-control">
                                                            </div>
                                                       </div>
                                      
                                                         <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Para venta:</label>
                                                                  <select class="form-control select2 select2-hidden-accessible" name="estadoventa" >
                                                                  <option value="1">Si</option>
                                                                  <option value="2">No</option>
                                                                
                                                                </select>
                                                            </div>
                                                       </div>

                                     
                                                   

                                                    </div> 
                                              </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-pink waves-effect" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
                                                    </div>
                                          </form>
                                     </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
         </div><!-- /.modal -->




    <div id="ModalEditarAlmacen" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarAlmacen" action="<?= base_url('administrador/regalmacen/editAlmacen') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <!-- <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar almacen</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div> -->
                                                    <div class="card-header bg-success"><h3 class="my-0 text-white">Editar almacen<i class="spinner-grow text-danger float-right"></i></h3></diV>
                                                    <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre:</label>
                                                                <input type="text" name="descripcion" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Para venta:</label>
                                                                  <select class="form-control select2" name="estadoventa" >
                                                                         <option value="1">Si</option>
                                                                            <option value="2" >No</option>
                                                                
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
    
</html>