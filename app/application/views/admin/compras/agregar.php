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
                <form id="FormComprasAgregar" action="<?= base_url('administrador/regcompras/agregarCompra') ?>" autocomplete="off">


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
                      <div class="col-md-3">
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
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Colocar producto en almacen:</label>
                          <select name="almacen" class="form-control select2">
                            <?php foreach ($almacenes as $a) : ?>
                              <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <!-- <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Mostrar</label>
                                        <select name="mostrar" class="form-control">
                                          <option value="valor">Valor Venta (Sin IGV)</option>
                                          <option value="precio">Precio Venta (Con IGV)</option>
                                        </select>
                                      </div>
                                    </div> -->
                    </div>
                  </fieldset>

                  <fieldset>
                    <legend>Proveedor</legend>
                    <div class="row">
                      <div class="col-md-2">
                        <label class="control-label" style="display: block"></label><br>
                        <div class="btn-group">
                          <button data-toggle="modal" data-target="#ModalAgregarProveedor" type="button" class="btn btn-success waves-effect btn-sm"><i class="fas fa-plus"></i></button>
                          <button id="CompraEditarProveedor" type="button" class="btn btn-warning waves-effect btn-sm"><i class="fas fa-pencil-alt"></i></button>
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
                            <?php foreach ($cajas as $c) : ?>
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
                          <label class="control-label">Diferencia: <b><span id="ComprasDiferencia">0.00</span></b></label>
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
                          <label class="control-label">Nombre</label>
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
                          <label class="control-label">Precio</label>
                          <input type="text" name="precioProducto" class="form-control">
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
                          <input type="checkbox" name="seriesProducto" class="custom-control-input" id="serieCheckComprar">
                          <label class="custom-control-label" for="serieCheckComprar" style="margin-top:28px">Series</label>
                        </div>
                      </div>
                      <div class="col-md-1">
                        <button type="submit" style="margin-top: 32px" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
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
                        <th style="text-align: center;">Valor Venta</th>
                        <th style="text-align: center;">Subtotal</th>
                        <th>

                        </th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="8"></td>
                        <th>Valor Venta</th>
                        <td id="CompraValorVenta">00.00</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                        <th>IGV</th>
                        <td id="CompraIGV">00.00</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                        <th>Total</th>
                        <td id="CompraTotal">00.00</td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </form>

                <div class="row" id="ComprasContenedorGuardar">
                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="submit" form="FormComprasAgregar" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                      <a href="<?= base_url('administrador/regcompras') ?>" class="btn btn-pink"><i class="fas fa-times"></i> Cerrar</a>
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


<div class="modal" id="ModalAgregarProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Proveedor - Agregar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormComprarAgregarProveedor" action="<?= base_url('administrador/regcompras/agregarProveedor') ?>" autocomplete="off" method="post">
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
                  <button class="btn btn-primary waves-effect waves-light" type="button" onclick="buscar();">sunat</button>
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


<div class="modal" id="ModalEditarProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Proveedor - Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormComprarEditarProveedor" action="<?= base_url('administrador/regcompras/editarProveedor') ?>" autocomplete="off" method="post">
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
      <form id="FormSeriesVerificar" action="<?= base_url('administrador/regcompras/verificarSerties') ?>" autocomplete="off" method="post">
        <input type="hidden" name="prodseri">
        <input type="hidden" name="almseri">
        <div class="modal-body">
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



<div class="modal" id="ModalFechaVencimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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