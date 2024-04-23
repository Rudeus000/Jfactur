<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFacturas - Producto</title>
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
                                <!-- <h4 class="page-title float-left"><i class="fas fa-cube"></i> Articulos General</h4> -->
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                    <li class="breadcrumb-item"><a href="#">Productos</a></li>
                                    <li class="breadcrumb-item active">Listado</li>
                                </ol>

                            </div>
                        </div>
                    </div>
                    <!-- Vertical Steps Example -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header bg-success">
                                    <h3 class="my-0 text-white">Lista de productos<a href="" class="btn btn-rounded btn-pink float-right" data-toggle="modal" data-target="#ModalAgregarProducto"><i class="fa fa-plus m-r-5"></i>Agregar</a></h3>
                                </div>
                                <div class="card-body table-responsive">

                                    <!-- Vertical Steps Example -->
                                    <!-- <div class="row">
                        <div class="col-12">
                            <div class="card"> -->
                                    <!-- <div class="card-body"> -->
                                    <fieldset>
                                        <legend>Filtro</legend>
                                        <form id="ProductoFormBusqueda" autocomplete="off">
                                            <div class="form-row">

                                                <div class="form-group col-md-3">
                                                    <label class="col-form-label">Fecha inicio:</label>
                                                    <div>
                                                        <div class="input-group">
                                                            <input type="date" class="form-control" name="desde">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                            </div>
                                                        </div><!-- input-group -->
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label class="col-form-label">Fecha fin:</label>
                                                    <div>
                                                        <div class="input-group">
                                                            <input type="date" class="form-control" name="hasta">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                            </div>
                                                        </div><!-- input-group -->
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label class="col-form-label ">Codigo de barras:</label>
                                                    <div class="input-group">
                                                        <input type="text" name="" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">

                                                    <label class="col-form-label ">Nombre producto:</label>

                                                    <div class="input-group">
                                                        <input type="text" name="tb_producto" class="form-control">

                                                    </div>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label class="col-form-label">Categoria:</label>
                                                    <select class="form-control  select2 select2-hidden-accessible" name="tb_categoria">
                                                        <option value="">--Todos--</option>
                                                        <?php foreach ($categoria as $c) : ?>
                                                            <option value="<?= $c->cod_categoria ?>"><?= $c->nomb_categoria ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label class="col-form-label">Marca:</label>
                                                    <select class="form-control  select2 select2-hidden-accessible" name="tb_marca">
                                                        <option value="">--Todos--</option>
                                                        <?php foreach ($marca as $m) : ?>
                                                            <option value="<?= $m->cod_marca ?>"><?= $m->nomb_marca ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label class="col-form-label">Tipo:</label>
                                                    <select class="form-control  select2 select2-hidden-accessible" name="tb_tiparticulo">
                                                        <option value="">--Todos--</option>
                                                        <?php foreach ($articulo as $a) : ?>
                                                            <option value="<?= $a->cod_tiparticulo ?>"><?= $a->nomb_tiparticulo ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label class="col-form-label" style="display: block"><br></label>
                                                    <span class="input-group-btn" style="padding-top:35px;">
                                                        <!-- <button class="btn btn-success waves-effect waves-light"><i class="fa fa-search"></i> Buscar</button> -->
                                                        <button class="btn btn-rounded btn-success waves-effect w-md waves-light"><i class="fas fa-binoculars m-r-5"></i>Buscar</button>
                                                    </span>

                                                    <span class="input-group-btn" style="padding-top:35px;" data-toggle="modal" data-target="#ModalCargaMasiva">
                                                        <!-- <button class="btn btn-success waves-effect waves-light"><i class="fa fa-search"></i> Importar</button> -->
                                                        <button type="button" class="btn btn-rounded btn-info  waves-effect w-md waves-light"><i class="fa fa-file-excel m-r-5"></i><span>Importar</span></button>
                                                    </span>
                                                </div>
                                        </form>
                                    </fieldset>
                                    <!-- End #wizard-vertical -->
                                    <!-- </div>
                            </div>
                        </div> -->

                                    <!-- </div>End row -->

                                    <table id="TableMantenimientoProducto" class="table  table-striped" cellspacing="0" width="100%">

                                        <div class="form-group col-md-6">
                                            <label class="col-form-label" style="display: block"><br></label>
                                            <!-- <span class="input-group-btn" data-toggle="modal" data-target="#ModalAgregarProducto" style="padding-top:35px;">
                                                    
                                                    <button  class="btn btn-success waves-effect w-md waves-light"><i class="fas fa-clipboard-list m-r-5"></i>Agregar</button>
                                                </span>                                           -->
                                            <span class="input-group-btn" style="padding-top:35px;">

                                                <button class="btn btn-rounded btn-primary buttons-excel waves-effect w-md waves-light"><i class="fa fa-file-excel m-r-5"></i><span>Exportar</span></button>
                                            </span>
                                            <span class="input-group-btn" style="padding-top:35px;">
                                                <button type="button" class="btn btn-rounded btn-danger  waves-effect w-md waves-light"><i class="far fa-file-pdf m-r-5"></i><span>PDF</span></button>
                                            </span>
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <div id="datatable-buttons_filter" class="dataTables_filter"></div>
                                        </div>
                                        <br>
                                        <thead>
                                            <tr class="bg-success text-white">
                                                <th style="text-aling:center;">Tipo</th>
                                                <th style="text-aling:center;">Producto</th>
                                                <th style="text-aling:center;">Marca</th>
                                                <th style="text-aling:center;">Categoria</th>
                                                <th style="text-aling:center;">Unidad</th>
                                                <th style="text-aling:center;">P. Costo</th>
                                                <th style="text-aling:center;">P. Venta</th>
                                                <th style="text-aling:center;">Stock</th>
                                                <th style="text-aling:center;">Fecha Modificacion</th>
                                                <th style="text-aling:center;">Estado</th>
                                                <th style="text-aling:center;">Acciones</th>

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

