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
                            <h4 class="page-title float-left">Editar Cotización</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="#">Cotización</a></li>
                                <li class="breadcrumb-item active">Editar</li>
                            </ol>
                        </div>
                    </div>
                </div>
              
                <!-- end row -->

                <!-- Vertical Steps Example -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                              
                              <form id="FormCotizacionEditar" class="FormCotizacion" action="<?= base_url('administrador/regcotizacion/editarCotizacion') ?>" autocomplete="off" method="POST">
                                <input type="hidden" name="id" value="<?= $cotizacion->cod_cot ?>">
                                <input type="hidden" name="cliente" value="<?= $cotizacion->id_cliente ?>">


                                <input type="hidden" name="total" value="<?= $cotizacion->total_cot ?>">
                                <fieldset>
                                  <legend>Principal</legend>
                                  <div class="row">
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">Fecha</label>
                                        <input type="text" name="fecha" class="form-control datepicker" value="<?= $cotizacion->fecha_cot ?>">
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Tipo pedido</label>
                                        <input type="text" class="form-control" value="<?= $cotizacion->nom_tipdocumento.' '.$cotizacion->serie ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">N°</label>
                                        <input type="text" name="numero" class="form-control" value="<?= $cotizacion->numero_cot ?>" disabled>
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
                                        <input type="text" id="RUCAutocomplete" name="rucdni" class="form-control" readonly value="<?= $cotizacion->doc_cliente ?>">
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Cliente</label>
                                        <input type="text" id="ClienteAutocomplete" name="nombreCliente" class="form-control" value="<?= $cotizacion->nomb_cliente ?>">
                                      </div>
                                    </div>
																		<div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">Precio</label>
                                        <input type="text" name="precioCliente" class="form-control"  readonly value="<?= $cotizacion->precio_cliente ?>">
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Dirección</label>
                                        <input type="text" id="DireccionCliente" class="form-control"  readonly value="<?= $cotizacion->direc_cliente ?>">
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
                                        <input type="text" name="tipoCambio" class="form-control" value="<?= $cotizacion->cambio_cat ?>" readonly>
                                      </div>
                                    </div>
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">Pago</label>
                                        <select name="pago" class="form-control">
                                          <option value="CO" <?= $cotizacion->pago_cot=='CO'?'selected':'' ?>>Contado</option>
                                          <option value="CRE" <?= $cotizacion->pago_cot=='CRE'?'selected':'' ?>>Crédito</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">Monto</label>
                                        <input type="text"  name="monto" class="form-control" value="<?= $cotizacion->monto_cot ?>" <?= ($cotizacion->pago_cot=='CO')?'readonly':'' ?>>
                                      </div>
                                    </div>
                                    
                                    <div class="col-md-1" <?= $cotizacion->pago_cot=='CO'?'style="display:none"':'' ?>>
                                      <div class="form-group">
                                        <label class="control-label">Dias</label>
                                        <input type="text" name="dias" class="form-control" value="<?= $cotizacion->dias_cot ?>" <?= $cotizacion->pago_cot=='CO'?'disabled':'' ?>>
                                      </div>
                                    </div>
                                    <div class="col-md-2" <?= $cotizacion->pago_cot=='CO'?'style="display:none"':'' ?>>
                                      <div class="form-group">
                                        <label class="control-label" >Fecha</label>
                                        <input type="text" name="fecVenc" class="form-control" value="<?= $cotizacion->fechavenc_cot ?>" readonly>
                                      </div>
                                    </div>
                                    <div class="col-md-2" <?= $cotizacion->pago_cot=='CO'?'style="display:none"':'' ?>>
                                      <div class="form-group">
                                        <label class="control-label">Saldo</label>
                                        <input type="text" name="saldo" class="form-control" value="<?= $cotizacion->saldo_cot ?>" readonly>
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
                                <table id="TableCotizacionProductos" class="table table-bordered">
                                  <thead>
                                    <tr class="bg-primary text-white">
                                      <th>Código</th>
                                      <th>Artículo</th>
                                      <th>Marca</th>
                                      <th>Unidad</th>
                                      <th>Cant.</th>
                                      <th>P. Unit.</th>
                                      <th>Desc.</th>
                                      <th>IGV</th>
                                      <th>Prec. Sin IGV</th>
                                      <th>Subtotal</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php foreach ($detalle as $d): ?>
                                    
                                    <tr id="prod-<?= $d->cod_producto ?>" data-id="<?= $d->cod_producto ?>">
                                      <input type="hidden" name="id_prod[]" value="<?= $d->cod_producto ?>">
                                      <td><?= $d->cod_producto ?></td>
                                      <td><?= $d->nomb_product ?></td>
                                      <td><?= $d->nomb_marca ?></td>
                                      <td><?= $d->nomb_unid ?></td>
                                      <td style="width:110px"><input type="number" class="cant form-control" name="cant_prod[]" value="<?= $d->cant_cotdet ?>" min="1" step="1"></td>
                                      <td style="width:140px"><input type="text" class="prec form-control" name="prec_prod[]" value="<?= $d->precunit_cotdet ?>"></td>
                                      <td>
                                        <input type="hidden" class="desc" name="desc_prod[]" value="<?= $d->descuento_cotdet ?>">
                                        <?= $d->descuento_cotdet ?>
                                      </td>
                                      <td><?= $d->igv_cotdet ?></td>
                                      <td><?= $d->prec_cotdet ?></td>
                                      <td><?= $d->subtotal_cotdet ?></td>
                                      <td>
                                        <div class="btn-group btn-group-justified m-b-10">
                                           <button data-id="<?= $d->cod_producto ?>" class="removerProducto btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                      </td>
                                    </tr>
                                    
                                    <?php endforeach ?>
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <td colspan="8"></td>
                                      <th>Valor Venta</th>
                                      <td id="CotizacionValorVenta"><?= $cotizacion->subtotal_cot ?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="8"></td>
                                      <th>IGV</th>
                                      <td id="CotizacionIGV"><?= $cotizacion->igv_cot ?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="8"></td>
                                      <th>Total</th>
                                      <td id="CotizacionTotal"><?= $cotizacion->total_cot ?></td>
                                    </tr>
                                  </tfoot>
                                </table>
                              </form>

                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <button type="submit" form="FormCotizacionEditar" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
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


<div class="modal" id="ModalAgregarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cliente - Agregar</h5>
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
                <label class="control-label">Ruc ó Dni:</label>
                <input type="text" name="documento" class="form-control" maxlength="11" minlength="8" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
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
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal" id="ModalEditarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cliente - Editar</h5>
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
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
