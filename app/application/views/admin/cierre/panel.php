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
              <div class="card-header bg-success">
                <h3 class="my-0 text-white">Caja chica<a class="btn btn-rounded btn-pink float-right" data-toggle="modal" data-target="#ModalAgregarCierre"><i class="fa fa-plus m-r-5"></i>Agregar</a></h3>
              </div>
              <div class="card-body table-responsive">
                <!-- <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="button" class="btn btn-pink" data-toggle="modal" data-target="#ModalAgregarCierre"><i class="fa fa-plus"></i>  Agregar</button>
                    </div>
                  </div>
                </div> -->
                <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                      Caja
                    </a>
                  </li>
                  <li class="nav-item" hidden>
                    <a href="#profile" data-toggle="tab" aria-expanded="true" class="nav-link">
                      Ingresos / Egresos
                    </a>
                  </li>
                </ul>

                <div class="tab-content">
                  <div class="tab-pane show active" id="home">
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
                          <?php if ($this->session->userdata('perfil') == 1) : ?>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="control-label">Usuario:</label>
                                <input type="text" name="usuario" class="form-control">
                              </div>
                            </div>
                          <?php endif ?>
                          <?php if ($this->session->userdata('perfil') != 1) : ?>
                            <div class="col-md-4" hidden="">
                              <div class="form-group">
                                <label class="control-label">Usuario:</label>
                                <input type="text" readonly value="<?= $this->session->userdata('nomb_usu') ?>" name="usuario" class="form-control">
                              </div>
                            </div>
                          <?php endif ?>
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
                            <button class="btn btn-success waves-effect waves-light" style="margin-top: 29px"><i class="fa fa-search"></i> Buscar</button>
                          </div>
                        </div>
                      </form>
                    </fieldset>
                    <br>

                    <div>
                      <table id="TableCierre" class="table  table-striped tblcierre tblcierree tblcierret tblcierrey tblcierrea" cellspacing="0" width="100%">
                        <thead>
                          <tr class="bg-success text-white">
                            <th>Secuencia</th>
                            <th>Fecha Hora Cierre</th>
                            <th>Caja Origen</th>
                            <th>Usuario</th>
                            <th>Caja Destino</th>
                            <th>Observacion</th>
                            <th class="bg-danger">Egresos</th>
                            <th class="bg-primary">Efectivo</th>
                            <th class="bg-warning">Tarjeta</th>
                            <th class="bg-purple">Yape</th>
                            <th class="bg-info">Abonado</th>
                            <th class="bg-success">Crédito</th>
                            <th class="bg-danger">Total</th>
                            <!-- <th>Opciones</th> -->
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th colspan="12" style="text-align:right"><span class="text-primary">Ingresos caja:</th>
                            <th><strong><span id="Totalingresos"></span></strong></th>
                          </tr>
                          <tr>
                            <th colspan="12" style="text-align:right"><span class="text-danger">Egresos caja:</th>
                            <th><strong><span id="Totalegresos" class="label label-danger"></span></strong></th>
                          </tr>
                          <tr>
                            <th colspan="12" style="text-align:right">Total en caja:</th>
                            <th><strong><span id="Totalcaja"></span></strong></th>
                          </tr>
                        </tfoot>

                      </table>

                    </div>
                  </div>
                  <div class="tab-pane" id="profile">
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
                          <?php if ($this->session->userdata('perfil') == 1) : ?>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="control-label">Usuario:</label>
                                <input type="text" name="usuario" class="form-control">
                              </div>
                            </div>
                          <?php endif ?>
                          <?php if ($this->session->userdata('perfil') != 1) : ?>
                            <div class="col-md-4" hidden="">
                              <div class="form-group">
                                <label class="control-label">Usuario:</label>
                                <input type="text" readonly value="<?= $this->session->userdata('nomb_usu') ?>" name="usuario" class="form-control">
                              </div>
                            </div>
                          <?php endif ?>
                          <div class="col-md-2">
                            <!-- <button class="btn btn-success waves-effect waves-light" style="margin-top: 29px"><i class="fa fa-search"></i> Buscar</button> -->

                            <button type="button" class="btn btn-pink" data-toggle="modal" data-target="#ModalAgregarMovimiento" style="margin-top: 29px"><i class="fas fa-plus-circle"></i> Agregar</button>
                          </div>
                        </div>
                      </form>
                    </fieldset>
                    <br>

                    <div>
                      <table id="TableCierre" class="table  table-striped tblcierre tblcierree tblcierret tblcierrea" cellspacing="0" width="100%">
                        <thead>
                          <tr class="bg-success text-white">
                            <th>Fecha</th>
                            <th>Descripcion</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th class="bg-primary">Accion</th>
                          </tr>
                        </thead>

                      </table>

                    </div>
                  </div>
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



<div class="modal" id="ModalAgregarCierre" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                      <?php foreach ($cajas_aperturas as $c) : ?>
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
                <fieldset>
                  <legend>Registrar movimiento</legend>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="control-label">Tipo:<span class="text-danger">[-]<span class="text-primary">[+]<span class="text-danger"> *</label>

                        <select name="tipmovement" class="form-control">
                          <option value="E">Egresos(-)</option>
                          <option value="I">Ingresos(+)</option>
                        </select>

                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="control-label">Monto:<span class="text-danger"> *</label>
                        <input type="text" name="amountmovement" class="form-control">
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="control-label">Observacion:</label>
                        <textarea name="obsmovement" class="form-control"></textarea>

                      </div>
                    </div>
                  </div>
                </fieldset>

              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Caja Destino</label>
                    <select name="destino" class="form-control">
                      <option value="">Seleccione</option>
                      <?php foreach ($cajas_destinos as $d) : ?>
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
                    <label class="control-label">Yape</label>
                    <input type="text" name="yape" class="form-control" readonly>
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

