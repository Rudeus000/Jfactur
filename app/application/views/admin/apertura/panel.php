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
              <!-- <h4 class="page-title float-left"> <i class="fas fa-box-open"></i> Apertura Caja</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Apertura Caja</a></li>
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
            <div class="card-header bg-success"><h3 class="my-0 text-white">Apertura de caja<a class="btn btn-rounded btn-pink float-right" id="AgregarApertura"><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
              <div class="card-body table-responsive">
                <!-- <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <button id="AgregarApertura" type="button" class="btn btn-pink"><i class="fa fa-plus"></i>  Agregar</button>
                    </div>
                  </div>
                </div> -->
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormAperturaFiltro" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Caja:</label>
                          <input type="text" name="caja" class="form-control">
                        </div>
                      </div>
                      <?php if($this->session->userdata('perfil')==1): ?> 
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Usuario:</label>
                          <input type="text"  name="usuario" class="form-control">                      
                        </div>                      
                      </div>
                      <?php endif ?>
                      <?php if($this->session->userdata('perfil')!=1): ?>
                      <div class="col-md-4" hidden="">
                        <div class="form-group">
                          <label class="control-label">Usuario:</label>                      
                          <input type="text" readonly value="<?=$this->session->userdata('nomb_usu')?>" name="usuario" class="form-control">                          
                        </div>                      
                      </div>
                      <?php endif ?>
                      <div class="col-md-2">
                        <button class="btn btn-success waves-effect waves-light" style="margin-top: 29px"><i class="fa fa-search"></i> Buscar</button>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>

                <div>
                  <table id="TableApertura" class="table  table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th style="text-align: center;">Secuencia</th>
                        <th style="text-align: center;">Caja</th>
                        <th style="text-align: center;">Usuario</th>
                        <th style="text-align: center;">Monto</th>
                        <th style="text-align: center;">Turno</th>
                        <th style="text-align: center;">Fecha</th>
                        <th style="text-align: center;">Opciones</th>
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

<div class="modal fade" id="ModalAgregarConfirmar" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form id="FormConfirmarAgregar" action="<?= base_url('administrador/regcajaapertura/confirmarContrasena') ?>" method="post" autocomplete="off">
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fab fa-expeditedssl m-r-5"></i>Confirmar permiso</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
						<div class="col-md-12">         
							<label>Confirmar permiso del Administrador</label>
							<input type="password" name="contrasena" class="form-control">
						</div>
					</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary btn-rounded">Confirmar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="ModalAgregarApertura" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="FormApertura" action="<?= base_url('administrador/regcajaapertura/agregar') ?>" method="post" autocomplete="off">
        <div class="modal-header bg-success">
          <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fas fa-coins m-r-5"></i>Agregar apertura</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Caja:</label>
                <select name="caja" class="form-control select2">
                  <option value=""></option>
                  <?php foreach ($cajas as $c): ?>
                  <option value="<?= $c->cod_caja ?>"><?= $c->nomb_caja ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Usuario:</label>
								<input type="text" name="usuario" readonly class="form-control" value="<?= $this->session->userdata('nomb_usu').' '.$this->session->userdata('apell_usu') ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Monto Apertura:</label>
                <input type="text" name="monto" class="form-control" value="0.00">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Fecha:</label>
                <input type="text" name="fecha" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Turno:</label>
                <select name="turno" class="form-control select2">
                  <option value="M">Mañana</option>
                  <option value="T">Tarde</option>
                  <option value="N">Noche</option>
                  <option value="C">Completo</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label">H. Inicio</label>
                <input type="text" name="inicio" class="form-control timepicker">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label">H. Fin</label>
                <input type="text" name="fin" class="form-control timepicker">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary btn-rounded">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="ModalEditarApertura" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="FormAperturaEditar" action="<?= base_url('administrador/regcajaapertura/editar') ?>" method="post" autocomplete="off">
        <input type="hidden" name="id">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar Apertura</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Caja</label>
                <select name="caja" class="form-control">
                  <option value=""></option>
                  <?php foreach ($cajas as $c): ?>
                  <option value="<?= $c->cod_caja ?>"><?= $c->nomb_caja ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Usuario</label>
                <input type="text" readonly class="form-control" value="<?= $this->session->userdata('nomb_usu').' '.$this->session->userdata('apell_usu') ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Monto Apertura</label>
                <input type="text" name="monto" class="form-control" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Fecha</label>
                <input type="text" name="fecha" class="form-control datepicker" readonly="true">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Turno</label>
                <select name="turno" class="form-control">
                  <option value="M">Mañana</option>
                  <option value="T">Tarde</option>
                  <option value="N">Noche</option>
                  <option value="C">Completo</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label">H. Inicio</label>
                <input type="text" name="inicio" class="form-control timepicker">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label">H. Fin</label>
                <input type="text" name="fin" class="form-control timepicker">
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
