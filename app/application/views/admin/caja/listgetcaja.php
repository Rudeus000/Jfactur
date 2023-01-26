<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BFacturas - caja</title>
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
                                    <!-- <h4 class="page-title float-left"> <i class="fas fa-cubes"></i> Caja</h4> -->
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="#">Caja</a></li>
                                        <li class="breadcrumb-item active">Listado</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>            
                       
        
                                                <!-- Vertical Steps Example -->
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="card">
                                                        <div class="card-header bg-success"><h3 class="my-0 text-white">Administrar cajas<a href="" class="btn btn-rounded btn-pink float-right" data-toggle="modal" data-target="#ModalAgregarCaja"><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
                                                            <div class="card-body table-responsive">
                                                        
                                                        <!-- Vertical Steps Example -->
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="card">                                
                                                            <div class="card-body"> 
                                                        
                                                            
                                                            <div class="col-md-8">
                                                            <form id="CajaFormBusqueda" autocomplete="off">
                                                                <label class="control-label " >Buscar por descripción:</label>
                                                                    <div class="input-group">
                                                                        <input type="text"  name="tb_caja" class="form-control">
                                                                            <span class="input-group-btn">
                                                                                <button type="submit" class="btn btn-effect-ripple btn-success"><i class="fa fa-search"></i></button>
                                                                            </span>
                                                                    </div>
                                                                    </form>
                                                            </div>  
                        
                                                                <!-- End #wizard-vertical -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- End row -->  
                                         
                                                <table id="TableMantenimientoCaja" class="table  table-striped" cellspacing="0" width="100%" >
                                                     
                                                  
                                                        <div class="form-group col-md-6">                                          
                                                        <label class="col-form-label" style="display: block"><br></label>
                                                        <!-- <span class="input-group-btn" data-toggle="modal" data-target="#ModalAgregarCaja" style="padding-top:35px;">
                                                            
                                                            <button  class="btn btn-success waves-effect w-md waves-light"><i class="fas fa-clipboard-list m-r-5"></i>Agregar</button>
                                                        </span>                                           -->
                                                        <span class="input-group-btn" style="padding-top:35px;">
                                                        
                                                            <button  class="btn btn-primary buttons-excel waves-effect w-md waves-light"><i class="fa fa-file-excel m-r-5"></i><span>Exportar</span></button>
                                                        </span>
                                                        <span class="input-group-btn" style="padding-top:35px;">                                                    
                                                            <button type="button" class="btn btn-danger  waves-effect w-md waves-light"><i class="far fa-file-pdf m-r-5"></i><span>PDF</span></button>
                                                        </span>
                                                    </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div id="datatable-buttons_filter" class="dataTables_filter"></div>
                                                     </div>
                                                
                                                    <br>
                                                    <thead>
                                                        <tr class="btn-success">
                                                            <th class="text-center">Secuencia</th>
                                                            <th  class="text-center">Nombre</th>
                                                            <th  class="text-center">Tipo caja</th>
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

     <div id="ModalAgregarCaja" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                                <form id="FormCaja" action="<?= base_url('administrador/regcaja/agregarCaja') ?>" method="post" autocomplete="off">
                                                <input type="hidden" >                                               
                                                   
                                                    <div class="card-header bg-success"><h3 class="my-0 text-white">Agregar caja</h3></div>
                                             <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre de caja:</label>
                                                                <input type="text" name="descripcion" class="form-control">
                                                            </div>
                                                       </div>
                                      
                                                         <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo:</label>
                                                                  <select class="form-control select2 select2-hidden-accessible" name="tipo" >
                                                                  <option value="1">Venta</option>
                                                                  <option value="2">Central</option>
                                                                  <option value="3">Recaudadora</option>
                                                                  <option value="4">Compras</option>
                                                                
                                                                </select>
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




    <div id="ModalEditarCaja" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarCaja" action="<?= base_url('administrador/regcaja/editCaja') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar caja</h4>
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
                                                                         <option value="1" >Venta</option>
                                                                          <option value="2" >Central</option>
                                                                          <option value="3" >Recaudadora</option>
                                                                          <option value="4" >Compras</option>
                                                                
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