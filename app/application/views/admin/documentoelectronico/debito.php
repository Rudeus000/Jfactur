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
              <!-- <h4 class="page-title float-left"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i> Nota de Débito</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#"> Nota de Débito</a></li>
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
            <div class="card-header bg-primary"><h3 class="my-0 text-white">Nota de debito</h3></div>              
              <div class="card-body">
                <div class="table-responsive">
                  <table id="TableNota" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-primary text-white">
                        <th>Tipo de Doc.</th>
                        <th>Serie</th>
                        <th>Num</th>
                        <th>Fecha</th>                      
                        <th>Cliente</th>
                        <th>IGV</th>
                        <th>Subtotal</th>
						            <th>Total</th>
                        <th>Motivo</th>
                        <th>Sec.</th>
                        <th>Fecha Nota</th>
                        <th></th>
                      </tr>
                    </thead>
                      <tbody>
                          <?php foreach($datos as $d): ?>
                          <tr>
                              <td><?= $d->nom_tipdocumento ?></td>
                              <td><?= (is_null($d->seriecomp_nota))?$d->serie:$d->seriecomp_nota ?></td>
                              <td><?= $d->numero_vent ?></td>
                              <td><?= $d->fecha_vent ?></td>
                              <td><?= $d->nomb_cliente ?></td>
                              <td><?= $d->totaligv_nota ?></td>
                              <td><?= $d->totalgravadas_nota ?></td>
                              <td><?= $d->total_nota ?></td>
                              <td><?= $d->motivo_nota ?></td>
                              <td><?= $d->numcomp_nota ?></td>
                              <td><?= $d->fecha_nota ?></td>
                              <td>
                                  <?php if(!is_null($d->tiponota_nota)): ?>
                                  <a href="<?= base_url('administrador/regdocumentoelectronico/imprimirDebito/'.$d->cod_nota) ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a>
                                  <a href="<?= base_url_app('facturacion/'.$d->rutaxml_nota.'/'.$d->archivoxml_nota.'.XML') ?>" target="_blank" class="btn btn-primary btn-sm">XML</a>
                                  <a href="<?= base_url_app('facturacion/'.$d->rutaxml_nota.'/R-'.$d->archivoxml_nota.'.XML') ?>" target="_blank" class="btn btn-primary btn-sm">CDR</a>
                                  <?php else: ?>
                                  <button type="button" data-id="<?= $d->cod_vent ?>" class="btn btn-info btn-sm nota-debito">Débito</button>
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



<div class="modal fade" id="ModalNotaDebito" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="FormNotaDebito" action="<?= base_url('administrador/regdocumentoelectronico/debitoDocumento') ?>" method="post" autocomplete="off">
      <input type="hidden" name="id">
      <input type="hidden" name="subtotalTotal">
      <input type="hidden" name="igvTotal">
      <input type="hidden" name="PEN" value="1">
      <input type="hidden" name="USD" value="<?= $cambio->valor_paramt ?>">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nota de Debito</h5>
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
                  <option value="01">INTERES POR MORA</option>
                  <option value="02">AUMENTO EN EL VALOR</option>
                  <option value="03">PENALIDADES</option>
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
                  <input type="text" id="DebitoProductoAutocomplete" name="nombreProducto" class="form-control">
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
                <button type="button" id="cargarNotaDebitoProducto" style="margin-top: 32px" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                
                  <table id="TableDebitoProductos" class="table table-bordered">
                    <thead>
                      <tr>
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
                        <td id="DebitoValorVenta">00.00</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                        <th>IGV</th>
                        <td id="DebitoIGV">00.00</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                        <th>Total</th>
                        <td id="DebitoTotal">00.00</td>
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