<div id="ModalAgregarProducto" class="modal bs-example-modal-center" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">


            <!-- <div class="card-header bg-success">
                    <h3 class="my-0 text-white">Agregar producto<i class="spinner-grow text-warning float-right"></i></h3>
                </div> -->
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12">
                        <!-- <div class="card"> -->
                        <div class="card-body">
                            <!-- <h4 class="header-title m-t-0 m-b-30">Tabs Bordered Justified</h4> -->

                            <ul class="nav nav-tabs tabs-bordered nav-justified">
                                <li class="nav-item">
                                    <a href="#producto" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                        Producto
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#marca" data-toggle="tab" aria-expanded="true" class="nav-link">
                                        Marca
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#categoria" data-toggle="tab" aria-expanded="false" class="nav-link">
                                        Categoria
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#unidadm" data-toggle="tab" aria-expanded="false" class="nav-link disabled">
                                        Unidad M.
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#barcode" data-toggle="tab" aria-expanded="false" class="nav-link">
                                        Barcode.
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="producto">
                                    <form id="FormProducto" action="<?= base_url('administrador/regproducto/addProducto') ?>" method="post" autocomplete="off">
                                        <input type="hidden">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="control-label">Nombre:<span class="text-danger"> *</label>

                                                    <div class="form-check form-check-inline ml-2">
                                                        <input class="form-check-input" type="checkbox" name="productAssignment" id="productAssignmentDad" value="P">
                                                        <label class="form-check-label" for="productAssignmentDad">P</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="productAssignment" id="productAssignmentSon" value="H">
                                                        <label class="form-check-label" for="productAssignmentSon">H</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="productoConasignacion" id="productAssignmentGson" value="G">
                                                        <label class="form-check-label" for="productAssignmentGson">G</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="productAssignmentDebit" id="productAssignmentD" value="D">
                                                        <label class="form-check-label" for="productAssignmentD">D</label>
                                                    </div>

                                                    <input type="text" name="nombre" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Producto Padre:<span class="text-danger"> *</label>
                                                    <select class="form-control select2" id="selectAssignmentDad" name="selectAssignmentDad">
                                                        <option value="">--Selecciona--</option>
                                                        <?php foreach ($TypeproductAssignments as $TypeproductAssignment) : ?>
                                                            <option value="<?= $TypeproductAssignment->cod_producto ?>"><?= $TypeproductAssignment->nomb_product ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Marca:<span class="text-danger"> *</label>
                                                    <div class="input-group" style="flex-wrap: inherit;">
                                                        <select class="form-control select2" name="marcas">
                                                            <option value="">--Selecciona--</option>
                                                            <?php foreach ($marca as $marc) : ?>
                                                                <option value="<?= $marc->cod_marca ?>"><?= $marc->nomb_marca ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <a href="" class="btn btn-rounded btn-pink float-right" id="btnAbrirMarca">+</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Categoria:<span class="text-danger"> *</label>
                                                    <div class="input-group" style="flex-wrap: inherit;">
                                                        <select class="form-control select2 select2-hidden-accessible input-sm" name="categorias">
                                                            <option value="">--Selecciona--</option>
                                                            <?php foreach ($categoria as $ca) : ?>
                                                                <option value="<?= $ca->cod_categoria ?>"><?= $ca->nomb_categoria ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <a href="" class="btn btn-rounded btn-pink float-right" id="btnAbrirCategoria">+</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Unidad de Medida:<span class="text-danger"> *</label>
                                                    <div class="input-group" style="flex-wrap: inherit;">
                                                        <select class="form-control select2 select2-hidden-accessible input-sm" name="unidad">
                                                            <option value="">--Selecciona--</option>
                                                            <?php foreach ($medida as $me) : ?>
                                                                <option value="<?= $me->cod_unid ?>"><?= $me->nomb_unid ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <a href="#" class="btn btn-rounded btn-pink float-right" id="btnAbrirUnid">+</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Tipo articulo:<span class="text-danger"> *</label>
                                                    <div class="input-group" style="flex-wrap: inherit;">
                                                        <select class="form-control select2 select2-hidden-accessible input-sm" name="tipoarticulo">
                                                            <option value="">--Selecciona--</option>
                                                            <?php foreach ($articulo as $tp) : ?>
                                                                <option value="<?= $tp->cod_tiparticulo ?>"><?= $tp->nomb_tiparticulo ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <a href="#" class="btn btn-rounded btn-pink float-right" id="btnAbrirTipoArt">+</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Linea:<span class="text-danger"> *</label>
                                                    <select class="form-control select2 select2-hidden-accessible input-sm" name="linea">
                                                        <!-- <option value="">--Selecciona--</option> -->
                                                        <?php foreach ($linea as $li) : ?>
                                                            <option value="<?= $li->cod_linea ?>"><?= $li->nomb_linea ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Sub Linea:<span class="text-danger"> *</label>
                                                    <select class="form-control select2 select2-hidden-accessible input-sm" name="sublinea">
                                                        <!-- <option value="">--Selecciona--</option> -->
                                                        <?php foreach ($sublinea as $sb) : ?>
                                                            <option value="<?= $sb->cod_sublinea ?>"><?= $sb->nomb_sublinea ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Talla:<span class="text-danger"> *</label>
                                                    <select class="form-control select2 select2-hidden-accessible input-sm" name="talla">
                                                        <!-- <option value="">--Selecciona--</option> -->
                                                        <?php foreach ($talla as $tl) : ?>
                                                            <option value="<?= $tl->cod_talla ?>"><?= $tl->nomb_talla  ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Presentacion:<span class="text-danger"> *</label>
                                                    <select class="form-control select2 select2-hidden-accessible input-sm" name="presentacion">
                                                        <!-- <option value="">--Selecciona--</option> -->
                                                        <?php foreach ($presentacion as $pres) : ?>
                                                            <option value="<?= $pres->cod_present ?>"><?= $pres->nomb_present ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Precio compra:<span class="text-danger"> *</label>
                                                    <input type="text" name="preciocosto" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Precio venta:<span class="text-danger"> *</label>
                                                    <input type="text" name="precioventa" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Precio x Mayor:<span class="text-danger"> *</label>
                                                    <input type="text" name="precioventa_mayor" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Precio Especial:<span class="text-danger"> *</label>
                                                    <input type="text" name="precioventa_especial" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Stock minimo:<span class="text-danger"> *</label>
                                                    <input type="text" name="stock" class="form-control" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Comision:<span class="text-danger"> *</label>
                                                    <input type="text" name="comision" class="form-control">
                                                </div>
                                            </div>


                                            <!-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Codigo de Barras:<span class="text-danger"> *</label>
                                                    <input type="text" name="codigobarra" class="form-control">
                                                </div>
                                            </div> -->
                                            <!-- <div class="form-group row"> -->

                                            <div class="col-md-4">
                                                <label class="control-label">Codigo de Barras:</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="codigobarra" placeholder="Datos para generar" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-dark waves-effect waves-light" type="button">Crear</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- </div> -->

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Disponibilidad venta: <span class="text-danger"> *</label>
                                                    <select class="form-control select2" required="" name="dispventa">
                                                        <option value="S">Si venta</option>
                                                        <option value="N">No venta</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Disponibilidad compra: <span class="text-danger"> *</label>
                                                    <select class="form-control select2" required="" name="dispcompra">
                                                        <option value="S">Si compra</option>
                                                        <option value="N">No compra</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Tipo IGV:<span class="text-danger"> *</label>
                                                    <select class="form-control input-sm select2" name="parametros">
                                                        <option value="">--Selecciona--</option>
                                                        <?php foreach ($parametros as $pr) : ?>
                                                            <option value="<?= $pr->cod_parametros ?>"><?= $pr->nom_paramt ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Fecha Vencimiento</label>
                                                    <select name="fecha_vencimiento" class="form-control select2">
                                                        <option value="0" selected>No</option>
                                                        <option value="1">Si</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="bipay" style="display: none;">
                                                <div class="form-group">
                                                    <label class="control-label">Consumo bipay:<span class="text-danger"> *</label>
                                                    <input type="text" name="bipay" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="decuento_prod">
                                                <div class="form-group">
                                                    <label class="control-label">Descuento:<span class="text-danger"> *</label>
                                                    <input type="text" name="descuento" id="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
                                        </div>
                                    </form>
                                    <!-- </form> -->
                                </div>
                                <div class="tab-pane" id="marca">
                                    <form id="FormMarcaProd" action="<?= base_url('administrador/regproducto/insertMarcaprod') ?>" method="post" autocomplete="off">
                                        <input type="hidden">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Ingrese marca:</label>
                                                    <input type="text" name="descripcion" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <legend class="scheduler-border"></legend>
                                    <div class="row" id="MarcaContenedorGuardar">
                                        <div class="col-md-12">
                                            <div class="form-group float-right">
                                                <a href="" class="btn btn-pink" id="btnCerrarMarca"><i class="fas fa-fast-backward"></i> Back</a>
                                                <button type="submit" form="FormMarcaProd" class="btn btn-success "><i class="fa fa-save m-r-5"></i>Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="categoria">

                                    <form id="FormCategoriaProd" action="<?= base_url('administrador/regproducto/insertCategoriaprod') ?>" method="post" autocomplete="off">
                                        <input type="hidden">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Ingrese categoria:</label>
                                                    <input type="text" name="descripcion" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <legend class="scheduler-border"></legend>
                                    <div class="row" id="CategoriaContenedorGuardar">
                                        <div class="col-md-12">
                                            <div class="form-group float-right">
                                                <a href="" class="btn btn-pink" id="btnCerrarCategoria"><i class="fas fa-fast-backward"></i> Back</a>
                                                <button type="submit" form="FormCategoriaProd" class="btn btn-success "><i class="fa fa-save m-r-5"></i>Guardar</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="unidadm">
                                    <form id="FormUmedida" action="<?= base_url('administrador/regproducto/insertUmedidaprod') ?>" method="post" autocomplete="off">
                                        <input type="hidden">

                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Nombre: <span class="text-danger"> *</span></label>
                                                    <input type="text" name="descripcion" class="form-control" maxlength="5">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Tipo unidad:<span class="text-danger"> *</span></label>
                                                    <select class="form-control select2 select2-hidden-accessible" name="tipounidad">
                                                        <option value="">--Selecciona--</option>
                                                        <?php foreach ($tipounidad as $t) : ?>
                                                            <option value="<?= $t->cod_tipunidad ?>"><?= $t->nomb_tipunidad ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Abreviatura: <span class="text-danger"> *</span></label>
                                                    <input type="text" name="abreviatura" class="form-control" maxlength="20">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Factor:<span class="text-danger"> *</span></label>
                                                    <input type="text" name="factor" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <legend class="scheduler-border"></legend>
                                    <div class="row" id="CategoriaContenedorGuardar">
                                        <div class="col-md-12">
                                            <div class="form-group float-right">
                                                <a href="<?= base_url('') ?>" class="btn btn-pink "><i class="fas fa-times"></i> Cancelar</a>
                                                <button type="submit" form="FormCategoria" class="btn btn-success "><i class="fa fa-save m-r-5"></i>Procesar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="barcode">
                                    <form id="FormBarcode" action="<?= base_url('administrador/regproducto/insertUmedidaprod') ?>" method="post" autocomplete="off">
                                        <input type="hidden">

                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Prefijo: <span class="text-danger"> *</span></label>
                                                    <input type="text" name="Prefijo" class="form-control" maxlength="5">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Sofijo: <span class="text-danger"> *</span></label>
                                                    <input type="text" name="sofijo" class="form-control" maxlength="5">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Valor inicial: <span class="text-danger"> *</span></label>
                                                    <input type="text" name="valorini" class="form-control" maxlength="5">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Valor final: <span class="text-danger"> *</span></label>
                                                    <input type="text" name="valorend" class="form-control" maxlength="5">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Incremento: <span class="text-danger"> *</span></label>
                                                    <input type="text" name="incremento" class="form-control" maxlength="5">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Mascara: <span class="text-danger"> *</span></label>
                                                    <input type="text" name="Mascara" class="form-control" maxlength="5">
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                    <legend class="scheduler-border"></legend>
                                    <div class="row" id="BarcoContenedorGuardar">
                                        <div class="col-md-12">
                                            <div class="form-group float-right">
                                                <a href="<?= base_url('') ?>" class="btn btn-pink "><i class="fas fa-times"></i> Cancelar</a>
                                                <button type="submit" form="FormBarcode" class="btn btn-success "><i class="fa fa-save m-r-5"></i>Generar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->
            </div> <!-- end col -->

        </div>
    </div>
    <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
                </div>
            </form> -->
