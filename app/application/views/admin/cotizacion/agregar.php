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
                            <!-- <h4 class="page-title float-left"><i class="fas fa-chalkboard-teacher"></i> Agregar Cotización</h4> -->
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="#">Cotización</a></li>
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
                        <div class="card-header bg-success"><h3 class="my-0 text-white">Agregar cotizacion</h3></div>
                            <div class="card-body">
                              <form id="FormCotizacionAgregar" class="FormCotizacion" action="<?= base_url('administrador/regcotizacion/agregarCotizacion') ?>" autocomplete="off">
                                <input type="hidden" name="cliente">
                                <input type="hidden" name="total">

                                <fieldset>
                                  <legend>Principal</legend>
                                  <div class="row">
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">Fecha</label>
                                        <input type="text" name="fecha" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Tipo pedido</label>
                                        <select name="tipoPedido" class="form-control">
                                          <option value="">Seleccion</option>
                                          <?php foreach ($tipos as $t): ?>
                                          <option value="<?= $t->cod_talonario ?>"><?= $t->nom_tipdocumento.'-'.$t->serie ?></option>
                                          <?php endforeach ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">N°</label>
                                        <input type="text" name="numero" class="form-control"  readonly>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>


                                <fieldset>
                                  <legend>Cliente</legend>
                                  <div class="row">
                                    <div class="col-md-2">
                                        <label class="control-label" style="display: block"></label><br>
                                      <div class="btn-group">
                                        <button data-toggle="modal" data-target="#ModalAgregarCliente" type="button" class="btn btn-success waves-effect btn-sm"><i class="fas fa-plus"></i></button>
                                        <button id="CotizacionEditarCliente" type="button" class="btn btn-warning waves-effect btn-sm"><i class="fas fa-pencil-alt"></i></button>
                                      </div>
                                    </div>
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">RUC/DNI</label>
                                        <input type="text" id="RUCAutocomplete" name="rucdni" class="form-control" readonly>
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Cliente</label>
                                        <input type="text" id="ClienteAutocomplete" name="nombreCliente" class="form-control">
                                      </div>
                                    </div>
																		<div class="col-md-2">
																			<div class="form-group">
																				<label class="control-label">Precio</label>
																				<input type="text" name="precioCliente" class="form-control" readonly>
																			</div>
																		</div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Dirección</label>
                                        <input type="text" id="DireccionCliente" class="form-control" readonly>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>

                                <fieldset>
                                  <legend>Pagos</legend>
                                  <div class="row">
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">Moneda</label>
                                        <select name="moneda" class="form-control">
                                          <option value="S" data-valor="1">Soles</option>
                                          <option value="D" data-valor="<?= $dolar->valor_paramt ?>">Dolares</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">Tipo cambio</label>
                                        <input type="text" name="tipoCambio" class="form-control" value="1" readonly>
                                      </div>
                                    </div>
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
                                        <label class="control-label">Monto</label>
                                        <input type="text" name="monto" class="form-control" value="0" required readonly>
                                      </div>
                                    </div>
                                    <div class="col-md-1" style="display: none">
                                      <div class="form-group">
                                        <label class="control-label">Dias</label>
                                        <input type="number" min="1" disabled name="dias" class="form-control">
                                      </div>
                                    </div>
                                    <div class="col-md-2" style="display: none">
                                      <div class="form-group">
                                        <label class="control-label">Fecha</label>
                                        <input type="text" name="fecVenc" disabled class="form-control" readonly>
                                      </div>
                                    </div>
                                    <div class="col-md-2" style="display: none">
                                      <div class="form-group">
                                        <label class="control-label">Saldo</label>
                                        <input type="text" name="saldo" required disabled class="form-control" readonly>
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>

                              </form>

                              <form id="FormCotizacionAgregarProducto" autocomplete="off">
                                <input type="hidden" name="producto">
                                <fieldset>
                                  <legend>Agregar Producto</legend>
                                  <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="control-label">Nombre</label>
                                        <input type="text" id="CotizacionProductoAutocomplete" name="nombreProducto" class="form-control">
                                      </div>
                                    </div>
                                    <div class="col-md-2">
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
                                      <div class="form-group">
                                        <label class="control-label">Desc. %</label>
                                        <input type="text" name="descuentoProducto" class="form-control">

                                      </div>
                                    </div>
                                    <div class="col-md-1">
                                      <button type="submit" style="margin-top: 32px" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
                                    </div>
                                  </div>
                                </fieldset>
                                <table id="TableCotizacionProductos" class="table table-striped">
                                  <thead>
                                    <tr class="btn-success">
                                      <th style="text-align: center;">Código</th>
                                      <th style="text-align: center;">Artículo</th>
                                      <th style="text-align: center;">Marca</th>
                                      <th style="text-align: center;">Unidad</th>
                                      <th style="text-align: center;">Cant.</th>
                                      <th style="text-align: center;">P. Unit.</th>
                                      <th style="text-align: center;">Desc.</th>
                                      <th style="text-align: center;">IGV</th>
                                      <th style="text-align: center;">Prec. Sin IGV</th>
                                      <th style="text-align: center;">Subtotal</th>
                                      <th style="text-align: center;">Opc.</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <td colspan="8"></td>
                                      <th>Valor Venta</th>
                                      <td id="CotizacionValorVenta">00.00</td>
                                      <td></td>
                                    </tr>
                                    <tr>
                                      <td colspan="8"></td>
                                      <th>IGV</th>
                                      <td id="CotizacionIGV">00.00</td>
                                      <td></td>
                                    </tr>
                                    <tr>
                                      <td colspan="8"></td>
                                      <th>Total</th>
                                      <td id="CotizacionTotal">00.00</td>
                                      <td></td>
                                    </tr>
                                  </tfoot>
                                </table>
                              </form>

                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <button type="submit" form="FormCotizacionAgregar" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                                    <a href="<?= base_url('administrador/regcotizacion') ?>" class="btn btn-pink"><i class="fas fa-times"></i> Cerrar</a>
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


