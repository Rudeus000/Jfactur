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
              <!-- <h4 class="page-title float-left">Punto de venta</h4> -->
              <ol class="breadcrumb float-right">
                <li class="breadcrumb-item"><a href="#">Gestion</a></li>
                <li class="breadcrumb-item"><a href="#">Sucursal</a></li>
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
            <div class="card-header bg-primary"><h3 class="my-0 text-white">Gestiona de suscursal o punto de venta<a data-toggle="modal" data-target="#ModalAgregarPunto" class="btn btn-pink btn-rounded  w-md waves-effect float-right" ><i class="fa fa-plus m-r-5"></i>Nuevo</a></h3></div>
              <div class="card-body"> 
              <fieldset>
                  <legend>Filtro</legend>
                <form id="PventaFormBusqueda" autocomplete="off">          
                  <div class="form-row">

                    <div class="form-group col-md-3">
                      <label  class="col-form-label">Almacen:</label>
                      <select class="form-control  select2 select2-hidden-accessible" name="tb_almacen" >
                        <option value="">--Todos--</option>
                        <?php foreach ($almacen as $a): ?>
                          <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>



                    <div class="form-group col-md-3">
                      <label  class="col-form-label">Sede:</label>
                      <select class="form-control  select2 select2-hidden-accessible" name="sede" >
                        <option value="">--Todos--</option>
                        <?php foreach ($sede as $s): ?>
                          <option value="<?= $s->cod_sede ?>"><?= $s->sede_nombre ?></option>
                        <?php endforeach ?>
                      </select>
                    </div> 

                    <div class="form-group col-md-6">

                     <label class="col-form-label " >Buscar por punto venta:</label>

                     <div class="input-group">
                      <input type="text"  name="tb_puntoventa" class="form-control">
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-effect-ripple btn-purple"><i class="fa fa-search"></i></button>
                      </span>
                    </div>
                  </span>

                </div>


              </div>  
            </form>
              </fieldset>
            <!-- End #wizard-vertical -->
          <!-- </div>
        </div>
      </div> -->

    </div><!-- End row -->  


    <!-- Vertical Steps Example
    <div class="row">
      <div class="col-sm-12">
        <div class="card"> -->
          <div class="card-body table-responsive">



            <table id="TableMantenimientoPventa" class="table  table-striped" cellspacing="0" width="100%">
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <div class="dt-buttons btn-group" data-toggle="modal" data-target="#ModalAgregarPunto">
                    <a class="btn btn-secondary" tabindex="0" aria-controls="datatable-buttons"><i style="color:#0099CC;" class="fas fa-user-plus"></i><span style="color:#0099CC;"> Agregar</span></a>

                  </div>
                  <div class="row">
                    <a class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="datatable-buttons" href=""><i
                      style="color:#00C851;" class="far fa-file-excel"></i><span style="color:#00C851;"> Excel</span></a>
                      <a class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="datatable-buttons" href=""><i style="color:#ff4444;"class="far fa-file-pdf"></i><span style="color:#ff4444;"> PDF</span></a>  
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <div id="datatable-buttons_filter" class="dataTables_filter"></div>
                  </div>
                </div>
                <br>
                <thead>
                  <tr class="bg-primary text-white">
                    <th></th>
                    <th  class="text-center">Nombre</th>

                    <th   class="text-center">Impresora</th>
                    <th   class="text-center">Sede</th>
                    <th   class="text-center">Estado</th>
                    <th   class="text-center">Por Defecto</th>
                    <th   class="text-center">Acciones</th>


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

<!-- Modal -->
<div class="modal fade" id="ModalAsignarAlmacen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">Asignar Almacenes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <b>Punto de Venta: <span id="NombreDePuntoVenta"></span></b>
            </div>
          </div>
        </div>
        <form id="FormAgregarAlmacenPuntoVenta" action="<?= base_url('administrador/regpventa/agregarAlmacen') ?>" method="post" style="display: none">
          <input type="hidden" name="puntoVenta">
          <fieldset>
            <legend>Agregar Almacen</legend>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Almacen</label>
                  <select name="almacen" class="form-control">
                    
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
              <button id="ButtonAgregarAlmacen" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Agregar</button>
            </div>
          </div>
        </div>
        <div class="row">          
          <div class="col-md-12">
            <table id="TableAsignarAlmacenPuntoVenta" class="table table-bordered">
              <thead>
                <tr>
                  <th>Almacen</th>
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


<!-- Modal -->
<div class="modal fade" id="ModalAsignarCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">Asignar Cajas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <b>Punto de Venta: <span id="NombreDePuntoVenta"></span></b>
            </div>
          </div>
        </div>
        <form id="FormAgregarCajaPuntoVenta" action="<?= base_url('administrador/regpventa/agregarCaja') ?>" method="post" style="display: none">
          <input type="hidden" name="puntoVenta">
          <fieldset>
            <legend>Agregar Caja</legend>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Caja</label>
                  <select name="caja" class="form-control">

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
              <button id="ButtonAgregarCaja" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Agregar</button>
            </div>
          </div>
        </div>
        <div class="row">          
          <div class="col-md-12">
            <table id="TableAsignarCajaPuntoVenta" class="table table-bordered">
              <thead>
                <tr>
                  <th>Caja</th>
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


