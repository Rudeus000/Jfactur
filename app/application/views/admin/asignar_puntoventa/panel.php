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
                            <!-- <h4 class="page-title float-left">Inventario Inicial (Ingresos)</h4> -->
                            <ol class="breadcrumb float-right">
                               
                                <li class="breadcrumb-item"><a href="#">Gestion</a></li>
                                <li class="breadcrumb-item active">Sucursal</li>
                                <li class="breadcrumb-item active">Lista</li>
                            </ol>
                   
                        </div>
                    </div>
                </div>
              
                <!-- end row -->

                <!-- Vertical Steps Example -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                        <div class="card-header bg-primary"><h3 class="my-0 text-white">Asignar sucursal al usuario</h3></div>
                            <div class="card-body">
                                <form id="FormAsignaPuntoVentaFiltro" action="" method="post" autocomplete="off">
                                  <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Grupo</label>
                                            <select name="grupo" class="form-control select2">
                                              <option value=""></option>
                                              <?php foreach ($grupos as $g): ?>
                                              <option value="<?= $g->cod_grupo ?>"><?= $g->nombre_grupo ?></option>
                                              <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                  </div>
                                </form>
                                <div class="table-responsive">
                                    <table id="TableAsignarPuntoVenta" class="table mb-0" cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th>Id</th>
                                                <th>Nombres</th>
                                                <th>Apellidos</th>
                                                <th>Grupo</th>
                                                <th>Punto de Venta</th>
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



<!-- Modal -->
<div class="modal fade" id="ModalAsignarPuntoVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">Asignar Punto de Venta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <b>Usuario: <span id="NombreUsuario"></span></b>
            </div>
          </div>
        </div>
        <form id="FormAgregarPuntoVentaUsuario" action="<?= base_url('administrador/regasignpuntoventa/agregarPuntoVenta') ?>" method="post" style="display: none">
          <input type="hidden" name="usuario">
          <fieldset>
            <legend>Agregar Punto de Venta</legend>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Punto de Venta</label>
                  <select name="punto" class="form-control">
                    
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
              </div>
            </div>
          </fieldset>
        </form>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <button id="ButtonAgregarPuntoVenta" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Agregar</button>
            </div>
          </div>
        </div>
        <div class="row">          
          <div class="col-md-12">
            <table id="TableAsignarPuntoVentaUsuario" class="table table-bordered">
              <thead>
                <tr class="bg-primary text-white">
                  <th>Punto de Venta</th>
                  <th>Por Defecto</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>