     
   
<div id="wrapper" data-stockminimos="1">
 <div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">
          <div class="page-title-box">
            <!-- <h4 class="page-title float-left"><i class="fas fa-chalkboard-teacher"></i> Agregar Ventas</h4> -->
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">
                <?= dia() ?> - <?= date('d/m/Y') ?> Cajero: <?= $this->session->userdata('nomb_usu').' '.$this->session->userdata('apell_usu') ?>
                <br>
                <?php if ($apertura!=false): ?>
                <?= $apertura->nomb_caja.' '.$apertura->horainicio_apertura.' - '.$apertura->horafin_apertura ?>
                <?php endif ?>
              </li>

            </ol>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
         <div class="card">
         <div class="card-header bg-success"><h3 class="my-0 text-white">Realizar ventas</h3></div>
           <div class="card-body">
            <form id="FormVentaAgregar" class="FormVenta" action="<?= base_url('administrador/regventas/agregarVenta') ?>" autocomplete="off">
              <input type="hidden" name="cliente">
              <input type="hidden" name="total">
              
              <?php if ($apertura==FALSE): ?>
              <div class="row">
                <div class="col-md-12">
                  <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle m-r-5 float-right fa-2x"></i>Debes aperturar una caja.
                  </div>
                </div>
              </div>
              <?php endif ?>

              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-body">

                          <fieldset>
                            <legend>Principal</legend>
                            <div class="row">
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Fecha</label>
                                  <input id="fechav" type="text" name="fecha" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Documento</label>
                                  <select name="tipoPedido" class="form-control select2 select2-hidden-accessible input-sm">
                                    <option value="">Seleccion</option>
                                    <?php foreach ($tipos as $t): ?>
                                      <option value="<?= $t->cod_talonario?>" data-dni="<?= $t->docclidni_talonario ?>" data-ruc="<?= $t->doccliruc_talonario ?>"><?= $t->nom_tipdocumento.' - '.$t->serie ?></option>
                                    <?php endforeach ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Serie</label>
                                  <input type="text" name="serie" class="form-control" readonly >
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Correlativo</label>
                                  <input type="text" name="correlativo" class="form-control" readonly>
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Punto de venta</label>
                                  <input type="text" name="puntoVenta" class="form-control" value="<?= $punto->nomb_puntoventa ??'' ?>" readonly>
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Almacen</label>
                                  <select name="almacen" class="form-control">
                                    <?php foreach ($almacenes as $a): ?>
                                    <option value="<?= $a->cod_almacen ?>" <?= ($a->pordefecto==1)?'selected':'' ?>><?= $a->nomb_almacen ?></option>
                                    <?php endforeach ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </fieldset>

                          <fieldset>
                            <legend>Cliente</legend>
                            <div class="row">
                              <div class="col-md-2">
                                <label class="control-label" style="display: block"></label><br>
                                <!-- <div class="btn-group"> -->
                                  <button data-toggle="modal" data-target="#ModalAgregarCliente" type="button" class="btn btn-rounded btn-success waves-effect "><i class="fas fa-user-alt m-r-5"></i>Agregar</button>
                                  <button id="VentaEditarCliente" type="button" class="btn btn-rounded btn-warning waves-effect "><i class="fas fa-user-edit m-r-5"></i>Editar</button>
                                <!-- </div> -->
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">RUC/DNI</label>
                                  <input type="text" id="RUCAutocomplete" name="rucdni" class="form-control" readonly disabled>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label class="control-label">Cliente</label>
                                  <input type="text" id="ClienteVentaAutocomplete" name="nombreCliente" class="form-control" disabled>
                                </div>
                              </div>                              
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Precio</label>
                                  <input type="text" id="precioCliente" name="precioCliente" class="form-control" readonly>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label class="control-label">Dirección</label>
                                  <input type="text" id="DireccionCliente" class="form-control" readonly>
                                </div>
                              </div>
                            </div>
                          </fieldset>

                          <fieldset>
                            <legend>Pagos</legend>
                            <div class="row">
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Moneda</label>
                                  <select name="moneda" class="form-control">
                                    <option value="S" data-valor="1">Soles</option>
                                    <option value="D" data-valor="<?= $dolar->valor_paramt ?>">Dolares</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Tipo cambio</label>
                                  <input type="text" name="tipoCambio" class="form-control" value="1" readonly>
                                </div>
                              </div>
                              
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Caja</label>
                                  <input type="text" class="form-control" value="<?= $apertura->nomb_caja  ??''?>" readonly>
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Pago</label>
                                  <select name="pago" class="form-control">
                                    <option value="CO">Contado</option>
                                    <option value="CRE">Crédito</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label class="control-label">Monto</label>
                                  <input type="text" name="monto" class="form-control" value="0" required readonly>
                                </div>
                              </div>
                               <div class="col-md-2"> 
                               <div class="form-group">                     
                                <button id="DeudaCliente" style="margin-top: 32px" data-toggle="modal" data-target="#ModalDeudaCliente" type="button" class="btn btn-rounded btn-pink waves-effect"><i class="fas fa-eye m-r-5"></i>Deuda</button>               
                              </div>
                             </div>
                              <div id="pagocredito" class="col-md-2" style="display: none">
                                <div class="form-group">
                                  <label class="control-label">Dias / Cuotas</label>
                                  <div>
                                    <input type="checkbox" data-plugin="switchery" data-color="#ff5d48" data-size="small" id="switch-dias-cuotas" name="dias_cuotas"/>
                                  </div>
                                </div>
                              </div>

                              
                              <div class="col-md-2 pagocredito-cuotas form-group" style="display: none">
                                <label class="control-label">Periodo</label>
                                <select name="periodo" class="form-control">
                                  <option value="Mensual">Mensual</option>
                                  <option value="Quincenal">Quincenal</option>
                                  <option value="Semanal">Semanal</option>
                                </select>
                              </div>
                              <div class="col-md-2 pagocredito-cuotas form-group" style="display: none">
                                <label class="control-label">N° Cuotas</label>
                                <input type="number" min="1" name="numero_cuotas" class="form-control" value="2">
                              </div>
                              <div class="col-md-2 pagocredito-cuotas form-group" style="display: none">
                                <button id="calcular-cuotas" style="margin-top:27px" type="button" class="btn btn-md btn-primary">Calcular</button>
                              </div>


                              <div class="col-md-2 pagocredito-dias form-group" style="display: none">
                                <label class="control-label">Dias</label>
                                <input type="number" min="1" disabled name="dias" class="form-control">
                              </div>                   
                              <div class="col-md-2 pagocredito-dias" style="display: none">
                                <div class="form-group">
                                  <label class="control-label">Fecha</label>
                                  <input type="text" name="fecVenc" class="form-control" readonly>
                                </div>
                              </div>
                              <div class="col-md-2 pagocredito-dias" style="display: none">
                                <div class="form-group">
                                  <label class="control-label">Saldo</label>
                                  <input type="text" name="saldo" value="0" disabled class="form-control" readonly>
                                </div>
                              </div>
                            </div>

                            <div class="row" id="TableCuotasContent" style="display:none">
                              <div class="col-md-12">
                                <div style="display:none" class="alert alert-danger cuotas-error" role="alert"></div>
                              </div>
                              <div class="col-md-12">
                                <table id="TableCuotas" class="table table-bordered">
                                  <thead>
                                    <tr>
                                      <th>Fecha</th>
                                      <th>Monto</th>
                                    </tr>
                                  </thead>
                                  <tbody></tbody>
                                  <tfoot>
                                    <tr>
                                      <th>Total</th>
                                      <td id="total-cuotas"></td>
                                    </tr>
                                  </tfoot>
                                </table>
                              </div>
                              <div class="col-md-12">
                                <div style="display:none" class="alert alert-danger cuotas-error" role="alert"></div>
                              </div>
                            </div>
                          </fieldset>
                          <!-- End #wizard-vertical -->
                        </div>
                      </div>
                    </div>              
                  </div>
                </div>
              </div>
            </form>
            <form id="FormVentaAgregarProducto" autocomplete="off">
              <input type="hidden" name="producto">
              <input type="hidden" name="idTypeAssignmentProduct">
              <fieldset>
								<legend>Agregar Producto</legend>
								<div class="row">
									<div class="col-md-6">
										<!-- <div class="switchery-demo"> -->
											<!-- <input type="checkbox" class="custom-control-input" > -->                      
                      <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" data-size="small" id="servicioCheck" name="servicioCheck"/>
											<label for="servicioCheck">Servicio</label>
										<!-- </div> -->
									</div>
                  <div class="col-md-6">
										<!-- <div class="switchery-demo"> -->
											<!-- <input type="checkbox" class="custom-control-input" > -->                      
                      <input type="checkbox" data-plugin="switchery" data-color="#9261c6" data-size="small" id="observacionCheck" name="observacionCheck"/>
											<label for="servicioChecked">Observaciones</label>
										<!-- </div> -->
									</div>
								</div>                
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
											<label class="control-label">Nombre</label>
											<textarea name="nombreProducto" id="nombre-servicio" class="form-control" placeholder="Ingrese descripcion del servicio" rows="5" disabled style="display:none"></textarea>
                      <input type="text" id="VentaProductoAutocomplete" name="nombreProducto" class="form-control" placeholder="Ingrese el nombre del producto">
                    </div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2" id="unidad_p">
                    <div class="form-group"  >
                      <label class="control-label">Unidad Med.</label>
                      <input type="text" name="unidadProducto" class="form-control">
                    </div>
									</div>
									<div class="col-md-1"  id="peso_p">
                    <div class="form-group">
                      <label class="control-label">Peso</label>
                      <input type="text" name="pesoProducto" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      <label class="control-label" id="precio_u">Precio Unit.</label>
                      <label class="control-label" disabled style="display:none" id="monto_s">Monto.</label>
                      <input type="text" name="precioProducto" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      <label class="control-label">Dscto.</label>
                      <input type="text" name="descuentoProducto" class="form-control">
                    </div>
									</div>
								
									<div class="col-md-6" id="serie_c" >
                    <div class="form-group" >
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="serieChek" name="serieCheckProducto" disabled>
                        <label class="custom-control-label" for="serieChek">Series</label>
                      </div>
                        <select id="select2-series" class="form-control selct2"  name="seriesProducto[]" multiple="multiple" disabled>
                        </select>
                    </div>
									</div>
									<div class="col-md-2">
                    <div class="form-group">
                      <label class="control-label">Cantidad</label>
                      <input type="text" name="cantidadProducto" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="control-label">Tipo</label>
                      <select name="tipo" class="form-control">
                        <option value="V">Venta</option>
                        <option value="B">Bonificacion</option>
                        <option value="O">Obsequio</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2" id="isdn_product" style="<?= ($this->session->userdata('movil_expert')==1?'display:none':'')?>">
                    <div class="form-group">
                      <label class="control-label">Numero ISDN</label>
                      <input type="text" name="numeroisdn" class="form-control" id="producto_isdn">
                    </div>
                  </div>
                  <div class="col-md-1">
                    <button type="submit" style="margin-top: 32px" class="btn btn-sm btn-pink"><i class="fa fa-plus"></i></button>
                  </div>
								</div>
              </fieldset>
              <div class="table-responsive">
              <table id="TableVentaProductos" class="table table-striped table-hover">
                <thead>
                  <tr class="bg-success text-white">
                    <th></th>
                    <th style="text-align: center;">Código</th>
                    <th style="text-align: center;">Artículo</th>
                    <th style="text-align: center;<?= ($this->session->userdata('movil_expert')==1?'display:none':'')?>">ISDN</th>
                    <th style="text-align: center;">Marca</th>
                    <th style="text-align: center;">Unidad</th>
                    <th style="text-align: center;">Cant.</th>
                    <th style="text-align: center;">P. Unit.</th>
                    <th style="text-align: center;">Desc.</th>
                    <th style="text-align: center;">IGV</th>
                    <th style="text-align: center;">Prec. Sin IGV</th>
                    <th style="text-align: center;">Subtotal</th>
                    <th style="text-align: center;">Opc.</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="10"></td>
                    <th class="bg-danger text-white">Valor Venta</th>
                    <td class="bg-danger text-white" id="VentaValorVenta">00.00</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="10"></td>
                    <th class="bg-danger text-white">IGV</th>
                    <td class="bg-danger text-white" id="VentaIGV">00.00</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="10"></td>
                    <th class="bg-danger text-white">Total</th>
                    <td class="bg-danger text-white" id="VentaTotal">00.00</td>
                    <td></td>
                  </tr>
                </tfoot>
              </table> 
            </div>
         
              <fieldset class="scheduler-border" >
                    <legend class="scheduler-border">Forma de Pago</legend>
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Metodo de Pago</label>
                          <select name="tipoPago" class="form-control input-sm">
                            <?php foreach ($tipos_pagos as $t): ?>
                              <option value="<?= $t->cod_tipopago ?>"><?= $t->nom_tipopago ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                   
                  
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Tipo de Tarjeta</label>
                          <select name="tipoTarjeta" class="form-control" disabled>
                            <option value=""></option>
                            <?php foreach ($tipos_tarjetas as $t): ?>
                              <option value="<?= $t->cod_tarj ?>"><?= $t->nomb_tarj?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>             
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">N° Operación</label>
                            <input type="text" name="operacion" class="form-control" disabled>
                        </div>
                      </div>

                       <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">% Descuento</label>
                          <input type="number" min="0" max="99" name="descuento" class="form-control" value="0">
                        </div>
                      </div>                      

                    
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Monto Recibido</label>
                          <input type="text" name="montoRecibido" class="form-control" value="0.00">
                        </div>
                      </div>                                           
                    
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Vuelto</label>
                          <input type="text" name="vuelto" class="form-control"  value="0.00" readonly>
                        </div>
                      </div>
                      <div class="col-md-12" style="display: none" id="observacion-a">
                        <div class="form-group">
                        <label for="exampleFormControlTextarea1">Observación</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="observacion" rows="3"></textarea>
                      </div>
                    </div>                     
                   </div>
                  </fieldset>              
            </form> 

            <legend class="scheduler-border"></legend>

            <div class="row" id="VentasContenedorGuardar">
              <div class="col-md-12">
                <div class="form-group">                  
                  <a href="<?= base_url('administrador/regventas') ?>" class="btn btn-pink btn-rounded"><i class="fas fa-times"></i> Cerrar</a>
                  <button type="submit" form="FormVentaAgregar" class="btn btn-success btn-rounded" <?= ($apertura==false)?'disabled':'' ?>><i class="fa fa-save m-r-5"></i>Guardar</button>
                </div>
              </div>
            </div>
         
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>


