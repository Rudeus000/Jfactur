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
              <!-- <h4 class="page-title float-left"> <i class="fas fa-shopping-cart"></i> Agregar Compra</h4> -->
              <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="#">Compras</a></li>
                <li class="breadcrumb-item active">Agregar</li>
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
                <h3 class="my-0 text-white">Agregar compra</h3>
              </div>
              <div class="card-body table-responsive">
                <form id="FormComprasAgregar" action="<?= base_url('administrador/regcompras/agregarCompra') ?>"
                  autocomplete="off">


                  <input type="hidden" name="proveedor">
                  <fieldset>
                    <legend>Principal</legend>
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Fecha:</label>
                          <input type="text" name="fecha" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Documento:</label>
                          <select name="documento" class="form-control select2">
                            <option value="">Seleccionar</option>
                            <option value="BOLETA ELECTRONICA">BOLETA ELECTRONICA</option>
                            <option value="FACTURA ELECTRONICA">FACTURA ELECTRONICA</option>
                            <option value="ORDEN DE COMPRA">ORDEN DE COMPRA</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">N° Documento:</label>
                          <input type="text" name="numDocumento" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Colocar producto en almacen:</label>
                          <select name="almacen" class="form-control select2">
                            <?php foreach ($almacenes as $a): ?>
                              <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Moneda</label>
                          <select name="tipmoneda" id="tipmoneda" class="form-control">
                            <option value="PEN">PEN (S/.)</option>
                            <option value="USD">USD ($)</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2" id="tipocam" style="display:none">
                        <div class="form-group">
                          <label class="control-label">Tipo cambio hoy:</label>
                          <input typet="text" name="tipocambio" id="tipoc" class="form-control">
                        </div>
                      </div>
                    </div>
                  </fieldset>

                  <fieldset>
                    <legend>Proveedor</legend>
                    <div class="row">
                      <div class="col-md-2">
                        <label class="control-label" style="display: block"></label><br>
                        <div class="btn-group">
                          <button data-toggle="modal" data-target="#ModalAgregarProveedor" type="button"
                            class="btn btn-success waves-effect btn-sm"><i class="fas fa-plus"></i></button>
                          <button id="CompraEditarProveedor" type="button"
                            class="btn btn-warning waves-effect btn-sm"><i class="fas fa-pencil-alt"></i></button>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">RUC/DNI</label>
                          <input type="text" id="RUCAutocomplete" name="rucdni" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="control-label">Proveedor:</label>
                          <input type="text" id="ProveedorAutocomplete" name="nombreProveedor" class="form-control">
                        </div>
                      </div>
                    </div>
                  </fieldset>

                  <fieldset>
                    <legend>Pagos</legend>
                    <div class="row">

                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Pago</label>
                          <select name="pago" class="form-control">
                            <option value="CO">Contado</option>
                            <option value="CRE">Crédito</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Efectivo:</label>
                          <input type="text" name="efectivo" class="form-control" readonly value="0">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Caja:</label>
                          <select name="caja" class="form-control" required>
                            <?php foreach ($cajas as $c): ?>
                              <option value="<?= $c->cod_caja ?>"><?= $c->nomb_caja ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2" style="display: none">
                        <div class="form-group">
                          <label class="control-label">Crédito:</label>
                          <input type="text" name="credito" class="form-control" value="0.00">
                        </div>
                      </div>
                      <div class="col-md-2" style="display: none">
                        <div class="form-group">
                          <label class="control-label">N° Dias</label>
                          <input type="text" name="dias" class="form-control" disabled required>
                        </div>
                      </div>
                      <div class="col-md-2" style="display: none">
                        <div class="form-group">
                          <label class="control-label">Fec.Venc.</label>
                          <input type="text" name="fecVenc" class="form-control" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Diferencia: <b><span
                                id="ComprasDiferencia">0.00</span></b></label>
                        </div>
                      </div>
                    </div>
                  </fieldset>

                </form>

                <form id="FormComprasAgregarProducto" autocomplete="on">
                  <input type="hidden" name="producto">
                  <input type="hidden" name="fec_venc">
                  <fieldset>
                    <legend>Agregar Producto</legend>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Nombre[<a title="" data-toggle="modal"
                              data-target="#ModalCompraAgregarProducto" role="button" aria-haspopup="false"
                              aria-expanded="false" data-original-title="Nuevo">
                              <i class="fas fa-external-link-alt text-pink waves-light waves-effect"></i></a> ]</label>
                          <input type="text" id="nombreProductoAutocomplete" name="nombreProducto" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group">
                          <label class="control-label">Uni.</label>
                          <input type="text" name="unidadProducto" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Tipo IGV:<span class="text-danger"> *</label>
                          <select class="form-control input-sm select2" name="parametros">
                            <option value="">--Selecciona--</option>
                            <?php foreach ($parametros as $pr): ?>
                              <option value="<?= $pr->cod_parametros ?>"><?= $pr->nom_paramt ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group">
                          <label class="control-label">Precio</label>
                          <input type="text" name="precioProducto" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group">
                          <label class="control-label">Prec.Vent.Act.</label>
                          <input type="text" name="precioProductovent" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group">
                          <label class="control-label">Cant.</label>
                          <input type="text" name="cantidadProducto" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" name="seriesProducto" class="custom-control-input"
                            id="serieCheckComprar">
                          <label class="custom-control-label" for="serieCheckComprar"
                            style="margin-top:28px">Series</label>
                        </div>
                      </div>
                      <div class="col-md-1">
                        <button type="submit" style="margin-top: 32px" class="btn btn-sm btn-success"><i
                            class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </fieldset>
                  <table id="TableComprasProductos" class="table table-striped">
                    <thead>
                      <tr class="btn-success">
                        <th></th>
                        <th style="text-align: center;">Código</th>
                        <th style="text-align: center;">Artículo</th>
                        <th style="text-align: center;">Marca</th>
                        <th style="text-align: center;">Unidad</th>
                        <th style="text-align: center;">Cantidad</th>
                        <th style="text-align: center;">P. Unit.</th>
                        <th style="text-align: center;">IGV</th>
                        <th style="text-align: center;">Sub.total</th>
                        <th style="text-align: center;">Importe</th>
                        <th>

                        </th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="8"></td>
                        <th class="bg-danger text-white">Gravada</th>
                        <td class="bg-danger text-white" id="compra-gravada">00.00</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                        <th class="bg-danger text-white">Exonerada</th>
                        <td class="bg-danger text-white" id="compra-exonerada">00.00</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                        <th class="bg-danger text-white">Gratuito</th>
                        <td class="bg-danger text-white" id="compra-gratuito">00.00</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                        <th class="bg-danger text-white">IGV</th>
                        <td class="bg-danger text-white" id="compra-igv">00.00</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                        <th class="bg-danger text-white">Total</th>
                        <td class="bg-danger text-white" id="compra-total">00.00</td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </form>

                <div class="row" id="ComprasContenedorGuardar">
                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="submit" id="guardarCompras" form="FormComprasAgregar" class="btn btn-success"><i class="fa fa-save"></i>
                        Guardar</button>
                      <a href="<?= base_url('administrador/regcompras') ?>" class="btn btn-pink"><i
                          class="fas fa-times"></i> Cerrar</a>
                    </div>
                  </div>
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


