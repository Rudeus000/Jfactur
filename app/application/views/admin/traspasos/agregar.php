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
              <!-- <h4 class="page-title float-left"><i class="fas fa-exchange-alt"></i>  Transferencia</h4> -->
              <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="#">Traspaso</a></li>
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
                <h3 class="my-0 text-white">Realizar transferencia de productos de un almacen a otro <i
                    class="spinner-grow text-warning float-right"></i> </h3>
              </div>
              <div class="card-body">
                <form id="FormAgregarTraspasos" action="<?= base_url('administrador/regcompras/agregarCompra') ?>"
                  autocomplete="off">


                  <input type="hidden" name="proveedor">
                  <fieldset>
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Fecha:</label>
                          <input type="text" name="fecha" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Origen:</label>
                          <select name="origen" class="form-control select2">
                            <option value="">Seleccione</option>
                            <?php foreach ($almacenes as $a): ?>
                              <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Destino:</label>
                          <select name="destino" class="form-control select2">
                            <option value="">Seleccione</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Observación:</label>
                          <input type="text" name="observacion" class="form-control">
                        </div>
                      </div>
                    </div>
                  </fieldset>

                </form>

                <form id="FormTraspasosAgregarProducto" autocomplete="off">
                  <input type="hidden" name="producto">
                  <fieldset>
                    <legend>Buscar Producto</legend>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Nombre:</label>
                          <input type="text" id="nombreProductoTraspasoAutocomplete" name="nombreProducto"
                            class="form-control">
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
                          <label class="control-label">Precio:</label>
                          <input type="text" name="precioProducto" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Cantidad</label>
                          <div class="input-group mb-3">
                            <input type="text" name="cantidadProducto" class="form-control">
                            <div class="input-group-append">
                              <span class="input-group-text">Serie</span>
                              <div class="input-group-text">
                                <input type="checkbox" id="checkbox-serie" name="serie">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-1">
                        <button type="submit" style="margin-top: 32px" class="btn btn-rounded btn-pink"><i
                            class="fas fa-angle-down m-r-5"></i>Agregar</button>
                      </div>
                    </div>
                  </fieldset>
                  <table id="TableTraspasosProductos" class="table table-bordered">
                    <thead>
                      <tr class="bg-success text-white">
                        <th style="text-align: center;">Código</th>
                        <th style="text-align: center;">Artículo</th>
                        <th style="text-align: center;">Unidad</th>
                        <th width="200px">Cantidad</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </form>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="submit" form="FormAgregarTraspasos" id="guardarTraspaso"
                        class="btn btn-rounded btn-info"><i class="fa fa-save"></i> Guardar</button>
                      <a href="<?= base_url('administrador/regtraspasos') ?>" class="btn btn-rounded btn-pink"><i
                          class="fas fa-times"></i> Cerrar</a>
                      <!-- <button type="button" class="btn btn-danger" ><i class="fas fa-times"></i>  Cerrar</button> -->
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

<div class="modal" id="serieModal" tabindex="-1" role="dialog" aria-labelledby="serieModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="serieModalLabel">Ingresar Series</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="serieMode">Modo de ingreso</label>
          <select class="form-control" id="serieMode">
            <option value="individual">Individual</option>
            <option value="bloque">En Bloque</option>
          </select>
        </div>

        <div class="row serie" id="seriesContainerIndividual"></div> <!-- Inputs para series individuales -->

        <div class="row" id="seriesContainerBloque" style="display: none;">
          <div class="col-md-6">
            <div class="form-group">
              <label for="serieInicial">Serie Inicial</label>
              <input type="text" class="form-control" id="serieInicial" placeholder="Ingrese la serie inicial"  required="">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="serieFinal">Serie Final</label>
              <input type="text" class="form-control" id="serieFinal" placeholder="Ingrese la serie final"  required="">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" id="saveSeries" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>