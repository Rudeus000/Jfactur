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
                <div class="content">
                    <div class="container-fluid">
                         <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <!-- <h4 class="page-title float-left">Editar Permisos</h4> -->

                                    <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="#">Modulo</a></li>
                                        <li class="breadcrumb-item"><a href="#">Permisos</a></li>
                                        <li class="breadcrumb-item active">Agregar</li>
                                    </ol>

                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                <div class="card-header bg-primary"><h3 class="my-0 text-white">Editar permisos</h3></div>
                                    <div class="card-body">

                                         <ol class="breadcrumb">
                                        <li><a href="<?= base_url('administrador/permisos') ?>"><i class="ion ion-md-arrow-round-back"></i> Regresar</a></li>
                                           
                                        </ol>

                                            <form action="<?php echo base_url();?>administrador/permisos/update" method="POST">
                                    <input type="hidden" name="idpermiso" value="<?php echo $permiso->id_permiso;?>">
                                     <div class="form-group">
                                        <label >Perfil:</label>
                                        <select name="rol"  class="form-control" disabled="disabled" >
                                          <?php foreach ($perfil as  $p):?> 
                                    <option value="<?php echo $p->cod_perfil;?>" <?php echo $p->cod_perfil == $permiso->cod_perfil ? "selected":"";?>>
                                    <?php echo $p->nomb_perfil;?></option>
                                          <?php endforeach;?>
                                         
                                        </select>
                                    
                                    </DIV>

                                    <DIV class="form-group">
                                        <label >Menu:</label>
                                        <select name="menu"  class="form-control" disabled="disabled">
                                          <?php foreach ($menus as  $menu):?> 
                                    <option value="<?php echo $menu->id_menu;?>" <?php echo $menu->id_menu == $permiso->id_menu  ? "selected":"";?>>
                                    <?php echo $menu->nombre;?></option>
                                          <?php endforeach;?>
                                         
                                        </select>
                                    
                                    </DIV>
                                     <DIV class="form-group">
                                        <label for="read">Leer: </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="read" value="1" <?php echo $permiso->read == 1 ? "checked":"";?>> Si
                                        </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="read" value="0" <?php echo $permiso->read == 0 ? "checked":"";?>> No
                                        </label>
                                    </DIV>

                                    <DIV class="form-group">
                                        <label for="read">Insertar: </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="insert" value="1" <?php echo $permiso->insert == 1 ? "checked":"";?>> Si
                                        </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="insert" value="0" <?php echo $permiso->insert == 0 ? "checked":"";?>> No
                                        </label>
                                    </DIV>

                                    <DIV class="form-group">
                                        <label for="read">Update: </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="update"  value="1" <?php echo $permiso->update == 1 ? "checked":"";?>> Si
                                        </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="update" value="0" <?php echo $permiso->update == 0 ? "checked":"";?>> No
                                        </label>
                                    </DIV>

                                    <DIV class="form-group">
                                        <label for="read">Anular: </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="delete" value="1" <?php echo $permiso->delete == 1 ? "checked":"";?>> Si
                                        </label>
                                        <label class="radio-inline">
                                        <input type="radio" name="delete" value="0" <?php echo $permiso->delete == 0 ? "checked":"";?>> No
                                        </label>
                                    </DIV>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success"> <span class="fa fa-save">          Guardar</button>
                                        
                                    </div>
                                
                                 </form>
                                    </div>
                                </div>

                            </div>
                            
                        </div>







                    </div>
                    
                </div>
            </div>


        </div>

    </body>

     