<div class="modal" id="ModalAgregarProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Proveedor - Agregar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormComprarAgregarProveedor" action="<?= base_url('administrador/regcompras/agregarProveedor') ?>"
        autocomplete="off" method="post">
        <div class="modal-body">
          <div class="row">


            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Tipo:</label>
                <select name="tipo" id="tipo_documento" class="form-control">
                  <option value="4">RUC</option>
                  <option value="2">DNI</option>
                </select>
              </div>
            </div>


            <div class="col-md-8">
              <div id="capa_load"></div>
              <label class="control-label">Nº documento</label>
              <div class="input-group">
                <input type="text" class="form-control" name="documento" id="txt_documento">
                <div class="input-group-append">
                  <button class="btn btn-primary waves-effect waves-light" type="button"
                    onclick="buscar();">sunat</button>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" id="txt_nombre">
              </div>
            </div>



            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" id="txt_direccion">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Email</label>
                <input type="text" name="email" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal" id="ModalEditarProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Proveedor - Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormComprarEditarProveedor" action="<?= base_url('administrador/regcompras/editarProveedor') ?>"
        autocomplete="off" method="post">
        <input type="hidden" name="id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Nombre</label>
                <input type="text" name="nombre" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Documento</label>
                <input type="text" name="documento" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Dirección</label>
                <input type="text" name="direccion" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Email</label>
                <input type="text" name="email" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>