<div class="modal fade" id="ModalAccionesDespuesGuardar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">HAGA CLICK EN UNAS DE LAS OPCIONES</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body table-responsive">
									<div class="text-center">

                    <a href="<?= base_url('administrador/regventas') ?>" class="btn btn-info btn-rounded w-md waves-effect waves-light">Volver al panel</a>
                    <a id="VentaImprimirA4" class="btn btn-inverse btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir A4</a>
                    <a id="VentaImprimirTicket" class="btn btn-purple btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir Ticket</a>
                    <!-- <?php if($q->siglas_talonario=='TK'):?>
										<a href="<?= base_url('administrador/regventas') ?>" class="btn btn-info btn-rounded w-md waves-effect waves-light">Volver al panel</a>
                    <a id="VentaImprimirA4" class="btn btn-inverse btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir A4</a>
										<a id="VentaImprimirTicket" class="btn btn-purple btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir Ticket</a>
                    

                    <?php elseif($q->siglas_talonario=='FC'):?>
                    <a href="<?= base_url('administrador/regventas') ?>" class="btn btn-info btn-rounded w-md waves-effect waves-light">Volver al panel</a>
                    <a id="VentaImprimirA4" class="btn btn-inverse btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir A4</a>
                    <a id="VentaImprimirTicket" class="btn btn-purple btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir Ticket</a>

                    <?php endif ;?> -->
                    
									</div>
                </div>
              </div>
            </div>
          </div> <!-- end row -->
        </div>
    </div>
  </div>
