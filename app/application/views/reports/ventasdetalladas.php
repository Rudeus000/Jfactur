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
              <!-- <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> Reporte detallaod de ventas </h4> -->
            </div>
          </div>
        </div>

        <!-- end row -->

        <!-- Vertical Steps Example -->
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header bg-success">
                <h3 class="my-0 text-white">Reporte detallado de ventas<i class="spinner-grow text-danger float-right"></i></h3>
              </div>
              <div class="card-body table-responsive">
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormReporteVentasDetalladasBusqueda" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Fecha</label>
                          <div class="input-group">
                            <!-- <input type="text" name="desde" class="form-control datepicker" value="2020-07-23"> -->
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
                          <label class="control-label">Cliente</label>
                          <input type="text" name="cliente" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <!-- <div class="form-group">
                          <label class="control-label">Vendedor</label>
                          <input type="text" name="vendedor" class="form-control">
                        </div> -->
                        <div class="form-group">
                          <label class="control-label">Agentes:</label>
                          <?php if ($this->session->userdata('perfil') == 1) : ?>
                            <select name="vendedor" class="form-control">
                              <option value="">Seleccione</option>
                              <?php foreach ($vendedores as $v) : ?>
                                <option value="<?= $v->cod_usu ?>"><?= $v->apell_usu . ' ' . $v->nomb_usu ?></option>
                              <?php endforeach ?>
                            </select>
                          <?php endif  ?>
                          <?php if ($this->session->userdata('perfil') != 1) : ?>
                            <input type="text" id="vendedorcod" name="vendedorcod" value="<?= $this->session->userdata('cod_usu') ?>" style="display:none">
                            <input type="text" name="vendedorname" readonly class="form-control" value="<?= $this->session->userdata('nomb_usu') . ' ' . $this->session->userdata('apell_usu') ?>">
                          <?php endif ?>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <button style="margin-top:27px" type="submit" class="btn btn-success"><i class=" fab fa-earlybirds m-r-5"></i>Filtrar</button>
                          <a id="ReporteVentasDetalladasExcel" href="#" class="btn btn-primary" style="margin-top:27px" target="_blank"><i class="far fa-file-excel m-r-5"></i> Exportar a EXCEL</a>
                        </div>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>
                <div class="table-responsive">
                  <table id="TableReporteDetalladoVentas" class="table table-bordered table-striped" cellspacing="0" width="100%">

                    <thead>
                      <tr class="btn-success">
                        <th style="text-align: center; width: 50px">Fecha</th>
                        <th style="text-align: center;">Almacen</th>
                        <th style="text-align: center;">Punto de Venta</th>
                        <th style="text-align: center;">DNI-RUC</th>
                        <th style="text-align: center;">Cliente</th>
                        <th style="text-align: center;">Documento</th>
                        <th style="text-align: center;">Vendedor</th>
                        <th style="text-align: center;">Unidad.M.</th>
                        <th style="text-align: center;">Producto</th>
                        <th style="text-align: center;">ISDN</th>
                        <th style="text-align: center;">Serie</th>
                        <th style="text-align: center;">T.Pago</th>
                        <th style="text-align: center;">Prec.Unid.</th>
                        <th style="text-align: center;">Descuento</th>
                        <th style="text-align: center;">Prec. con Desc.</th>
                        <th class="bg-danger" tyle="text-align: center;">Cantidad</th>
                        <th style="text-align: center;">Subtotal</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th colspan="16" style="text-align:right">Total:</th>
                        <th><strong><span id="TotalReporteVentasDetalladas"></span></strong></th>
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