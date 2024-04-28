<style>
  body.enlarged{
    min-height: auto;
    padding-bottom: 0px;
  }
  footer.footer{
    display:none;
  }
  #container-pos{
    height: calc(100vh - 90px);
  }

  #container-pos-galeria{
    height: calc(100vh - 195px);
    overflow-y: scroll;
  }

  #container-pos-resumen{
    height: calc(100vh - 95px);
    background: white;
    overflow: hidden;
  }

  #container-pos-categorias{
    overflow: hidden;
    height: 40px;
    width: auto;
    white-space:nowrap; 
  }

  #pos-categorias{
    padding: 0 5px;
  }

  #categorias {
    width: auto;
    white-space:nowrap; 
    height: 40px;
    transition: transform 0.5s ease;
    padding: 0px 15px;
  }

  #container-pos-categorias .pos-categoria{
    display: inline-block;
    font-size: 18px;
    font-weight: bold;
    margin-left: 10px;
    border-radius: 10px;
    background-color: #007bff;
    padding: 3px 10px;
    cursor: pointer;
  }

  #button-right{
    position: absolute;
    right: 0;
    z-index: 10;
  }
  #button-left{
    position: absolute;
    left: 0;
    z-index: 10;
  }

  .pos-producto{
    background-color: #d5d5d5;
    border-radius: 10px;
    padding: 10px;
    cursor: pointer;
  }

  .pos-producto-imagen{
    text-align: center;
  }
  .pos-producto-imagen img{
    max-width: 90px;
  }





  #contenedor-resumen {
    display: flex;
    flex-direction: column;
    
    height: calc(100vh - 95px); /* Opcional: ajusta la altura del contenedor */
}

#pos-resumen-top {
    /* Altura automática según el contenido */
    flex: 0 0 auto;
    
}

#pos-resumen-center {
    /* El centro se expandirá para llenar el espacio disponible */
    flex: 1;
    overflow-y: scroll; /* Agrega scroll vertical si es necesario */

}

#pos-resumen-bottom {
    /* Altura automática según el contenido */
    flex: 0 0 auto;

}

#pos-resumen-datos{
  background: #007bff;
  color: white;
  text-align: center;
  padding: 3px 5px;
  font-size: 16px;
  cursor: pointer;
}

.pos-resumen-item{
  background: #d5d5d5;
  border-radius: 15px;
  padding: 10px 4px;
  margin-top: 10px;
}
.pos-resumen-item-nombre-imagen{
  display: flex;
}

.pos-resumen-item-imagen {
  width: 50px;
}

.pos-resumen-item-nombre {
  margin-left: 5px;
  flex-grow: 1; /* Ocupa todo el espacio restante */
}

.pos-resumen-item-cantidad{
  text-align: right;

}