</div>


<div class="modal fade" id="ModalDeudaCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title text-white" id="exampleModalLabel">Historial Deuda Clientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body table-responsive">
                  <h4 class="m-t-0 header-title mb-4"><b>Vista Previa</b></h4>

                  <table id="TableDeudaCliente" class="table table-bordered">
                    <thead>
                      <tr class="bg-success text-white">
                        <th style="text-align: center;">Cliente</th>
                        <th>DNI/RUC</th>
                        <th>Fecha venta</th>
                        <th>Venta</th>
                        <th>Saldo</th>
                        <th>Pagar</th>
                      </tr>
                    </thead>

                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div> <!-- end row -->


        </div>
    </div>
  </div>
</div>


<div class="modal fade" id="ModalAgregarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
  <div class="card-header bg-primary"><h3 class="my-0 text-white">Agregar cliente</h3></div>
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
                                                  <?php foreach($doc_clientes as $d): ?> 
                                                  <option value="<?= $d->cod_tipdocucli ?>"><?= $d->nom_tipdocucli ?></option>
                                                  <?php endforeach ?>
                                                </select>
                                              </div>
                                            </div>

                                            <div class="col-md-8">
                                              <div class="form-group">
                                                <label class="control-label">Ruc ó Dni:</label>
                                                <div class="input-group">
                                                <input type="text" id="txt_documento" name="documento" class="form-control" maxlength="11" minlength="8" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                                                <div class="input-group-append">
                                                  <button class="btn btn-info waves-effect waves-light" type="button"  onclick="buscar();">RENIEC-SUNAT
                                                    <i class="fa fa-search"></i>
                                                  </button>                                                           
                                                </div>
                                              </div>
                                              </div>            

                                            </div>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <label class="control-label">Nombre o Razon Social</label>
                                                <input type="text" id="txt_nombre" name="nombre" class="form-control">
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
                                            <div class="col-md-6" id="fnacimiento">
                                              <div class="form-group">
                                                <label class="control-label">F.nacimiento</label>
                                                <input type="date" name="fnacimiento" class="form-control" >
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


