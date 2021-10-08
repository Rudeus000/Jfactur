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
              <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i>Stock de articulos</h4>
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
                  <form id="FormReporteComprasDetalladasBusqueda" action="" method="post" autocomplete="off">
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
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Almacen</label>
                          <input type="text" name="almacen" class="form-control">
                        </div>
                      </div>
											<div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Proveedor</label>
                          <input type="text" name="proveedor" class="form-control">
                        </div>
                      </div>
											<div class="col-md-3">
                        <div class="form-group">
                          <button style="margin-top:27px" type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <a id="ReporteComprasDetalladasExcel" href="#" class="btn btn-purple" target="_blank"><i class="far fa-file-excel"></i> EXCEL</a>
                  </div>
                </div>
                <br>
                <div>
                  <table id="TableReporteDetalladoCompras" class="table table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                      <tr>
											<th style="width: 50px"></th>
												<th style="text-align: center; width: 50px">Fecha</th>
                        <th style="text-align: center;">Almacen</th>
												<th style="text-align: center;">Proveedor</th>
												<th style="text-align: center;">Documento</th>
												<th style="text-align: center;">N° Doc. </th>
												<th style="text-align: center;">Producto</th>
												<th style="text-align: center;">Serie</th>
												<th style="text-align: center;">Ingreso</th>
												<th style="text-align: center;">Stock</th>
												<th style="text-align: center;">Venta</th>
												<th style="text-align: center;">Prec. Unid.</th>
												<th style="text-align: center;">Subtotal</th>
                      </tr>
                    </thead>
											<tfoot>
												<tr>
													<th colspan="11" style="text-align:right">Total:</th>
													<th><strong><span id="TotalReporteComprasDetalladas"></span></strong></th>
												</tr>
											</tfoot>
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