<div class="modal" id="ModalAgregarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fas fa-user-friends m-r-5"></i>Cliente - Agregar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormCotizacionAgregarCliente" action="<?= base_url('administrador/regcotizacion/agregarCliente') ?>" autocomplete="off" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Tipo:</label>
                  <select name="tipo" class="form-control">
										<option value="">Seleccione</option>
										<?php foreach($doc_clientes as $d): ?> 
										<option value="<?= $d->cod_tipdocucli ?>"><?= $d->nom_tipdocucli ?></option>
										<?php endforeach ?>
                 </select>
              </div>

            </div>

            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Nombre o Razon Social</label>
                <input type="text" name="nombre" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Ruc ó Dni:</label>
                <input type="text" name="documento" class="form-control" maxlength="11" minlength="8" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
              </div>
            </div>
						<div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Precio:</label>
                <select name="precio" class="form-control">
										<option value="Normal">Precio Normal</option>
										<option value="Mayor">Precio x Mayor</option>
										<option value="Especial">Precio Especial</option>
								</select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
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
                <label class="control-label">Contacto</label>
                <input type="text" name="contacto" class="form-control">
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Email</label>
                <input type="email" name="email" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal" id="ModalEditarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fas fa-user-edit m-r-5"></i>Cliente - Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormCotizacionEditarCliente" action="<?= base_url('administrador/regcotizacion/editarCliente') ?>" autocomplete="off" method="post">
        <input type="hidden" name="id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Tipo:</label>
                  <select name="tipo" class="form-control">
										<option value="">Seleccione</option>
										<?php foreach($doc_clientes as $d): ?> 
										<option value="<?= $d->cod_tipdocucli ?>"><?= $d->nom_tipdocucli ?></option>
										<?php endforeach ?>
                 </select>
              </div>

            </div>

            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Nombre o Razon Social</label>
                <input type="text" name="nombre" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Ruc ó Dni:</label>
                <input type="text" name="documento" class="form-control" maxlength="11" minlength="8" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
              </div>
            </div>
						<div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Precio:</label>
                <select name="precio" class="form-control">
										<option value="Normal">Precio Normal</option>
										<option value="Mayor">Precio x Mayor</option>
										<option value="Especial">Precio Especial</option>
								</select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
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
                <label class="control-label">Contacto</label>
                <input type="text" name="contacto" class="form-control">
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Email</label>
                <input type="email" name="email" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
