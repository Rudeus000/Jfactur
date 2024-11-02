<style>
  input[type=checkbox][readonly] {
    cursor: not-allowed;
  }
</style>
<input type="hidden" name="busqueda_general" value="<?= $busqueda_general ?>">
<div id="wrapper" data-stockminimos="1" data-vencimiento="1">
  <div class="content-page">
    <div class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="page-title-box">
              <!-- <h4 class="page-title float-left"><i class="fas fa-chalkboard-teacher"></i> Agregar Ventas</h4> -->
              <ol class="breadcrumb float-right">
                <li class="breadcrumb-item">
                  <?= dia() ?> - <?= date('d/m/Y') ?> Cajero:
                  <?= $this->session->userdata('nomb_usu') . ' ' . $this->session->userdata('apell_usu') ?>
                  <br>
                  <?php if ($apertura != false): ?>
                    <?= $apertura->nomb_caja . ' ' . $apertura->horainicio_apertura . ' - ' . $apertura->horafin_apertura ?>
                  <?php endif ?>
                </li>

              </ol>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header bg-success">
                <h3 class="my-0 text-white">Realizar ventas</h3>
              </div>
              <div class="card-body">
                <form id="FormVentaAgregar" class="FormVenta"
                  action="<?= base_url('administrador/regventas/agregarVenta') ?>" autocomplete="off">
                  <input type="hidden" name="cliente" value="<?= !is_null($cliente) ? $cliente->id : '' ?>">
                  <input type="hidden" name="total">
                  <input type="hidden" name="monto" value="0.00">
                  <input type="hidden" name="operacion">
                  <input type="hidden" name="monto_tb">
                  <input type="hidden" name="monto_efectivo">
                  <input type="hidden" name="descuento">
                  <input type="hidden" name="montoRecibido" value="0.00">
                  <input type="hidden" name="vuelto">
                  <input type="hidden" name="tipoPago" id="tipoPagoHidden">
                  <input type="hidden" name="tipoTarjeta" id="tipoTarjetaHidden">
                  <input type="hidden" name="pago" id="tipoPago">
                  <input type="hidden" name="dias_cuotas" id="dias_cuotas">
                  <input type="hidden" name="dias" value="1" disabled>
                  <input type="hidden" name="fecVenc">
                  <!-- <input type="hidden" name="saldo"> -->


                  <?php if ($apertura == FALSE): ?>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                          <i class="fas fa-exclamation-triangle m-r-5 float-right fa-2x"></i>Debes aperturar una caja.
                          <a href="<?= base_url('administrador/regcajaapertura') ?>"
                            class="btn btn-pink btn-bordered waves-effect w-md waves-light"><i
                              class="fas fa-inbox m-r-5"></i>Aperturar</a>
                        </div>
                      </div>
                    </div>
                  <?php endif ?>

                  <div class="row">
                    <div class="col-md-12">
                      <button type="button" class="btn btn-block btn-xs btn-success waves-effect waves-light"
                        id="btn_opciones">Opciones</button>
                      <div class="row">
                        <!-- <div class="col-md-12"> -->
                        <div id="content-opcion-datos" class="col-md-12" style="display:none">
                          <div class="card">
                            <div class="card-body">

                              <fieldset>
                                <legend>Principal</legend>
                                <div class="row">
                                  <div class="col-md-2">
                                    <div class="form-group">
                                      <label class="control-label">Fecha</label>
                                      <input id="fechav" type="text" name="fecha" class="form-control datepicker"
                                        value="<?= date('Y-m-d') ?>">
                                    </div>
                                  </div>
                                  <div class="col-md-2">
                                    <div class="form-group">
                                      <label class="control-label">Documento</label>
                                      <select name="tipoPedido"
                                        class="form-control select2 select2-hidden-accessible input-sm">
                                        <option value="">Seleccion</option>
                                        <?php foreach ($tipos as $t): ?>
                                          <option value="<?= $t->cod_talonario ?>"
                                            data-dni="<?= $t->docclidni_talonario ?>"
                                            data-ruc="<?= $t->doccliruc_talonario ?>"
                                            data-ex="<?= $t->doccliex_talonario ?>"
                                            data-pass="<?= $t->docclipass_talonario ?>"
                                            <?= $punto->talonario_defecto == $t->cod_talonario ? 'selected' : '' ?>>
                                            <?= $t->nom_tipdocumento . ' - ' . $t->serie ?>
                                          </option>
                                        <?php endforeach ?>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-2">
                                    <div class="form-group">
                                      <label class="control-label">Serie</label>
                                      <input type="text" name="serie" class="form-control" readonly>
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
                                      <input type="text" name="puntoVenta" class="form-control"
                                        value="<?= $punto->nomb_puntoventa ?? '' ?>" readonly>
                                    </div>
                                  </div>
                                  <div class="col-md-2">
                                    <div class="form-group">
                                      <label class="control-label">Almacen</label>
                                      <select name="almacen" class="form-control">
                                        <?php foreach ($almacenes as $a): ?>
                                          <option value="<?= $a->cod_almacen ?>" <?= ($a->pordefecto == 1) ? 'selected' : '' ?>><?= $a->nomb_almacen ?></option>
                                        <?php endforeach ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <div class="row">
                                <div class="col-md-8">
                                  <fieldset>
                                    <legend>Cliente</legend>
                                    <div class="row">


                                      <div class="col-md-2" hidden>
                                        <div class="form-group mb-1">
                                          <label>Tipo Doc.</label>
                                          <select name="tipo_documento" class="form-control input-sm" readonly>
                                            <option value="">Seleccione</option>
                                            <option value="DNI">DNI</option>
                                            <option value="RUC">RUC</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="col-md-2" hidden>
                                        <div class="form-group">
                                          <label class="control-label">RUC/DNI</label>
                                          <input type="text" id="RUCAutocomplete" name="rucdni" class="form-control"
                                            readonly disabled value="<?= !is_null($cliente) ? $cliente->ruc : '' ?>">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label class="control-label">Cliente [<a title="" data-toggle="modal"
                                              data-target="#ModalAgregarCliente" role="button" aria-haspopup="false"
                                              aria-expanded="false" data-original-title="Nuevo">
                                              <i
                                                class=" fas fa-user-plus noti-icon text-pink waves-light waves-effect"></i></a>
                                            ][<a title="" id="VentaEditarCliente" role="button" aria-haspopup="false"
                                              aria-expanded="false" data-original-title="Nuevo">
                                              <i
                                                class=" fas fa-user-edit noti-icon text-primary waves-light waves-effect"></i>
                                              ]
                                            </a></label>
                                          <input type="text" id="ClienteVentaAutocomplete" name="nombreCliente"
                                            class="form-control" disabled
                                            value="<?= !is_null($cliente) ? $cliente->nombre : '' ?>">
                                        </div>
                                      </div>



                                      <div class="col-md-2">
                                        <div class="form-group">
                                          <label class="control-label">Precio</label>
                                          <input type="text" id="precioCliente" name="precioCliente"
                                            class="form-control" readonly
                                            value="<?= !is_null($cliente) ? $cliente->precio_cliente : '' ?>">
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label class="control-label">Dirección</label>
                                          <input type="text" id="DireccionCliente" name="DireccionCliente"
                                            class="form-control" readonly
                                            value="<?= !is_null($cliente) ? $cliente->direccion : '' ?>">
                                        </div>
                                      </div>
                                      <div class="col-md-1" hidden>
                                        <div class="form-group">
                                          <button id="DeudaCliente" style="margin-top: 32px" data-toggle="modal"
                                            data-target="#ModalDeudaCliente" type="button"
                                            class="btn btn-danger waves-effect"><i
                                              class="fas fa-eye m-r-5"></i>Deuda</button>
                                        </div>
                                      </div>
                                    </div>

                                  </fieldset>
                                </div>
                                <div class="col-md-4">
                                  <fieldset>
                                    <legend>Moneda</legend>
                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label class="control-label">Moneda</label>
                                          <select name="moneda" class="form-control">
                                            <option value="S" data-valor="1">Soles</option>
                                            <option value="D" data-valor="<?= $dolar->valor_paramt ?>">Dolares</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label class="control-label">Tipo cambio</label>
                                          <input type="text" name="tipoCambio" class="form-control" value="1" readonly>
                                        </div>
                                      </div>

                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label class="control-label">Caja</label>
                                          <input type="text" class="form-control"
                                            value="<?= $apertura->nomb_caja ?? '' ?>" readonly>
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>
                              </div>
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

                      <div id="check_servicio"
                        class="col-md-3 <?= ($this->session->userdata('enable_serv') == '0' ? 'd-none' : '') ?>">
                        <!-- <div class="switchery-demo"> -->
                        <!-- <input type="checkbox" class="custom-control-input" > -->
                        <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" data-size="small"
                          id="servicioCheck" name="servicioCheck" />
                        <label for="servicioCheck">Servicio/producto</label>
                        <!-- </div> -->
                      </div>
                      <div class="col-md-3" style="display_none">
                        <!-- <div class="switchery-demo"> -->
                        <!-- <input type="checkbox" class="custom-control-input" > -->
                        <input type="checkbox" data-plugin="switchery" data-color="#9261c6" data-size="small"
                          id="observacionCheck" name="observacionCheck" />
                        <label for="observacionChecked">Observaciones</label>
                        <!-- </div> -->
                      </div>


                      <div class="col-md-3">
                        <!-- <div class="switchery-demo"> -->
                        <!-- <input type="checkbox" class="custom-control-input" > -->
                        <input type="checkbox" data-plugin="switchery" data-color="#9261c6" data-size="small"
                          id="detraccion-check" name="detraccion-check" />
                        <label for="detraccion-check">Detracción</label>
                        <!-- </div> -->
                      </div>
                      <div class="col-md-3">
                        <!-- <div class="switchery-demo"> -->
                        <!-- <input type="checkbox" class="custom-control-input" > -->
                        <input type="checkbox" data-plugin="switchery" data-color="#FC0B00 " data-size="small"
                          id="retencion-check" name="retencion-check" />
                        <label for="retencion-check">Retencion</label>
                        <!-- </div> -->
                      </div>


                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="control-label">Nombre</label>
                          <textarea name="nombreProducto" id="nombre-servicio" class="form-control"
                            placeholder="Ingrese descripcion del producto o servicio" rows="5" disabled
                            style="display:none"></textarea>
                          <input type="text" id="VentaProductoAutocomplete" name="nombreProducto" class="form-control"
                            placeholder="Ingrese el nombre del producto">
                        </div>
                      </div>

                      <div class="col-md-1">
                        <div class="form-group">
                          <label class="control-label" id="precio_u">Precio Unit.</label>
                          <label class="control-label" disabled style="display:none" id="monto_s">Monto.</label>
                          <input type="text" name="precioProducto" class="form-control" min="0.1">
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Cantidad</label>
                          <input type="text" name="cantidadProducto" id="cantidadProducto" class="form-control"
                            value="">
                        </div>
                      </div>
                      <div class="col-md-1">
                        <button type="submit" style="margin-top: 32px" class="btn btn-sm btn-pink"><i
                            class="fa fa-plus"></i></button>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-md-2" id="tipo_vent_ser" style="display: none;">
                        <div class="form-group">
                          <label class="control-label">Tipo</label>
                          <select name="tipo" class="form-control select2">
                            <option value="V">Gravada</option>
                            <option value="E">Exonerada</option>

                          </select>
                        </div>
                      </div>

                      <div class="col-md-2" id="unidad_p">
                        <div class="form-group">
                          <label class="control-label">Unidad Med.</label>
                          <input type="text" name="unidadProducto" class="form-control">
                        </div>
                      </div>

                      <div class="col-md-3" id="unidad_medida" style="display:none">
                        <div class="form-group">
                          <label class="control-label">Unidad Med.</label>
                          <select name="unidad_medida" class="form-control">
                            <?php foreach ($unidades as $u): ?>
                              <option value="<?= $u->abreviatura_unid ?>"><?= $u->nomb_unid ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-1" id="peso_p">
                        <div class="form-group">
                          <label class="control-label">Peso</label>
                          <input type="text" name="pesoProducto" class="form-control">
                        </div>
                      </div>

                      <div class="col-md-1">
                        <div class="form-group">
                          <label class="control-label">Dscto.</label>
                          <input type="text" name="descuentoProducto" class="form-control">
                        </div>
                      </div>

                      <div class="col-md-6" id="serie_c">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="serieChek" name="serieCheckProducto"
                              readonly>
                            <label class="custom-control-label" for="serieChek">Series</label>
                          </div>
                          <select id="select2-series" class="form-control selct2" name="seriesProducto[]"
                            multiple="multiple" style="margin-top: 6px" disabled>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-2 <?= ($this->session->userdata('movil_expert') == '0' ? 'd-none' : '') ?>"
                        id="isdn_product">
                        <div class="form-group">
                          <label class="control-label">Numero ISDN</label>
                          <input type="text" name="numeroisdn" class="form-control" id="producto_isdn">
                        </div>
                      </div>
                    </div>

                  </fieldset>
                  <div class="table-responsive">
                    <table id="TableVentaProductos" class="table table-striped table-hover">
                      <thead>
                        <tr class="bg-success text-white">
                          <th></th>
                          <th
                            style="text-align: center;<?= ($this->session->userdata('multi_business') == '1' ? 'display:none' : '') ?>">
                            Código</th>
                          <th style="text-align: center;">Artículo</th>
                          <th
                            style="text-align: center; <?= ($this->session->userdata('movil_expert') == '0' ? 'display:none' : '') ?>">
                            ISDN</th>
                          <th
                            style="text-align: center;<?= ($this->session->userdata('multi_business') == '1' ? 'display:none' : '') ?>">
                            Marca</th>
                          <th
                            style="text-align: center;<?= ($this->session->userdata('multi_business') == '1' ? 'display:none' : '') ?>">
                            Unidad</th>
                          <th style="text-align: center;">Cant.</th>
                          <th
                            style="text-align: center;<?= ($this->session->userdata('multi_business') == '1' ? 'display:none' : '') ?>">
                            P. Unit.</th>
                          <th
                            style="text-align: center;<?= ($this->session->userdata('multi_business') == '1' ? 'display:none' : '') ?>">
                            Desc.</th>
                          <th
                            style="text-align: center;<?= ($this->session->userdata('multi_business') == '1' ? 'display:none' : '') ?>">
                            IGV</th>
                          <th
                            style="text-align: center;<?= ($this->session->userdata('multi_business') == '1' ? 'display:none' : '') ?>">
                            Prec. Sin IGV</th>
                          <th style="text-align: center;">Subtotal</th>
                          <th style="text-align: center;">Opc.</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                      <tfoot>
                        <?php
                        // Determinamos el colspan dinámico
                        $colspan = 9; // Valor por defecto
                        
                        // Cambia el colspan según las condiciones adicionales
                        if ($this->session->userdata('multi_business') == '1') {
                          $colspan = 3;
                        } elseif ($this->session->userdata('movil_expert') == '1') { // Primera condición extra
                          $colspan = 10;
                        } 
                        ?>
                        <tr>
                          <td colspan="<?= $colspan ?>"></td>
                          <th class="bg-danger text-white">Gravada</th>
                          <td class="bg-danger text-white text-center" id="venta-gravadas">00.00</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="<?= $colspan ?>"></td>
                          <th class="bg-danger text-white">Exonerada</th>
                          <td class="bg-danger text-white text-center" id="venta-exoneradas">00.00</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="<?= $colspan ?>"></td>
                          <th class="bg-danger text-white">Gratuito</th>
                          <td class="bg-danger text-white text-center" id="venta-gratuito">00.00</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="<?= $colspan ?>"></td>
                          <th class="bg-danger text-white">Descuentos</th>
                          <td class="bg-danger text-white text-center" id="venta-descuentos">00.00</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="<?= $colspan ?>"></td>
                          <th class="bg-danger text-white">IGV</th>
                          <td class="bg-danger text-white text-center" id="venta-igv">00.00</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="<?= $colspan ?>"></td>
                          <th class="bg-danger text-white">Total</th>
                          <td class="bg-danger text-white text-center" id="venta-total">00.00</td>
                          <td></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  <div class="row">
                    <div class="col-md-12" style="display: none" id="observacion-a">
                      <div class="form-group">
                        <label for="exampleFormControlTextarea1">Observación</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="observacion"
                          rows="3"></textarea>
                      </div>
                    </div>
                    <div id="content-detalles-detraccion" class="col-md-12" style="display:none">
                      <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Detalles de detraccion</legend>
                        <div class="row">
                          <div class="col-md-3">
                            <div class="form-group">
                              <label class="control-label">Cuenta de banco de la nacion:</label>
                              <select name="detraccion_cuenta" class="form-control select2">
                                <?php foreach ($banco as $b): ?>
                                  <option value="<?= $b->nro_cuenta_ban ?>">
                                    <?= $b->nro_cuenta_ban, " - ", $b->nomb_ban ?>
                                  </option>
                                <?php endforeach ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label class="control-label">Codigo del bien: </label>
                              <select name="detraccion_bien" class="form-control select2">
                                <?php foreach ($cod_bien as $e): ?>
                                  <option data-porcentaje="<?= $e->porcentaje ?>" value="<?= $e->id_cod_detraccion ?>">
                                    <?= $e->id_cod_detraccion, " - ", $e->descripcion, " ", "(", $e->porcentaje, ")" ?>
                                  </option>
                                <?php endforeach ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label class="control-label">Medio de pago: </label>
                              <select name="detraccion_medio_pago" class="form-control select2">
                                <?php foreach ($cod_medio_pay as $pay): ?>
                                  <option value="<?= $pay->id_mediopago ?>">
                                    <?= $pay->id_mediopago, " - ", $pay->descripcion ?>
                                  </option>
                                <?php endforeach ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-1">
                            <label class="control-label">Porcentaje: </label>
                            <div class="input-group">
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                              <input type="text" name="detraccion_porcentaje" class="form-control" value="0" readonly>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <label class="control-label">Monto: </label>
                            <div class="input-group">
                              <div class="input-group-append">
                                <span class="input-group-text">S/.</span>
                              </div>
                              <input type="text" name="detraccion_monto" class="form-control" value="0.00" readonly>
                            </div>
                          </div>
                          <div class="col-md-12" id="observacion-a">
                            <div class="form-group">
                              <label for="exampleFormControlTextarea1">Informacion</label>
                              <input class="form-control" id="exampleFormControlTextarea1" name="detraccion_informacion"
                                rows="3"
                                value="OPERACION SUJETA AL SISTEMA DE PAGO OBLIGACIONES TRIBUTARIAS DEL BANCO DE LA NACION"></input>
                            </div>
                          </div>
                          <div class="form-group col-md-12">
                            <div class="text-info text-size-small"><i class="fa fa-info text-info hover-q font-italic">
                              </i> <em>&nbsp;Operacion Sujeta a
                                Detracción: Debe existir al menos un artículo sujeto a detracción. Si existe más de
                                uno, el facturador tomara el mayor porcentaje por una interpretación conservadora
                                Resolución 183-204 SUNAT/15.08.2004.</em></div>
                          </div>
                        </div>

                      </fieldset>
                    </div>
                    <div id="content-detalles-retencion" class="col-md-12" style="display:none">
                      <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Informacion de la retencion</legend>
                        <div class="row">
                          <div class="col-md-4">
                            <label class="control-label">Base imponible: </label>
                            <div class="input-group">
                              <div class="input-group-append">
                                <span class="input-group-text">S/.</span>
                              </div>
                              <input type="text" name="base_monto" class="form-control" value="0.00" readonly>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <label class="control-label">Porcentaje: </label>
                            <div class="input-group">
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                              <input type="text" name="retencion_porcentaje" class="form-control" value="3" readonly>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <label class="control-label">Monto retencion: </label>
                            <div class="input-group">
                              <div class="input-group-append">
                                <span class="input-group-text">S/.</span>
                              </div>
                              <input type="text" name="retencion_monto" class="form-control" value="0.00" readonly>
                            </div>
                          </div>

                      </fieldset>
                    </div>
                    <button data-toggle="modal" data-target="#ModalprocesarVenta" type="button"
                      class="btn btn-block btn-xs btn-primary waves-effect waves-light">Procesar</button>

                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal FormVenta" id="ModalprocesarVenta" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white" id="exampleModalLabel">Opciones de Pago</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body table-responsive">
                  <div>
                    <fieldset>
                      <legend>Formas de pago</legend>
                      <div class="row">
                        <!-- <div class="col-md-2">
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
                            <input type="text" class="form-control" value="<?= $apertura->nomb_caja ?? '' ?>" readonly>
                          </div>
                        </div> -->
                        <div class="col-md-2">
                          <div class="form-group">
                            <label class="control-label">Opciones</label>
                            <select name="pago" class="form-control select2">
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
                        <div id="pagocredito" class="col-md-2" style="display: none">
                          <div class="form-group">
                            <label class="control-label">Dias / Cuotas</label>
                            <div>
                              <input type="checkbox" data-plugin="switchery" data-color="#ff5d48" data-size="small"
                                id="switch-dias-cuotas" name="dias_cuotas" />
                            </div>
                          </div>
                        </div>


                        <div class="col-md-2 pagocredito-cuotas form-group" style="display: none">
                          <label class="control-label">Periodo</label>
                          <select name="periodo" class="form-control select2">
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
                          <button id="calcular-cuotas" style="margin-top:27px" type="button"
                            class="btn btn-md btn-primary">Calcular</button>
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
                          <div style="display:none" class="alert alert-danger cuotas-error" role="alert">
                          </div>
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
                          <div style="display:none" class="alert alert-danger cuotas-error" role="alert">
                          </div>
                        </div>
                      </div>

                    </fieldset>
                    <fieldset class="scheduler-border">
                      <legend class="scheduler-border">Metodo de pago</legend>

                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Opciones</label>
                            <select name="tipoPago" class="form-control select2 input-sm">
                              <?php foreach ($tipos_pagos as $t): ?>
                                <option value="<?= $t->cod_tipopago ?>"><?= $t->nom_tipopago ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                        </div>


                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Tipo de Tarjeta</label>
                            <select name="tipoTarjeta" class="form-control select2" disabled>
                              <option value=""></option>
                              <?php foreach ($tipos_tarjetas as $t): ?>
                                <option value="<?= $t->cod_tarj ?>"><?= $t->nomb_tarj ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">N° Operación</label>
                            <input type="text" name="operacion" class="form-control" disabled>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">YAPE</label>
                            <input type="text" name="monto_tb" class="form-control" placeholder="0.00">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Efectivo</label>
                            <input type="text" name="monto_efectivo" class="form-control" placeholder="0.00">
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Descuento (%)</label>
                            <input type="number" min="0" max="99" name="descuento" class="form-control" value="0">
                          </div>
                        </div>


                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Monto Recibido</label>
                            <input type="text" name="montoRecibido" class="form-control" value="0.00">
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Vuelto</label>
                            <input type="text" name="vuelto" class="form-control" value="0.00" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Monto Total</label>
                            <input type="text" name="monto_total" class="form-control" value="0.00" readonly>
                          </div>
                        </div>
                        <div
                          class="card-body d-flex flex-column flex-sm-row justify-content-center justify-content-around">
                          <button type="button" data-monto="10"
                            class="monto-recibido btn btn-primary btn-bordered waves-effect w-md">S/.10</button>
                          <button type="button" data-monto="20"
                            class="monto-recibido btn btn-success btn-bordered waves-effect w-md">S/.20</button>
                          <button type="button" data-monto="50"
                            class="monto-recibido btn btn-danger btn-bordered waves-effect w-md">S/.50</button>
                          <button type="button" data-monto="100"
                            class="monto-recibido btn btn-purple btn-bordered waves-effect w-md">S/.100</button>
                          <button type="button" data-monto="200"
                            class="monto-recibido btn btn-pink btn-bordered waves-effect w-md">S/.200</button>
                          <button type="button" data-monto="500"
                            class="monto-recibido btn btn-info btn-bordered waves-effect w-md">S/.500</button>
                        </div>
                      </div>
                      <!-- </div> -->
                    </fieldset>
                  </div>

                </div>

              </div>

            </div>

          </div>
        </div>
        <div class="modal-footer">

          <legend class="scheduler-border"></legend>

          <div class="row" id="VentasContenedorGuardar">
            <div class="col-md-12">
              <div class="form-group float-right">
                <a href="<?= base_url('administrador/regventas') ?>" class="btn btn-pink "><i class="fas fa-times"></i>
                  Cancelar</a>
                <button type="submit" form="FormVentaAgregar" class="btn btn-primary " <?= ($apertura == false) ? 'disabled' : '' ?>><i class="fa fa-save m-r-5"></i>Procesar</button>
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>

  </div>

  <div class="modal" id="ModalAccionesDespuesGuardar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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

                    <a href="<?= base_url('administrador/regventas') ?>"
                      class="btn btn-info btn-rounded w-md waves-effect waves-light">Volver al panel</a>
                    <a id="VentaImprimirA4" class="btn btn-inverse btn-rounded w-md waves-effect waves-light"
                      target="_blank">Imprimir A4</a>
                    <a id="VentaImprimirTicket" class="btn btn-purple btn-rounded w-md waves-effect waves-light"
                      target="_blank">Imprimir Ticket</a>
                    <!-- <?php if ($q->siglas_talonario == 'TK'): ?>
                    <a href="<?= base_url('administrador/regventas') ?>" class="btn btn-info btn-rounded w-md waves-effect waves-light">Volver al panel</a>
                    <a id="VentaImprimirA4" class="btn btn-inverse btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir A4</a>
                    <a id="VentaImprimirTicket" class="btn btn-purple btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir Ticket</a>
                    

                    <?php elseif ($q->siglas_talonario == 'FC'): ?>
                    <a href="<?= base_url('administrador/regventas') ?>" class="btn btn-info btn-rounded w-md waves-effect waves-light">Volver al panel</a>
                    <a id="VentaImprimirA4" class="btn btn-inverse btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir A4</a>
                    <a id="VentaImprimirTicket" class="btn btn-purple btn-rounded w-md waves-effect waves-light" target="_blank">Imprimir Ticket</a>

                    <?php endif; ?> -->

                  </div>
                </div>
              </div>
            </div>
          </div> <!-- end row -->
        </div>
      </div>
    </div>
  </div>


  <div class="modal" id="ModalDeudaCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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


  <div class="modal" id="ModalAgregarCliente" tabindex="" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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
        <form id="FormVentaAgregarCliente" action="<?= base_url('administrador/regventas/agregarCliente') ?>"
          autocomplete="off" method="post">
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
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="home-b2">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Tipo:</label>
                            <select name="tipo" id="tipo_documento" class="form-control">
                              <option value="">Seleccione</option>
                              <?php foreach ($doc_clientes as $d): ?>
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
                      <div class="col-md-12" id="telefono">
                        <div class="form-group">
                          <label class="control-label">Teléfono</label>
                          <input type="text" name="telefono" class="form-control"
                            onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
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
                            <input type="text" id="fnacimiento" name="fnacimiento" class="form-control datepicker"
                              value="<?= date('Y-m-d') ?>">
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


  <div class="modal" id="ModalEditarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="card-header bg-success">
        <h3 class="my-0 text-white">Editar cliente</h3>
      </div>
      <div class="modal-content">
        <!-- <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cliente - Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
        <form id="FormVentaEditarCliente" action="<?= base_url('administrador/regventas/editarCliente') ?>"
          autocomplete="off" method="post">
          <input type="hidden" name="id">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Tipo:</label>
                  <select name="tipo" class="form-control">
                    <option value="">Seleccione</option>
                    <?php foreach ($doc_clientes as $d): ?>
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
                  <input type="text" name="documento" class="form-control" maxlength="11" minlength="8"
                    onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                </div>
              </div>
              <div class="col-md-6" id="fnacimiento">
                <div class="form-group">
                  <label class="control-label">F.nacimiento</label>
                  <input type="date" name="fnacimiento" class="form-control">
                </div>
              </div>
              <div class="col-md-6" id="telefono">
                <div class="form-group">
                  <label class="control-label">Teléfono</label>
                  <input type="text" name="telefono" class="form-control"
                    onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
                </div>
              </div>
              <div class="col-md-12" id="idtelefono">
                <div class="form-group">
                  <label class="control-label">Teléfono</label>
                  <input type="text" name="telefono" class="form-control"
                    onKeyPress="if (event.keyCode < 48 || event.keyCode > 57)event.returnValue = false;">
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



  <?php $this->load->view('reports/modal_alertas') ?>


  <script>
    jQuery(document).on('click', function (e) {
      // check for type, avoid selecting the element for performance
      if (e.target.type == 'checkbox') {
        var el = jQuery(e.target);
        if (el.prop('readonly')) {
          // prevent it from changing state
          e.preventDefault();

        }
      }

    });

    var tipo_documento = document.getElementById("tipo_documento");
    var txt_documento = document.getElementById("txt_documento");
    var scan = document.getElementById("scan");
    var txtdocu = document.getElementById("txtdocu");

    tipo_documento.addEventListener("change", function () {
      var selectedValue = tipo_documento.value;
      txt_documento.value = ''; // Limpiar el valor del input al cambiar la opción

      txt_documento.removeEventListener("input", validarDni);
      txt_documento.removeEventListener("input", validarRuc);

      if (selectedValue === "2") {
        txtdocu.textContent = "Ingrese DNI";
        scan.style.display = "block";
        txt_documento.addEventListener("input", validarDni);
        scan.textContent = "RENIEC";

      }
      if (selectedValue === "4") {
        txtdocu.textContent = "Ingrese RUC";
        scan.style.display = "block"
        txt_documento.addEventListener("input", validarRuc);
        scan.textContent = "SUNAT";

      }
      if (selectedValue === "3") {
        txtdocu.textContent = "Ingrese Carnet Ex.";
        txt_documento.addEventListener("input", validarOthers);
        scan.style.display = "none";
      }
      if (selectedValue === "5") {
        txtdocu.textContent = "Ingrese Pasaporte";
        txt_documento.addEventListener("input", validarOthers);
        scan.style.display = "none";
      }
    });


    function validarDni(input) {
      var regex = /^\d{8}$/;
      if (!regex.test(txt_documento.value)) {
        txt_documento.value = txt_documento.value.replace(/[^\d$]/g, '').substring(0, 8);
      }
    }

    function validarRuc(input) {
      var regex = /^\d{11}$/;
      if (!regex.test(txt_documento.value)) {
        txt_documento.value = txt_documento.value.replace(/[^\d$]/g, '').substring(0, 11);
      }
    }

    function validarOthers(input) {
      var regex = /^[0-9a-zA-Z]{12}$/;
      if (!regex.test(input.value)) {
        txt_documento.value = txt_documento.value.replace(/[^0-9a-zA-Z]/g, '').substring(0, 12);;
      }
    }
  </script>

  <script type="text/javascript">
    document.getElementById('ClienteVentaAutocomplete').addEventListener('paste', function (event) {
      // Prevenir la acción predeterminada de pegar
      event.preventDefault();

      // Obtener el texto pegado
      const clipboardData = event.clipboardData || window.clipboardData;
      const pastedText = clipboardData.getData('text');

      // Eliminar espacios en blanco y actualizar el valor del campo de entrada
      const trimmedText = pastedText.replace(/\s+/g, '');
      this.value = trimmedText;
    });
  </script>