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
              <!-- <h4 class="page-title float-left"> Cotización</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Cotización</a></li>
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
			<div class="card-header bg-success"><h3 class="my-0 text-white">Lista de cotizacion<a href="<?= base_url('administrador/regcotizacion/agregar') ?>" class="btn btn-rounded btn-pink float-right" ><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
              <div class="card-body table-responsive">
                <!-- <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <a class="btn btn-info" href="<?= base_url('administrador/regcotizacion/agregar') ?>"><i class="fa fa-plus"></i> Agregar</a>
                    </div>
                  </div>
                </div> -->
                <fieldset>
                  <!-- <legend>Filtro</legend> -->
                  <form id="FormCotizacionFiltro" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Fecha</label>
                          <div class="input-group">
                            <input type="text" name="desde" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                            <input type="text" name="hasta" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Estado</label>
                          <select name="estado" class="form-control">
                            <option value="G" selected>Generado</option>
                            <option value="A">Anulado</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Pago</label>
                          <select name="pago" class="form-control">
                            <option value=""></option>
                            <option value="CO">Contado</option>
                            <option value="CR">Crédito</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-success waves-effect waves-light" style="margin-top: 29px"><i class="fa fa-search"></i> Buscar</button>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>
                
                
                <div class="row">
                  <div class="col-md-12">
                    <a id="CotizacionReportePdf" href="#" class="btn btn-pink" target="_blank"><i class="far fa-file-pdf"></i> PDF</a>
                    <a id="CotizacionReporteExcel" href="#" class="btn btn-purple" target="_blank"><i class="far fa-file-excel"></i> EXCEL</a>
                  </div>
                </div>
                <br>

                <div>
                  <table id="TableCotizacion" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                      <tr class="btn-success">
                        <th></th>
                        <th style="text-align: center;">Id</th>
                        <th style="text-align: center;">Cliente</th>
                        <th style="text-align: center;">Tipo</th>
                        <th style="text-align: center;">Num</th>
                        <th style="text-align: center;">Monto</th>
                        <th style="text-align: center;">Pago</th>
                        <th style="text-align: center;">Fecha</th>
                        <th style="text-align: center;">Acciones</th>
                      </tr>
                    </thead>

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