.pos-resumen-item-cantidad-numero{
  font-weight: bold;
  font-size: 16px;
  margin: 0px 10px;
}

  
</style>

  <div class="content-page">
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12 mt-1">
            <a id="VentaImprimirA4" style="display:none" target="_blank">Imprimir A4</a>
            <a id="VentaImprimirTicket" style="display:none" target="_blank">Imprimir Ticket</a>
            <div class="card py-0 my-0">
              <div class="card-body p-0">
                <div class="row mt-2" id="container-pos">
                  <div class="col-md-8">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <input type="text" class="form-control" placeholder="Filtro por nombre" id="pos-filtrar-nombre">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <input type="text" class="form-control" placeholder="Código de barra" id="ingresar-codigo-barra">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <button type="button" class="btn btn-success btn-sm mt-1" id="pos-ver-todos-productos">Ver todos</button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <button id="button-left" class="btn btn-circle btn-primary"><i class="fa fa-arrow-left fa-lg"></i></button>
                      <button id="button-right" class="btn btn-circle btn-primary"><i class="fa fa-arrow-right fa-lg"></i></button>
                      <div id="container-pos-categorias">
                        <div id="pos-categorias">

                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 mt-2" id="container-pos-galeria">
                      <div class="row">
                        
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4" id="container-pos-resumen">
                    <div id="contenedor-resumen">
                        <div id="pos-resumen-top">
                          <div class="row">
                            <div class="col-md-4 p-2">
                              <div style="font-weight:bold;font-size:16px">Nuevo Pedido</div>
                            </div>
                            <div class="col-md-8 p-2">
                              <div>
                              <?= fechaFormateada() ?> 
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div id="pos-resumen-datos">
                                Datos
                              </div>
                            </div>
                          </div>
                        </div>
                        <div id="pos-resumen-center">
                          <form id="FormPos" action="" autocomplete="off">
                            <input type="hidden" name="total">
                            <input type="hidden" name="fecha" value="<?= date('Y-m-d') ?>">
                            <input type="hidden" name="puntoVenta" value="<?= $punto->nomb_puntoventa ?? '' ?>">
                            <input type="hidden" name="almacen" value="<?= $almacenes[0]->cod_almacen?>">
                            <input type="hidden" name="precioCliente" value="Normal">
                            <input type="hidden" name="moneda" value="S">
                            <input type="hidden" name="tipoCambio" value="1">
                            <input type="hidden" name="pago" value="CO">
                            <input type="hidden" name="monto">
                            <input type="hidden" name="periodo" value="Mensual">
                            <input type="hidden" name="numero_cuotas" value="2">
                            <input type="hidden" name="fecVenc">
                            <input type="hidden" name="producto">
                            <input type="hidden" name="idTypeAssignmentProduct">
                            <input type="hidden" name="nombreProducto">
                            <input type="hidden" name="precioProducto">
                            <input type="hidden" name="cantidadProducto">
                            <input type="hidden" name="tipo" value="V">
                            <input type="hidden" name="unidadProducto">
                            <input type="hidden" name="unidad_medida" value="NIU">
                            <input type="hidden" name="pesoProducto">
                            <input type="hidden" name="descuentoProducto">
                            <input type="hidden" name="numeroisdn">
                            <input type="hidden" name="descuento" value="0">

                            <input type="hidden" name="detraccion_cuenta" value="4564564564">
                            <input type="hidden" name="detraccion_bien" value="1">
                            <input type="hidden" name="detraccion_medio_pago" value="1">
                            <input type="hidden" name="detraccion_porcentaje" value="10">
                            <input type="hidden" name="detraccion_monto" value="35">
                            <input type="hidden" name="detraccion_informacion" value="OPERACION	SUJETA	AL	SISTEMA	DE	PAGO	OBLIGACIONES	TRIBUTARIAS	DEL	BANCO	DE	LA	NACION">
                            
                            <div id="pos-resumen-datos-content" class="row mx-0" style="display:none">
                              <div class="col-md-12 mt-1">
                                <div class="form-group mb-1">
                                  <select name="tipoPedido" class="form-control input-sm">
                                      <option value="">Seleccione</option>
                                      <?php foreach ($tipos as $t) : ?>
                                      <option value="<?= $t->cod_talonario ?>" data-dni="<?= $t->docclidni_talonario ?>" data-ruc="<?= $t->doccliruc_talonario ?>" data-ex="<?= $t->doccliex_talonario ?>" data-pass="<?= $t->docclipass_talonario ?>" <?= $punto->talonario_defecto == $t->cod_talonario ? 'selected' : '' ?>><?= $t->nom_tipdocumento . ' - ' . $t->serie ?></option>
                                      <?php endforeach ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group mb-1">
                                  <label>Serie</label>
                                  <input type="text" name="serie" placeholder="Serie" class="form-control input-sm" readonly>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group mb-1">
                                  <label>Número</label>
                                  <input type="text" name="correlativo" placeholder="Número" class="form-control input-sm" readonly>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group mb-1">
                                  <label>Tipo Doc.</label>
                                  <select name="tipo_documento" class="form-control input-sm" readonly>
                                    <option value="">Seleccione</option>
                                    <option value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group mb-1">
                                  <label>N° Doc. (Presione enter)</label>
                                  <input type="text" name="rucdni" placeholder="N° Documento" class="form-control input-sm">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group mb-1">
                                  <label>Cliente</label>
                                  <input type="hidden" name="cliente">
                                  <input type="text" placeholder="Nombres" name="nombreCliente" class="form-control input-sm" readonly>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group mb-1">
                                  <label>Dirección</label>
                                  <input type="text" name="direccion_cliente" placeholder="Dirección" class="form-control input-sm" readonly>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <textarea name="observacion" placeholder="Observaciones" class="form-control" rows="2"></textarea>
                                </div>
                              </div>
                            </div>
                            <div id="items" style="overflow:hidden"></div>
                          </form>
                        </div>
                        <div id="pos-resumen-bottom">
                          <div class="row mt-2">
                            <div class="col-6 font-weight-bold">Subtotal</div>
                            <div class="col-6 text-right font-weight-bold" id="pos-resumen-subtotal"></div>
                          </div>
                          <div class="row">
                            <div class="col-6 font-weight-bold">IGV 18%</div>
                            <div class="col-6 text-right font-weight-bold" id="pos-resumen-igv"></div>
                          </div>
                          <div>
                            <hr class="m-1">
                          </div>
                          <div class="row">
                            <div class="col-6 font-weight-bold"><b>Total</b></div>
                            <div class="col-6 text-right font-weight-bold" id="pos-resumen-total"></div>
                          </div>
                          <button type="submit" form="FormPos" id="pasar-a-caja" class="btn btn-block btn-success btn-lg">Pasar a caja</button>
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

