

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
                            <div class="col-sm-12">
                                <div class="profile-bg-picture" style="background-image:url('<?= base_url_app() ?>assets/images/portada/fondo.jpg')">
                                    <span class="picture-bg-overlay"></span><!-- overlay -->
                                </div>
                                <!-- meta -->
                                <div class="profile-user-box">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="profile-user-img"><img src="<?= base_url_app() ?>assets/images/users/usuario_inicio.png" alt="" class="thumb-lg rounded-circle"></div>
                                            <div class="">
                                                <h4 class="mt-5 m-b-5 font-18 ellipsis"><?= $this->session->userdata('nomb_usu').' '.$this->session->userdata('apell_usu') ?></h4>
                                                <p class="font-13"><?= $perfil->nomb_perfil ?></p>
                                                <!-- <p class="text-muted m-b-0"><small>Ayacucho, Peru</small></p> -->
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-right">
                                                <button type="button" class="btn btn-success waves-effect waves-light">
                                                    <i class="mdi mdi-account-settings-variant m-r-5"></i> Edit Profile
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ meta -->
                            </div>
                            </div>
                        </div>
                       
                                                
                        <div class="row m-t-30">
                            <div class="col-sm-12">
                                <div class="card p-0">
                                    <div class="card-body p-0"> 
                                        <ul class="nav nav-tabs profile-tabs">
                                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#aboutme">Acerca de mi</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#user-activities">Ocupaciones</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#edit-profile">Actulizar datos</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#projects">Proyectos</a></li>
                                        </ul>
        
                                        <div class="tab-content m-0 p-4"> 
        
                                            <div id="aboutme" class="tab-pane active">
                                            <div class="profile-desk">
                                                <h4 class="text-uppercase font-weight-bold"><?= $this->session->userdata('nomb_usu').' '.$this->session->userdata('apell_usu') ?></h4>
                                                <div class="designation mb-4"><?= $perfil->nomb_perfil ?></div>
                                                <p class="text-muted">
                                                <?=$usuarios->acerca_usu ?>
                                                </p>
                                                <a class="btn btn-primary m-t-20" href="#"> <i class="fa fa-check"></i> Compartir</a>
                                                
                                                <table class="table table-condensed table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="3"><h4 class="mt-4">Informacion de contacto</h4></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><i class="fa fa-hospital-o"></i><b>Url</b></td>
                                                            <td>
                                                            <a target="_blank" href="http://www.bee.com.pe/" class="ng-binding">
                                                                <span class="pull-right badge bg-info"><i class="fas fa-globe"></i></span>&nbsp;www.bee.com.pe
                                                            </a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Email</b></td>
                                                            <td>
                                                            <a href="" class="ng-binding"><span class="pull-right badge bg-info"><i class="far fa-envelope"></i></span>&nbsp;
                                                                <?=$usuarios->email_usu ?>
                                                            </a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Phone</b></td>
                                                            <td class="ng-binding"><span class="pull-right badge bg-info"><i class="fas fa-mobile-alt"></i></span>&nbsp;<?=$usuarios->telf_usu ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Facebook</b></td>
                                                            <td>
                                                            <a href="" class="ng-binding">
                                                                <span class="pull-right badge bg-info"><i class="fab fa-facebook-f"></i></span>&nbsp;jonathandeo123
                                                            </a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- end profile-desk -->
                                        </div> <!-- about-me -->
        
        
                                        <!-- Activities -->
                                        <div id="user-activities" class="tab-pane">
                                            <div class="timeline-2">
                                                <div class="time-item">
                                                    <div class="item-info ml-3 mb-3">
                                                        <div class="text-muted">5 minutes ago</div>
                                                        <p><strong><a href="#" class="text-info">John Doe</a></strong> Uploaded a photo <strong>"DSC000586.jpg"</strong></p>
                                                    </div>
                                                </div>
        
                                                <div class="time-item">
                                                    <div class="item-info ml-3 mb-3">
                                                        <div class="text-muted">30 minutes ago</div>
                                                        <p><a href="" class="text-info">Lorem</a> commented your post.</p>
                                                        <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                    </div>
                                                </div>
        
                                                <div class="time-item">
                                                    <div class="item-info ml-3 mb-3">
                                                        <div class="text-muted">59 minutes ago</div>
                                                        <p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>
                                                        <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                    </div>
                                                </div>
        
                                                <div class="time-item">
                                                    <div class="item-info ml-3 mb-3">
                                                        <div class="text-muted">5 minutes ago</div>
                                                        <p><strong><a href="#" class="text-info">John Doe</a></strong>Uploaded 2 new photos</p>
                                                    </div>
                                                </div>
        
                                                <div class="time-item">
                                                    <div class="item-info ml-3 mb-3">
                                                        <div class="text-muted">30 minutes ago</div>
                                                        <p><a href="" class="text-info">Lorem</a> commented your post.</p>
                                                        <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                    </div>
                                                </div>
        
                                                <div class="time-item">
                                                    <div class="item-info ml-3 mb-3">
                                                        <div class="text-muted">59 minutes ago</div>
                                                        <p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>
                                                        <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut tincidunt euismod. "</em></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <!-- settings -->
                                        <div id="edit-profile" class="tab-pane">
                                            <div class="user-profile-content">
                                                <!-- <form role="form"> -->
                                                    <form id="FormEditarUsuario" action="<?= base_url('perfil/editPerfil') ?>" method="post" autocomplete="off">
                                                        <input type="hidden" name="id" value="<?=$usuarios->cod_usu ?>"> 
                                                    <div class="form-group">
                                                        <label for="FullName">Nombre completo</label>
                                                        <input type="text" name="nombre" value="<?= $usuarios->nomb_usu.' '.$usuarios->apell_usu ?>" id="FullName" class="form-control" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Email">Email</label>
                                                        <input type="email" name="email" value="<?=$usuarios->email_usu ?>" id="Email" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Username">Usuario</label>
                                                        <input type="text" name="login" value="<?=$usuarios->login_usu ?>" id="Username" class="form-control" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Password">Contraseña</label>
                                                        <input type="password" name="passwoord" id="Password" placeholder="Ingrese la contraseña" class="form-control" required="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="RePassword">Re-Contraseña</label>
                                                        <input type="password" name="repasswoord" placeholder="Repita la contraseña" id="RePassword" class="form-control" required="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="AboutMe">Acerca de mi</label>
                                                        <textarea style="height: 125px;" name="about" id="AboutMe" class="form-control" placeholder="Escribe una breve descripcion acerca de ti "></textarea>
                                                    </div>
                                                    <button class="btn btn-primary" type="submit">Guardar</button>
                                                </form>
                                            </div>
                                        </div>
        
        
                                        <!-- profile -->
                                        <!-- <div id="projects" class="tab-pane">
                                            <div class="row m-t-10">
                                                <div class="col-md-12">
                                                    <div class="portlet">
                                                        <div id="portlet2" class="panel-collapse collapse show">
                                                            <div class="portlet-body">
                                                                <div class="table-responsive">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Project Name</th>
                                                                                <th>Start Date</th>
                                                                                <th>Due Date</th>
                                                                                <th>Status</th>
                                                                                <th>Assign</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Velonic Admin</td>
                                                                                <td>01/01/2015</td>
                                                                                <td>07/05/2015</td>
                                                                                <td><span class="label label-info">Work in Progress</span></td>
                                                                                <td>Coderthemes</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Velonic Frontend</td>
                                                                                <td>01/01/2015</td>
                                                                                <td>07/05/2015</td>
                                                                                <td><span class="label label-success">Pending</span></td>
                                                                                <td>Coderthemes</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Velonic Admin</td>
                                                                                <td>01/01/2015</td>
                                                                                <td>07/05/2015</td>
                                                                                <td><span class="label label-pink">Done</span></td>
                                                                                <td>Coderthemes</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Velonic Frontend</td>
                                                                                <td>01/01/2015</td>
                                                                                <td>07/05/2015</td>
                                                                                <td><span class="label label-purple">Work in Progress</span></td>
                                                                                <td>Coderthemes</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>Velonic Admin</td>
                                                                                <td>01/01/2015</td>
                                                                                <td>07/05/2015</td>
                                                                                <td><span class="label label-warning">Coming soon</span></td>
                                                                                <td>Coderthemes</td>
                                                                            </tr>
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
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



