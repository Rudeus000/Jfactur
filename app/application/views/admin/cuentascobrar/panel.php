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
              <!-- <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> Cuentas por Cobrar</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Cuentas por cobrar</a></li>
                <li class="breadcrumb-item active">Listado</li>
              </ol>
            </div>
          </div>
        </div>

        <!-- end row -->

        <!-- Vertical Steps Example -->
                <?php if ($apertura==FALSE): ?>
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle m-r-5 float-right fa-2x"></i>
              Debes aperturar una caja.
            </div>
          </div>
        </div>
        <?php endif ?>
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
            <div class="card-header bg-success"><h3 class="my-0 text-white">Cuentas por cobrar</h3></div>
              <div class="card-body table-responsive">
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormCuentasCobrarFiltro" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Cliente</label>
                          <input type="text" name="cliente" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-success waves-effect waves-light" style="margin-top: 29px"><i class="fab fa-earlybirds m-r-5"></i> Buscar</button>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <a id="CuentasCobrarReportePdf" href="#" class="btn btn-pink" target="_blank"><i class="far fa-file-pdf"></i> PDF</a>
                    <a id="CuentasCobrarReporteExcel" href="#" class="btn btn-purple" target="_blank"><i class="far fa-file-excel"></i> EXCEL</a>
                  </div>
                </div>
                <br>
                <div>
                  <table id="TableCuentasCobrar" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th style="text-align: center;">Cliente</th>
                        <th style="text-align: center;">DNI/RUC</th>
                        <th style="text-align: center;">Monto</th>
                        <th style="text-align: center;">Abonos</th>
                        <th style="text-align: center;">Saldo</th>
                        <th></th>
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
