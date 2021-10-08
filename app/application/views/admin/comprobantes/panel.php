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
              <h4 class="page-title float-left"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i> Comprobantes</h4>
            </div>
          </div>
        </div>

        <!-- end row -->

        <!-- Vertical Steps Example -->
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body table-responsive">
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormComprobantesFiltro" action="<?= base_url('administrador/regcomprobante/resultados') ?>" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
													<label class="control-label">RUC/DNI</label>
													<input type="text" name="ruc_dni" class="form-control">
												</div>
                      </div>
											<div class="col-md-3">
                        <div class="form-group">
													<label class="control-label">N° Factura/Boleta</label>
													<input type="text" name="factura_boleta" class="form-control">
												</div>
                      </div>
                    </div>
										<div class="row">
											<div class="col-md-3">
												<div class="form-group">
														<label class="control-label d-block">Captcha</label>
														<div id="captcha" class="d-inline">
														</div>
														<div class="d-inline"><button type="button" id="actualizarCaptcha" class="btn btn-success btn-sm" title="Actualizar captcha"><i class="fa fa-redo"></i></button></div>
														<input type="text" name="captcha" class="form-control">
													</div>
												</div>
												<div class="col-md-2">
													<button type="submit" class="btn btn-success waves-effect waves-light" style="margin-top: 80px"><i class="fa fa-search"></i> Buscar</button>
												</div>
										</div>
                  </form>
                </fieldset>
                <br>

                <div>
									
                  <table id="TableComprobantes" class="table  table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr class="btn-primary">
                        <th style="text-align: center;">Det</th>
                        <th style="text-align: center;">N°</th>
                        <th style="text-align: center;">Tipo Doc.</th>
                        <th style="text-align: center;">Tipo. Doc. Cli.</th>
                        <th style="text-align: center;">N° Doc. Cli.</th>
                        <th style="text-align: center;">N° Factura</th>
                        <th style="text-align: center;">Moneda</th>
                        <th style="text-align: center;">Importe Total</th>
                        <th style="text-align: center;">Fecha Emisión</th>
                        <th style="text-align: center;">Estado SUNAT</th>
                      </tr>
                    </thead>
										<tbody>
											
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



<div class="modal fade" id="ModalDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detalle</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
			<div class="modal-body">
				<div class="row mb-1">
					<div class="col-md-12">
						<div class="float-right">
						
						<a href="" target="_blank" id="imprimir" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Imprimir</a>
						<a href="" download id="descargar" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Descargar</a>
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-md-8 text-center">
						<h4 class="m-0"><?= $empresa->razon_social ?></h4>
						<p class="m-0">
						<?= $empresa->direcc_emp ?> <br>
						Telefono: <?= $empresa->telf_emp ?><br>
						Email: <?= $empresa->email_emp ?>
						</p>
					</div>
					<div class="col-md-4 text-center">
						<h4 class="m-0 p-2" style="border:1px solid #666f7b">
						<span id="tipo-documento-cliente"></span><br>
						<span id="tipo-documento"></span><br>
						<span id="numero-comprobante"></span>
						</h4>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-6">
						<b>Nombre / Razón Social:</b> <span id="nombre-razonsocial"></span>
					</div>
					<div class="col-md-6">
						<b>Moneda:</b> <span id="moneda"></span>
					</div>
					<div class="col-md-6">
						<b>Número de documento:</b> <span id="num-documento"></span>
					</div>
					<div class="col-md-6">
						<b>Fecha Emisión:</b> <span id="fecha-emision"></span>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-12">
						<table id="detalle-comprobante" class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th>Item</th>
									<th>Código</th>
									<th>Descripción</th>
									<th>Unidad</th>
									<th>Cant.</th>
									<th>Valor Unit.</th>
									<th>Prec. Unit.</th>
									<tH>IGV</th>
									<th>Descuento</th>
									<th>Valor total</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="offset-8 col-md-4">
						<table class="table table-bordered table-condensed">
							<tr>
								<th>Valor venta</th>
								<td class="text-right" id="valor-venta"></td>
							</tr>
							<tr>
								<th>IGV</th>
								<td class="text-right" id="igv">33433</td>
							</tr>
							<tr>
								<th>Descuento</th>
								<td class="text-right" id="descuento">234</td>
							</tr>
							<tr>
								<th>Importe total</th>
								<td class="text-right" id="importe-total">445621</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<p>Autorizado a ser emisor electrónico mediante R.I. SUNAT N° 018-005-0002378</p>
						<p>Representación impresa de su factura electrónica, este puede ser consultado en <?= WEBSITE ?></p>
					</div>
				</div>
			</div>
    </div>
  </div>
</div>