<div class="modal" id="ModalSeries" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Series</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormSeriesVerificar" action="<?= base_url('administrador/regcompras/verificaSerie') ?>"
        autocomplete="off" method="post">
        <input type="hidden" name="prodseri">
        <input type="hidden" name="almseri">
        <div class="modal-body">
          <!-- Nuevo Combobox dentro del Modal -->
          <div class="form-group">
            <label class="control-label">Tipo de Ingreso</label>
            <select class="form-control input-sm" id="tipoIngresoSeries">
              <option value="independiente">Ingreso Independiente</option>
              <option value="masivo">Ingreso Masivo</option>
            </select>
          </div>
          <!-- Campos adicionales para ingreso masivo -->
          <div class="row" id="serieMasivaInputs" style="display:none;">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Serie Inicial</label>
                <input type="text" name="serieInicial" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Serie Final</label>
                <input type="text" name="serieFinal" class="form-control">
              </div>
            </div>
          </div>
          <div class="row inputSeries">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>



<div class="modal" id="ModalFechaVencimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Fecha Vencimiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormCompraFechaVencimiento" autocomplete="off" method="post">
        <input type="hidden" name="producto">
        <div class="modal-body">
          <div id="BodyFechaVencimientoCompra"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div id="ModalCompraAgregarProducto" class="modal bs-example-modal-center" role="dialog"
  aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="producto">
                  <form id="FormCompraAddProducto" action="<?= base_url('administrador/regcompras/addProducto') ?>"
                    method="post" autocomplete="off">
                    <input type="hidden">
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="control-label">Nombre:<span class="text-danger"> *</label>

                          <div class="form-check form-check-inline ml-2">
                            <input class="form-check-input" type="checkbox" name="productAssignment"
                              id="productAssignmentDad" value="P">
                            <label class="form-check-label" for="productAssignmentDad">P</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="productAssignment"
                              id="productAssignmentSon" value="H">
                            <label class="form-check-label" for="productAssignmentSon">H</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="productoConasignacion"
                              id="productAssignmentGson" value="G">
                            <label class="form-check-label" for="productAssignmentGson">G</label>
                          </div>

                          <input type="text" name="nombre" class="form-control">
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Producto Padre:<span class="text-danger"> *</label>
                          <select class="form-control select2" id="selectAssignmentDad" name="selectAssignmentDad">
                            <option value="">--Selecciona--</option>
                            <?php foreach ($TypeproductAssignments as $TypeproductAssignment): ?>
                              <option value="<?= $TypeproductAssignment->cod_producto ?>">
                                <?= $TypeproductAssignment->nomb_product ?></option>
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
                              <?php foreach ($marca as $marc): ?>
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
                              <?php foreach ($categoria as $ca): ?>
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
                              <?php foreach ($medida as $me): ?>
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
                              <?php foreach ($articulo as $tp): ?>
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
                            <?php foreach ($linea as $li): ?>
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
                            <?php foreach ($sublinea as $sb): ?>
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
                            <?php foreach ($talla as $tl): ?>
                              <option value="<?= $tl->cod_talla ?>"><?= $tl->nomb_talla ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Presentacion:<span class="text-danger"> *</label>
                          <select class="form-control select2 select2-hidden-accessible input-sm" name="presentacion">
                            <!-- <option value="">--Selecciona--</option> -->
                            <?php foreach ($presentacion as $pres): ?>
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
                          <input type="text" name="stock" class="form-control"
                            onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
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
                            <?php foreach ($parametros as $pr): ?>
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
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
                    </div>
                  </form>
                  <!-- </form> -->
                </div>
                <div class="tab-pane" id="marca">
                  <form id="FormMarcaProd" action="<?= base_url('administrador/regproducto/insertMarcaprod') ?>"
                    method="post" autocomplete="off">
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
                        <a href="" class="btn btn-pink" id="btnCerrarMarca"><i class="fas fa-fast-backward"></i>
                          Back</a>
                        <button type="submit" form="FormMarcaProd" class="btn btn-success "><i
                            class="fa fa-save m-r-5"></i>Guardar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="categoria">

                  <form id="FormCategoriaProd" action="<?= base_url('administrador/regproducto/insertCategoriaprod') ?>"
                    method="post" autocomplete="off">
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
                        <a href="" class="btn btn-pink" id="btnCerrarCategoria"><i class="fas fa-fast-backward"></i>
                          Back</a>
                        <button type="submit" form="FormCategoriaProd" class="btn btn-success "><i
                            class="fa fa-save m-r-5"></i>Guardar</button>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="tab-pane" id="unidadm">
                  <form id="FormUmedida" action="<?= base_url('administrador/regproducto/insertUmedidaprod') ?>"
                    method="post" autocomplete="off">
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
                            <?php foreach ($tipounidad as $t): ?>
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
                        <button type="submit" form="FormCategoria" class="btn btn-success "><i
                            class="fa fa-save m-r-5"></i>Procesar</button>
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
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
  $(document).ready(function() {
    $('#tipmoneda').on('change', function() {
      var moneda = $(this).val();
      if (moneda === 'USD') {
        // Consumir la API cuando se selecciona USD
        $('#tipocam').show();
        $.ajax({
          url: 'https://apis.bfacturas.pro/example/t',
          method: 'GET',
          success: function(response) {
            if (response.success) {
              // Asignar el valor de venta al input
              $('#tipoc').val(response.result.venta);
            } else {
              alert('No se pudo obtener el tipo de cambio.');
            }
          },
          error: function() {
            alert('Error al obtener el tipo de cambio.');
          }
        });
      } else {
        // Limpiar el input si se selecciona otra moneda
        $('#tipocam').hide();
        $('#tipoc').val('');
      }
    });
  });
