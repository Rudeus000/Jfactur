<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BFacturs - SMS</title>
        <meta content="Sistema de gestión de ventas con facturacion electrónica" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?= base_url_app() ?>assets/images/favicon.ico">

        <!-- App css -->
        <link href="<?= base_url_app() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url_app() ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url_app() ?>assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url_app() ?>assets/css/app.css" rel="stylesheet" type="text/css" />
        <!-- your custom css -->
        <link href="<?= base_url_app() ?>assets/css/style.css" rel="stylesheet" type="text/css" />

        <script src="<?= base_url_app() ?>assets/js/modernizr.min.js"></script>

    </head>


    <body>
			<!-- new -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-11">
                                <div class="profile-bg-picture" style="background-image:url('<?= base_url_app() ?>assets/images/portada/portada.png')">
                                    <!-- <span class="picture-bg-overlay"></span> --><!-- overlay -->
                                </div>
                                <!-- meta -->
                                <div class="profile-user-box">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="profile-user-img"><img class="thumb-lg rounded-circle bx-s" src="<?= base_url_app() ?>assets/images/users/usuario_inicio.png" alt=""></div>

                                            <div class="">
                                                <h4 class="mt-5 m-b-5 font-18 ellipsis"><?= $usuario->nomb_usu.' '.$usuario->apell_usu ?></h4>
                                                <p class="font-13"><?= $perfil->nomb_perfil ?></p>
                                                <p class="text-muted m-b-0"><small>California, United States</small></p>
                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-right">
                                                <!-- <button href="<?=base_url('auth/logout')?>" type="button" class="btn btn-danger waves-effect waves-light"> -->
                                                <a href="<?= base_url('auth/logout') ?>" class="btn btn-danger waves-effect waves-light">
                                                
                                                    <i class="mdi mdi-account-settings-variant m-r-5"></i> Cerrar sesión 
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ meta -->
                            </div>
                            </div>
                        </div>

                       
                        <div class="row m-t-30">
                            <div class="col-sm-11">
                                <div class="card p-0">
                                    <div class="card-body p-0"> 
                                        <ul class="nav nav-tabs profile-tabs">
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#aboutme">Mis datos</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#user-activities">Sucursales</a></li>
                                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#edit-profile">Que hay de nuevo hoy!</a></li>
                                            
                                        </ul>
        
                                        <div class="tab-content m-0 p-4"> 
        
                                            <div id="aboutme" class="tab-pane">
                                            <div class="profile-desk">
                                                <h4 class="text-uppercase font-weight-bold"><?= $usuario->nomb_usu.' '.$usuario->apell_usu ?></h4>
                                                <div class="designation mb-4"><?= $perfil->nomb_perfil ?></div>                                            
                                              
                                                
                                                <table class="table table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="3"><h4 class="mt-4">Información de contacto</h4></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Documento:</b></td>
                                                            <td>
                                                            <a href="#" class="ng-binding">
                                                               <?= $usuario->docum_usu ?>
                                                            </a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Teléfono:</b></td>
                                                            <td>
                                                            <a href="" class="ng-binding">
                                                               <?= $usuario->telf_usu ?>
                                                            </a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Dirección:</b></td>
                                                            <td>
                                                            <a href="" class="ng-binding">
                                                             <?= $usuario->direcc_usu ?>
                                                             </a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Email:</b></td>
                                                            <td>
                                                            <a href="" class="ng-binding">
                                                                <?= $usuario->email_usu ?>
                                                            </a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Usuario:</b></td>
                                                            <td>
                                                            <a href="" class="ng-binding">
                                                                 <?= $usuario->login_usu ?>
                                                            </a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- end profile-desk -->
                                        </div> <!-- about-me -->
                                      
        
        
                                        <!-- Activities -->
                                        <div id="user-activities" class="tab-pane">
                                            <div class="row m-t-10">
                                                <div class="col-md-12">
                                                    <div class="portlet"><!-- /primary heading -->
                                                        <div id="portlet2" class="panel-collapse collapse show">
                                                            <div class="portlet-body">
                                                                <div class="table-responsive">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Opción</th>
                                                                                <th>Sucursal</th>                                                                              
                                                                               
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody> 
                                                                        <?php
                                                                                $item = 1;
                                                                               
                                                                                ?>                                                                      
                                                                        	<?php foreach($sucursales as $s): ?>
                                                                            <tr>                                                                              

                                                                                <td><span class="badge badge-primary"><?=  $item++;?></span></td>
                                                                                <td><a href="<?= base_url('auth/setPuntoVenta/'.$s->cod_puntoventa) ?>" class="btn btn-info btn-sm">Acceder</a></td>
                                                                                <td>
                                                                                <?php if($s->cod_puntoventa==$this->session->userdata('puntoventa_reportes')): ?> 
																				<i class="fa fa-check"></i>
																				<?php endif ?>
                                                                                </td>
																				<td><span class="label label-info"><?= $s->nomb_puntoventa ?></span></td>
                                                                            </tr>
                                                                            <?php endforeach ?>
																			<?php if($perfil->cod_perfil==1): ?> 
                                                                            <tr>
                                                                            <td> <span class="badge badge-danger"><?= $item ?></span></td>
                                                                                <td><a href="<?= base_url('auth/setPuntoVenta/admin') ?>" class="btn btn-primary btn-sm">Acceder</a></td>
                                                                                 <td>                                                                               
                                                                                	<?php if($this->session->userdata('puntoventa_reportes')=='admin'): ?> 
																					<i class="fa fa-check"></i>
																					<?php endif ?>
                                                                                </td>
                                                                                <td><span class="label label-pink">Acceso Administrador</span></td>
                                                                               
                                                                            </tr>
                                                                            <?php endif ?>
                                                                           
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> <!-- /Portlet -->
                                                </div>
                                            </div>
                                        </div>

        
                                        <!-- settings -->

                                        <div id="edit-profile" class="tab-pane active">
                                            <div class="profile-desk">
                                            

                                                                    
                                                                        <?php foreach($nuevos as $n): ?>                                                                        

                                                                        <div class="col-md-12">
                                                                            <p class="text-muted m-b-0"><small>Post creado: <?=$n->fecha ?></small></p>                                                                         
                                                                            <h4 class="mt-5 m-b-5 font-18 ellipsis"><?= $n->titulo ?></h4>                                                                          
                                                                                
                                                                            <div class="card">                                                                                
                                                                                <div class="card-body">                                                                                    
                                                                                    <div class="quehaydenuevo-content">
                                                                                        <div class="col-md-12">
                                                                                            <?= $n->contenido ?>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                       
                                                                       
                                                                        <?php endforeach  ?>  
                                                                    
                                                                </div>
                                                            </div>
                                                



                                        <!-- end -->
                                       <!--  <div id="edit-profile" class="tab-pane active">
                                            <div class="user-profile-content">
                                                <form role="form">
                                                    
                                                       <thead>
                                                        <tr>
                                                            <th colspan="3"><h4 class="mt-4"><?= $n->titulo ?></h4></th>
                                                        </tr>
                                                      </thead>
                                                    
                                                                                                 
                                                                                                    
                                                </form>
                                            </div>
                                        </div>                
 -->
                                    </div>
                        
                                </div> 
                            </div>
                        </div>
                                    


                    </div> <!-- container -->

            
                </div> <!-- content -->

                <footer class="footer text-right">                    
                    2019 - 2022 © Bee company - SMS
                </footer>

            </div>

            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->




        <!-- jQuery  -->
        <script src="<?php echo base_url_app();?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url_app();?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url_app();?>assets/js/metisMenu.min.js"></script>
        <script src="<?php echo base_url_app();?>assets/js/waves.js"></script>
        <script src="<?php echo base_url_app();?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo base_url_app();?>assets/js/jquery.backstretch.min.js"></script>
        <script src="<?php echo base_url_app();?>assets/js/scripts.js"></script>
        <!-- App js -->
        <script src="<?php echo base_url_app();?>assets/js/jquery.core.js"></script>
        <script src="<?php echo base_url_app();?>assets/js/jquery.app.js"></script>

    </body>

</html>
