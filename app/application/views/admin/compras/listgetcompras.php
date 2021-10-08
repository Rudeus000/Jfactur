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
              <!-- <h4 class="page-title float-left"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i> Compras</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Compras</a></li>
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
            <div class="card-header bg-success"><h3 class="my-0 text-white">Lista de compras<a href="<?= base_url('administrador/regcompras/agregar') ?>" class="btn btn-rounded btn-pink float-right" ><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
              <div class="card-body table-responsive">
                <!-- <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <a href="<?= base_url('administrador/regcompras/agregar') ?>" class="btn btn-info"><i class="fa fa-plus"></i>  Agregar</a>
                    </div>
                  </div>
                </div> -->
                <fieldset>
                  <!-- <legend>Filtro</legend> -->
                  <form id="FormComprasFiltro" action="" method="post" autocomplete="off">
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
                          <label class="control-label">Almacen</label>
                          <select name="almacen" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($almacenes as $a): ?>
                            <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Proveedor</label>
                          <input type="text" name="proveedor" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Estado</label>
                          <select name="estado" class="form-control">
                            <option value="1">Activo</option>
                            <option value="2">Anulado</option>
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
                    <a id="ComprasReportePdf" href="#" class="btn btn-pink" target="_blank"><i class="far fa-file-pdf"></i> PDF</a>
                    <a id="ComprasReporteExcel" href="#" class="btn btn-purple" target="_blank"><i class="far fa-file-excel"></i> EXCEL</a>
                  </div>
                </div>
                <br>
                <div>
                  <table id="TableCompras" class="table  table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr class="btn-success">
                        <th></th>
                        <th style="text-align: center;">Fecha</th>
                        <th style="text-align: center;">Código</th>
                        <th style="text-align: center;">Documento</th>
                        <th style="text-align: center;">Proveedor</th>
                        <th style="text-align: center;">RUC/DNI</th>
                        <th style="text-align: center;">Almacen</th>
                        <th style="text-align: center;">Subtotal</th>
                        <th style="text-align: center;">IGV</th>
                        <th style="text-align: center;">Total</th>
                        <th style="text-align: center;">Pagos</th>
                        <th style="text-align: center;">Saldo</th>
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