<div class="modal fade " id="ModalProcesarCotizacion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myLargeModalLabel">Procesar Cotización</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <input type="hidden" name="id">
        <div class="modal-body">
					<form id="FormProcesarCotizacion" action="<?= base_url('administrador/regcotizacion/procesarCotizacion') ?>" autocomplete="off" method="post">
						<input type="hidden" name="tipoCambio" class="form-control">
						<input type="hidden" name="cliente" class="form-control">
						<input type="hidden" name="moneda" class="form-control">
						<input type="hidden" name="total">
						<div class="row">
							<div class="col-md-9">
									
								<fieldset class="scheduler-border">
									<legend>Principal</legend>
									
									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label for="">Fecha</label>
												<input type="text" name="fecha" class="form-control" readonly value="<?= date('Y-m-d') ?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label class="control-label">Documento</label>
												<select name="tipo_documento" class="form-control">
													<option value="">Seleccion</option>
													
												</select>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="control-label">Serie</label>
												<input type="text" name="serie" class="form-control" readonly >
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="control-label">Correlativo</label>
												<input type="text" name="correlativo" class="form-control" readonly>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">Punto de venta</label>
												<input type="text" name="puntoVenta" class="form-control" value="<?= $punto->nomb_puntoventa ?>"  readonly>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="">Almacen</label>
												<select name="almacen" class="form-control">
													<?php foreach($almacenes as $a): ?> 
													<option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
									</div>
								</fieldset>

								<fieldset class="scheduler-border">
									<legend>Cliente</legend>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="">Cliente</label>
												<input type="text" class="form-control" name="cliente_nombre" readonly>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="">RUC/DNI</label>
												<input type="text" class="form-control" name="ruc_dni" readonly>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="">Precio Venta</label>
												<input type="text" class="form-control" name="precioCliente" readonly>
											</div>
										</div>
									</div>
								</fieldset>

								<fieldset>
									<legend>Pagos</legend>
									<div class="row">
													
										<div class="col-md-2">
											<div class="form-group">
												<label class="control-label">Caja</label>
												<input type="text" class="form-control" value="<?= $apertura->nomb_caja ?>" disabled>
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
										<div class="col-md-2" style="display: none">
											<div class="form-group">
												<label class="control-label">Dias</label>
												<input type="number" min="1" disabled name="dias" class="form-control">
											</div>
										</div>
										<div class="col-md-2" style="display: none">
											<div class="form-group">
												<label class="control-label">Fecha</label>
												<input type="text" name="fecVenc" class="form-control" readonly>
											</div>
										</div>
										<div class="col-md-2" style="display: none">
											<div class="form-group">
												<label class="control-label">Saldo</label>
												<input type="text" name="saldo" value="0" disabled class="form-control" readonly>
											</div>
										</div>
									</div>
								</fieldset>
								
							</div>
							<div class="col-md-3">
								<fieldset class="scheduler-border">
									<legend>Forma de Pago</legend>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label">Metodo de Pago</label>
												<select name="tipoPago" class="form-control input-sm">
													<?php foreach ($tipos_pagos as $t): ?>
														<option value="<?= $t->cod_tipopago ?>"><?= $t->nom_tipopago ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label">Tipo de Tarjeta</label>
												<select name="tipoTarjeta" class="form-control" disabled>
													<option value=""></option>
													<?php foreach ($tipos_tarjetas as $t): ?>
														<option value="<?= $t->cod_tarj ?>"><?= $t->nomb_tarj?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label">N° Operación</label>
													<input type="text" name="operacion" class="form-control" disabled>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label">Monto Recibido</label>
												<input type="text" name="montoRecibido" class="form-control" value="0.00">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label">Vuelto</label>
												<input type="text" name="vuelto" class="form-control"  value="0.00" readonly>
											</div>
										</div>
									</div>

									
								</fieldset>
							</div>
						</div>
					</form>

					<form id="FormCotizacionProcesarAgregarProducto" autocomplete="off">
						<input type="hidden" name="producto">
						<fieldset>
							<legend>Agregar Producto</legend>
							<div class="row">
								<div class="col-md-12">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="servicioCheck" name="servicioCheck">
										<label class="custom-control-label" for="servicioCheck">Servicio</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">Nombre</label>
										<textarea name="nombreProducto" id="nombre-servicio" class="form-control" placeholder="Ingrese el nombre del servicio" rows="5" disabled style="display:none"></textarea>
										<input type="text" id="VentaProductoAutocomplete" name="nombreProducto" class="form-control" placeholder="Ingrese el nombre del producto">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">Unidad Med.</label>
										<input type="text" name="unidadProducto" class="form-control">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">Peso</label>
										<input type="text" name="pesoProducto" class="form-control">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">Precio Unit.</label>
										<input type="text" name="precioProducto" class="form-control">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">Dscto.</label>
										<input type="text" name="descuentoProducto" class="form-control">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="serieChek" name="serieCheckProducto" disabled>
											<label class="custom-control-label" for="serieChek">Series</label>
										</div>
											<select id="select2-series" class="form-control" name="seriesProducto[]" multiple="multiple" disabled>
											</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">Cantidad</label>
										<input type="text" name="cantidadProducto" class="form-control">
									</div>
								</div>
								<div class="col-md-1">
									<button type="submit" style="margin-top: 32px" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
								</div>
							</div>
						</fieldset>						
						<table id="TableProcesarCotizacionProductos" class="table table-bordered">
							<thead>
								<tr class="btn-primary">
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
									<th style="text-align: center;">Opc.</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
							<tfoot>
								<tr>
									<td colspan="9"></td>
									<th>Valor Venta</th>
									<td id="VentaValorVenta">00.00</td>
									<td></td>
								</tr>
								<tr>
									<td colspan="9"></td>
									<th>IGV</th>
									<td id="VentaIGV">00.00</td>
									<td></td>
								</tr>
								<tr>
									<td colspan="9"></td>
									<th>Total</th>
									<td id="venta-total">00.00</td>
									<td></td>
								</tr>
							</tfoot>
						</table>
					</form>

        </div>
        <div class="modal-footer">
					<div class="row" id="CotizacionProcesarContenedorGuardar">
						<div class="col-md-12">								
							<button type="button" class="btn btn-pink" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
							<button type="submit" form="FormProcesarCotizacion" class="btn btn-success" <?= ($apertura==false)?'disabled':'' ?>><i class="fa fa-save"></i> Guardar</button>
						</div>
					</div>
        </div>
    </div>
  </div>
</div>


<div class="modal fade" id="ModalAccionesDespuesGuardar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">HAGA CLICK EN UNAS DE LAS OPCIONES</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body table-responsive">
									<div class="text-center">
										<button type="button" class="mb-2 btn btn-pink btn-rounded w-md waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
										<a class="mb-2 btn btn-warning btn-rounded w-md waves-effect waves-light" href="<?= base_url('administrador/regventas') ?>">Ir a Ventas</a>
										<a id="VentaImprimirA4" class="mb-2 btn btn-inverse btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir A4</a>
										<a id="VentaImprimirTicket" class="mb-2 btn btn-purple btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir Ticket</a>
									</div>
                </div>
              </div>
            </div>
          </div> <!-- end row -->
        </div>
    </div>
  </div>
</div>

<style>
.modal-lg {
	max-width: 95%;
}

</style>
