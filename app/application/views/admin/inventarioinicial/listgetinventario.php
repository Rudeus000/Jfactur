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
                            <!-- <h4 class="page-title float-left"><i class="fas fa-dolly"></i>  Inventario Inicial (Ingresos)</h4> -->
                            <ol class="breadcrumb float-right">

                                <li class="breadcrumb-item"><a href="#">Inventario Inicial</a></li>
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
                            <div class="card-header bg-success">
                                <h3 class="my-0 text-white">Inventario incial</h3>
                            </div>
                            <div class="card-body table-responsive">
                                <fieldset>
                                    <legend>Filtro</legend>
                                    <form id="FormAlmacenInventarioInicialFiltro" action="" method="post"
                                        autocomplete="off">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Almacen:</label>
                                                    <select name="almacen" class="form-control select2">
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($almacenes as $a): ?>
                                                            <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Producto:</label>
                                                    <input type="text" name="producto" class="form-control"
                                                        placeholder="Escriba y presione enter">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Categoria:</label>
                                                    <select name="categoria" class="form-control select2">
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($categorias as $c): ?>
                                                            <option value="<?= $c->cod_categoria ?>">
                                                                <?= $c->nomb_categoria ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Marca:</label>
                                                    <select name="marca" class="form-control select2">
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($marcas as $m): ?>
                                                            <option value="<?= $m->cod_marca ?>"><?= $m->nomb_marca ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </fieldset>

                                <br>
                                <div class="row float-right">
                                    <div class="col-md-12">
                                        <a id="InventarioInicialReportePdf" href="#" class="btn btn-rounded btn-pink"
                                            target="_blank"><i class="far fa-file-pdf"></i> PDF</a>

                                        <a id="InventarioInicialReporteExcel" href="#"
                                            class="btn btn-rounded btn-purple" target="_blank"><i
                                                class="far fa-file-excel"></i> EXCEL</a>

                                        <a id="InventarioInicialReporteExcelSeries" href="#"
                                            class="btn btn-rounded btn-primary" target="_blank"><i
                                                class="far fa-file-excel"></i> Exportar series</a>

                                    </div>
                                </div>
                                <br>
                                <div>
                                    <table id="TableAlmacenInventarioInicial" class="table  table-striped"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="bg-success text-white">
                                                <th>Codigo</th>
                                                <th>Producto</th>
                                                <th>Marca</th>
                                                <th>Categoria</th>
                                                <th>Unidad</th>
                                                <th>P. Costo</th>
                                                <th>P. Venta</th>
                                                <th>Fechas</th>
                                                <th>Stock Actual</th>
                                                <th>Stock Inicial</th>
                                            </tr>
                                        </thead>

                                    </table>

                                </div>
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


<div id="ModalInventarioSeries" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Series</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="TableInventarioSeries" class="table table-striped  table-condensed">
                    <thead>
                        <tr>
                            <th>Serie</th>
                            <th>Estado</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div id="ModalFechasProductos" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Fechas del Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-success" id="agregar-producto-fecha">Agregar</button>

                <form id="FormProductoFecha"
                    action="<?= base_url('administrador/reginventarioinicial/agregarProductoFecha') ?>"
                    autocomplete="off" style="display:none">
                    <input type="hidden" name="producto">
                    <input type="hidden" name="almacen">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Cantidad</label>
                            <input type="text" name="cantidad" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Fecha de Producción</label>
                            <input type="text" name="fecha_produccion" class="form-control datepicker">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Fecha de Vencimiento</label>
                            <input type="text" name="fecha_vencimiento" class="form-control datepicker">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Fecha de Alerta</label>
                            <input type="text" name="fecha_alerta" class="form-control datepicker">
                        </div>
                        <div class="col-md-12 form-group">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <button type="button" class="btn btn-danger" id="cerrar-producto-fecha">Cerrar</button>
                        </div>
                    </div>
                </form>
                <br><br>
                <table id="TableFechasProductos" class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fec. Prod.</th>
                            <th>Fec. Venc.</th>
                            <th>Fec. Alerta</th>
                            <th>Cant</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ... Código existente ... -->

<div id="ModalSeleccionAlmacen" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="FormSeleccionarAlmacen">
                <input hidden name="producto">
                <input hidden name="series">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Seleccione un almacén</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <input type="text" name="nombre_producto" class="form-control" readonly>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Stock</label>
                            <input type="text" name="stock" class="form-control">
                        </div>
                        <div class="col-md-9 form-group">
                            <label for="selectAlmacen">Almacén:</label>
                            <select name="almacen" class="form-control">
                                <option value="">Seleccione</option>
                                <?php foreach ($almacenes as $a): ?>
                                    <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>

        </div>
    </div>
</div>




<div id="ModalSeleccionAlmacenParaFechaVencimiento" class="modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="FormSeleccionarAlmacenParaFechaVencimiento" action="" method="post">
                <input type="hidden" name="producto">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Seleccione un almacén</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <input type="text" name="nombre_producto" class="form-control" readonly>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="selectAlmacen">Almacén:</label>
                            <select name="almacen" class="form-control">
                                <option value="">Seleccione</option>
                                <?php foreach ($almacenes as $a): ?>
                                    <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>

        </div>
    </div>
</div>