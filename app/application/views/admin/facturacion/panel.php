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
              <!-- <h4 class="page-title float-left"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i> Facturas & Boletas</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Facturas & Boletas</a></li>
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
            <div class="card-header bg-success"><h3 class="my-0 text-white">Lista de facturas y boletas electronicas emetidas</h3></div>
              <div class="card-body">
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormFacturasFiltro" action="" method="post" autocomplete="off">
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
                            <option value="P">Pendientes</option>
                            <option value="G">Generados</option>
                            <option value="A">Rechazados</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-success waves-effect waves-light" style="margin-top: 29px"><i class="fa fa-search"></i> Buscar</button>
                      </div>
                    </div>
                  </form>

									
                </fieldset>
               <!--  <br>
                <div class="row">
                  <div class="col-md-12">
                    <a id="VentasReportePdf" href="#" class="btn btn-info" target="_blank">PDF</a>
                    <a id="VentasReporteExcel" href="#" class="btn btn-warning" target="_blank">EXCEL</a>
                  </div>
                </div>
                <br> -->

								<br>
								<div class="row">
									<div class="col-md-12">
										<button id="procesar-facturas" class="btn btn-success btn-md"><i class="fas fa-spinner m-r-5"></i>Procesar Documentos</button>
                     <a id="Reportexcelfe" href="#" class="btn btn-primary" target="_blank"><i class="far fa-file-excel"></i> Exportar a excel</a>
									</div>
								</div>
								<br>

                <div class="table-responsive">
                  <table id="TableFacturacion" class="table mb-0 table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
												<th>Id</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Subtotal</th>
                        <th>IGV</th>
                        <th>EXONERADA</th>
                        <th>GRATUITA</th>
                        <th>Total</th>
												<th>Tipo</th>
                        <th>Fecha Limite</th>
                        <th>Estado</th>
												<th>Facturación</th>
                        <th>Msj. Sunat</th>
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
