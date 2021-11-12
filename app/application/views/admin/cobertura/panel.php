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
              <h4 class="page-title float-left"><i class="fas fa-user-tie" aria-hidden="true"></i> Coberturas</h4>
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Coberturas</a></li>
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
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="button" class="btn btn-pink" data-toggle="modal" data-target="#ModalAgregarCobertura"><i class="fa fa-plus"></i>  Agregar</button>
                    </div>
                  </div>
                </div>
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormClienteCoberturaFiltro" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Cliente</label>
                          <input type="text" name="cliente" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-success waves-effect waves-light" style="margin-top: 29px"><i class="fa fa-search"></i> Buscar</button>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>

                <div class="table-responsive">
                  <table id="TableClienteCobertura" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-info text-white">
                        <th>Id</th>
                        <th>Cliente</th>
                        <th>Fec. Inicio</th>
                        <th>Fec. Limite</th>
                        <th>Monto</th>
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



<div class="modal fade" id="ModalAgregarCobertura" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="FormCobertura" action="<?= base_url('administrador/regclientecobertura/agregar') ?>" method="post" autocomplete="off">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Agregar Cobertura</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Fecha Inicio</label>
                <input type="text" name="inicio" class="form-control datepicker">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Fecha Límite</label>
                <input type="text" name="limite" class="form-control datepicker">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Cliente</label>
                <select name="cliente" class="form-control" style="width: 100%"></select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Habilitar Ampliación</label>
                <div class="form-check">
                  <input id="cobertura" class="form-check-input" name="cobertura" type="checkbox">
                  <label for="cobertura" class="form-check-label">
                      Cobertura
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Monto Máximo</label>
                <input type="text" name="monto" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="ModalEditarCobertura" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="FormCoberturaEditar" action="<?= base_url('administrador/regclientecobertura/editar') ?>" method="post" autocomplete="off">
        <input type="hidden" name="id" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar Cobertura</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Fecha Inicio</label>
                <input type="text" name="inicio" class="form-control datepicker">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Fecha Límite</label>
                <input type="text" name="limite" class="form-control datepicker">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Cliente</label>
                <input type="text" name="cliente" class="form-control" disabled>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Habilitar Ampliación</label>
                <div class="form-check">
                  <input id="cobertura" class="form-check-input" name="cobertura" type="checkbox">
                  <label for="cobertura" class="form-check-label">
                      Cobertura
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Monto Máximo</label>
                <input type="text" name="monto" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>