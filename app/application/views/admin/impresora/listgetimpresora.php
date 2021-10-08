<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solutions - impresora</title>
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
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
                                    <h4 class="page-title float-left">Impresora</h4>
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="#">Impresora</a></li>
                                        <li class="breadcrumb-item active">Listado</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>
                 

                        <!-- Vertical Steps Example -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body"> 
                                    <ol class="breadcrumb">
                                        <li><a href="<?= base_url('administrador/regimpresora') ?>"><i class="ion ion-ios-refresh"></i> Actualizar</a></li>
                                        
                                    </ol>
                                    
                                    <div class="col-md-8">
                                    <form id="ImpresoraFormBusqueda" autocomplete="off">
                                         <label class="control-label " >Buscar por descripción:</label>
                                            <div class="input-group">
                                                <input type="text"  name="tb_impresora" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-effect-ripple btn-info"><i class="fa fa-search"></i></button>
                                                    </span>
                                            </div>
                                            </form>
                                    </div>  
  
                                        <!-- End #wizard-vertical -->
                                    </div>
                                </div>
                            </div>
                        </div><!-- End row -->  
        
        
                        <!-- Vertical Steps Example -->
                        <div class="row">
                            <div class="col-sm-12">
                                 <div class="card">
                               
                                    <div class="card-body table-responsive">
                                   
                                  
                                         
                                                <table id="TableMantenimientoImpresora" class="table  table-striped" cellspacing="0" width="100%" >
                                                     
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="dt-buttons btn-group" data-toggle="modal" data-target="#ModalAgregarImpresora">
                                                                <a class="btn btn-secondary" tabindex="0" aria-controls="datatable-buttons"><i style="color:#0099CC;" class="fas fa-user-plus"></i><span style="color:#0099CC;"> Agregar</span></a>
                                                                
                                                            </div>
                                                            <div class="row">
                                                              <a class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="datatable-buttons" href=""><i
                                                            style="color:#00C851;" class="far fa-file-excel"></i><span style="color:#00C851;"> Excel</span></a>
                                                                <a class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="datatable-buttons" href=""><i style="color:#ff4444;"class="far fa-file-pdf"></i><span style="color:#ff4444;"> PDF</span></a>  
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div id="datatable-buttons_filter" class="dataTables_filter"></div>
                                                        </div>
                                                
                                                    <br>
                                                    <thead>
                                                        <tr class="bg-info text-white">
                                                            
                                                            <th  class="text-center">Impresora</th>
                                                            <th  class="text-center">Local</th>
                                                             <th  class="text-center">Serie</th>
                                                              <th  class="text-center">Url</th>
                                                              <th  class="text-center">Ip</th>
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

     <div id="ModalAgregarImpresora" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                                <form id="FormImpresora" action="<?= base_url('administrador/regimpresora/agregarImpresora') ?>" method="post" autocomplete="off">
                                                <input type="hidden" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Agregar impresora</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                             <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre impresora:<span class="text-danger"> *</span></label>
                                                                <input type="text" name="impresora" class="form-control ">
                                                            </div>
                                                       </div>

                                                         <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Local:<span class="text-danger"> *</span></label>
                                                                <input type="text" name="nombrelocal" class="form-control ">
                                                            </div>
                                                       </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Serie:</label>
                                                                <input type="text" name="serie" class="form-control ">
                                                            </div>
                                                       </div>

                                                       <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Url:</label>
                                                                <input type="text" name="url" class="form-control ">
                                                            </div>
                                                       </div>

                                                       <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Ip:<span class="text-danger"> *</span></label>
                                                                <input type="text" name="ip" class="form-control ">
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




    <div id="ModalEditarImpresora" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarImpresora" action="<?= base_url('administrador/regimpresora/editimpresora') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar informacion impresora</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <div class="row">
                                                       <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre impresora:<span class="text-danger"> *</span></label>
                                                                <input type="text" name="impresora" class="form-control ">
                                                            </div>
                                                       </div>

                                                         <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Local:<span class="text-danger"> *</span></label>
                                                                <input type="text" name="nombrelocal" class="form-control ">
                                                            </div>
                                                       </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Serie:</label>
                                                                <input type="text" name="serie" class="form-control ">
                                                            </div>
                                                       </div>

                                                       <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Url:</label>
                                                                <input type="text" name="url" class="form-control ">
                                                            </div>
                                                       </div>

                                                       <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Ip:<span class="text-danger"> *</span></label>
                                                                <input type="text" name="ip" class="form-control ">
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