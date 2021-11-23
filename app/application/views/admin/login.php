
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistema de gestión de ventas con facturacion electrónica</title>
        <meta content="Sistema de gestión de ventas con facturacion electrónica" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />   



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>    

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url_app() ?>assets/images/favicon.ico">



        <!-- App css -->
        <link href="<?php echo base_url_app() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url_app() ?>assets/plugins/switchery/switchery.min.css" rel="stylesheet">
        <link href="<?php echo base_url_app() ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url_app() ?>assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url_app() ?>assets/css/app.css" rel="stylesheet" type="text/css" />
      
        <!-- your custom css -->
        <link href="<?php echo base_url_app() ?>assets/css/style.css" rel="stylesheet" type="text/css" />

        <script src="<?php echo base_url_app() ?>assets/js/modernizr.min.js"></script>


    </head>


   <body class="container register-body">
    <div class="container register">
    	<form role="form" class="form-horizontal" action="<?php echo base_url();?>auth/login" method="post">
                <div class="row">
                    <div class="col-md-3 register-left">
                        <img src="<?php echo base_url_app() ?>assets/images/bee.png" alt="">
                        <h3 class="m-0 text-center text-white">Bienvenido a</h3>
                        <p>BFacturas tu sistema de gestión de ventas con facturación electronica!</p>
                        
                    </div>
                    <div class="col-md-9 register-right">
                        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Ingresar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Facturas</a>
                            </li>
                        </ul>
                       
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <?php 
                                if($error=$this->session->flashdata('message')):
                                 ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                      <strong></strong> <?php echo $this->session->flashdata('message');  ?> 
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>

                                <?php endif; ?>
                                                              
                                <h3 class="register-heading">Ingresar al sistema</h3>                                                                      
                                <div class="row register-form">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Usuario *" value="" name="username" required="">
                                        </div>
                                      
                                        <div class="form-group">
                                            <input type="password" class="form-control password1" placeholder="contraseña *" value="" name="paswoord" required="">
                                            <span class="fa fa-fw fa-eye password-icon show-password text-success"></span>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="switchery-demo">
                                                
                                                	<input type="checkbox" checked data-plugin="switchery" data-color="#1bb99a" data-size="small"/>
                                                    <!-- <input type="radio" name="gender" value="male" checked=""> -->
                                                         <label for="remember">Recordar </label>                                       
                                            </div>
                                        </div>
                                    
                                      <div class="form-group">                                 
                                                                            
                                        <button class="btn-md btn-block btnRegister waves-effect waves-light" type="submit">Ingresar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h3 class="register-heading">Descargar mis facturas</h3>
                                <div class="row register-form">
                               
                                    <div class="col-md-6">
                                    	<label>Para descargar tu factura dale click aqui </label>
                                        
                                        <div class="form-group">
                                        <input type="submit" class="btnRegister" value="Descargar">
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </form>

            </div>                                             


                    <!-- jQuery  -->
        <script src="<?php echo base_url_app();?>assets/plugins/switchery/switchery.min.js"></script>
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


        <script src="<?php echo base_url_app();?>assets/plugins/notifications/notify.min.js"></script>
        <script src="<?php echo base_url_app();?>assets/plugins/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url_app();?>assets/plugins/notifications/notifications.js"></script>

        <script type="text/javascript">
                   window.addEventListener("load", function() {

            // icono para mostrar contraseña
            showPassword = document.querySelector('.show-password');
            showPassword.addEventListener('click', () => {

                // elementos input de tipo clave
                password1 = document.querySelector('.password1');
                password2 = document.querySelector('.password2');

                if ( password1.type === "text" ) {
                    password1.type = "password"                 
                    showPassword.classList.remove('fa-eye-slash');
                } else {
                    password1.type = "text"                   
                    showPassword.classList.toggle("fa-eye-slash");
                }

            })

        });
        </script>

    </body>
