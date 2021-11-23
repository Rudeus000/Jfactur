<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solution</title>
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
                                    <!-- <h4 class="page-title float-left">Agregar Permisos</h4> -->

                                    <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="#">Gestion</a></li>
                                        <li class="breadcrumb-item"><a href="#">Permisos</a></li>
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
                                    <div class="card-header bg-primary"><h3 class="my-0 text-white">Agregar permisos por perfil y modulo</h3></div>
                                        <div class="card-body">
                                            <ol class="breadcrumb">
                                        <li><a href="<?= base_url('administrador/permisos') ?>"><i class="ion ion-md-arrow-round-back"></i> Regresar</a></li>
                                           
                                           </ol>
                                         <form  action="<?php echo base_url();?>administrador/permisos/store" method="POST" autocomplete="off">                               
                                     
                                                <div class="form-group col-md-4">
                                                    <label  class="col-form-label">Perfil:<span class="text-danger"> *</span></label>
                                                     <select name="perfil"  class="form-control select2">
                                                               <?php foreach ($perfil as $p): ?>
                                                                          <option value="<?= $p->cod_perfil ?>"><?= $p->nomb_perfil ?></option>
                                                                        <?php endforeach ?>
                                                     </select>
                                                </div>


                                                <div class="form-group col-md-4">
                                                    <label  class="col-form-label">Modulo:<span class="text-danger"> *</span></label>
                                                     <select name="menu"  class="form-control select2">
                                                              <?php foreach ($menus as $m): ?>
                            <option value="<?= $m->id_menu ?>"><?= $m->nombre ?></option>
                          <?php endforeach ?>
                                                     </select>
                                                </div>

                                                <div class="form-group col-md-8">
                                                    <label for="read">Leer: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="radio-inline">
                                                    <input type="radio" name="read" value="1" checked="checked"> Si
                                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="radio-inline">
                                                    <input type="radio" name="read" value="0" checked="checked"> No
                                                    </label>
                                               </div>

                                               <div class="form-group col-md-8">
                                                        <label for="read">Insertar: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="radio-inline">
                                                        <input type="radio" name="insert" value="1" checked="checked"> Si
                                                        </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="radio-inline">
                                                        <input type="radio" name="insert" value="0" checked="checked"> No
                                                        </label>
                                               </div>

                                                <div class="form-group col-md-8">
                                                        <label for="read">Actualizar: </label>&nbsp;
                                                        <label class="radio-inline">
                                                        <input type="radio" name="update" value="1" checked="checked"> Si
                                                        </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="radio-inline">
                                                        <input type="radio" name="update" value="0" checked="checked"> No
                                                        </label>
                                               </div>

                                                <div class="form-group col-md-8">
                                                    <label for="read">Anular: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="radio-inline">
                                                    <input type="radio" name="delete" value="1" checked="checked"> Si
                                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="radio-inline">
                                                    <input type="radio" name="delete" value="0" checked="checked"> No
                                                    </label>
                                                </div>
                                                
                                                 
                                   
                                            <div class="form-group mb-0 justify-content-end row">
                                                <div class="col-0">
                                                    <button type="submit" class="btn btn-info waves-effect waves-light">Guardar</button>
                                                </div>
                                            </div>
                                        </form>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                                
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