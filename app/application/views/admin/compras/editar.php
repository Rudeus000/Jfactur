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
                            <h4 class="page-title float-left"><i class="fas fa-shopping-cart"></i> Editar Compra</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="#">Compras</a></li>
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
                            <div class="card-body table-responsive">
                              <form id="FormComprasEditar" action="<?= base_url('administrador/regcompras/editarCompra') ?>" autocomplete="off" method="post">
                                <input type="hidden" name="id" value="<?= $compra->cod_comp ?>">
                                <input type="hidden" name="proveedor" value="<?= $compra->tb_proveedor_id ?>">
                                <fieldset>
                                  <legend>Principal</legend>
                                  <div class="row">
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">Fecha:</label>
                                        <input type="text" name="fecha" class="form-control datepicker" value="<?= $compra->fecha_comp ?>">
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Documento:</label>
                                        <select name="documento" class="form-control">
                                          
                                          <option value="BOLETA ELECTRONICA" <?= ($compra->documento_comp=='BOLETA ELECTRONICA')?'selected':'' ?>>BOLETA ELECTRONICA</option>
                                          <option value="FACTURA ELECTRONICA" <?= ($compra->documento_comp=='FACTURA ELECTRONICA')?'selected':'' ?>>FACTURA ELECTRONICA</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">N° Documento</label>
                                        <input type="text" name="numDocumento" class="form-control" value="<?= $compra->numdocumento_comp ?>">
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Colocar producto en almacen:</label>
                                        <select name="almacen" class="form-control">
                                          <?php foreach ($almacenes as $a): ?>
                                          <option value="<?= $a->cod_almacen ?>" <?= ($a->cod_almacen==$compra->cod_almacen)?'selected':'' ?>><?= $a->nomb_almacen ?></option>
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
                                        <!-- <button data-toggle="modal" data-target="#ModalAgregarProveedor" type="button" class="btn btn-secondary waves-effect btn-sm"><i class="fas fa-plus"></i></button> -->
                                        <button id="CompraEditarProveedor" type="button" class="btn btn-warning waves-effect btn-sm"><i class="fas fa-pencil-alt"></i></button>
                                      </div>
                                    </div>
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">RUC/DNI</label>
                                        <input type="text" name="rucdni" class="form-control" value="<?= $compra->tb_proveedor_doc ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label">Proveedor:</label>
                                        <input type="text"  name="nombreProveedor" class="form-control" value="<?= $compra->tb_proveedor_nom ?>" disabled>
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
                                            <th style="text-align: center;">Abono</th>
                                            <th style="text-align: center;">Saldo</th>
                                            <th style="text-align: center;">Estado</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>Efectivo</td>
                                            <td><?= $compra->nomb_caja ?></td>
                                            <td><?= $compra->efectivo_comp ?></td>
                                            <td><?= $compra->efectivo_comp ?></td>
                                            <td>0.00</td>
                                            <td>Cancelado</td>
                                          </tr>
                                          <?php if ($compra->saldo_comp > 0): ?>
                                          <tr>
                                            <td>Crédito</td>
                                            <td>A <?= $compra->dias_comp ?> días| <?= $compra->fecvenc_comp ?></td>
                                            <td><?= $compra->saldo_comp ?></td>
                                            <td>0.00</td>
                                            <td><?= $compra->saldo_comp ?></td>
                                            <td>Por Pagar</td>
                                          </tr>
                                          <?php endif ?>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </fieldset>
                               
                               <br>
                              <table id="TableComprasProductos" class="table table-bordered">
                                <thead>
                                  <tr class="bg-primary text-white">
                                    <th style="text-align: center;"></th>
                                    <th style="text-align: center;">Código</th>
                                    <th style="text-align: center;">Artículo</th>
                                    <th style="text-align: center;">Marca</th>
                                    <th style="text-align: center;">Unidad</th>
                                    <th style="text-align: center;">Cantidad</th>
                                    <th style="text-align: center;">P. Unit.</th>
                                    <th style="text-align: center;">IGV</th>
                                    <th style="text-align: center;">Valor Venta</th>
                                    <th style="text-align: center;">Subtotal</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($detalle as $d): ?>
                                    <tr class="fila-producto hide">
                                      <td class="details-control">
																				<?php if(!empty($d->series)): ?> 
																				
                                        <button type="button" class="btn btn-icon waves-effect waves-light btn-success"><span class="fa fa-caret-right"></span></button>
																				<?php endif ?>
                                      </td>
                                      <input type="hidden" name="id_prod[<?= $d->cod_compdet ?>]" value="<?= $d->cod_compdet ?>">
                                      <td><?= $d->cod_producto ?></td>
                                      <td><?= $d->nomb_product ?></td>
                                      <td><?= $d->nomb_marca ?></td>
                                      <td><?= $d->nomb_unid ?></td>
                                      <td style="width:140px;"><input type="text" class="cant form-control" name="cant_prod[<?= $d->cod_compdet ?>]" value="<?= $d->cant_compdet ?>"></td>
                                      <td style="width:140px;"><input type="text" class="prec form-control" name="prec_prod[<?= $d->cod_compdet ?>]" value="<?= $d->precunit_compdet ?>"></td>
                                      <td><?= $d->igv_compdet ?></td>
                                      <td><?= $d->precventa_compdet ?></td>
                                      <td><?= $d->subtotal_compdet ?></td>
                                    </tr>
																		<?php if(!empty($d->series)): ?> 
																		
                                    <tr style="display:none">
                                      <td colspan="9">
                                        <table class="table table-bordered">
                                          <thead>
                                            <tr class="table-danger">
                                              <th>Serie</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            
                                            <?php foreach($d->series as $s): ?> 
                                              <tr>
                                                <td><?= $s->serie_descripcion ?></td>
                                              </tr>
                                              <?php endforeach ?>
                                            </tbody>
                                          </table>
                                      </td>
                                    </tr>
																		<?php endif ?>
                                  <?php endforeach ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <td colspan="8"></td>
                                    <th>Valor Venta</th>
                                    <td id="CompraValorVenta"><?= $compra->total_comp - ($compra->total_comp * 0.18) ?></td>
                                  </tr>
                                  <tr>
                                    <td colspan="8"></td>
                                    <th>IGV</th>
                                    <td id="CompraIGV"><?= $compra->total_comp * 0.18 ?></td>

                                  </tr>
                                  <tr>
                                    <td colspan="8"></td>
                                    <th>Total</th>
                                    <td id="CompraTotal"><?= $compra->total_comp ?></td>
                                  </tr>
                                </tfoot>
                              </table>
                              
                              </form>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <button type="submit" form="FormComprasEditar" class="btn btn-info"><i class="fa fa-save"></i> Guardar</button>
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
