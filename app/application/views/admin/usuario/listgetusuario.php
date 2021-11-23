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
                <!-- <h4 class="page-title float-left">Usuario General</h4> -->
                <ol class="breadcrumb float-right">
                  <li class="breadcrumb-item"><a href="#">Gestion</a></li>
                  <li class="breadcrumb-item"><a href="#">Usuario</a></li>
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
              <div class="card-header bg-primary"><h3 class="my-0 text-white">Gestion de usuario<a data-toggle="modal" data-target="#ModalAgregarUsuario" class="btn btn-pink btn-rounded  w-md waves-effect float-right" ><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
                <div class="card-body"> 
                  <ol class="breadcrumb">
                    <li><a href="<?= base_url('administrador/regusuario') ?>"><i class="ion ion-ios-refresh"></i> Actualizar</a></li>

                  </ol>
                  <fieldset>
                  <legend>Filtro</legend>
                  <form id="UsuarioFormBusqueda" autocomplete="off">          
                    <div class="form-row">

                      <div class="form-group col-md-3">
                        <label class="col-form-label">Fecha inicio:</label>
                        <div>
                          <div class="input-group">
                            <input type="date" class="form-control" name="desde" >
                            <div class="input-group-append">
                              <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                          </div><!-- input-group -->
                        </div>
                      </div>

                      <div class="form-group col-md-3">
                        <label class="col-form-label">Fecha fin:</label>
                        <div>
                          <div class="input-group">
                            <input type="date" class="form-control" name="hasta">
                            <div class="input-group-append">
                              <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                          </div><!-- input-group -->
                        </div>
                      </div>

                      <div class="form-group col-md-2">
                        <label  class="col-form-label">Grupo:</label>
                        <select class="form-control  select2 select2-hidden-accessible" name="tb_grupo" >
                          <option value="">--Todos--</option>
                          <?php foreach ($grupos as $g): ?>
                            <option value="<?= $g->cod_grupo ?>"><?= $g->nombre_grupo ?></option>
                          <?php endforeach ?>
                        </select>
                      </div> 

                      <div class="form-group col-md-4">

                       <label class="col-form-label " >Buscar por usuario:</label>

                       <div class="input-group">
                        <input type="text"  name="tb_usuario" class="form-control">
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-effect-ripple btn-primary"><i class="fa fa-search"></i></button>
                        </span>
                      </div>
                    </div>


                  </div>

                </form>
                  </fieldset>
                <!-- End #wizard-vertical -->
              <!-- </div>
            </div>
          </div> -->

        </div>
        
        
        <!-- Vertical Steps Example -->
        <!-- <div class="row">
          <div class="col-sm-12">
            <div class="card"> -->
              <div class="card-body table-responsive">



                <table id="TableMantenimientoUsuario" class="table  table-striped" cellspacing="0" width="100%">
                  <div class="row">
                    <div class="col-sm-12 col-md-6">
                      <div class="dt-buttons btn-group" data-toggle="modal" data-target="#ModalAgregarUsuario">
                        <a class="btn btn-secondary" tabindex="0" aria-controls="datatable-buttons"><span>Agregar</span></a>

                      </div>
                      <a class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="datatable-buttons" href=""><span>Excel</span></a>
                      <a class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="datatable-buttons" href=""><span>PDF</span></a>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <div id="datatable-buttons_filter" class="dataTables_filter"></div>
                    </div>
                  </div>
                  <br>
                  <thead>
                    <tr class="bg-primary text-white">
                      <th style="text-aling:center;">ID</th>
                      <th  style="text-aling:center;">Apellidos</th>
                      <th  style="text-aling:center;">Usuario</th>
                      <th  style="text-aling:center;">Fecha Nacimiento</th>
                      <th  style="text-aling:center;">Fecha visita</th>
                      <th  style="text-aling:center;">Grupo</th>
                      <th  style="text-aling:center;">Perfil</th>
                      <th  style="text-aling:center;">Estado</th>
                      <th  style="text-aling:center;">Acciones</th>

                    </tr>
                  </thead>

                </table>
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


<div class="modal fade" id="ModalAsignarCajaDocumento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">Asignar Caja - Documento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <b>Usuario: <span id="NombreDeUsuario"></span></b>
            </div>
          </div>
        </div>
        <form id="FormAgregarCajaDocumento" action="<?= base_url('administrador/regusuario/agregarCajaDocumento') ?>" method="post" style="display: none" autocomplete="off">
          <input type="hidden" name="usuario">
          <input type="hidden" name="punto">
          <fieldset>
            <legend>Agregar Caja - Documento</legend>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Caja</label>
                  <select name="caja" class="form-control">
                  
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Documento</label>
                  <select name="documento" class="form-control">
                  
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Serie</label>
                  <input type="text" name="serie" class="form-control" readonly></option>
                </div>
              </div>
                <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Formato</label>
                  <select type="text" name="formato" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="A4">A4</option>
                    <option value="T">Ticket</option>
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
              <button id="ButtonAgregarCajaDocumento" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Agregar</button>
            </div>
          </div>
        </div>
        <div class="row">          
          <div class="col-md-12">
            <table id="TableAsignarCajaDocumento" class="table table-bordered">
              <thead>
                <tr class="bg-primary text-white">
                  <th>Caja</th>
                  <th>Documento</th>
                  <th>Serie</th>
                  <th>Formato</th>
                  <th>Accion</th>
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


