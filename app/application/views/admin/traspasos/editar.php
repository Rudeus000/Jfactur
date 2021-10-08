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
                            <h4 class="page-title float-left">Editar Traspasos</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="#">Traspasos</a></li>
                                <li class="breadcrumb-item active">Editar</li>
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
                              <form id="FormEditarTraspasos" action="<?= base_url('administrador/regtraspasos/editarGuardar') ?>" method="post" autocomplete="off">
                                <input type="hidden" name="id" value="<?= $traspaso->cod_tras ?>">
                                <fieldset>
                                  <legend>Principal</legend>
                                  <div class="row">
                                    <div class="col-md-2">
                                      <div class="form-group">
                                        <label class="control-label">Fecha</label>
                                        <input type="text" name="fecha" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Origen</label>
                                        <select  class="form-control" disabled>
                                            <?php foreach ($almacenes as $a): ?>
                                            <option value="<?= $a->cod_almacen ?>" <?= ($a->cod_almacen==$traspaso->origen_tras)?'selected':'' ?>><?= $a->nomb_almacen ?></option>
                                            <?php endforeach ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Destino</label>
                                        <select  class="form-control" disabled>
                                          <?php foreach ($almacenes as $a): ?>
                                          <option value="<?= $a->cod_almacen ?>" <?= ($a->cod_almacen==$traspaso->destino_tras)?'selected':'' ?>><?= $a->nomb_almacen ?></option>
                                          <?php endforeach ?>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <label class="control-label">Observación</label>
                                        <input type="text" name="observacion" class="form-control">
                                      </div>
                                    </div>
                                  </div>
                                </fieldset>

                              </form>

                              
                              <table id="TableTraspasosProductos" class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Código</th>
                                    <th>Artículo</th>
                                    <th>Unidad</th>
                                    <th>Cantidad</th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($detalles as $d): ?>
                                  <tr>
                                    <td><?= $d->cod_producto ?></td>
                                    <td><?= $d->nomb_product ?></td>
                                    <td><?= $d->nomb_unid?></td>
                                    <td><?= $d->cant_trasdet ?></td>
                                  </tr>
                                  <?php endforeach ?>
                                </tbody>
                              </table>
                              

                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <button type="submit" form="FormEditarTraspasos" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
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



