<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFacturas - SMS</title>
    <meta content="Sistema de gestión de ventas con facturacion electrónica" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url_app() ?>assets/images/favicon.ico">
    <!--Morris Chart CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!-- Custom box css -->
    <link href="<?= base_url_app() ?>assets/plugins/custombox/css/custombox.min.css" rel="stylesheet">
    <!-- Dropzone css -->
    <link href="<?= base_url_app() ?>assets/plugins/dropzone/dropzone.css" rel="stylesheet" type="text/css" />

    <!-- Plugins css-->
    <link href="<?= base_url_app() ?>assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="<?= base_url_app() ?>assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="<?= base_url_app() ?>assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?= base_url_app() ?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url_app() ?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="<?= base_url_app() ?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?= base_url_app() ?>assets/plugins/switchery/switchery.min.css" rel="stylesheet">

    <!-- App css -->
    <link href="<?= base_url_app() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url_app() ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url_app() ?>assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url_app() ?>assets/css/app.css" rel="stylesheet" type="text/css" />
    <!-- your custom css -->

    <link href="<?= base_url_app(); ?>assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url_app(); ?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="<?= base_url_app() ?>assets/plugins/EasyAutocomplete-1.3.5/easy-autocomplete.min.css">
    <link rel="stylesheet" href="<?= base_url_app() ?>assets/jquery-upload/css/jquery.fileupload.css">

    <link rel="stylesheet" href="<?= base_url_app() ?>assets/js/jquery.core.js">

    <link href="<?= base_url_app() ?>assets/css/style.css?v=<?= time() ?>" rel="stylesheet" type="text/css" />

    <script src="<?= base_url_app() ?>assets/js/modernizr.min.js"></script>


    <script src="<?php echo base_url_app(); ?>assets/template/jquery/jquery.min.js"></script>


    <link href="<?= base_url_app() ?>assets/jquery-toast/src/jquery.toast.css" rel="stylesheet" type="text/css" />

    <!--  <link href="https://file.myfontastic.com/TvcWTqoUED5DW24kLXpmZ9/icons.css" rel="stylesheet"> -->
    <script src="<?php echo base_url_app(); ?>assets/js/helpers.js"></script>


</head>

<body data-path="<?= base_url() ?>" data-path-app="<?= base_url_app() ?>" data-movilexpert="<?= $this->session->userdata('movil_expert') ?>">
   