<div id="ModalAgregarPunto" class="modal fade bs-example-modal-center" tabindex="" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
 <div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <form id="FormPuntoVenta" action="<?= base_url('administrador/regpventa/agregarPventa') ?>" method="post" autocomplete="off">
      <input type="hidden" > 
      <div class="modal-header bg-primary">
        <h4 class="custom-modal text-white"  >Información Punto de Venta</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Nombre del punto venta:<span class="text-danger"> *</span></label>
              <input type="text" name="punto" class="form-control">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Almacen:<span class="text-danger"> *</span></label>
              <select class="form-control select2 select2-hidden-accessible" name="almacen" >
               <option value="">--Selecciona--</option>
               <?php foreach ($almacen as $a): ?>
                <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>


        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Caja:<span class="text-danger"> *</span></label>
            <select class="form-control select2 select2-hidden-accessible" name="caja" >
             <option value="">--Selecciona--</option>
             <?php foreach ($caja as $c): ?>
              <option value="<?= $c->cod_caja ?>"><?= $c->nomb_caja ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label">Impresora:<span class="text-danger"> *</span></label>
          <select class="form-control select2 select2-hidden-accessible" name="impresora" >
           <option value="">--Selecciona--</option>
           <?php foreach ($impresora as $i): ?>
            <option value="<?= $i->cod_impresora ?>"><?= $i->nom_impresora ?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>
    <div class="col-md-4">
     <div class="form-group">
        <label class="control-label">Sede:<span class="text-danger"> *</span></label>
        <select class="form-control select2 select2-hidden-accessible" name="sede" >
          <option value="">--Selecciona--</option>
          <?php foreach ($sede as $s): ?>
            <option value="<?= $s->cod_sede ?>"><?= $s->sede_nombre ?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="control-label">Direccion</label>
        <input type="text" name="direccion" class="form-control">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="control-label">Ubigeo</label>
        <select name="ubigeo" class="form-control select2">
          <option value="">Seleccione</option>
          <?php foreach($ubigeos as $u): ?> 
          <option value="<?= $u->ubigeo ?>"><?= $u->departamento.' - '.$u->provincia.' - '.$u->distrito ?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label class="control-label">Telefono</label>
        <input type="text" name="telefono" class="form-control">
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label class="control-label">Email</label>
        <input type="text" name="email" class="form-control">
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label class="control-label">Código de Sunat</label>
        <input type="text" name="codigo" class="form-control">
      </div>
    </div>
  </div>  
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
  <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
</div>
</form>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->








<div id="ModalEditarPventa" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
 <div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <form id="FormEditarPventa" action="<?= base_url('administrador/regpventa/editPventa') ?>" method="post" autocomplete="off">
      <input type="hidden" name="id" > 
      <div class="modal-header bg-primary">
        <h4 class="custom-modal text-white"  >Editar Informacion Punto de Venta</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Nombre del punto venta:<span class="text-danger"> *</span></label>
              <input type="text" name="punto" class="form-control">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Almacen:<span class="text-danger"> *</span></label>
              <select class="form-control select" name="almacen" >

               <?php foreach ($almacen as $a): ?>
                <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>


        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Caja:<span class="text-danger"> *</span></label>
            <select class="form-control select" name="caja" >

             <?php foreach ($caja as $c): ?>
              <option value="<?= $c->cod_caja ?>"><?= $c->nomb_caja ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label">Impresora:<span class="text-danger"> *</span></label>
          <select class="form-control select" name="impresora" >

           <?php foreach ($impresora as $i): ?>
            <option value="<?= $i->cod_impresora ?>"><?= $i->nom_impresora ?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label class="control-label">Sede:<span class="text-danger"> *</span></label>
        <select class="form-control select" name="sede" >

         <?php foreach ($sede as $s): ?>
            <option value="<?= $s->cod_sede ?>"><?= $s->sede_nombre ?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label class="control-label">Direccion</label>
        <input type="text" name="direccion" class="form-control">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="control-label">Ubigeo</label>
        <select name="ubigeo" class="form-control select2">
          <option value="">Seleccione</option>
          <?php foreach($ubigeos as $u): ?> 
          <option value="<?= $u->ubigeo ?>"><?= $u->departamento.' - '.$u->provincia.' - '.$u->distrito ?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label class="control-label">Telefono</label>
        <input type="text" name="telefono" class="form-control">
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label class="control-label">Email</label>
        <input type="text" name="email" class="form-control">
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label class="control-label">Código de Sunat</label>
        <input type="text" name="codigo" class="form-control">
      </div>
    </div>

</div> 
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
  <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
</div>
</form>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->