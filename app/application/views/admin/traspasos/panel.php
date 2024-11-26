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
              <!-- <h4 class="page-title float-left"><i class="fas fa-arrows-alt-h" aria-hidden="true"></i> Traspasos</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Almacen</a></li>
                <li class="breadcrumb-item"><a href="#">Traspaso</a></li>
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
              <div class="card-header bg-success">
                <h3 class="my-0 text-white">Lista de traspasos<a
                    href="<?= base_url('administrador/regtraspasos/agregar') ?>"
                    class="btn btn-rounded btn-pink float-right"><i class="fa fa-plus m-r-5"></i>Agregar</a></h3>
              </div>
              <div class="card-body table-responsive">

                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormTraspasosFiltro" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Fecha:</label>
                          <div class="input-group">
                            <input type="text" name="desde" class="form-control datepicker"
                              value="<?= date('Y-m-d') ?>">
                            <input type="text" name="hasta" class="form-control datepicker"
                              value="<?= date('Y-m-d') ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Origen:</label>
                          <select name="origen" class="form-control select2">
                            <option value="">Seleccione</option>
                            <?php foreach ($almacenes as $a): ?>
                              <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Destino:</label>
                          <select name="destino" class="form-control select2">
                            <option value="">Seleccione</option>
                            <?php foreach ($almacenes as $a): ?>
                              <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-rounded btn-success" style="margin-top: 29px"><i
                            class="fab fa-earlybirds m-r-5"></i>Buscar</button>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <a id="TraspasosReportePdf" href="#" class="btn btn-rounded btn-pink" target="_blank"><i
                        class="far fa-file-pdf"></i> PDF</a>
                    <a id="TraspasosReporteExcel" href="#" class="btn btn-rounded btn-purple" target="_blank"><i
                        class="far fa-file-excel"></i> EXCEL</a>
                  </div>
                </div>
                <br>
                <div>
                  <table id="TableTraspasos" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th></th>
                        <th style="text-align: center;">Id</th>
                        <th style="text-align: center;">Fecha</th>
                        <th style="text-align: center;">Almacen Origen</th>
                        <th style="text-align: center;">Almace Destino</th>
                        <th style="text-align: center;">Producto</th>
                        <th style="text-align: center;">Cantidad</th>
                        <th style="text-align: center;">Usuario T.</th>
                        <th style="text-align: center;">Estado</th>
                        <th style="text-align: center;">Usario R.</th>
                        <th style="text-align: center;">Observación</th>
                        <th style="text-align: center;">Opcion</th>
                        <th style="text-align: center;">Imprimir</th>
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
<div class="modal fade" id="modalValidacion" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Validar Traspaso</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idTraspaso">
        <label>Contraseña del Validador</label>
        <input type="password" class="form-control" id="password" required>
        <br>
        <label>Acción</label>
        <select id="accion" class="form-control">
          <option value="1">Aceptar</option>
          <option value="2">Rechazar</option>
        </select>
        <br>
        <div id="motivoRechazoContainer" style="display: none;">
          <label>Motivo de Rechazo</label>
          <textarea class="form-control" id="motivoRechazo" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnValidar" class="btn btn-primary">Confirmar</button>
      </div>
    </div>
  </div>
</div>
<script>
  function abrirModalValidacion(cod_tras) {
    // Asignar el valor de cod_tras al input oculto
    $('#idTraspaso').val(cod_tras);

    // Mostrar el modal de validación
    $('#modalValidacion').modal('show');
}

</script>