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
              <div class="card-header bg-primary">
                <h3 class="my-0 text-white">Guia de remision<a href="" class="btn btn-pink float-right" data-toggle="modal" data-target="#ModalGuiaRemision"><i class="fa fa-plus m-r-5"></i>GRE</a></h3>
              </div>
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
                      <?php foreach ($datos as $d) : ?>
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
                            <?php if (!is_null($d->cod_guia)) : ?>
                              <a href="<?= base_url('administrador/regdocumentoelectronico/imprimirGuia/' . $d->cod_guia) ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a>
                              <a href="<?= base_url_app('facturacion/' . $d->rutaxml_guia . '/' . $d->archivoxml_guia . '.XML') ?>" target="_blank" class="btn btn-primary btn-sm">XML</a>
                              <a href="<?= base_url_app('facturacion/' . $d->rutaxml_guia . '/R-' . $d->archivoxml_guia . '.XML') ?>" target="_blank" class="btn btn-primary btn-sm">CDR</a>
                            <?php else : ?>
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



<div class="modal" id="ModalGuiaRemision" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width:1000px">
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
          <fieldset>
            <legend>Datos del cliente</legend>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Cliente</label>
                  <div class="input-group">
                  <input type="text" name="name_cliente" class="form-control">
                  <div class="input-group-append">
                      <button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#ModalAgregarCliente" type="button">[+]</i></button>
                    </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Datos de traslado</legend>
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
                  <label class="control-label">Tipo Transporte</label>
                  <select name="tipo_transportista" class="form-control">
                    <option value="01">Transporte público</option>
                    <option value="02">Transporte privado</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Fecha inicio de traslado</label>
                  <input id="fechav" type="text" name="fecha" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
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
                  <label class="control-label"># Paquetes</label>
                  <input type="text" name="num_paquetes" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label"># Numero de contenedor</label>
                  <input type="text" name="num_paquetes" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label"># Codigo de puerto</label>
                  <input type="text" name="num_paquetes" class="form-control">
                </div>
              </div>
            </div>

          </fieldset>
          <fieldset>
            <legend>Datos del transportista</legend>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Doc. Transporte</label>
                  <select name="doc_transporte" id="tipo_documento" class="form-control">
                    <option value="4">RUC</option>
                    <option value="2">DNI</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Num Doc. Transp.</label>
                  <div class="input-group">
                    <input type="text" name="num_doc_transporte" id="txt_documento" class="form-control">
                    <div class="input-group-append">
                      <button class="btn btn-primary waves-effect waves-light" type="button" onclick="buscar();"><i class="fas fa-search
