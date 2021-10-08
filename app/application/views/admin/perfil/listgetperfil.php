<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solutions - perfil</title>
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
                                    <h4 class="page-title float-left">Perfil Usuario</h4>
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="#">Perfil</a></li>
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
                                    <div class="card-body"> 
                                    <ol class="breadcrumb">
                                        <li><a href="<?= base_url('administrador/regperfil') ?>"><i class="ion ion-ios-refresh"></i> Actualizar</a></li>
                                        <li><a href="<?= base_url('administrador/regperfil/nuevo') ?>"><i class="ion ion-ios-person-add"></i> Nuevo</a></li>   
                                    </ol>
                                    
                                    <div class="col-md-8">
                                    <form id="PerfilFormBusqueda" autocomplete="off">
                                         <label class="control-label " >Buscar por descripción:</label>
                                            <div class="input-group">
                                                <input type="text"  name="tb_perfil" class="form-control">
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
                                   
                                  
                                         
                                                <table id="TableMantenimientoPerfil" class="table  table-striped" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr class="bg-info text-white">
                                                            <th style="text-aling:center;">Secuencia</th>
                                                            <th  style="text-aling:center;">Descripcion</th>
                                                            <th  style="text-aling:center;">Estado</th>
                                                            <th  style="text-aling:center;">Acciones</th>
                                                           
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
    <div id="ModalEditarPerfil" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <form id="FormEditarPerfil" action="<?= base_url('administrador/regperfil/editPerfil') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar perfil usuario</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Perfil:</label>
                                                                <input type="text" name="descripcion" class="form-control ">
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