<div id="ModalAgregarMovimiento" class="modal bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <form id="FormMovimiento" action="<?= base_url('administrador/regcajacierre/addEgresosIngresos') ?>" method="post" autocomplete="off">
        <input type="hidden">
        <div class="modal-header bg-success">
          <h4 class="text-white">Registrar movimientos</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label">Sucursal:</label>
                <?php if ($this->session->userdata('perfil') == 1) : ?>
                  <select name="sucursal" class="form-control">
                    <option value="">Seleccione</option>
                    <?php foreach ($puntos as $p) : ?>
                      <option value="<?= $p->cod_puntoventa ?>"><?= $p->nomb_puntoventa ?></option>
                    <?php endforeach ?>
                  </select>
                <?php endif  ?>
                <?php if ($this->session->userdata('perfil') != 1) : ?>
                  <select name="sucursal" class="form-control" hidden>
                    <?php foreach ($puntos as $p) : ?>
                      <option value="<?= $p->cod_puntoventa ?>"><?= $p->nomb_puntoventa ?></option>
                    <?php endforeach ?>
                  </select>
                  <input type="text" name="sucursal" readonly class="form-control" value="<?= $p->nomb_puntoventa ?? '' ?>">
                <?php endif  ?>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Usuario:</label>
                <?php if ($this->session->userdata('perfil') == 1) : ?>
                  <select name="usuario" class="form-control">
                    <option value="">Seleccione</option>
                    <?php foreach ($vendedores as $v) : ?>
                      <option value="<?= $v->cod_usu ?>"><?= $v->apell_usu . ' ' . $v->nomb_usu ?></option>
                    <?php endforeach ?>
                  </select>
                <?php endif  ?>
                <?php if ($this->session->userdata('perfil') != 1) : ?>
                  <input type="text" id="usuario" name="vendedor" value="<?= $this->session->userdata('cod_usu') ?>" style="display:none">
                  <input type="text" name="usuario" readonly class="form-control" value="<?= $this->session->userdata('nomb_usu') . ' ' . $this->session->userdata('apell_usu') ?>">
                <?php endif ?>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label">Fecha:<span class="text-danger"> *</label>
                <input type="text" name="fecha" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Tipo:<span class="text-danger">[-]<span class="text-primary">[+]<span class="text-danger"> *</label>

                <select name="tipmovimiento" class="form-control">
                  <option value="E">Egresos(-)</option>
                  <option value="I">Ingresos(+)</option>
                </select>

              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Tipo abono:<span class="text-danger"> *</label>
                <select name="tipoAbono" class="form-control">
                  <?php foreach ($tipos_pagos as $t) : ?>
                    <option value="<?= $t->cod_tipopago ?>"><?= $t->nom_tipopago ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-md-4 tipo-tarjeta form-group" style="display: none">
              <div class="form-group">
                <label class="control-label">Tipo Tarjeta:<span class="text-danger"> *</label>
                <select name="tipoTarjeta" class="form-control">
                  <option value=""></option>
                  <?php foreach ($tipos_tarjetas as $t) : ?>
                    <option value="<?= $t->cod_tarj ?>"><?= $t->nomb_tarj ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-md-4 banco-movimiento form-group" style="display: none">
              <div class="form-group">
                <label class="control-label">Banco:<span class="text-danger"> *</label>
                <select class="form-control select2 select2-hidden-accessible input-sm" name="banco">
                  <option value="">--Selecciona--</option>
                  <?php foreach ($banco as $b) : ?>
                    <option value="<?= $b->cod_ban ?>"><?= $b->nomb_ban ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>

            <div class="col-md-4 cuenta-movimiento form-group" style="display: none">
              <div class="form-group">
                <label class="control-label">Cuenta:</label>
                <input type="text" name="cuenta" class="form-control">
              </div>
            </div>


            <div class="col-md-4 numero-operacion form-group" style="display: none">
              <div class="form-group">
                <label class="control-label">Nro operacion:</label>
                <input type="text" name="operacion" class="form-control">
              </div>
            </div>


            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Tipo gasto</label>
                <select class="form-control select" name="tipomovimiento">
                  <option value="">--Selecciona--</option>
                  <?php foreach ($tipogastos as $t) : ?>
                    <option value="<?= $t->cod_tipgastos ?>"><?= $t->descripcion ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>

            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Descripción:</label>
                <input type="text" name="descripcion" class="form-control">
              </div>
            </div>





            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Monto:<span class="text-danger"> *</label>
                <input type="text" name="montom" class="form-control">
              </div>
            </div>



            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Observacion:</label>
                <textarea name="observacionm" class="form-control"></textarea>

              </div>
            </div>

          </div>
          <fieldset>
            <legend>Responsable | Proveedor <span class="text-primary">(opcional)</span></legend>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Tipo:</label>
                  <select name="tipo" id="tipo_documento" class="form-control">
                    <option value="">Seleccione</option>
                    <?php foreach ($doc_clientes as $d) : ?>
                      <option value="<?= $d->cod_tipdocucli ?>"><?= $d->nom_tipdocucli ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <label class="control-label">Nro documento</label>
                <div class="input-group md-3">
                  <input type="text" class="form-control" name="documento" placeholder="Ingrese numero" aria-label="Recipient's username" aria-describedby="basic-addon2">
                  <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="button"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Datos:<span class="text-danger"> *</label>
                  <input type="text" name="namemovimiento" class="form-control">
                </div>
              </div>

            </div>

          </fieldset>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div><!-- /.modal -->