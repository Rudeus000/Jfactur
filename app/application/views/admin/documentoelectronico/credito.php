<!-- Begin page -->
<div id="wrapper">

  <!-- ============================================================== -->
  <!-- Start right Content here -->
  <!-- ============================================================== -->
  <div class="content-page">
    <!-- Start content -->

    <div class="content">
      <div class="container-fluid">
        <div class="row ">
          <div class="col-12">
            <div class="page-title-box">
            <!-- <div class="card-header bg-info"><h3 class="my-0">Productos compuestos</h3></div> -->
              <!-- <h4 class="page-title float-left "><i class="fas fa-cart-arrow-down" aria-hidden="true"></i> Nota de Crédito</h4> -->
              
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#"> Nota de Crédito</a></li>
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
            <div class="card-header bg-info"><h3 class="my-0 text-white">Nota de Crédito</h3></div>
              <div class="card-body">
                <div class="table-responsive">
                <form action="<?= base_url('administrador/regdocumentoelectronico/credito') ?>">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Fecha</label>
                          <div class="input-group">
                            <input type="text" name="desde" class="form-control datepicker" value="<?= isset($_GET['desde'])?$_GET['desde']:date('Y-m-d') ?>">
                            <input type="text" name="hasta" class="form-control datepicker" value="<?= isset($_GET['hasta'])?$_GET['hasta']:date('Y-m-d') ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button style="margin-top:28px" type="submit" class="btn btn-primary"> Filtrar</button>
                      </div>
                    </div>
                  </form>
                  <table id="TableResumen" class="table mb-0 table-striped table-borderless" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-info text-white">
                        <th>Tipo de Doc.</th>
                        <th>Fecha</th>                      
                        <th>Cliente</th>
                        <th>Nota Débito</th>
						            <th>Total</th>
                        <th>Motivo</th>
                        <th>Fecha Nota</th>
                        <th></th>
                      </tr>
                    </thead>
                      <tbody>
                          <?php foreach($datos as $d): ?>
                          <tr>
                              <td><?= $d->nom_tipdocumento.'<br>'.$d->serie.'-'.$d->numero_vent ?></td>
                              <td><?= $d->fecha_vent ?></td>
                              <td><?= $d->nomb_cliente ?></td>
                              <td><?= $d->seriecomp_nota.'-'.$d->numcomp_nota ?></td>
                              <td><?= $d->total_nota ?></td>
                              <td><?= $d->motivo_nota ?></td>
                              <td><?= $d->fecha_nota ?></td>
                              <td>
                                  <?php if(!is_null($d->tiponota_nota)): ?>
                                  <a href="<?= base_url('administrador/regdocumentoelectronico/imprimirCredito/'.$d->cod_nota) ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a>
                                  <a href="<?= base_url_app('facturacion/'.$d->rutaxml_nota.'/'.$d->archivoxml_nota.'.XML') ?>" target="_blank" class="btn btn-primary btn-sm">XML</a>
                                  <a href="<?= base_url_app('facturacion/'.$d->rutaxml_nota.'/R-'.$d->archivoxml_nota.'.XML') ?>" target="_blank" class="btn btn-primary btn-sm">CDR</a>
                                  <?php else: ?>
                                  <button type="button" data-id="<?= $d->cod_vent ?>" class="btn btn-info btn-sm nota-credito">Crédito</button>
                                  <?php endif ?>
                              </td>
                          </tr>
                          <?php endforeach ?>
                      </tbody>
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



<div class="modal" id="ModalNotaCredito" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="FormNotaCredito" action="<?= base_url('administrador/regdocumentoelectronico/creditoDocumento') ?>" method="post" autocomplete="off">
      <input type="hidden" name="id">
      <input type="hidden" name="subtotalTotal">
      <input type="hidden" name="igvTotal">
      <input type="hidden" name="PEN" value="1">
      <input type="hidden" name="USD" value="<?= $cambio->valor_paramt ?>">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nota de Crédito</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
					<div class="row">
            <div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Cliente</label>
								<input type="text" name="cliente" class="form-control" disabled>
							</div>
						</div>
            <div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Doc. Cliente</label>
								<input type="text" name="doc_cliente" class="form-control" disabled>
							</div>
						</div>	
            <div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Num. Doc. Cliente</label>
								<input type="text" name="num_doc_cliente" class="form-control" disabled>
							</div>
						</div>
            <div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Dirección Cliente</label>
								<input type="text" name="direccion_cliente" class="form-control" disabled>
							</div>
						</div>
            <div class="col-md-3">
							<div class="form-group">
								<label class="control-label">Telefono</label>
								<input type="text" name="telefono_cliente" class="form-control" disabled>
							</div>
						</div>	
            <div class="col-md-3">
							<div class="form-group">
								<label class="control-label">Email</label>
								<input type="text" name="email_cliente" class="form-control" disabled>
							</div>
						</div>	
					</div>
          <div class="row">
            <div class="col-md-3">
							<div class="form-group">
								<label class="control-label">N° Fact. Modif.</label>
								<input type="text" name="num_fact_modificado" class="form-control" disabled>
							</div>
						</div>
            <div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Motivo</label>
								<select name="motivo" class="form-control">
                  <option value="01">Anulacion de la operación</option>
                  <option value="02">Anulacion por error en RUC</option>
                  <option value="03">Correccion por error de la descripcion</option>
                  <option value="04">Descuento Global</option>
                  <option value="05">Descuento por ITEM</option>
                  <option value="06">Devolucion Global</option>
                  <option value="07">Devolucion por ITEM</option>
                </select>
							</div>
						</div>
            <div class="col-md-2">
							<div class="form-group">
								<label class="control-label">Moneda</label>
								<input type="text" name="moneda" class="form-control" disabled>
							</div>
						</div>
          </div>
          <fieldset>
            <legend>Detalle</legend>
            <div class="row">
              <input type="hidden" name="producto">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Descripción</label>
                  <input type="text" id="CreditoProductoAutocomplete" name="nombreProducto" class="form-control">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Precio</label>
                  <input type="text" name="precioProducto" class="form-control">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Cant.</label>
                  <input type="text" name="cantidadProducto" class="form-control" value="1">
                </div>
              </div>
              <div class="col-md-1">
                <button type="button" id="cargarNotaCreditoProducto" style="margin-top: 32px" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                
                  <table id="TableCreditoProductos" class="table table-bordered">
                    <thead>
                      <tr class="bg-primary text-white">
                        <th style="text-align: center;">Código</th>
                        <th style="text-align: center;">Artículo</th>
                        <th style="text-align: center;">Marca</th>
                        <th style="text-align: center;">Unidad</th>
                        <th style="text-align: center;">Cant.</th>
                        <th style="text-align: center;">P. Unit.</th>
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
                        <td id="CreditoValorVenta">00.00</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                        <th>IGV</th>
                        <td id="CreditoIGV">00.00</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                        <th>Total</th>
                        <td id="CreditoTotal">00.00</td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div> 
              </div>
            </div>
          </fieldset>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
        </div>
      </form>
    </div>
  </div>
</div>

