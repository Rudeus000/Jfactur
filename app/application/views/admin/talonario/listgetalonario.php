<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFacturas - Talonario</title>
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
                                <!-- <h4 class="page-title float-left">Talonario</h4> -->
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                    <li class="breadcrumb-item"><a href="#">Talonario</a></li>
                                    <li class="breadcrumb-item active">Listado</li>
                                </ol>

                            </div>
                        </div>
                    </div>

                    <!-- end row -->

                    <!-- Vertical Steps Example -->
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h3 class="my-0 text-white">Administrar talonario de documentos electronicos<a data-toggle="modal" data-target="#ModalAgregarTalonario" class="btn btn-pink btn-rounded  w-md waves-effect float-right"><i class="fa fa-plus m-r-5"></i>Nuevo</a></h3>
                                </div>
                                <div class="card-body">
                                    <form id="TalonarioFormBusqueda" autocomplete="off">
                                        <div class="form-row align-items-center">
                                            <div class="form-group col-md-4">
                                                <select class="form-control  select2 select2-hidden-accessible" name="tb_tipodocumento">
                                                    <option value="">--Todos documentos--</option>
                                                    <?php foreach ($documento as $d) : ?>
                                                        <option value="<?= $d->cod_tipdocu ?>"><?= $d->nom_tipdocumento ?></option>
                                                    <?php endforeach ?>
                                                </select>

                                            </div>
                                            <div class="form-group col-md-3">

                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-effect-ripple btn-info"><i class="fa fa-search"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- End #wizard-vertical -->
                                    <!-- </div>
                                </div>
                            </div> -->

                                </div><!-- End row -->


                                <!-- Vertical Steps Example -->
                                <!-- <div class="row">
                            <div class="col-sm-12">
                                <div class="card"> -->
                                <div class="card-body table-responsive">



                                    <table id="TableMantenimientoTalonario" class="table  table-striped" cellspacing="0" width="100%">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="dt-buttons btn-group" data-toggle="modal" data-target="#ModalAgregarTalonario">
                                                    <a class="btn btn-secondary" tabindex="0" aria-controls="datatable-buttons"><i style="color:#0099CC;" class="fas fa-user-plus"></i><span style="color:#0099CC;"> Agregar</span></a>

                                                </div>
                                                <div class="row">
                                                    <a class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="datatable-buttons" href=""><i style="color:#00C851;" class="far fa-file-excel"></i><span style="color:#00C851;"> Excel</span></a>
                                                    <a class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="datatable-buttons" href=""><i style="color:#ff4444;" class="far fa-file-pdf"></i><span style="color:#ff4444;"> PDF</span></a>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div id="datatable-buttons_filter" class="dataTables_filter"></div>
                                            </div>
                                        </div>
                                        <br>
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th class="text-center">Documento</th>
                                                <th class="text-center">Punto</th>
                                                <th class="text-center">Impresora</th>
                                                <th class="text-center">Serie</th>
                                                <th class="text-center">Inicio</th>
                                                <th class="text-center">Fin</th>
                                                <th class="text-center">Correlativo actual</th>
                                                <th class="text-center">Siglas</th>
                                                <th class="text-center">Estado</th>
                                                <th class="text-center">Acciones</th>


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

