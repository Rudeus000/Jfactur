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
              <!-- <h4 class="page-title float-left"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i> Guia de Remisión</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Guia de Remisión</a></li>
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
            <div class="card-header bg-primary"><h3 class="my-0 text-white">Guia de remision</h3></div>
              <div class="card-body">              
                <div class="table-responsive">
                  <table class="table table-striped " id="TableGuia" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-primary text-white">
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
                              <td><?= $d->nota_guia ?></td>
                              <td><?= $d->secuencia_guia ?></td>
                              <td><?= $d->fecha_guia ?></td>
                              <td>
                                  <?php if(!is_null($d->cod_guia)): ?>
                                   <a href="<?= base_url('administrador/regdocumentoelectronico/imprimirGuia/'.$d->cod_guia) ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a>
                                  <a href="<?= base_url_app('facturacion/'.$d->rutaxml_guia.'/'.$d->archivoxml_guia.'.XML') ?>" target="_blank" class="btn btn-primary btn-sm">XML</a>
                                  <a href="<?= base_url_app('facturacion/'.$d->rutaxml_guia.'/R-'.$d->archivoxml_guia.'.XML') ?>" target="_blank" class="btn btn-primary btn-sm">CDR</a>
                                  <?php else: ?>
                                  <button type="button" data-id="<?= $d->cod_vent ?>" class="btn btn-pink btn-sm guia-remision">Guia</button>
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



<div class="modal fade" id="ModalGuiaRemision" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="FormGuiaRemision" action="<?= base_url('administrador/regdocumentoelectronico/guiaRemisionDocumento') ?>" method="post" autocomplete="off">
      <input type="hidden" name="id">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Guia Remisión</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Motivo Traslado</label>
								<select name="motivo" class="form-control">
                  <option value="01">VENTA</option>
                  <option value="14">VENTA SUJETA A CONFIRMACION DEL COMPRADOR</option>
                  <option value="04">TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA</option>
                  <option value="18">TRASLADO EMISOR ITINERANTE CP</option>
                  <option value="08">IMPORTACION</option>
                  <option value="09">EXPORTACION</option>
                  <option value="19">TRASLADO A ZONA PRIMARIA</option>
                  <option value="13">OTROS</option>
                </select>
							</div>
						</div>
            <div class="col-md-4">
							<div class="form-group">
								<label class="control-label"># Paquetes</label>
								<input type="text" name="num_paquetes" class="form-control">
							</div>
						</div>
             <div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Peso (Kilos)</label>
								<input type="text" name="peso" class="form-control">
							</div>
						</div>
            <div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Tipo Transporte</label>
								<select name="tipo_transportista" class="form-control">
                  <option value="01">Transporte público</option>
                  <option value="02">Transporte privado</option>
                </select>
							</div>
						</div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Doc. Transporte</label>
                  <select name="doc_transporte" class="form-control">
                  <option value="6">RUC</option>
                  <option value="1">DNI</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Num Doc. Transp.</label>
                <input type="text" name="num_doc_transporte" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Razon Social Transporte</label>
                <input type="text" name="razon_social_transporte" class="form-control">
              </div>
            </div>
					</div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Ubigeo Partida</label>
								<select name="ubigeo_partida" class="form-control select2">
									<option value="">Seleccione</option>
									<?php foreach($ubigeos as $u): ?> 
										<option ><?= $u->departamento.' - '.$u->provincia.' - '.$u->distrito ?></option>
									<?php endforeach ?>
								</select>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Dirección Partida</label>
                <input type="text" name="direccion_partida" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Ubigeo Destino</label>
								<select name="ubigeo_destino" class="form-control select2">
									<option value="">Seleccione</option>
									<?php foreach($ubigeos as $u): ?> 
										<option><?= $u->departamento.' - '.$u->provincia.' - '.$u->distrito ?></option>
									<?php endforeach ?>
								</select>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Dirección Destino</label>
                <input type="text" name="direccion_destino" class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Nota</label>
                <textarea name="nota" class="form-control" rows="10"></textarea>
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

