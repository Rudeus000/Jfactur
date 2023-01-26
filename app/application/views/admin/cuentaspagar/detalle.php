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
              <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> Cuentas por pagar - Detalle</h4>
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Cuentas por pagar - Detalle</a></li>
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
                      <h4><?= $proveedor->tb_proveedor_nom ?></h4>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <a href="<?= base_url('administrador/regcuentaspagar/reporteDetallePdf/'.$this->uri->segment(4)) ?>" class="btn btn-pink" target="_blank"> <i class="far fa-file-pdf"></i> PDF</a>
                    <a href="<?= base_url('administrador/regcuentaspagar/reporteDetalleExcel/'.$this->uri->segment(4)) ?>" class="btn btn-purple" target="_blank"><i class="far fa-file-excel"></i> EXCEL</a>
                  </div>
                </div>
                <br>
                <div class="table-responsive">
                  <table class="table mb-0 table-condensed" cellspacing="0" width="100%">
                    <thead>
                      
                      <tr class="bg-info text-white">
                        <th rowspan="2" style="text-align: center;">Detalle</th>
                        <th rowspan="2" style="text-align: center;">Fecha</th>
                        <th rowspan="2" style="text-align: center;">Fec. Venc.</th>
                        <th rowspan="2" style="text-align: center;">Estado</th>
                        <th rowspan="2" style="text-align: center;">Monto</th>
                        <th rowspan="2" style="text-align: center;">Abonos</th>
                        <th rowspan="2" style="text-align: center;">Saldo</th>
                        <th rowspan="2" style="text-align: center;"></th>
                        
                        <th colspan="3" class="text-center">Pagos</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($datos as $d): ?>
                      <tr>
                        <td><?= $d->documento_comp.': '.$d->numdocumento_comp ?></td>
                        <td><?= $d->fecha_comp ?></td>
                        <td><?= $d->fecvenc_comp ?></td>
                        <td>
                          <?php 
                              if (date('Y-m-d') > $d->fecvenc_comp) {
                                echo '<label class="label label-danger">Vencido</label>';
                              }else{
                                echo '<label class="label label-warning">Por vencer</label>';
                              }
                           ?>
                        </td>
                        <td><?= $d->saldo_comp ?></td>
                        <td><?= $d->abono ?></td>
                        <td><?= number_format($d->saldo_comp - $d->abono, 2, '.', ',') ?></td>
                        <td>
                          <button data-id="<?= $d->cod_comp ?>" class="btn btn-xs btn-success CuentasPagar"><i class="fas fa-hand-holding-usd"></i> Pagar</button>
                        </td>
                        <td colspan="3">
                          <table class="table table-bordered table-condensed">
                            <tr>
                              <th style="text-align: center;">Fecha</th>
                              <th style="text-align: center;">Caja</th>
                              <th style="text-align: center;">Monto</th>
                            </tr>
                            <?php foreach ($d->pagos as $p): ?>
                            <tr>
                              <td><?= $p->fecha_pago ?></td>
                              <td><?= $p->nomb_caja ?></td>
                              <td><?= $p->monto_pago ?></td>
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
                                    
                                    <a href="<?= base_url('administrador/regcuentaspagar') ?>" class="btn btn-pink"><i class="far fa-hand-point-left"></i> Volver Pagos</a>
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


<div class="modal" id="ModalCuentasPagar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Información de pagos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormCuentasPagar" action="<?= base_url('administrador/regcuentaspagar/pagar') ?>" autocomplete="off" method="post">
        <input type="hidden" name="compra">
        <input type="hidden" name="proveedor">
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
                <select name="caja" class="form-control">
                  <?php foreach ($cajas as $c): ?>
                  <option value="<?= $c->cod_caja ?>"><?= $c->nomb_caja ?></option>
                  <?php endforeach ?>
                </select>
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