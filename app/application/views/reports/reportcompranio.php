 <!-- Chart JS -->
 <script src="<?= base_url() ?>assets/plugins/chart.js/chart.min.js"></script>

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
              <!-- <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> Ventas cobradas por año </h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Compras </a></li>
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
            <div class="card-header bg-success"><h3 class="my-0 text-white">GRAFICO DE COMPRAS POR AÑO</h3></div>
              <div class="card-body table-responsive">
                <form id="FormFiltroReporteComprasAnio" action="">
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-groupo">
                        <label class="control-label">Años</label>
                        <select name="anio" class="form-control">
                          <?php foreach ($anios as $a): ?>
                          <option value="<?= $a->anio ?>"><?= $a->anio ?></option>
                          <?php endforeach ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label for="">Tipos de Pagos</label>
                      <div class="form-group">
                        <label class="checkbox-inline" for="Contado">
                          <input type="checkbox" name="Contado" value="Contado" id="Contado" checked> Contado
                        </label>
                        &nbsp;
                        <label class="checkbox-inline" for="Credito">
                          <input type="checkbox" name="Credito" value="Credito" id="Credito" checked> Crédito
                        </label>
                      </div>
                    </div>
                  </div>
                </form>
                <div class="row">
                  <div class="col-md-12" style="height: 300px">
                    <div id="ContentComprasAnio">
                      <canvas id="ComprasAnio"></canvas>
                    </div>
                  </div>
                </div>
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


