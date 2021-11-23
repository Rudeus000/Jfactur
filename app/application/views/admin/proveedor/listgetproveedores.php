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
              <!-- <h4 class="page-title float-left"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i> Proveedores</h4> -->
              <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="#">Gestion</a></li>
                <li class="breadcrumb-item"><a href="#">proveedor</a></li>
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
            <div class="card-header bg-primary"><h3 class="my-0 text-white">Gestionar proveedor<a data-toggle="modal" data-target="#ModalAgregarProveedor" class="btn btn-pink btn-rounded  w-md waves-effect float-right" ><i class="fa fa-plus m-r-5"></i>Nuevo</a></h3></div>
              <div class="card-body">
              
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormProveedorBuscar" action="" method="post" autocomplete="off">
                    <div class="row">
                   
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Tipo:</label>
                          <select name="tipo" class="form-control">
                            <option value="">--Todos--</option>
                            <option value="1">Ruc</option>
                            <option value="2">DNI</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <label class="control-label">Proveedor</label>
                          <input type="text" name="nombre" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Estado</label>
                          <select name="estado" class="form-control">
                            <option value="1">Activo</option>
                            <option value="2">Anulado</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button  class="btn btn-purple waves-effect waves-light" style="margin-top: 29px"><i class="fa fa-search"></i> Buscar</button>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>
                <div class="row">
                  <div class="col-md-12">
                     <button data-toggle="modal" data-target="#ModalAgregarProveedor" type="button" class="btn btn-pink"><i class="fa fa-plus"></i>  Agregar</button>
                    <a id="ProveedorReportePdf" href="#" class="btn btn-info" target="_blank">PDF</a>
                    <a id="ProveedorReporteExcel" href="#" class="btn btn-warning" target="_blank">EXCEL</a>
                  </div>
                </div>
                <br>
                <div class="table-responsive">
                  <table id="TableListarProveedor" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-primary text-white">
                       
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Proveedor</th>
                         <th>Ruc/Dni</th>
                         <th>Dirección</th>
                         <th>Contacto</th>
                         <th>Telefono</th>
                         <th>Email</th>
                         <th>Estado</th>
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


<div class="modal fade bs-example-modal-lg" id="ModalAgregarProveedor"  data-refresh="true" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="myLargeModalLabel">Proveedor - Agregar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target=".bs-example-modal-lg">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormAgregarProveedor" action="<?= base_url('administrador/regproveedor/agregarProveedor') ?>" autocomplete="off" method="post">
        <div id="capa_load"></div>
        <div class="modal-body">
            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Tipo:</label>
                    <select name="tipo" id="tipo_documento" class="form-control select2">                      
                            <option value="">SELECCIONAR</option>
                            <option value="4">RUC</option>
                            <option value="2">DNI</option>
                   </select>
                </div>
              </div>
              <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Ruc ó Dni:</label>
                <div class="input-group">
                <input type="text" id="txt_documento" name="documento" class="form-control" maxlength="11" minlength="8" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                <div class="input-group-append">
                  <button class="btn btn-purple waves-effect waves-light" type="button"  onclick="buscar();">
                    <i class="fa fa-search"></i>
                  </button>                                                           
                 </div>
               </div>
              </div>          
            </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Nombre o razon social</label>
                  <input type="text" id="txt_nombre" name="nombre" class="form-control"    onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
              </div>            
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Teléfono</label>
                  <input type="text" id="txt_telefono" name="telefono" class="form-control" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Dirección</label>
                  <input type="text" id="txt_direccion" name="direccion" class="form-control">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Contacto</label>
                  <input type="text" name="contacto" class="form-control">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Email</label>
                  <input type="email" name="email" class="form-control">
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-pink" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade bs-example-modal-lg" id="ModalEditarProveedor" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="myLargeModalLabel">Proveedor - Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormEditarProveedor" action="<?= base_url('administrador/regproveedor/editarProveedor') ?>" autocomplete="off" method="post">
        <input type="hidden" name="id">
        <div class="modal-body">
            <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Tipo:</label>
                    <select name="tipo" class="form-control select">
                                 <option value="1" <?php echo set_value('tipo',$proveedor->tb_proveedor_tip)==1? "selected" : "" ?>>Ruc</option>
                                  <option value="2" <?php echo set_value('tipo',$proveedor->tb_proveedor_tip)==2 ? "selected" : "" ?>>Dni</option>
                   </select>
                </div>

            </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Nombre</label>
                  <input type="text" name="nombre" class="form-control">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Documento</label>
                  <input type="text" name="documento" class="form-control">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Teléfono</label>
                  <input type="text" name="telefono" class="form-control">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Dirección</label>
                  <input type="text" name="direccion" class="form-control">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Contacto</label>
                  <input type="text" name="contacto" class="form-control">
                </div>
              </div>

              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Email</label>
                  <input type="email" name="email" class="form-control">
                </div>
              </div>

             <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Estado:</label>
                    <select name="estado" class="form-control select">
                                 <option value="1" <?php echo set_value('estado',$proveedor->tb_proveedor_xac)==1? "selected" : "" ?>>Activo</option>
                                  <option value="2" <?php echo set_value('estado',$proveedor->tb_proveedor_xac)==2 ? "selected" : "" ?>>Inactivo</option>
                   </select>
                </div>

            </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-pink" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
    function soloLetras(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key).toLowerCase();
       letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }

        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }


</script>


