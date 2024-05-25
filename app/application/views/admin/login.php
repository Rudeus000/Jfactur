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
                        <?php $empresa = getDatosEmpresa(); ?>
                        <?php if ($empresa['empresa']->company_status == 0) : ?>
                            <a class="nav-link" id="cpe-tab" data-toggle="tab" href="#demo" role="tab" aria-controls="cpe" aria-selected="false">Registrar</a>
                        <?php else : ?>
                            <a class="nav-link" id="demo-tab" data-toggle="tab" href="#cpe" role="tab" aria-controls="demo" aria-selected="false">CPE</a>
                        <?php endif; ?>
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
                                        <button class="g-recaptcha btnRegister waves-effect waves-light" data-sitekey="6LeC4CgpAAAAAMh1A_t0iiTP2spx2vj167P5mZX-" data-callback='onSubmit' data-action='submit'>Ingresar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade show" id="demo" role="tabpanel" aria-labelledby="demo-tab">
                        <form role="form" id="FormRegistronewuser" class="form-horizontal needs-validation" novalidate action="<?= base_url('auth/registrarnewusuario') ?>" method="post" autocomplete="off">
                            <h3 class="register-heading">Registrar mi empresa gratis</h3>
                            <div class="row register-form ">

                                <div class="col-md-6">
                                    <label>Nombres</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class=" ion ion-md-person"></i></span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Usuario *" value="" name="newnombre">
                                    </div>

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
                                        <input type="text" class="form-control" placeholder="ingrese numero de ruc *" value="" name="newruc" id="newruc" required="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="buscar();">Sunat</button>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Razon social:</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Razon social *" value="" name="newrsocial" id="newrsocial" readonly required="">
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
                                        <input class="form-check-input" type="checkbox" id="autoSizingCheck" name="terms" required>
                                        <label class="form-check-label" for="autoSizingCheck">
                                            He leído y acepto los términos y condiciones y la política de privacidad.
                                        </label>
                                    </div>

                                    <button class="btn-md btn-block btnRegister waves-effect waves-light" type="submit">Registrar</button>


                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade show" id="cpe" role="tabpanel" aria-labelledby="cpe-tab">

                        <h3 class="register-heading">Descargar mis comprobantes</h3>
                        <div class="row register-form ">

                            <div class="col-md-6">
                                <label>Haga click en boton <b>ingresar al portal</b> para descargar los CPE</label>

                                <!-- <div class="form-group"> -->
                                <!-- <input type="submit" class="btnRegister" value="Descargar"> -->
                                <a href="<?= base_url('administrador/regcomprobante') ?>" class="btn btn-success btn-rounded w-md waves-effect waves-light btnRegister" role="button">Ingresar al portal</a>
                            </div>
                        </div>

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

        <script>
            function buscar() {

                tipo_doc = 1;

                if (tipo_doc == "") {

                    alert("Debes seleccionar un tipo de documento.");

                } else {

                    $('#capa_load').html('<img src="<?= base_url_app() ?>assets/images/loading.gif" alt="" style="position: absolute;top: 10px;left: 46%;">');

                    $.post('<?= base_url() ?>Validardatos/validarDocumento', {
                        dni: $('#newruc').val(),
                        tipo_doc: tipo_doc
                    }, function(data) {



                        if (tipo_doc == "2") {

                            var datos = eval(data);

                            if (datos == ",,,") {

                                alert("No existe este DNI.");

                                $('#capa_load').html("");

                                $("#FormVentaAgregarCliente")[0].reset();

                            } else {

                                $('#txt_documento').val(datos[0]);

                                $('#txt_nombre').val(datos[5]);

                                $('#txt_direccion').val(datos[4]);

                                $('#fnacimiento').val(datos[6]);


                                $('#capa_load').html("");

                            }

                        } else {

                            var datos = eval(data);

                            var nada = 'nada';

                            doc = $('#newruc').val();

                            if (doc.length < 11) {
                                alert("ingrese ruc valido");

                            }

                            if (datos[0] == nada) {

                                alert('RUC no válido o no registrado');

                                $('#capa_load').html("");

                                $("#FormVentaAgregarCliente")[0].reset();

                            } else {

                                $('#newruc').val(datos[0]);
                                $('#newrsocial').val(datos[1]);

                            }

                            $('#capa_load').html("");

                        }



                    });

                }

            }
        </script>

</body>