<div class="modal" id="ModalMetodoPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Método de Pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="FormMetodoPago" autocomplete="off">
          
        <div class="modal-body">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5>Total a pedido</h5>
            </div>
            <div class="col-sm-6" >
              <h5 id="totalPedidoPagar"></h5>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Metodo de Pago</label>
                <select name="tipoPago" class="form-control">
                  <?php foreach ($tipos_pagos as $t) : ?>
                    <option value="<?= $t->cod_tipopago ?>"><?= $t->nom_tipopago ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Tipo de Tarjeta</label>
                <select name="tipoTarjeta" class="form-control" disabled>
                  <option value=""></option>
                  <?php foreach ($tipos_tarjetas as $t) : ?>
                    <option value="<?= $t->cod_tarj ?>"><?= $t->nomb_tarj ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Monto Recibido</label>
                <input type="text" name="montoRecibido" class="form-control">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Vuelto</label>
                <input type="text" name="vuelto" class="form-control" value="0.00" readonly>
              </div>
            </div>
          </div>
          <!--
          <div class="row">
            <div class="col-6">
              <div class="row mt-1">
                <div class="col-6">
                  <b>Total Pedido S/</b>
                </div>
                <div class="col-6" id="modal-total-pedido">  
                </div>
              </div>
              <div class="row mt-1">
                <div class="col-6">Efectivo</div>
                <div class="col-6">
                  <div class="input-group">
                    <input type="number" name="metodo_pago_efectivo" class="form-control input-pos">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-success">
                        <span class="fa fa-calculator"></span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                <div class="col-6">Visa</div>
                <div class="col-6">
                  <div class="input-group">
                    <input type="number" name="metodo_pago_visa" class="form-control input-pos">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-success">
                        <span class="fa fa-calculator"></span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                <div class="col-6">Yape</div>
                <div class="col-6">
                  <div class="input-group">
                    <input type="number" name="metodo_pago_yape" class="form-control input-pos">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-success">
                        <span class="fa fa-calculator"></span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                <div class="col-6">Plin</div>
                <div class="col-6">
                  <div class="input-group">
                    <input type="number" name="metodo_pago_plin" class="form-control input-pos">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-success">
                        <span class="fa fa-calculator"></span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                <div class="col-6">Mastercard</div>
                <div class="col-6">
                  <div class="input-group">
                    <input type="number" name="metodo_pago_mastercard" class="form-control input-pos">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-success">
                        <span class="fa fa-calculator"></span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                <div class="col-6">Depósito</div>
                <div class="col-6">
                  <div class="input-group">
                    <input type="number" name="metodo_pago_deposito" class="form-control input-pos">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-success">
                        <span class="fa fa-calculator"></span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <table id="table-numerico-pos" class="table table-borderless text-center" style="max-width: 200px; margin: 0 auto">
                <tr>
                  <td class="p-0" colspan="3">
                    <h4>TECLADO</h4>
                  </td>
                  <td class="p-0"></td>
                </tr>
                <tr>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">1</button>
                  </td>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">2</button>
                  </td>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">3</button>
                  </td>
                  <td class="p-1">
                    <button type="button" class="btn btn-primary btn-lg">
                      <spanc class="fa fa-arrow-left"></span>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">4</button>
                  </td>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">5</button>
                  </td>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">6</button>
                  </td>
                  <td class="p-1"></td>
                </tr>
                <tr>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">7</button>
                  </td>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">8</button>
                  </td>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">9</button>
                  </td>
                  <td class="p-1"></td>
                </tr>
                <tr>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">0</button>
                  </td>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">00</button>
                  </td>
                  <td class="p-1">
                    <button type="button" class="btn btn-lg btn-block numerico">.</button>
                  </td>
                  <td class="p-1"></td>
                </tr>
                <tr>
                  <td class="p-0" colspan="3">
                    <button type="button" class="btn btn-primary btn-block btn-lg">Borrar todo</button>
                  </td>
                  <td class="p-1"></td>
                </tr>
              </table>
            </div>
          </div>
          -->
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Realizar Pago</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
      const container = document.getElementById('container-pos-categorias');
    const objects = document.getElementById('categorias');
    const leftButton = document.getElementById('button-left');
    const rightButton = document.getElementById('button-right');

    let scrollPosition = 0;
    const scrollStep = 300; // Cambia este valor según quieras ajustar la cantidad de desplazamiento

    leftButton.addEventListener('click', function() {
      scrollPosition -= scrollStep;
      if (scrollPosition < 0) {
        scrollPosition = 0;
      }
      objects.style.transform = `translateX(-${scrollPosition}px)`;
    });

    rightButton.addEventListener('click', function() {
      const maxScroll = objects.scrollWidth - container.clientWidth;
      scrollPosition += scrollStep;
      if (scrollPosition > maxScroll) {
        scrollPosition = maxScroll;
      }
      objects.style.transform = `translateX(-${scrollPosition}px)`;
    });
</script>