<div class="modal fade" id="ModalEditarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="card-header bg-success"><h3 class="my-0 text-white">Editar cliente</h3></div>
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cliente - Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
      <form id="FormVentaEditarCliente" action="<?= base_url('administrador/regventas/editarCliente') ?>" autocomplete="off" method="post">
        <input type="hidden" name="id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Tipo:</label>                
                <select name="tipo" class="form-control">
                  <option value="">Seleccione</option>
                  <?php foreach($doc_clientes as $d): ?> 
                  <option value="<?= $d->cod_tipdocucli ?>"><?= $d->nom_tipdocucli ?></option>
                  <?php endforeach ?>
                </select>
              </div>

            </div>

            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Nombre o Razon Social</label>
                <input type="text" name="nombre" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Ruc ó Dni:</label>
                <input type="text" name="documento" class="form-control" maxlength="11" minlength="8" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
              </div>
            </div>
            <div class="col-md-6" id="fnacimiento">
              <div class="form-group">
                <label class="control-label">F.nacimiento</label>
                <input type="date" name="fnacimiento" class="form-control" >
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
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>



<div id="ModalStockMinimos" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg">
  <div class="card-header bg-warning"><h3 class="my-0 text-white">Alerta de productos con stock mínimo <i class="spinner-grow text-pink float-right"></i></h3></div>
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h5 class="modal-title">Alerta de productos con stock mínimo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->      
      <div class="modal-body">
            <table id="TableStockMinimos" class="table table-hover table-striped tblstockminimo">
                <thead>
                    <tr class="bg-warning text-white">
                        <th>Almacen</th>
                        <th>Producto</th>
                        <th>Categoria</th>
                        <th>Unidades</th>
                        <th>P. Costo</th>
                        <th>Stock</th>
                        <th class="bg-danger">Mínimo</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-rounded" id="posponer-stockminimo"><span class="m-r-5">Posponer</span><i class="fas fa-undo"></i></button>
        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button> -->
      </div>
    </div>
  </div>
</div>