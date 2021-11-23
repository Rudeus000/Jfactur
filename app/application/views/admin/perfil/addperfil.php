<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BFacturas - perfil</title>
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
                                    <!-- <h4 class="page-title float-left">Agregar perfil</h4> -->

                                    <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="#">Gestion</a></li>
                                        <li class="breadcrumb-item"><a href="#">Perfil</a></li>
                                        <li class="breadcrumb-item active">Agregar</li>
                                    </ol>

                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                <div class="card-header bg-primary"><h3 class="my-0 text-white">Agregar perfil de usuario</h3></div>
                                    <div class="card-body">
                                    <ol class="breadcrumb">
                                        <li><a href="<?= base_url('administrador/regperfil') ?>"><i class="ion ion-md-arrow-round-back"></i> Regresar</a></li>
                                           
                                    </ol>
                                    <form id="FormRegistrarPerfil" action="<?php echo base_url();?>administrador/regperfil/guardar" method="POST" autocomplete="off">                               
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label  class="col-form-label">Grupo:<span class="text-danger"> *</span></label>
                                                    <input type="text" class="form-control" parsley-trigger="change" name="descripcion" onkeypress="return soloLetras(event)">
                                                </div>
                                                
                                                <div class="form-group col-md-3">
                                                    <label  class="col-form-label">Estado</label>
                                                    <select class="form-control select2 select2-hidden-accessible" name="estado" >
                                                      <option value="1">Activado</option>
                                                      <option value="2">Inactivo</option>
                                                   </select>
                                                </div>   
                                            </div>
                                            <div class="form-group mb-0 justify-content-end row">
                                                <div class="col-0">
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div> <!-- end card-box -->
                            </div> <!-- end col -->
                        </div>
                        <!-- end Row -->
                    </div> <!-- container -->
                </div> <!-- content -->
            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

    </body>

    <script>
            function soloLetras(e){
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toLowerCase();
            letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
            especiales = "8-37-39-46";

            tecla_especial = false
            for(var i in especiales){
                    if(key == especiales[i]){
                        tecla_especial = true;
                        break;
                    }
                }

                if(letras.indexOf(tecla)==-1 && !tecla_especial){
                    return false;
                }
            }
    </script> 
</html>