</script>
<script>
  $(document).ready(function () {
    // Manejar el clic en el botón
    $('#btnAbrirMarca').click(function (event) {
      event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

      // Activar el tab-pane con el ID "marca"
      $('.nav-tabs a[href="#marca"]').tab('show');
    });
    $('#btnAbrirCategoria').click(function (event) {
      event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

      // Activar el tab-pane con el ID "marca"
      $('.nav-tabs a[href="#categoria"]').tab('show');
    });
    $('#btnCerrarMarca').click(function (event) {
      event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

      // Activar el tab-pane con el ID "marca"
      $('.nav-tabs a[href="#producto"]').tab('show');
    });
    $('#btnCerrarCategoria').click(function (event) {
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

  $("#productAssignmentDad").click(function () {
    $("#selectAssignmentDad").prop('disabled', 'disabled');
  });

  $("#productAssignmentSon").click(function () {
    ($('#productAssignmentSon').is(':checked')) ? $("#selectAssignmentDad").prop('disabled', false) : $("#selectAssignmentDad").prop('disabled', 'disabled');
  });


  $("#FormEditarProducto #editproductAssignmentSon").click(function () {
    if ($('#FormEditarProducto #editproductAssignmentSon').is(':checked')) {
      $("#FormEditarProducto select[name='editselectAssignmentDad']").prop('disabled', false);
      $("#FormEditarProducto input[name='editproductAssignmentDad']").prop('checked', false);
    } else {
      $("#FormEditarProducto #editselectAssignmentDad").prop('disabled', 'disabled')
    }

  });

  $("#FormEditarProducto #editproductAssignmentDad").click(function () {
    if ($('#FormEditarProducto #editproductAssignmentDad').is(':checked')) {
      $("#FormEditarProducto select[name='editselectAssignmentDad']").prop('disabled', true);
      $("#FormEditarProducto select[name='editselectAssignmentDad']").prop('value', '')
      $("#FormEditarProducto input[name='editproductAssignmentSon']").prop('checked', false);
    } else {
      $("#FormEditarProducto #editselectAssignmentDad").prop('disabled', 'disabled')
    }

  });
</script>