<div id="wrapper">
 <div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">
          <div class="page-title-box">
            <!-- <h4 class="page-title float-left"><i class="fas fa-chalkboard-teacher"></i> Editar Ventas</h4> -->
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a><?= dia() ?> - <?= date('d/m/Y') ?> CAJERO: <?= $this->session->userdata('nomb_usu').' '.$this->session->userdata('apell_usu') ?> </a></li>

            </ol>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
         <div class="card">
         <div class="card-header bg-success"><h3 class="my-0 text-white">Vista previa de ventas <i class="spinner-grow text-pink float-right"></i></h3></div>
           <div class="card-body">
            <form id="FormVentaEditar" class="FormVenta" action="<?= base_url('administrador/regventas/agregarVenta') ?>" autocomplete="off">
              <input type="hidden" name="cliente" value="<?= $venta->id_cliente ?>">
              <input type="hidden" name="total">

              <div class="row">
                <div class="col-md-9">
                  <div class="row">
                    <div class="col-sm-14">
                      <div class="card">
                        <div class="card-body">

                          <fieldset>
                            <legend>Principal</legend>
                            <div class="row">

                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Fecha</label>
                                  <input type="text" name="fecha" class="form-control datepicker" value="<?= $venta->fecha_vent ?>" disabled>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label class="control-label">Documento</label>
                                  <select name="tipoPedido" class="form-control" disabled>
                                    <?php foreach ($tipos as $t): ?>
                                      <option value="<?= $t->cod_talonario ?>" <?= ($t->cod_talonario==$venta->cod_talonario)?'selected':'' ?>><?= $t->nom_tipdocumento.' - '.$t->serie ?></option>
                                    <?php endforeach ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Serie</label>
                                  <input type="text" name="serie" disabled class="form-control" value="<?= $venta->serie ?>">
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Correlativo</label>
                                  <input type="text" name="correlativo" class="form-control" readonly value="<?= $venta->numero_vent ?>">
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label class="control-label">Punto de venta</label>
                                  <input type="text" name="puntoVenta" class="form-control" value="<?= $venta->nomb_puntoventa ?>"  readonly>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label class="control-label">Almacen</label>
                                  <select name="almacen" class="form-control" disabled>
                                    <?php foreach ($almacenes as $a): ?>
                                    <option value="<?= $a->cod_almacen ?>" <?= ($a->pordefecto==1)?'selected':'' ?>><?= $a->nomb_almacen ?></option>
                                    <?php endforeach ?>
                                  </select>
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
                                  <button id="VentaEditarCliente" type="button" class="btn btn-warning waves-effect btn-sm"><i class="fas fa-pencil-alt"></i></button>


                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">RUC/DNI</label>
                                  <input type="text" id="RUCAutocomplete" name="rucdni" class="form-control" value="<?= $venta->doc_cliente ?>">
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label class="control-label">Cliente</label>
                                  <input type="text" id="ClienteVentaAutocomplete" name="nombreCliente" class="form-control" value="<?= $venta->nomb_cliente ?>">
                                </div>
                              </div>
                              <div class="col-md-5">
                                <div class="form-group">
                                  <label class="control-label">Dirección</label>
                                  <input type="text" id="DireccionCliente" class="form-control" value="<?= $venta->direc_cliente ?>">
                                </div>
                              </div>
                            </div>
                          </fieldset>

                          <fieldset>
                            <legend>Pagos</legend>
                            <div class="row">
                              <div class="col-md-8">
                                <table class="table table-bordered" cellspacing="0" width="100%">
                                  <thead>
                                    <tr class="table-warning">
                                      <th style="text-align: center;">Forma</th>
                                      <th style="text-align: center;">Detalle</th>
                                      <th style="text-align: center;">Monto</th>
                                      <th style="text-align: center;">Saldo</th>
                                      <th style="text-align: center;">Estado</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>Efectivo</td>
                                      <td><?= $venta->nomb_caja ?></td>
                                      <td><?= $venta->monto_vent ?></td>
                                      <td><?= $venta->saldo_vent ?></td>
                                      <td>Cancelado</td>
                                    </tr>
                                    <?php if ($venta->saldo_vent > 0): ?>
                                    <tr>
                                      <td>Crédito</td>
                                      <td>A <?= $venta->dias_vent ?> días| <?= $fechavenc_vent ?></td>
                                      <td><?= $venta->saldo_vent ?></td>
                                      <td></td>
                                      <td>Por Pagar</td>
                                    </tr>
                                    <?php endif ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </fieldset>
                          <!-- End #wizard-vertical -->
                        </div>
                      </div>
                    </div>              
                  </div>
                </div>
                <div class="col-md-3">
                  <fieldset class="scheduler-border" >
                    <legend class="scheduler-border">Forma de Pago</legend>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Metodo de Pago</label>
                          <input type="text" class="form-control" readonly value="<?= $venta->nom_tipopago ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Tipo de Tarjeta</label>
                          <input type="text" class="form-control" readonly value="<?= $venta->nomb_tarj ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Metodo Recibido</label>
                          <input type="text" name="montoRecibido" class="form-control" value="<?= $venta->montorecibido_vent ?>" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Vuelto</label>
                          <input type="text" name="montoRecibido" class="form-control" value="<?= $venta->vuelto_vent ?>" readonly>
                        </div>
                      </div>
                    </div>

                    <button id="DeudaCliente" data-toggle="modal" data-target="#ModalDeudaCliente" type="button" class="btn btn-success waves-effect btn-sm"> Ver Deuda Cliente</button>
                  </fieldset>
                </div>
              </div>
            </form>
            <form id="FormVentaAgregarProducto" autocomplete="off">
              
              <table id="TableVentaProductos" class="table table-bordered">
                <thead>
                  <tr>
                    <th></th>
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
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($venta->detalle as $v): ?>
									
                  <tr class="fila-producto hide">
                    <td class="details-control">
											<?php if(!empty($v->series)): ?> 
                      <button type="button" class="btn btn-icon waves-effect waves-light btn-success"><span class="fa fa-caret-right"></span></button>
											<?php endif ?>
                    </td>
                    <td><?= $v->cod_producto  ?></td>
                    <td><?= $v->producto_ventdet  ?></td>
                    <td><?= $v->nomb_marca  ?></td>
                    <td><?= $v->nomb_unid  ?></td>
                    <td><?= $v->cant_ventdet  ?></td>
                    <td><?= $v->precunit_ventdet  ?></td>
                    <td><?= ($v->tipo_ventdet=='V')?$v->descuento_ventdet:''  ?></td>
                    <td><?= ($v->tipo_ventdet=='V')?$v->igv_ventdet:''  ?></td>
                    <td><?= ($v->tipo_ventdet=='V')?$v->subtotal_ventdet - $v->igv_ventdet:''  ?></td>
                    <td><?= ($v->tipo_ventdet=='V')?$v->subtotal_ventdet:''  ?></td>
                  </tr>
									<?php if(!empty($v->series)): ?> 
                  <tr style="display:none">
                    <td><b>Series</b></td>
                    <td colspan="10">
                      <?php foreach($v->series as $s): ?> 
                      <label class="label label-info"><?= $s->serie_descripcion ?></label>
                      <?php endforeach ?>
                    </td>
                  </tr>
									<?php endif ?>
                  <?php endforeach ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="9"></td>
                    <th>Valor Venta</th>
                    <td id="VentaValorVenta"><?= $venta->subtotal_vent ?></td>
                  </tr>
                  <tr>
                    <td colspan="9"></td>
                    <th>IGV</th>
                    <td id="VentaIGV"><?= $venta->igv_vent ?></td>
                  </tr>
                  <tr>
                    <td colspan="9"></td>
                    <th>Total</th>
                    <td id="venta-total"><?= $venta->total_vent ?></td>
                  </tr>
                </tfoot>
              </table>
            </form>
            <!-- <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <button type="submit" form="FormVentaAgregar" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                  <a href="<?= base_url('administrador/regventas') ?>" class="btn btn-pink"><i class="fas fa-times"></i> Cerrar</a>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>


    </div>




  </div>



</div>

</div>

</div>







<div class="modal" id="ModalDeudaCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Historial Deuda Clientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body table-responsive">
                  <h4 class="m-t-0 header-title mb-4"><b>Vista Previa</b></h4>

                  <table id="TableDeudaCliente" class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="text-align: center;">Cliente</th>
                        <th>DNI/RUC</th>
                        <th>Fecha venta</th>
                        <th>Venta</th>
                        <th>Saldo</th>
                        <th>Pagar</th>
                      </tr>
                    </thead>

                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div> <!-- end row -->


        </div>
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
      <form id="FormVentaAgregarCliente" action="<?= base_url('administrador/regventas/agregarCliente') ?>" autocomplete="off" method="post">
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


<div class="modal" id="ModalEditarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cliente - Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormVentaEditarCliente" action="<?= base_url('administrador/regventas/editarCliente') ?>" autocomplete="off" method="post">
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









