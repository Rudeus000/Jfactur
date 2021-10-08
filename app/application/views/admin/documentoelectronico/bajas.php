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
              <!-- <h4 class="page-title float-left"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i> Bajas Sunat</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Bajas Sunat</a></li>
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
            <div class="card-header bg-success"><h3 class="my-0 text-white">Comunicacion de baja sunat</h3></div>
              <div class="card-body">
                
                <div class="table-responsive">
                  <table id="TableBajas" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th>Tipo de Doc.</th>
                        <th>Serie</th>
                        <th>Num</th>
                        <th>Fecha</th>                      
                        <th>Cliente</th>
                        <th>IGV</th>
                        <th>Subtotal</th>
						            <th>Total</th>
                        <th>Motivo</th>
                        <th>Sec.</th>
                        <th>Fecha Baja</th>
                        <th></th>
                      </tr>
                    </thead>
                        <tbody>
                            <?php foreach($datos as $d): ?>
                            <tr>
                                <td><?= $d->nom_tipdocumento ?></td>
                                <td><?= $d->serie ?></td>
                                <td><?= $d->numero_vent ?></td>
                                <td><?= $d->fecha_vent ?></td>
                                <td><?= $d->nomb_cliente ?></td>
                                <td><?= $d->igv_vent ?></td>
                                <td><?= $d->subtotal_vent ?></td>
                                <td><?= $d->total_vent ?></td>
                                <td><?= $d->motivo_baja ?></td>
                                <td><?= $d->secuencia_baja ?></td>
                                <td><?= $d->fecha_baja ?></td>
                                <td>
                                    <?php if(!is_null($d->fecha_baja)): ?>
                                    <a href="<?= base_url('administrador/regdocumentoelectronico/impresionBaja/'.$d->cod_baja) ?>" target="_blank" class="btn btn-info btn-sm" title="Impresion"><i class="far fa-file-alt"></i></a>
                                    <a href="<?= base_url_app('facturacion/'.$d->rutaxml_baja.'/'.$d->archivoxml_baja.'.XML') ?>" target="_blank" class="btn btn-info btn-sm">XML</a>
                                    <a href="<?= base_url_app('facturacion/'.$d->rutaxml_baja.'/R-'.$d->archivoxml_baja.'.XML') ?>" target="_blank" class="btn btn-info btn-sm">CDR</a>
                                    <?php else: ?>
                                    <button type="button" data-id="<?= $d->cod_vent ?>" class="btn btn-danger btn-sm baja-sunat">Baja Sunat</button>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
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



<div class="modal fade" id="ModalBajaSunat" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="FormBajaSunat" action="<?= base_url('administrador/regdocumentoelectronico/bajaDocumento') ?>" method="post" autocomplete="off">
      <input type="hidden" name="id">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Baja Sunat</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

					<div class="row">
            <div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Motivo</label>
								<input type="text" name="motivo" class="form-control" placeholder="Motivo de la Baja">
							</div>
						</div>		
					</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
        </div>
      </form>
    </div>
  </div>
</div>