<div id="ModalAgregarTalonario" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="FormTalonario" action="<?= base_url('administrador/regtalonario/agregarTalonario') ?>" method="post" autocomplete="off">
                <input type="hidden">
                <div class="modal-header bg-primary">
                    <h4 class="custom-modal text-white">Información Administracion de Talonario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Documento:<span class="text-danger"> *</span></label>
                                <select class="form-control select2 select2-hidden-accessible" name="documento">
                                    <option value="">--Selecciona--</option>
                                    <?php foreach ($documento as $d) : ?>
                                        <option value="<?= $d->cod_tipdocu ?>"><?= $d->nom_tipdocumento ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Serie: <span class="text-danger"> *</span></label>
                                <input type="text" name="serie" class="form-control" maxlength="5">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Punto de venta:<span class="text-danger"> *</span></label>
                                <select class="form-control select2 select2-hidden-accessible" name="punto">
                                    <option value="">--Selecciona--</option>
                                    <?php foreach ($punto as $p) : ?>
                                        <option value="<?= $p->cod_puntoventa ?>"><?= $p->nomb_puntoventa ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Impresora:<span class="text-danger"> *</span></label>
                                <select class="form-control select2 select2-hidden-accessible" name="impresora">
                                    <option value="">--Selecciona--</option>
                                    <?php foreach ($impresora as $i) : ?>
                                        <option value="<?= $i->cod_impresora ?>"><?= $i->nom_impresora ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Correlativo inicio:<span class="text-danger"> *</span></label>
                                <input type="text" name="inicio" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Correlativo fin:<span class="text-danger"> *</span></label>
                                <input type="text" name="fin" class="form-control" maxlength="8" value="99999999">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Correlativo actual:<span class="text-danger"> *</span></label>
                                <input type="text" name="actual" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Tipo Documento:</label>
                                <select class="form-control select2 select2-hidden-accessible" name="siglas">
                                    <option value="">--Seleccionar</option>
                                    <option value="FC">FACTURAS - BOLETAS</option>
                                    <option value="PC">PROFORMA - COTIZACIONES</option>
                                    <option value="TK">TICKETS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Doc. Cliente:</label>
                                <div class="form-check">
                                    <label for="doccli_ruc" class="form-check-label">
                                        <input id="doccli_ruc" class="form-check-input" name="doccli_ruc" type="checkbox">
                                        RUC
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="doccli_dni" class="form-check-label">
                                        <input id="doccli_dni" class="form-check-input" name="doccli_dni" type="checkbox">
                                        DNI
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="doccli_dni" class="form-check-label">
                                        <input id="doccli_dni" class="form-check-input" name="doccli_ex" type="checkbox">
                                        CARNET DE EXTRANJERIA
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="doccli_dni" class="form-check-label">
                                        <input id="doccli_dni" class="form-check-input" name="doccli_pass" type="checkbox">
                                        PASAPORTE
                                    </label>
                                </div>
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








<div id="ModalEditarTalonario" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="FormEditarTalonario" action="<?= base_url('administrador/regtalonario/editTalonario') ?>" method="post" autocomplete="off">
                <input type="hidden" name="id">
                <div class="modal-header bg-primary">
                    <h4 class="custom-modal text-white">Editar Administracion de Talonario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Documento:<span class="text-danger"> *</span></label>
                                <select class="form-control select2" name="documento">

                                    <?php foreach ($documento as $d) : ?>
                                        <option value="<?= $d->cod_tipdocu ?>"><?= $d->nom_tipdocumento ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Serie: <span class="text-danger"> *</span></label>
                                <input type="text" name="serie" class="form-control" maxlength="5">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Punto de venta:<span class="text-danger"> *</span></label>
                                <select class="form-control select2 select-hidden-accessible" name="punto">

                                    <?php foreach ($punto as $p) : ?>
                                        <option value="<?= $p->cod_puntoventa ?>"><?= $p->nomb_puntoventa ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Impresora:<span class="text-danger"> *</span></label>
                                <select class="form-control select2 select-hidden-accessible" name="impresora">

                                    <?php foreach ($impresora as $i) : ?>
                                        <option value="<?= $i->cod_impresora ?>"><?= $i->nom_impresora ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Correlativo inicio:<span class="text-danger"> *</span></label>
                                <input type="text" name="inicio" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Correlativo fin:<span class="text-danger"> *</span></label>
                                <input type="text" name="fin" class="form-control" maxlength="8" value="99999999">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Correlativo actual:<span class="text-danger"> *</span></label>
                                <input type="text" name="actual" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Tipo Documento</label>
                                <select class="form-control select2" name="siglas">
                                    <option value="FC">FACTURAS - BOLETAS</option>
                                    <option value="PC">PROFORMA - COTIZACIONES</option>
                                    <option value="TK">TICKETS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Doc. Cliente:</label>
                                <div class="form-check">
                                    <label for="doccli_ruc_edit" class="form-check-label">
                                        <input id="doccli_ruc_edit" class="form-check-input" name="doccli_ruc" type="checkbox">
                                        RUC
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="doccli_dni_edit" class="form-check-label">
                                        <input id="doccli_dni_edit" class="form-check-input" name="doccli_dni" type="checkbox">
                                        DNI
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="doccli_es" class="form-check-label">
                                        <input id="doccli_ex" class="form-check-input" name="doccli_ex" type="checkbox">
                                        CARNET DE EXTRANJERIA
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="doccli_pass" class="form-check-label">
                                        <input id="doccli_pass" class="form-check-input" name="doccli_pass" type="checkbox">
                                        PASAPORTE
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Estado</label>
                                <select class="form-control select2" name="estado">
                                    <option value="1">Activo</option>
                                    <option value="2">Desactivado</option>

                                </select>
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