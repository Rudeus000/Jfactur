<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BFacturas - categoria</title>
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
                                    <!-- <h4 class="page-title float-left"><i class="ion ion-ios-clipboard"></i> Categoria</h4> -->
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="#">Categoria</a></li>
                                        <li class="breadcrumb-item active">Listado</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>
                        
        
                        <!-- Vertical Steps Example -->
                        <div class="row">
                            <div class="col-sm-12">
                                 <div class="card">
                                 <div class="card-header bg-success"><h3 class="my-0 text-white">Lista de categorias<i class="spinner-grow text-primary float-right"></i></h3></div>  
                                    <div class="card-body table-responsive">
                                   
                                    <div class="col-md-8">
                                    <form id="CategoriaFormBusqueda" autocomplete="off">
                                         <label class="control-label " >Buscar por descripción:</label>
                                            <div class="input-group">
                                                <input type="text"  name="tb_categoria" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-effect-ripple btn-success"><i class="fab fa-earlybirds"></i></button>
                                                                                                               
                                                            <button  class="btn btn-success waves-effect w-md waves-light" data-toggle="modal" data-target="#ModalAgregarCategoria"><i class="fas fa-clipboard-list m-r-5"></i>Agregar</button>
                                                            <button  class="btn btn-primary buttons-excel waves-effect w-md waves-light"><i class="fa fa-file-excel m-r-5"></i><span>Exportar</span></button>
                                                            <button type="button" class="btn btn-danger  waves-effect w-md waves-light"><i class="far fa-file-pdf m-r-5"></i><span>PDF</span></button>
                                                        </span>                                          
                                                     
                                            </div>
                                            </form>
                                    </div>                               
                                         
                                                <table id="TableMantenimientoCategoria" class="table  table-striped" cellspacing="0" width="100%" >
                                                                                                        
                                                        <div class="col-sm-12 col-md-6">
                                                            <div id="datatable-buttons_filter" class="dataTables_filter"></div>
                                                     </div>
                                                
                                                    <br>
                                                    <thead>
                                                        <tr class="bg-success text-white">
                                                            <th class="text-center">ID</th>
                                                            <th  class="text-center">Nombre</th>
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

     <div id="ModalAgregarCategoria" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                                <form id="FormCategoria" action="<?= base_url('administrador/regcategoria/agregarCategoria') ?>" method="post" autocomplete="off">
                                                <input type="hidden" >
                                                <div class="card-header bg-success"><h3 class="my-0 text-white">Agregar categoria<i class="spinner-grow text-warning float-right"></i></h3></div> 
                                                                                                       
                                             <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre:</label>
                                                                <input type="text" name="descripcion" class="form-control">
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




    <div id="ModalEditarCategoria" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarCategoria" action="<?= base_url('administrador/regcategoria/editCategoria') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" >
                                                    
                                                    <div class="card-header bg-success"><h3 class="my-0 text-white">Editar Informacion de Categoria<i class="spinner-grow text-danger float-right"></i></h3></div> 
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
                                                                <label class="control-label">Estado:</label>
                                                                <select class="form-control select" name="estado" >
                                                                    <option value="1">Activo</option>
                                                                    <option value="2">Inactivo</option>
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