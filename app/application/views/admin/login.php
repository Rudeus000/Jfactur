<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFacturas - SMS</title>
    <meta content="Sistema de gestión de ventas con facturacion electrónica" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Agrega los scripts de SweetAlert y jQuery -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url_app() ?>assets/images/favicon.ico">

    <!-- App css -->
    <link href="<?php echo base_url_app() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url_app() ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url_app() ?>assets/css/app.css" rel="stylesheet" type="text/css" />

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <!-- your custom css -->
    <link href="<?php echo base_url_app() ?>assets/css/style.css" rel="stylesheet" type="text/css" />

    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
        function onSubmit(token) {
            document.getElementById("demo-form").submit();
        }
    </script>






</head>


<body class="container register-body">
    <div class="container register">
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
                        <a class="nav-link " id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Registrar</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                        <form role="form" id="demo-form" class="form-horizontal" action="<?php echo base_url(); ?>auth/login" method="post">
                            <?php
                            if ($error = $this->session->flashdata('message')) :
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
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Usuario *" value="" name="username" required="">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class=" ion ion-md-person"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control password1" placeholder="contraseña *" value="" name="paswoord" required="">
                                        <span class="fa fa-fw fa-eye password-icon show-password text-success"></span>
                                    </div>

                                    <div class="form-group">
                                        <div class="switchery-demo">

                                            <input type="checkbox" checked data-plugin="switchery" data-color="#1bb99a" data-size="small" />
                                            <!-- <input type="radio" name="gender" value="male" checked=""> -->
                                            <label for="remember">Recordar </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <!-- <button class="btn-md btn-block btnRegister waves-effect waves-light" type="submit">Ingresar</button> -->
                                        <button class="g-recaptcha btnRegister waves-effect waves-light" data-sitekey="6LejkR8pAAAAAK-_jKSsr4xPFr65xM-rWxw7Af-g" data-callback='onSubmit' data-action='submit'>Ingresar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form role="form" id="FormRegistronewuser" class="form-horizontal" action="<?= base_url('auth/registrarnewusuario') ?>" method="post" autocomplete="off">
                            <h3 class="">Registrar mi empresa gratis</h3>
                            <div class="row ">

                                <div class="col-md-6">
                                    <label>Nombres</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class=" ion ion-md-person"></i></span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Usuario *" value="" name="newnombre">
                                    </div>
                                    <!-- <div class="form-group"> -->
                                    <!-- <input type="submit" class="btnRegister" value="Descargar"> -->
                                    <!-- <a href="<?= base_url('administrador/regcomprobante') ?>" class="btn btn-success btn-rounded w-md waves-effect waves-light btnRegister" role="button">Ingresar al portal</a>                                      
                                    </div> -->
                                </div>
                                <div class="col-md-6">
                                    <label>Apellidos</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-id-card"></i></span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Ingrese su correo *" value="" name="newapellido" required="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>E-mail</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ion ion-ios-mail"></i></span>
                                        </div>
                                        <input type="email" class="form-control" placeholder="Ingrese su correo *" value="" name="newemail" required="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Contraseña</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ion ion-md-lock"></i></span>
                                        </div>
                                        <input type="password" class="form-control" placeholder="Crea una contraseña *" value="" name="newpassword" required="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Ruc:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ion ion-ios-business"></i></span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="ingrese numero de ruc *" value="" name="newruc" required="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button">Sunat</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Razon social:</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Razon social *" value="" name="newrsocial" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Numero del movil</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ion ion-logo-whatsapp"></i></span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Ingrese numero del movil *" value="" name="newphone" required="">
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="autoSizingCheck">
                                        <label class="form-check-label" for="autoSizingCheck">
                                            He leído y acepto los términos y condiciones y la política de privacidad.
                                        </label>
                                    </div>

                                    <button class="btn-md btn-block btnRegister waves-effect waves-light" type="submit">Registrar</button>


                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- </form> -->

        </div>
        <script src="<?php echo base_url_app(); ?>assets/plugins/jquery-validate/jquery.validate.js"></script>
        <script src="<?php echo base_url_app(); ?>assets/plugins/jquery-validate/additional-methods.js"></script>
        <script src="<?php echo base_url_app(); ?>assets/plugins/jquery-validate/localization/messages_es.js"></script>
        <script src="<?php echo base_url_app(); ?>assets/js/scripts.js"></script>

        <script type="text/javascript">
            window.addEventListener("load", function() {

                // icono para mostrar contraseña
                showPassword = document.querySelector('.show-password');
                showPassword.addEventListener('click', () => {

                    // elementos input de tipo clave
                    password1 = document.querySelector('.password1');
                    password2 = document.querySelector('.password2');

                    if (password1.type === "text") {
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