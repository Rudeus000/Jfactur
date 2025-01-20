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
              <!-- <h4 class="page-title float-left"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i> Ventas</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Ventas</a></li>
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
                <h3 class="my-0 text-white">Lista de ventas<a href="<?= base_url('administrador/regventas/agregar') ?>" class="btn btn-pink float-right"><i class="fa fa-plus m-r-5"></i>Vender</a></h3>
              </div>
              <div class="card-body">
                <!-- <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <a href="<?= base_url('administrador/regventas/agregar') ?>" class="btn btn-pink"><i class="fa fa-plus"></i>  Agregar</a>
                    </div>
                  </div>
                </div> -->
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormVentasFiltro" action="" method="post" autocomplete="off">
                    <div class="row">
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
                        <div class="form-group">
                          <label class="control-label">Clientes:</label>
                          <input type="text" name="cliente" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Vendedor:</label>
                          <?php if ($this->session->userdata('perfil') == 1 || $this->session->userdata('perfil') == 4) : ?>
                            <select name="vendedor" class="form-control">
                              <option value="">Seleccione</option>
                              <?php foreach ($vendedores as $v) : ?>
                                <option value="<?= $v->cod_usu ?>"><?= $v->apell_usu . ' ' . $v->nomb_usu ?></option>
                              <?php endforeach ?>
                            </select>
                          <?php else:  ?>                          
                            <input type="text" id="vendedor" name="vendedor" value="<?= $this->session->userdata('cod_usu') ?>" style="display:none">
                            <input type="text" name="vendedor" readonly class="form-control" value="<?= $this->session->userdata('nomb_usu') . ' ' . $this->session->userdata('apell_usu') ?>">
                          <?php endif ?>
                        </div>

                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Punto de venta:</label>
                          <select name="punto" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($puntos as $p) : ?>
                              <option value="<?= $p->cod_puntoventa ?>"><?= $p->nomb_puntoventa ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group">
                          <label class="control-label">Estado</label>
                          <select name="estado" class="form-control">
                            <option value="G">Generado</option>
                            <option value="A">Anulado</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group">
                          <label class="control-label">Cod.Venta:</label>
                          <input type="text" name="cod_venta" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group">                          
                          <button class="btn btn-success waves-effect waves-light" style="margin-top: 29px"><i class="fa fa-search"></i></button>
                        </div>
                      </div>                     
                      <div class="col-md-6">                      

                        <a id="VentasReportePdf" href="#" class="btn btn-danger" style="margin-top: 29px" target="_blank"><i class="far fa-file-pdf m-r-5"></i>Vista PDF</a>
                        <a id="VentasReporteExcel" href="#" class="btn btn-primary" style="margin-top: 29px" target="_blank"><i class="fas fa-angle-double-up m-r-5"></i> Vistas EXCEL</a>
                        <a href="<?= base_url('reportes/regreportedetallado/Ventas') ?>" class="btn btn-purple" style="margin-top: 29px" target="_blank"><i class="fab fa-accessible-icon m-r-5"></i>Ir a ventas detalladas</a>
                      </div>

                    </div>
                  </form>
                </fieldset>
                <br>
                <div class="table-responsive">
                  <table id="TableVentas" class="table  mb-0 table-hover table-striped table-borderless" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th></th>
                        <th>Tipo Documento</th>
                        <th>Fecha Emisión</th>
                        <th>Clientes</th>
                        <th>Ruc/Dni</th>
                        <th>Moneda</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Cobro</th>
                        <th>Saldo</th>
                        <th>Opciones</th>
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



<div id="ModalEnviarWhatsapp" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fab fa-whatsapp"></i> Enviar documento a whatsapp</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 id="nombre-cliente"></h4>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text">+51</div>
          </div>
          <input id="numero-whatsapp" type="text" class="form-control" placeholder="Número de whatsapp">
          <div class="input-group-append">
            
            <button id="generar-documento-whatsapp" data-id="99999" data-telefono="222222" 
            class="btn btn-success waves-effect waves-light" type="button">Generar documento a enviar</button>
          </div>
        </div>
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <a href="" target="_blank" class="btn btn-primary disabled" id="enviar-whatsapp"><i class="ion ion-logo-whatsapp"></i> Enviar Whatsapp</a>
      </div>
    </div>
  </div>
</div>



<div id="ModalEnviarEmail" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-envelope"></i> Enviar documento a email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 id="email-cliente"></h4>
        <button id="generar-documento-email" class="btn btn-success btn-md btn-lg btn-block"><i class="fa fa-envelope"></i> Enviar Email</button>
      </div>
    </div>
  </div>
</div>