<!-- Begin page -->
    <div id="wrapper">

        <!-- Top Bar Start -->
        <div class="topbar">

            <!-- LOGO -->
            <div class="topbar-left">
                <a href="<?php echo base_url(); ?>reportes/regdashboard" class="logo">
                    <span>
                        <img src="<?php echo base_url_app(); ?>assets/images/logo.png" alt="" height="40">
                    </span>
                    <i>
                        <img src="<?php echo base_url_app(); ?>assets/images/logo_sm.png" alt="" height="40">
                    </i>
                </a>
            </div>

            <nav class="navbar-custom">

                <ul class="list-inline float-right mb-0">                                   
                    <li class="list-inline-item dropdown notification-list">
                        <a title="" data-placement="top" class="tooltips" data-toggle="tooltip" role="button" aria-haspopup="false" aria-expanded="false" data-original-title="Buscar">
                            <i class="fa fa-search noti-icon text-purple waves-light waves-effect" id="search-phone"></i>
                        </a>
                    </li>

                    <li class="list-inline-item dropdown notification-list">
                        <a title="" data-placement="top" class="tooltips" data-toggle="tooltip" href="<?= base_url('administrador/regcotizacion/agregar') ?>" role="button" aria-haspopup="false" aria-expanded="false" data-original-title="Cotizacion">
                            <i class="ion ion-ios-list-box noti-icon text-purple waves-light waves-effect"></i>
                        </a>
                    </li>


                    <li class="list-inline-item dropdown notification-list">
                        <a title="" data-placement="top" class="tooltips" data-toggle="tooltip" href="<?= base_url('administrador/regcompras/agregar') ?>" role="button" aria-haspopup="false" aria-expanded="false" data-original-title="Compras">
                            <i class="ion ion-md-cart noti-icon text-primary waves-light waves-effect"></i>
                        </a>
                    </li>

                    <li class="list-inline-item dropdown notification-list">
                        <a title="" data-placement="top" class="tooltips" data-toggle="tooltip" href="<?= base_url('reportes/regreportedetallado/Ventas') ?>" role="button" aria-haspopup="false" aria-expanded="false" data-original-title="Reporte Ventas">
                            <i class="ion ion-md-clipboard noti-icon text-danger waves-light waves-effect"></i>
                        </a>
                    </li>

                    <li class="list-inline-item dropdown notification-list">
                        <a title="" data-placement="top" class="tooltips" data-toggle="tooltip" href="<?= base_url('administrador/regventas/agregar') ?>" role="button" aria-haspopup="false" aria-expanded="false" data-original-title="Venta">
                            <!--  <i class="ion ion-md-clipboard noti-icon text-danger waves-light waves-effect"></i> -->
                            <i class="noti-icon ion ion-ios-basket text-pink waves-light waves-effect"></i>
                        </a>
                    </li>

                    <li class="list-inline-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="noti-icon"><img src="<?php echo base_url_app(); ?>assets/images/users/usuario_inicio.png" alt="user" class="img-fluid rounded-circle"></i>
                            <!-- <span class="profile-username ml-2 text-dark"><?= $this->session->userdata('nomb_usu') ?> </span> <span class="mdi mdi-menu-down text-dark"></span>                           -->
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-menu-right profile-dropdown ">

                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <i class="mdi mdi-account"></i> <span><?= $this->session->userdata('nomb_usu') ?></span>

                            </div>

                            <div class="dropdown-divider"></div>
                            <!-- item-->
                            <?php if ($this->session->userdata('puntoventa_reportes') == 'admin') : ?>
                                <div class="dropdown-item noti-title">
                                    <i class="mdi mdi-mdi mdi-store"></i> <span>Super admin</span>
                                </div>
                            <?php elseif ($this->session->userdata('puntoventa_reportes') != 'admin') : ?>
                                <div class="dropdown-item noti-title">
                                    <i class="mdi mdi-mdi mdi-store"></i> <span><?= character_limiter($this->session->userdata('puntoventa_nombre'), 10, '') ?> </span>
                                </div>
                            <?php endif ?>


                            <!-- item-->
                            <a href="<?php echo base_url(); ?>perfil" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-circle"></i> <span>Perfil</span>
                            </a>

                            <!-- item-->
                            <a href="<?php echo base_url(); ?>auth/acceder" class="dropdown-item notify-item">
                                <i class="mdi mdi-home"></i> <span>Inicio</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <!-- item-->
                            <a href="<?php echo base_url(); ?>auth/logout" class="dropdown-item notify-item">
                                <i class="mdi mdi-power"></i> <span>Cerrar sesion</span>
                            </a>

                        </div>
                    </li>

                </ul>

                <ul class="list-inline menu-left mb-0">
                    <li class="float-left">
                        <button class="button-menu-mobile open-left waves-light waves-effect">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>
                    <li class="hide-phone app-search">
                        <form role="search" class="">
                            <input id="input-general" type="text" placeholder="Buscar..." class="form-control">
                            <a href=""><i class="fa fa-search"></i></a>
                        </form>
                    </li>
                </ul>

            </nav>

        </div>
        <!-- Top Bar End -->
    <!-- </div> -->

</body>




<div id="ModalBusquedaGeneral" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width:1000px">
        <div class="modal-content">
            <!-- <div class="modal-header"> -->
            <div class="modal-header bg-success">

                <h3 class="my-0 text-white"><i class="mdi mdi-database-search"></i> Busqueda general<i class="spinner-grow text-pink float-right"></i><a> </h3>
                <!-- <h5 class="modal-title" id="exampleModalLabel">Búsqueda general</h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="FormBusquedaGeneral" action="">
                    <input type="hidden" name="almacen_usuario" value="<?= $this->session->userdata('almacen') ?>">
                    <input type="hidden" name="producto">
                    <input type="hidden" name="almacen">
                    <input type="hidden" name="idTypeAssignmentProduct">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" id="BusquedaGeneralAutocomplete" name="nombreProducto" class="form-control" placeholder="Ingrese el nombre del producto">
                        </div>
                    </div>
                </form>
                <br>


                <form id="FormBusquedaGeneralTabla" action="<?= base_url('administrador/regventas/agregar') ?>" method="get" class="table-responsive">
                    <input type="hidden" name="busqueda_general_venta" value="1">
                    <table id="table-busqueda-general" class="table table-bordered">
                        <thead>
                            <tr class="bg-success text-white">
                                <th>Almacen</th>
                                <th>Producto</th>
                                <th>Marca</th>
                                <th>Categoría</th>
                                <th>Unidad</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Op.</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" style="text-align:right">Total:</td>
                                <td id="total-texto" class="text-danger">0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-danger float-right" type="submit"><i class="fab fa-opencart"></i> Siguiente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</html>