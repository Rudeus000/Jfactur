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
              <!-- <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> Cuentas por cobrar - Detalle</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Cuentas por cobrar - Detalle</a></li>
                <li class="breadcrumb-item active">Listado</li>
              </ol>
            </div>
          </div>
        </div>

        <!-- end row -->

        <!-- Vertical Steps Example -->
            <?php if ($caja==FALSE): ?>
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
              Debes aperturar una caja.
            </div>
          </div>
        </div>
        <?php endif ?>
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
            <div class="card-header bg-success"><h3 class="my-0 text-white">Cuentas por cobrar</h3></div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <h4><?= $cliente->nomb_cliente ?></h4>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <a href="<?= base_url('administrador/regcuentascobrar/reporteDetallePdf/'.$this->uri->segment(4)) ?>" class="btn btn-pink" target="_blank"> <i class="far fa-file-pdf"></i> PDF</a>
                    <a href="<?= base_url('administrador/regcuentascobrar/reporteDetalleExcel/'.$this->uri->segment(4)) ?>" class="btn btn-purple" target="_blank"><i class="far fa-file-excel"></i> EXCEL</a>
                  </div>
                </div>
                <br>
                <div class="table-responsive">
                  
                  <table class="table mb-0 table-condensed" cellspacing="0" width="100%">
                    <thead>
                      
                      <tr class="bg-info text-white">
                        <th></th>
                        <th rowspan="2" style="text-align: center;">Detalle</th>
                        <th rowspan="2" style="text-align: center;">Fecha</th>
                        <th rowspan="2" style="text-align: center;">Fec. Venc.</th>
                        <th rowspan="2" style="text-align: center;">Estado</th>
                        <th rowspan="2" style="text-align: center;">Monto</th>
                        <th rowspan="2" style="text-align: center;">Abonos</th>
                        <th rowspan="2" style="text-align: center;">Saldo</th>
                        <th rowspan="2" style="text-align: center;"></th>
                        
                        <th colspan="3" class="text-center">Cobros</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <?php foreach ($datos as $d): ?>
                      <tr>
                        <td>
                          <button class="btn btn-primary btn-xs detalle-cuentacobrar" data-id="<?= $d->cod_vent ?>"><i class="fa fa-plus"></i></button>
                        </td>
                        <td><?= $d->nom_tipdocumento.': '.$d->numero_vent ?></td>
                        <td><?= $d->fecha_vent ?></td>
                        <td><?= $d->fechavenc_vent ?></td>
                        <td>
                          <?php 
                              if (date('Y-m-d') > $d->fechavenc_vent) {
                                echo '<label class="label label-danger">Vencido</label>';
                              }else{
                                echo '<label class="label label-warning">Por vencer</label>';
                              }
                          ?>
                        </td>
                        <td><?= $d->total_vent ?></td>
                        <td><?= $d->abono ?></td>
                        
                        <td><?= number_format($d->pendiente_vent, 2, '.', ',') ?></td>
                        <td>
                          <?php if($caja!=FALSE): ?> 
                          <button data-id="<?= $d->cod_vent ?>" class="btn btn-xs btn-success CuentasCobrar"><i class="fas fa-hand-holding-usd"></i> Cobrar</button>
                        <?php endif?>
                        </td>
                        <td colspan="3">
                          <table class="table table-bordered table-condensed">
                            <tr>
                              <th style="text-align: center;">Fecha</th>
                              <th style="text-align: center;">Caja</th>
                              <th style="text-align: center;">Monto</th>
                            </tr>
                            <?php foreach ($d->cobros as $c): ?>
                            <tr>
                              <td><?= $c->fecha_cobro ?></td>
                              <td><?= $c->nomb_caja ?></td>
                              <td><?= $c->monto_cobro ?></td>
                            </tr>
                            <?php endforeach ?>
                          </table>
                        </td>
                      </tr>
                      <?php endforeach ?>
                    </tbody>

                  </table>

                </div>
                <!-- End #wizard-vertical -->

                    <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    
                                    <a href="<?= base_url('administrador/regcuentascobrar') ?>" class="btn btn-pink"><i class="far fa-hand-point-left"></i> Volver Cobros</a>
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


<div class="modal" id="ModalCuentasCobrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Información de cobros</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormCuentasCobrar" action="<?= base_url('administrador/regcuentascobrar/cobrar') ?>" autocomplete="off" method="post">
        <input type="hidden" name="venta">
        <input type="hidden" name="cliente">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label class="control-label">Fecha</label>
                <input type="text" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label">Caja</label>
                  <input type="text" name="caja" class="form-control" value="<?= $caja->nomb_caja ?>" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label class="control-label">RUC/DNI</label>
                <input type="text" name="ruc" disabled class="form-control">
              </div>
            </div>
             <div class="col-md-10">
              <div class="form-group">
                <label class="control-label">Anexo</label>
                <input type="text" name="anexo" disabled class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <textarea name="detalle" class="form-control" rows="5"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label class="control-label">Importe</label>
                <input type="text" name="importe" class="form-control">
              </div>
            </div>
            <div class="col-md-2 offset-md-4">
              <div class="form-group">
                <label class="control-label">Saldo</label>
                <input type="text" name="saldo" class="form-control" disabled>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-pink" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal" id="ModalDetalleVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detalle de Venta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="table-ventadetalle-cuentacobrar" class="table table-bordered">
          <thead>
            <tr>
              <th>Descripción</th>
              <th>Marca</th>
              <th>Unidad</th>
              <th>Precio Unit.</th>
              <th>Cant.</th>
              <th>Descuento</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
          <tfoot>
            <tr>
              <th colspan="5"></th>
              <th>Total</th>
              <th id="cuentacobrardetalle-Total"></th>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-pink" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
      </div>
    </div>
  </div>
</div>