</div>
</div>
</div><!-- /.modal -->


<div id="ModalEditarProducto" class="modal bs-example-modal-center" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="FormEditarProducto" action="<?= base_url('administrador/regproducto/editProducto') ?>" method="post" autocomplete="off">
                <input type="hidden" name="id">
                <!-- <div class="modal-header">
                    <h4 class="custom-modal">Editar Producto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div> -->
                <div class="card-header bg-success">
                    <h3 class="my-0 text-white">Editar producto<i class="spinner-grow text-danger float-right"></i></h3>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Nombre:<span class="text-danger"> *</label>

                                <div class="form-check form-check-inline ml-2">
                                    <input class="form-check-input" type="checkbox" name="editproductAssignmentDad" id="editproductAssignmentDad" value="P">
                                    <label class="form-check-label" for="editproductAssignmentDad">P</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="editproductAssignmentSon" id="editproductAssignmentSon" value="H">
                                    <label class="form-check-label" for="editproductAssignmentSon">H</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="editproductAssignmentGson" id="editproductAssignmentGson" value="G">
                                    <label class="form-check-label" for="editproductAssignmentGson">G</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="editproductAssignmentDebit" id="editproductAssignmentDebit" value="D">
                                    <label class="form-check-label" for="editproductAssignmentDebit">D</label>
                                </div>

                                <input type="text" name="nombre" class="form-control">

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Producto Padre:<span class="text-danger"> *</label>
                                <select class="form-control select2" id="editselectAssignmentDad" name="editselectAssignmentDad">
                                    <option value="">--Selecciona--</option>
                                    <?php foreach ($TypeproductAssignments as $TypeproductAssignment) : ?>
                                        <option value="<?= $TypeproductAssignment->cod_producto ?>"><?= $TypeproductAssignment->nomb_product ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Marca:<span class="text-danger"> *</label>
                                <select class="form-control select2 select2-hidden-accessible input-sm" name="marca">

                                    <?php foreach ($marca as $marc) : ?>
                                        <option value="<?= $marc->cod_marca ?>"><?= $marc->nomb_marca ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Categoria:<span class="text-danger"> *</label>
                                <select class="form-control select2 select2-hidden-accessible input-sm" name="categoria">

                                    <?php foreach ($categoria as $ca) : ?>
                                        <option value="<?= $ca->cod_categoria ?>"><?= $ca->nomb_categoria ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Linea:<span class="text-danger"> *</label>
                                <select class="form-control select2 select2-hidden-accessible input-sm" name="linea">

                                    <?php foreach ($linea as $li) : ?>
                                        <option value="<?= $li->cod_linea ?>"><?= $li->nomb_linea ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Sub Linea:<span class="text-danger"> *</label>
                                <select class="form-control select2 select2-hidden-accessible input-sm" name="sublinea">

                                    <?php foreach ($sublinea as $sb) : ?>
                                        <option value="<?= $sb->cod_sublinea ?>"><?= $sb->nomb_sublinea ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>



                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Unidad de Medida:<span class="text-danger"> *</label>
                                <select class="form-control select2 select2-hidden-accessible input-sm" name="unidad">

                                    <?php foreach ($medida as $me) : ?>
                                        <option value="<?= $me->cod_unid ?>"><?= $me->nomb_unid ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Talla:<span class="text-danger"> *</label>
                                <select class="form-control select2 select2-hidden-accessible input-sm" name="talla">

                                    <?php foreach ($talla as $tl) : ?>
                                        <option value="<?= $tl->cod_talla ?>"><?= $tl->nomb_talla     ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>



                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Tipo articulo:<span class="text-danger"> *</label>
                                <select class="form-control select2 select2-hidden-accessible input-sm" name="tipoarticulo">

                                    <?php foreach ($articulo as $tp) : ?>
                                        <option value="<?= $tp->cod_tiparticulo ?>"><?= $tp->nomb_tiparticulo ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Precio compra:<span class="text-danger"> *</label>
                                <input type="text" name="preciocosto" class="form-control" value="0.00">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Precio venta:<span class="text-danger"> *</label>
                                <input type="text" name="precioventa" class="form-control" value="0.00">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Precio x Mayor:<span class="text-danger"> *</label>
                                <input type="text" name="precioventa_mayor" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Precio Especial:<span class="text-danger"> *</label>
                                <input type="text" name="precioventa_especial" class="form-control">
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Stock minimo:<span class="text-danger"> *</label>
                                <input type="text" name="stock" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Comision:<span class="text-danger"> *</label>
                                <input type="text" name="comision" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Codigo de Barras:<span class="text-danger"> *</label>
                                <input type="text" name="codigobarra" class="form-control">
                            </div>
                        </div>



                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Disponibilidad venta: <span class="text-danger"> *</label>
                                <select class="form-control select2 " required="" name="dispventa">
                                    <option value="S">Si disponible</option>
                                    <option value="N">No disponible</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Disponibilidad compra: <span class="text-danger"> *</label>
                                <select class="form-control select2 " required="" name="dispcompra">
                                    <option value="S">Si disponible</option>
                                    <option value="N">No disponible</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Presentacion:<span class="text-danger"> *</label>
                                <select class="form-control select2 select2-hidden-accessible input-sm" name="presentacion">

                                    <?php foreach ($presentacion as $pres) : ?>
                                        <option value="<?= $pres->cod_present ?>"><?= $pres->nomb_present ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Tipo IGV:<span class="text-danger"> *</label>
                                <select class="form-control input-sm" name="parametros">

                                    <?php foreach ($parametros as $pr) : ?>
                                        <option value="<?= $pr->cod_parametros ?>"><?= $pr->nom_paramt ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Fecha Vencimiento</label>
                                <select name="fecha_vencimiento" class="form-control">
                                    <option value="0" selected>No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Fecha:</label>
                                <input type="input" name="fecharegistro" class="form-control datepicker" readonly>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Estado</label>
                                <select class="form-control select2" name="estado">
                                    <option value="1">Activo</option>
                                    <option value="2">Desactivado</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" id="bipayedit">
                            <div class=" form-group">
                                <label class="control-label">Consumo bipay:<span class="text-danger"> *</label>
                                <input type="text" name="bipay" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Descuento:<span class="text-danger"> *</label>
                                <input type="text" name="descuento_prod" id="" class="form-control">
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


