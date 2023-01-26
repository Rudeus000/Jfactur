<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BFacturas - unidad</title>
        <meta content="Sistema de gestión de ventas con facturacion electrónica" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    </head>


    <body>

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
                                    <!-- <h4 class="page-title float-left"><i class="ion ion-ios-journal"></i> Unidad</h4> -->
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="#">Unidad</a></li>
                                        <li class="breadcrumb-item active">Listado</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>
                 
                        <!-- Vertical Steps Example -->
                        <div class="row">
                            <div class="col-sm-12">
                                 <div class="card">
                                 <div class="card-header bg-success"><h3 class="my-0 text-white">Lista unidad medida<a href="" class="btn btn-rounded btn-pink float-right" data-toggle="modal" data-target="#ModalAgregarUmedida"><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
                                    <div class="card-body table-responsive">
                                   
                                                          <!-- Vertical Steps Example -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">  
                                                <form id="UmedidaFormBusqueda" autocomplete="off">
                                                    <div class="form-row">
                                                        
                                                            <div class="form-group col-md-3">
                                                                <label  class="col-form-label">Tipo unidad:</label>
                                                                <select class="form-control  select2 select2-hidden-accessible" name="tb_tipounidad" >
                                                                <option value="">--Todos--</option>
                                                            <?php foreach ($tipounidad as $t): ?>
                                                            <option value="<?= $t->cod_tipunidad ?>"><?= $t->nomb_tipunidad ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                            </div>                                          
                                    
                                                            <div class="form-group col-md-6">
                                            
                                                                    <label class="col-form-label " >Buscar por descripcion :</label>
                                                                    
                                                                    <div class="input-group">
                                                            <input type="text"  name="tb_unidades" class="form-control">
                                                                <span class="input-group-btn">
                                                                    <button type="submit" class="btn btn-effect-ripple btn-success"><i class="fa fa-search"></i></button>
                                                                </span>
                                                        </div>
                                                                </span>
                                                        
                                                        </div>
                                                
                                                        
                                                </div> 
                                                </form>
                                        
            
                                                    <!-- End #wizard-vertical -->
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End row --> 
                                         
                                                <table id="TableMantenimientoUmedida" class="table  table-striped" cellspacing="0" width="100%" >
                                                                                                      
                                                        <div class="form-group col-md-6">                                          
                                                    <label class="col-form-label" style="display: block"><br></label>
                                                <!-- <span class="input-group-btn" data-toggle="modal" data-target="#ModalAgregarUmedida" style="padding-top:35px;">
                                                    
                                                    <button  class="btn btn-success btn-rounded waves-effect w-md waves-light"><i class="fas fa-clipboard-list m-r-5"></i>Agregar</button>
                                                </span>                                           -->
                                                <span class="input-group-btn" style="padding-top:35px;">
                                                   
                                                    <button  class="btn btn-primary btn-rounded buttons-excel waves-effect w-md waves-light"><i class="fa fa-file-excel m-r-5"></i><span>Exportar</span></button>
                                                </span>
                                                <span class="input-group-btn" style="padding-top:35px;">                                                    
                                                    <button type="button" class="btn btn-danger  btn-rounded waves-effect w-md waves-light"><i class="far fa-file-pdf m-r-5"></i><span>PDF</span></button>
                                                </span>
                                            </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div id="datatable-buttons_filter" class="dataTables_filter"></div>
                                                     </div>
                                                
                                                    <br>
                                                    <thead>
                                                        <tr class="bg-success text-white" >
                                                            <th class="text-center">ID</th>
                                                            <th  class="text-center">Abreviatura</th>
                                                             <th  class="text-center">Descripcion</th>
                                                              <th  class="text-center">Factor</th>
                                                               <th  class="text-center">Tipo medida</th>
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
                    </div> <!-- container -->         
                </div> <!-- content -->
            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

    </body>

     <div id="ModalAgregarUmedida" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                                                <form id="FormUmedida" action="<?= base_url('administrador/regunidad/insertUmedida') ?>" method="post" autocomplete="off">
                                                <input type="hidden" > 
                                                    <!-- <div class="modal-header">
                                                        <h4 class="custom-modal"  >Información Unidad de Medida</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div> -->
                                                    <div class="card-header bg-success"><h3 class="my-0 text-white">Agregar unidad medida<i class="spinner-grow text-warning float-right"></i></h3></diV>
                                             <div class="modal-body">
                                                    <div class="row">

                                                          <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre: <span class="text-danger"> *</span></label>
                                                                <input type="text" name="descripcion" class="form-control" maxlength="5">
                                                            </div>
                                                       </div>
                                                      
                                                       <div class="col-md-3">
                                                              <div class="form-group">
                                                                <label class="control-label">Tipo unidad:<span class="text-danger"> *</span></label>
                                                              <select class="form-control select2 select2-hidden-accessible" name="tipounidad" >
                                                                 <option value="">--Selecciona--</option>
                                                                 <?php foreach ($tipounidad as $t): ?>
                                                                  <option value="<?= $t->cod_tipunidad ?>"><?= $t->nomb_tipunidad ?></option>
                                                                 <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                       </div>

                                                      <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label">Abreviatura: <span class="text-danger"> *</span></label>
                                                                <input type="text" name="abreviatura" class="form-control" maxlength="20">
                                                            </div>
                                                       </div>
                                                     
    
                                                    

                                                     
                                                         <div class="col-md-3">
                                                            <div class="form-group">
                                                              <label class="control-label">Factor:<span class="text-danger"> *</span></label>
                                                              <input type="text" name="factor" class="form-control">
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


    <div id="ModalEditarUmedida" class="modal bs-example-modal-center" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                                                <form id="FormEditarUmedida" action="<?= base_url('administrador/regunidad/editUmedida') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <!-- <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar Unidad de Medida</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div> -->
                                                    <div class="card-header bg-success"><h3 class="my-0 text-white">AEditar unidad medida<i class="spinner-grow text-danger float-right"></i></h3></diV>
                                             <div class="modal-body">
                                                      <div class="row">
                                                   
                                                     <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre: <span class="text-danger"> *</span></label>
                                                                <input type="text" name="descripcion" class="form-control">
                                                            </div>                                                           
                                                       </div>

                                                           <div class="col-md-4">
                                                            <div class="form-group">
                                                                   <label class="control-label">Documento:<span class="text-danger"> *</span></label>
                                                                    <select class="form-control select2" name="tipounidad" >
                                                               
                                                                 <?php foreach ($tipounidad as $t): ?>
                                                                  <option value="<?= $t->cod_tipunidad ?>"><?= $t->nomb_tipunidad ?></option>
                                                                 <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                       </div>


                                                         <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Abreviatura: <span class="text-danger"> *</span></label>
                                                                <input type="text" name="abreviatura" class="form-control" maxlength="20">
                                                            </div>
                                                       </div>

                                                      <div class="col-md-3">
                                                            <div class="form-group">
                                                              <label class="control-label">Factor:<span class="text-danger"> *</span><i class="fa fa-info-circle text-info hover-q " aria-hidden="true" data-container="body" data-toggle="popover" data-placement="auto" data-content="Id único de producto o Unidad de stock de mantenimiento <br> <br> manténgalo en blanco para generar automáticamente sku. <br> <small class='text-muted'> Puede modificar el prefijo sku en Configuración de empresa . </small> " data-html="true" data-trigger="hover" data-original-title="" title=""></i></label>
                                                              <input type="text" name="factor" class="form-control">
                                                           </div>
                                                       </div>
                                                    
                                                      <div class="col-md-3">
                                                          <div class="form-group">
                                                             <label  class="control-label">Estado</label>
                                                              <select class="form-control select2" name="estado" >
                                                               <option value="1" >Activo</option>
                                                               <option value="2" >Desactivado</option>
   
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