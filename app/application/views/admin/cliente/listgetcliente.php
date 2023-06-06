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
              <!-- <h4 class="page-title float-left"><i class="fas fa-user-tie" aria-hidden="true"></i> Clientes</h4> -->
              <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="#">  Gestion</a></li>
                <li class="breadcrumb-item"><a href="#">Clientes</a></li>
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
            <div class="card-header bg-primary"><h3 class="my-0 text-white">Gestion de clientes</h3></div>
              <div class="card-body">             
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormClienteBuscar" action="" method="post" autocomplete="off">
                    <div class="row">
                   
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Tipo:</label>
                          <select name="tipo" class="form-control select2">
                            <option value="">--Todos--</option>
                            <option value="1">Ruc</option>
                            <option value="2">DNI</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <label class="control-label">Buscar por nombre:</label>
                          <input type="text" name="nombre" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Estado</label>
                          <select name="estado" class="form-control select2">
                            <option value="1">Activo</option>
                            <option value="2">Anulado</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button  class="btn btn-success waves-effect waves-light" style="margin-top: 29px"><i class="fa fa-search"></i> Buscar</button>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>
                <div class="row">
                  <div class="col-md-12">
                     <button data-toggle="modal" data-target="#ModalAgregarCliente" type="button" class="btn btn-pink"><i class="fa fa-plus"></i>  Agregar</button>
                    <a id="ComprasReportePdf" href="#" class="btn btn-info disabled" target="_blank">PDF</a>
                    <a id="ComprasReporteExcel" href="#" class="btn btn-warning disabled" target="_blank">EXCEL</a>
                    <button data-toggle="modal" data-target="#ModalCumpleanos" type="button"  class="btn btn-purple" ><i class="fa fa-calendar"></i> Cumpleaños</button>
                  </div>
                </div>
                <br>
                <div class="table-responsive">
                  <table id="TableListarClientes" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-primary text-white">
                       
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>F.Nacimiento</th>
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


<div class="modal bs-example-modal-lg" id="ModalAgregarCliente"  data-refresh="true" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="myLargeModalLabel">Cliente - Agregar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target=".bs-example-modal-lg">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormAgregarCliente" action="<?= base_url('administrador/regcliente/agregarCliente') ?>" autocomplete="off" method="post">
        <div class="modal-body">
            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Tipo:</label>
                    <select name="tipo" class="form-control select2">
                            <option value="">SELECCIONAR</option>
                            <option value="4">RUC</option>
                            <option value="2">DNI</option>
                   </select>
                </div>

              </div>
               <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Ruc ó Dni:</label>
                  <input type="text" name="documento" class="form-control" maxlength="11" minlength="8" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Nombre o Razon Social</label>
                  <input type="text" name="nombre" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Fecha de nacimiento</label>
                  <input type="date" name="fnacimiento" class="form-control">
                </div>
              </div>             
							<div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Precio Venta:</label>
                  <select name="precio_venta" class="form-control select2">
										<option value="Normal">Precio Normal</option>
										<option value="Mayor">Precio x Mayor</option>
										<option value="Especial">Precio Especial</option>
									</select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Teléfono</label>
                  <input type="text" name="telefono" class="form-control" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
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


<div class="modal bs-example-modal-lg" id="ModalEditarCliente" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="myLargeModalLabel">Proveedor - Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormEditarCliente" action="<?= base_url('administrador/regcliente/editarCliente') ?>" autocomplete="off" method="post">
        <input type="hidden" name="id">
        <div class="modal-body">
            <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Tipo:</label>
                    <select name="tipo" class="form-control select2">
                                 <option value="4" >RUC</option>
                                  <option value="2" >DNI</option>
                   </select>
                </div>

            </div>
               <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Ruc ó Dni:</label>
                  <input type="text" name="documento" class="form-control" maxlength="11" minlength="8" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Nombre o Razon Social</label>
                  <input type="text" name="nombre" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Fecha de nacimiento</label>
                  <input type="date" name="fnacimiento" class="form-control">
                </div>
              </div> 
							<div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Precio Venta:</label>
                  <select name="precio_venta" class="form-control slect2">
										<option value="Normal">Precio Normal</option>
										<option value="Mayor">Precio x Mayor</option>
										<option value="Especial">Precio Especial</option>
									</select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Teléfono
                    <span class="text-danger">*</span>
                  </label>
                  <input type="text" name="telefono" class="form-control" placeholder="ingresar tu numero es obligatorio" required="">
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
                  <input type="email" placeholder="micorreo@bfacturas.com" name="email" class="form-control" required="">
                </div>
              </div>

             <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Estado:</label>
                    <select name="estado" class="form-control select2">
                                 <option value="1" >Activo</option>
                                  <option value="2">Inactivo</option>
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


<div id="ModalCumpleanos" class="modal bs-example-modal-lg" id="exampleModalCenter" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="FormCumpleanos" action="<?= base_url('administrador/regcliente/cumpleanos') ?>" method="post" autocomplete="off">
      <input type="hidden" name="empresa" value="<?= $tb_empresa->nombre_comercial ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-calendar"></i> Cumpleaños</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3 form-group">
              <label class="control-label">Buscar por:</label>
              <div>
              <label class="radio-inline">
                <input type="radio" name="tipo" id="radioHoy" class="radio-cumple" value="hoy" checked>Hoy
              </label>
              <label class="radio-inline">
                <input type="radio" name="tipo" id="radioMes" class="radio-cumple" value="mes">Mes
              </label>
              </div>
            </div>
            <div id="MesCumple" class="col-md-4 form-group" style="display:none">
              <label class="control-label">Mes:</label>
              <select name="mes" class="form-control select2" style="width: 100%">
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Setiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
              </select>
            </div>
            <div class="col-md-2">            
              <button style="margin-top:30px" type="submit" class="btn btn-info"><i class="fa  fa-search"></i> Buscar</button>
            </div>
          </div>
          <br>
          
          <div class="row">
            <div class="col-md-12 form-group">
              <a style="display:none" target="_blank" id="enviar-whatsapp">Enviar</a>
              <button type="button" class="btn btn-sm btn-warning button-emojis"><i class="fa fa-fw fa-smile-o"></i>emojis</button>
              <br>
                             <textarea name="mensaje" class="form-control textarea-emojis" rows="3"><?= $tb_empresa->cumpleano_clin?></textarea>
                          <!--  <textarea name="mensaje" class="form-control textarea-emojis" rows="3">Escriba un mensaje</textarea> -->
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <table id="TableCumpleanos" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr class="btn-primary btn-xs">
                    <th style="text-align: center;">Paciente</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Fecha</th>
                    <th style="background-color: #3c8dbc; color: white; text-align: center;">Cumple</th>
                    <th>Enviar</th>
                     <!-- <th style="background-color: #3c8dbc; color: white; text-align: center;">#</th> -->
                  </tr>
                </thead>
                <tbody></tbody>
                
              </table>
            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>

        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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

<script type="text/javascript">
  $(function(){
    new EmojiPicker({
              trigger: [
                      {	selector: '.button-emojis',
                          insertInto: '.textarea-emojis'
                      }
              ],
              closeButton: true
          });
  }) 
            

		</script>


