<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BFacturas - tipo documento</title>
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
                                    <!-- <h4 class="page-title float-left">Tipo de Documento</h4> -->
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Administrar</a></li>
                                        <li class="breadcrumb-item"><a href="#">Tipo documento</a></li>
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
                                <div class="card-header bg-primary"><h3 class="my-0 text-white">Administrar tipo de documentos electronicos<a data-toggle="modal" data-target="#ModalAgregarDocum" class="btn btn-pink btn-rounded  w-md waves-effect float-right" ><i class="fa fa-plus m-r-5"></i>Nuevo</a></h3></div>
                                    <div class="card-body"> 
                             
                                    <div class="col-md-8">
                                    <form id="DocumentoFormBusqueda" autocomplete="off">
                                         <label class="control-label " >Buscar por documento:</label>
                                            <div class="input-group">
                                                <input type="text"  name="tb_tipodocumento" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-effect-ripple btn-purple"><i class="fa fa-search"></i></button>
                                                    </span>
                                            </div>
                                            </form>
                                    </div>  
  
                                        <!-- End #wizard-vertical -->
                                    <!-- </div>
                                </div>
                            </div> -->
                        </div><!-- End row -->  
        
        
                        <!-- Vertical Steps Example -->
                        <!-- <div class="row">
                            <div class="col-sm-12">
                                <div class="card"> -->
                                    <div class="card-body table-responsive">
                                   
                                  
                                         
                                                <table id="TableMantenimientoDocum" class="table  table-striped" cellspacing="0" width="100%">
                                                     
                                                <div class="col-sm-12 col-md-6">
                                                            <div class="dt-buttons btn-group" data-toggle="modal" data-target="#ModalAgregarDocum">
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
                                                        <tr class="bg-primary text-white">
                                                            <th class="text-center">Secuencia</th>
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

     <div id="ModalAgregarDocum" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                                <form id="FormDocumento" action="<?= base_url('administrador/regtipodocum/agregarDocum') ?>" method="post" autocomplete="off">
                                                <input type="hidden" > 
                                                    <div class="modal-header bg-primary">
                                                        <h4 class="custom-modal text-white" >Informacion de documento</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                             <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre del documento:</label>
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




    <div id="ModalEditarDocumento" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarDocumento" action="<?= base_url('administrador/regtipodocum/editDocumento') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header bg-primary">
                                                        <h4 class="custom-modal text-white"  >Editar almacen</h4>
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
                                                                <label class="control-label">Para venta:</label>
                                                                  <select class="form-control select" name="estado" >
                                                                         <option value="1" >Activo</option>
                                                                         <option value="2" >Inactivo</option>
                                                                
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