<div id="ModalAgregarUsuario" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
 <div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <form id="FormUsuario" action="<?= base_url('administrador/regusuario/agregarUsuario') ?>" method="post" autocomplete="off">
      <input type="hidden" > 
      <div class="modal-header bg-primary">
        <h4 class="custom-modal text-white">Agregar usuario</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Apellidos:</label>
              <input type="text" name="apellido" class="form-control">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Nombres:</label>
              <input type="text" name="nombre" class="form-control ">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">F: nacimiento:</label>
              <input type="date" name="fnacimiento" class="form-control">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Direccion:</label>
              <input type="text" name="direccion" class="form-control">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Telefono:</label>
              <input type="text" name="telefono" class="form-control ">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Documento:</label>
              <input type="text" name="documento" class="form-control ">
            </div>
          </div>

          <div class="col-md-5">
            <div class="form-group">
              <label class="control-label">Email:<span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control " >

            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Login:</label>
              <input type="text" name="login" class="form-control ">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Contraseña:</label>
              <input  type="password" name="passwoord" class="form-control ">
            </div>
          </div>
          

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Grupo:</label>
              <select class="form-control select2 select2-hidden-accessible" name="grupo" >
               <option value="">--Selecciona--</option>
               <?php foreach ($grupos as $gru): ?>
                <option value="<?= $gru->cod_grupo ?>"><?= $gru->nombre_grupo ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Perfil:</label>
            <select class="form-control select2 select2-hidden-accessible" name="perfil" >
             <option value="">--Selecciona--</option>
             <?php foreach ($perfiles as $perf): ?>
              <option value="<?= $perf->cod_perfil ?>"><?= $perf->nomb_perfil ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
         <label class="control-label">Estado: *</label>
         <select class="form-control select2 select2-hidden-accessible" required="" name="estado" >
          <option value="1">Activado</option>
          <option value="2">Inactivo</option>
        </select>
      </div>
    </div>









  </div> 
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
  <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
</div>
</form>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->








<div id="ModalEditarUsuario" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
 <div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <form id="FormEditarUsuario" action="<?= base_url('administrador/regusuario/editUsuario') ?>" method="post" autocomplete="off">
      <input type="hidden" name="id" > 
      <div class="modal-header bg-primary">
        <h4 class="custom-modal text-white"  >Editar usuario</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Apellidos:</label>
              <input type="text" name="apellido" class="form-control ">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Nombres:</label>
              <input type="text" name="nombre" class="form-control ">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">F: nacimiento:</label>
              <input type="date" name="fnacimiento" class="form-control">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Direccion:</label>
              <input type="text" name="direccion" class="form-control ">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Telefono:</label>
              <input type="text" name="telefono" class="form-control ">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Documento:</label>
              <input type="text" name="documento" class="form-control ">
            </div>
          </div>

          <div class="col-md-5">
            <div class="form-group">
              <label class="control-label">Email:<span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control " >

            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Login:</label>
              <input type="text" name="login" class="form-control ">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label" for="cambiarPassword">Contraseña:</label>
              <input  type="password" name="passwoord" class="form-control " disabled>
              <input type="checkbox" id="cambiarPassword">
              Cambiar Password
            </div>


          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Punto de venta:</label>
              <select class="form-control" name="punto" >
               <?php foreach ($puntos as $p): ?>
                <option value="<?= $p->cod_puntoventa ?>"><?= $p->nomb_puntoventa ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Grupo:</label>
            <select class="form-control select" name="grupo" >

             <?php foreach ($grupos as $gru): ?>
              <option value="<?= $gru->cod_grupo ?>"><?= $gru->nombre_grupo ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <label class="control-label">Perfil:</label>
          <select class="form-control select" name="perfil" >

           <?php foreach ($perfiles as $perf): ?>
            <option value="<?= $perf->cod_perfil ?>"><?= $perf->nomb_perfil ?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
       <label class="control-label">Fecha:</label>
       <input type="input" name="fecharegistro" class="form-control datepicker" disabled=true>
     </div>
   </div>


   <div class="col-md-3">
    <div class="form-group">
      <label  class="control-label">Estado</label>
      <select class="form-control select select2 select2-hidden-accessible" name="estado" >
       <option value="1" >Activo</option>
       <option value="2" >Desactivado</option>

     </select>
   </div>
 </div> 




</div> 
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
  <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
</div>
</form>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->