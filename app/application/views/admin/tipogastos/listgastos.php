<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solutions - Tipo Gastos</title>
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
                                    <!-- <h4 class="page-title float-left"><i class="ion ion-ios-cube"></i> Tipo Gastos</h4> -->
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Gestion</a></li>
                                        <li class="breadcrumb-item"><a href="#">Tipo Gastos</a></li>
                                        <li class="breadcrumb-item active">Listado</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>
                 

                        <!-- Vertical Steps Example -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                <div class="card-header bg-primary"><h3 class="my-0 text-white">Tipo de gastos<a class="btn btn-pink btn-rounded  w-md waves-effect float-right" data-toggle="modal" data-target="#ModalAgregarTipoGastos"><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
                                    <div class="card-body"> 
                                
                                <fieldset>
                                <legend>Filtro</legend>
                                <form id="TipoGastosFormBusqueda" action="" method="post" autocomplete="off">
                                <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Descripcion</label>                                  
                                    
                                        <div class="input-group">                                        
                                        <input type="text" name="tb_tipo_gastos" class="form-control">
                                        
                                    <div class="input-group-append">
                                        <button class="btn btn-primary waves-effect waves-light" ><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                        </div>
                                    </div>
                                    </div>              

                                </form>
                                </fieldset>
                          
                            <br>        
        
                        <!-- Vertical Steps Example -->
                        <div class="row">
                            <div class="col-sm-12">                           
                                                                     
                                    <div class="row">
                                    <div class="col-md-12">                                  
                                        <a id="ProveedorReportePdf" href="#" class="btn btn-info" target="_blank">PDF</a>
                                        <a id="ProveedorReporteExcel" href="#" class="btn btn-warning" target="_blank">EXCEL</a>
                                    </div>
                                    </div>
                                                <table id="TableMantenimientoTipoGastos" class="table  table-striped" cellspacing="0" width="100%" >
                                                     
                                                       
                                                        <div class="col-sm-12 col-md-6">
                                                            <div id="datatable-buttons_filter" class="dataTables_filter"></div>
                                                     </div>
                                                
                                                    <br>
                                                    <thead>
                                                        <tr class="bg-primary text-white">
                                                            <th class="text-center">ID</th>
                                                            <th  class="text-center">Descripcion</th>
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

     <div id="ModalAgregarTipoGastos" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                                <form id="FormTipoGastos" action="<?= base_url('administrador/regtipogastos/insertTipoGastos') ?>" method="post" autocomplete="off">
                                                <input type="hidden" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Tipo Gastos</h4>
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




    <div id="ModalEditarTipoGastos" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarTipoGastos" action="<?= base_url('administrador/regtipogastos/editTipoGastos') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar Informacion de Marca</h4>
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
                                                                <label class="control-label">Estado:</label>
                                                                  <select class="form-control select" name="estado" >
                                                                           <option value="1" <?php echo set_value('estado',$tipogastos->estado_tipo)==1? "selected" : "" ?>>Activo</option>
                                  <option value="2" <?php echo set_value('estado',$tipogastos->estado_tipo)==2 ? "selected" : "" ?>>Inactivo</option>

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