"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label class="control-label">Nombre / Razon Social Transporte</label>
                  <input type="text" name="razon_social_transporte" id="txt_nombre" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">N° Licencia Conducir:</label>
                  <input type="text" name="razon_social_transporte" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label"> N° Placa Vehíc.: *</label>
                  <input type="text" name="razon_social_transporte" class="form-control">
                </div>
              </div>
            </div>
          </fieldset>
          <div class="row">
            <div class="col-md-6">
              <!-- First / Last Name -->
              <fieldset class="form-group border p-3">
                <legend class="w-auto px-2">Punto de partida</legend>
                <div class="form-group">
                  <label class="control-label">Ubigeo Partida</label>
                  <select name="ubigeo_partida" class="form-control select2">
                    <option value="">Seleccione</option>
                    <?php foreach ($ubigeos as $u) : ?>
                      <option><?= $u->departamento . ' - ' . $u->provincia . ' - ' . $u->distrito ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="form-group">
                  <label class="control-label">Dirección Partida</label>
                  <input type="text" name="direccion_partida" class="form-control">
                </div>
              </fieldset>
            </div>
            <div class="col-md-6">
              <!-- About -->
              <fieldset class="form-group border p-3">
                <legend class="w-auto px-2">Punto de llegada</legend>
                <div class="form-group">
                  <label class="control-label">Ubigeo Partida</label>
                  <select name="ubigeo_partida" class="form-control select2">
                    <option value="">Seleccione</option>
                    <?php foreach ($ubigeos as $u) : ?>
                      <option><?= $u->departamento . ' - ' . $u->provincia . ' - ' . $u->distrito ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="form-group">
                  <label class="control-label">Dirección Partida</label>
                  <input type="text" name="direccion_partida" class="form-control">
                </div>
              </fieldset>
            </div>
          </div>
          <ul class="nav nav-tabs tabs-bordered nav-justified">
            <li class="nav-item">
              <a href="#gre" data-toggle="tab" aria-expanded="false" class="nav-link activar">
                Detalles de GRE
              </a>
            </li>
            <li class="nav-item">
              <a href="#doc_ref" data-toggle="tab" aria-expanded="true" class="nav-link ">
                Documento de referencia
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane show active" id="gre">
              <form id="FormGre" action="<?= base_url('administrador/guia/guia') ?>" method="get">
                <!-- <input type="hidden" name="busqueda_general_venta" value="1"> -->
                <button type="submit" class="btn btn-pink float-right"><i class="fas fa-cart-plus"></i> Agregar</button>
                <table id="table-gre" class="table table-bordered">
                  <thead>
                    <tr class="bg-success text-white">
                      <th>Descripcion</th>
                      <th>Unidad</th>
                      <th>Cntidad</th>
                      <th>Peso unit.(KGM)</th>
                      <th>Peso total(KGM)</th>
                    </tr>
                  </thead>
                </table>
              </form>
            </div>
            <div class="tab-pane" id="doc_ref">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo doc.</label>
                    <input type="text" name="ref_tipo_doc" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Serie</label>
                    <input type="text" name="ref_serie" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Numero</label>
                    <input type="text" name="ref_num" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <fieldset>
            <legend>Información adicional para SUNAT</legend>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Nota</label>
                  <textarea name="nota" class="form-control" rows="10"></textarea>
                </div>
              </div>
            </div>
          </fieldset>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Procesar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal" id="ModalAgregarCliente" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="card-header bg-primary">
      <h3 class="my-0 text-white">Agregar cliente</h3>
    </div>
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cliente - Agregar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
      <form id="FormVentaAgregarCliente" action="<?= base_url('administrador/regventas/agregarCliente') ?>" autocomplete="off" method="post">
        <div id="capa_load"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <!-- <div class="card"> -->
              <div class="card-body">
                <!-- <h4 class="header-title m-t-0 m-b-30">Tabs Bordered Justified</h4> -->

                <ul class="nav nav-tabs tabs-bordered nav-justified">
                  <li class="nav-item">
                    <a href="#home-b2" data-toggle="tab" aria-expanded="false" class="nav-link active">
                      Datos del cliente
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#profile-b2" data-toggle="tab" aria-expanded="true" class="nav-link">
                      Dirección
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#messages-b2" data-toggle="tab" aria-expanded="false" class="nav-link">
                      Datos adicionales
                    </a>
                  </li>
                  <!-- <li class="nav-item">
                                                <a href="#settings-b2" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                    Settings
                                                </a>
                                            </li> -->
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="home-b2">
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

                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="control-label" id="txtdocu">Ruc|Dni:</label>
                          <div class="input-group">
                            <input type="text" id="txt_documento" name="documento" class="form-control">
                            <div class="input-group-append">
                              <button class="btn btn-info" id="scan" type="button" onclick="buscar();">
                                <i class="fa fa-search"></i>
                              </button>
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Nombre o Razon Social</label>
                          <input type="text" id="txt_nombre" name="nombre" class="form-control  text-uppercase">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Precio Venta:</label>
                          <select name="precio_venta" class="form-control">
                            <option value="Normal">Precio Normal</option>
                            <option value="Mayor">Precio x Mayor</option>
                            <option value="Especial">Precio Especial</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Dirección</label>
                          <input type="text" id="txt_direccion" name="direccion" class="form-control">
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="tab-pane" id="profile-b2">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="control-label">Contacto</label>
                        <input type="text" name="contacto" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6" id="telefono">
                      <div class="form-group">
                        <label class="control-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                      </div>
                    </div>
                    <div class="col-md-12" id="idtelefono">
                      <div class="form-group">
                        <label class="control-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="control-label">Email</label>
                        <input type="email" name="email" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="messages-b2">
                    <!-- <div class="col-md-6" id="fnacimiento">
                                              <div class="form-group">
                                                <label class="control-label">F.nacimiento</label>
                                                <input type="date" id="fnacimiento" name="fnacimiento" class="form-control" >
                                              </div> -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">F.nacimiento</label>
                        <div class="input-group">
                          <input type="text" id="fnacimiento" name="fnacimiento" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- </div> -->
          </div> <!-- end col -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>

    </div>
  </div>
</div>