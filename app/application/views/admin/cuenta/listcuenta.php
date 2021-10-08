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
                            <!-- <h4 class="page-title float-left">Cuentas</h4> -->
                            <ol class="breadcrumb float-right">
                               
                                <li class="breadcrumb-item"><a href="#">Cuentas</a></li>
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
                        <div class="card-header bg-success"><h3 class="my-0 text-white">Administrar cuentas<a href="" class="btn btn-rounded btn-pink float-right" data-toggle="modal" data-target="#ModalAsignarCuenta"><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
                            <div class="card-body table-responsive">
                               <!-- <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group">
                                     <button type="button" class="btn btn-pink" data-toggle="modal" data-target="#ModalAsignarCuenta"><i class="fa fa-plus"></i>  Agregar</button>
                                    </div>
                                  </div>
                               </div> -->
                                <form id="FormCuentaAsignar" action="" method="post" autocomplete="off">
                                  <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Tipo cuenta:</label>
                                            <select name="tipo_cuenta" class="form-control select2">
                                              <option value="">Todos</option>
                                              <?php foreach ($tipo as $t): ?>
                                              <option value="<?= $t->id_tipcuenta ?>"><?= $t->nomb_tipcuenta ?></option>
                                              <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>

                                     <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nombre:</label>
                                            
                                                <input type="text"  name="tb_cuenta" class="form-control">
                        
                                            
                                        </div>
                                    </div>
                                  </div>
                                </form>
                                <div>
                                    <table id="TableCuentListado" class="table mb-0" cellspacing="0" width="100%">

                                        <thead>
                                            <tr class="btn-success">
                                                <th style="text-align: center;">Codigo</th>
                                                <th style="text-align: center;">Tipo cuenta</th>
                                                <th style="text-align: center;">Nombre</th>
                                                <th style="text-align: center;">Orden</th>
                                                <th style="text-align: center;">Estado</th>
                                               
                                                <th>Opciones</th>

                                                <th></th>
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




  <div id="ModalAsignarCuenta" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                                <form id="FormAgregarcuentas" action="<?= base_url('administrador/regcuenta/agregarCuent') ?>" method="post" autocomplete="off">
                                                <input type="hidden" > 
                                                    <div class="modal-header bg-success">
                                                        <h4 class="custom-modal text-white"  ><i class=" fas fa-clipboard-check m-r-5"></i>Agregar Cuentas</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                             <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo cuenta:</label>
                                                                  <select name="tipo" class="form-control select2">
                                                                      <option value="">Seleccionar</option>
                                                                      <?php foreach ($tipo as $t): ?>
                                                                      <option value="<?= $t->id_tipcuenta ?>"><?= $t->nomb_tipcuenta ?></option>
                                                                      <?php endforeach ?>
                                                                  </select>
                                                            </div>
                                                       </div>

                                                       <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre:</label>
                                                                <input type="text" name="nombre" class="form-control">
                                                            </div>
                                                       </div>
                                      
                                                      <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label">Orden:</label>
                                                                <input type="text" name="orden" class="form-control">
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




    <div id="ModalEditarCuenta" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarCuenta" action="<?= base_url('administrador/regcuenta/editCuenta') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar Cuenta</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <div class="row">
                                                         <div class="col-md-6">
                                                              <div class="form-group">
                                                                <label class="control-label">Tipo:</label>
                                                                <select class="form-control select" name="tipo" >

                                                                 <?php foreach ($tipo as $t): ?>
                                                                  <option value="<?= $t->id_tipcuenta ?>"><?= $t->nomb_tipcuenta ?></option>
                                                                <?php endforeach ?>
                                                              </select>
                                                            </div>
                                                          </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre:</label>
                                                                <input type="text" name="nombre" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Orden:</label>
                                                                <input type="text" name="orden" class="form-control">
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