<!--  Modal content for the above example -->
<div class="modal bs-example-modal-lg" id="ModalCargaMasiva" tabindex="" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="card-header bg-success">
            <h3 class="my-0 text-white">Importar productos series desde excel<a type="button" class="btn btn-rounded btn-pink float-right" data-dismiss="modal" aria-hidden="true">x</a></h3>
        </div>
        <!-- <div class="card-header bg-success"><h3 class="my-0 text-white">Lista de productos<a class="btn btn-rounded btn-danger float-right">x</a></h3></div> -->
        <div class="modal-content">

            <!-- <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"> <i class="fas fa-angle-double-down m-r-5"></i>Importar productos desde excel</h4>
                    
                </div> -->

            <div class="modal-body">
                <div class="card m-b-20 text-xs-center">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <i class=" fas fa-cloud-download-alt text-info fa-4x"></i>
                            </div>
                            <div class="col-md-6">
                                <p><b>Descarga nuestra plantilla en excel!</b><br>
                                    Para poder importar tus productos en lote debes descargar la plantilla y enviarlo utilizando el mismo formato!
                                <p>
                            </div>
                            <div class="col-md-2">
                                <a href="<?= base_url('administrador/regproducto/descargarPlantillaProducto') ?>" class="btn btn-link  waves-effect w-md waves-light"><i class="fas fa-cloud-download-alt m-r-5"></i>Plantilla productos</a>
                                <a href="<?= base_url('administrador/regproducto/descargarPlantillaStock') ?>" class="btn btn-link  waves-effect w-md waves-light mt-2"><i class="fas fa-cloud-download-alt m-r-5"></i>Plantilla stock y serie</a>
                            </div>
                            <div>
                            </div>
                        </div>


                        <div class="card m-b-20 text-xs-center">
                            <div class="card-body">
                                <blockquote class="card-bodyquote">
                                    <div class="custom-file">
                                        <input type="file" name="plantilla" class="custom-file-input" id="ImportarPlantilla" lang="es">
                                        <label class="custom-file-label label-productos" for="customFileLang">Importar productos</label>
                                    </div>

                                    <div class="progress" style="display:none">
                                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <button type="button" id="iniciarImportacionProducto" class="btn btn-primary btn-rounded btn-bordered waves-effect w-md waves-light mt-2"><i class="fas fa-cloud-upload-alt m-r-5"></i>Iniciar</button>
                                </blockquote>
                                <br><br>
                                <blockquote class="card-bodyquote">
                                    <div class="custom-file">
                                        <input type="file" name="plantillaStock" class="custom-file-input" id="ImportarPlantillaStock" lang="es">
                                        <label class="custom-file-label label-stock" for="customFileLang">Importar stock y serie</label>
                                    </div>

                                    <div class="progressStock" style="display:none">
                                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <button type="button" id="iniciarImportacionStock" class="btn btn-success btn-rounded btn-bordered waves-effect w-md waves-light mt-2"><i class="fas fa-cloud-upload-alt m-r-5"></i>Iniciar</button>
                                </blockquote>

                                <!--
                                <div class="card m-b-2 text-white bg-info text-xs-center">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <i class="fas fa-info-circle fa-4x"></i>
                                            </div>                                           
                                            <div class="col-md-8"> 
                                                <p>Todos los productos que se ingresen en el excel, serán registrados en el almacén para la sucursal: AGROVET ANDAHUAYLAS (ID: 7)</p>
                                            </div>  
                                        </div>                                                          
                                    </div>
                                </div>
                                -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="alert-errres-plantilla" class="alert alert-danger" role="alert" style="display:none">
                                            <ul id="errores-plantilla">

                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-rounded waves-effect" data-dismiss="modal">Cancelar</button>
                            <button type="button" id="uploadBtn" class="btn btn-primary btn-rounded waves-effect waves-light">Iniciar importacion..</button>
                        </div>
                        -->
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <script>
            $(document).ready(function() {
                // Manejar el clic en el botón
                $('#btnAbrirMarca').click(function(event) {
                    event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

                    // Activar el tab-pane con el ID "marca"
                    $('.nav-tabs a[href="#marca"]').tab('show');
                });
                $('#btnAbrirCategoria').click(function(event) {
                    event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

                    // Activar el tab-pane con el ID "marca"
                    $('.nav-tabs a[href="#categoria"]').tab('show');
                });
                $('#btnCerrarMarca').click(function(event) {
                    event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

                    // Activar el tab-pane con el ID "marca"
                    $('.nav-tabs a[href="#producto"]').tab('show');
                });
                $('#btnCerrarCategoria').click(function(event) {
                    event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

                    // Activar el tab-pane con el ID "marca"
                    $('.nav-tabs a[href="#producto"]').tab('show');
                });
            });
        </script>
        <script>
            function soloLetras(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
                especiales = "8-37-39-46";

                tecla_especial = false
                for (var i in especiales) {
                    if (key == especiales[i]) {
                        tecla_especial = true;
                        break;
                    }
                }

                if (letras.indexOf(tecla) == -1 && !tecla_especial) {
                    return false;
                }
            }

            $("#selectAssignmentDad").prop('disabled', 'disabled');

            $("#productAssignmentDad").click(function() {
                $("#selectAssignmentDad").prop('disabled', 'disabled');
            });

            $("#productAssignmentSon").click(function() {
                ($('#productAssignmentSon').is(':checked')) ? $("#selectAssignmentDad").prop('disabled', false): $("#selectAssignmentDad").prop('disabled', 'disabled');
            });

            $("#productAssignmentD").click(function() {

                if ($(this).is(":checked")) {
                    $('#bipay').show();
                } else {
                    $('#bipay').hide();
                }
            });



            $("#FormEditarProducto #editproductAssignmentSon").click(function() {
                if ($('#FormEditarProducto #editproductAssignmentSon').is(':checked')) {
                    $("#FormEditarProducto select[name='editselectAssignmentDad']").prop('disabled', false);
                    $("#FormEditarProducto input[name='editproductAssignmentDad']").prop('checked', false);
                } else {
                    $("#FormEditarProducto #editselectAssignmentDad").prop('disabled', 'disabled')
                }

            });

            $("#FormEditarProducto #editproductAssignmentDad").click(function() {
                if ($('#FormEditarProducto #editproductAssignmentDad').is(':checked')) {
                    $("#FormEditarProducto select[name='editselectAssignmentDad']").prop('disabled', true);
                    $("#FormEditarProducto select[name='editselectAssignmentDad']").prop('value', '')
                    $("#FormEditarProducto input[name='editproductAssignmentSon']").prop('checked', false);
                } else {
                    $("#FormEditarProducto #editselectAssignmentDad").prop('disabled', 'disabled')
                }

            });
        </script>