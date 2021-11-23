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
              <!-- <h4 class="page-title float-left"> <i class="fas fa-box-open"></i> Cierre Caja</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Cierre Caja</a></li>
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
            <div class="card-header bg-success"><h3 class="my-0 text-white">Agregar cierre de caja<a class="btn btn-rounded btn-pink float-right" data-toggle="modal" data-target="#ModalAgregarCierre"><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
              <div class="card-body table-responsive">
                <!-- <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="button" class="btn btn-pink" data-toggle="modal" data-target="#ModalAgregarCierre"><i class="fa fa-plus"></i>  Agregar</button>
                    </div>
                  </div>
                </div> -->
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormCierreFiltro" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Caja:</label>
                          <input type="text" name="caja" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Usuario:</label>
                          <input type="text" name="usuario" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-success waves-effect waves-light" style="margin-top: 29px"><i class="fa fa-search"></i> Buscar</button>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>

                <div>
                  <table id="TableCierre" class="table  table-striped tblcierre tblcierree tblcierret tblcierrea" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th>Secuencia</th>
                        <th>Fecha Hora Cierre</th>
                        <th>Caja Origen</th>
                        <th>Usuario</th>
                        <th>Caja Destino</th>
                        <th class="bg-primary">Efectivo</th>
                        <th class="bg-warning">Tarjeta</th>
                        <th>Abonado</th>
                        <th class="bg-success">Crédito</th>
                        <th class="bg-danger">Total</th>
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



<div class="modal fade" id="ModalAgregarCierre" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="FormCierre" action="<?= base_url('administrador/regcajacierre/agregar') ?>" method="post" autocomplete="off">
        <div class="modal-header bg-success">
          <h5 class="modal-title text-white" id="exampleModalLabel">Agregar Cierre</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Caja Origen:</label>
                    <select name="caja" class="form-control">
                      <option value="">Seleccione</option>
                      <?php foreach ($cajas_aperturas as $c): ?>
                      <option value="<?= $c->cod_caja ?>"><?= $c->nomb_caja ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Apertura:</label>
                    <select name="apertura" class="form-control">
                      
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Caja Destino</label>
                    <select name="destino" class="form-control">
                      <option value="">Seleccione</option>
                      <?php foreach ($cajas_destinos as $d): ?>
                      <option value="<?= $d->cod_caja ?>"><?= $d->nomb_caja ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Fecha Hora Cierre</label>
                    <input type="text" name="fechaHora" class="form-control datepicker" readonly value="<?= date('Y-m-d H:i:s') ?>">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Efectivo</label>
                    <input type="text" name="efectivo" class="form-control" readonly>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Tarjeta</label>
                    <input type="text" name="tarjeta" class="form-control" readonly>
                  </div>
                  </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Bonos Cobrados</label>
                    <input type="text" name="bonos_cobrados" class="form-control" readonly>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Crédito</label>
                    <input type="text" name="credito" class="form-control" readonly>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Total Cierre</label>
                    <input type="text" name="totalCierre" class="form-control" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Transferir y Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
