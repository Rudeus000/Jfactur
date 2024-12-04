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
              <!-- <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> Reporte detallaod de ventas </h4> -->
            </div>
          </div>
        </div>

        <!-- end row -->

        <!-- Vertical Steps Example -->
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header bg-success">
                <h3 class="my-0 text-white">Reporte detallado de ventas<i
                    class="spinner-grow text-danger float-right"></i></h3>
              </div>
              <div class="card-body table-responsive">
                <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormReporteVentasDetalladasBusqueda" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Fecha</label>
                          <div class="input-group">
                            <!-- <input type="text" name="desde" class="form-control datepicker" value="2020-07-23"> -->
                            <input type="text" name="desde" class="form-control datepicker"
                              value="<?= date('Y-m-d') ?>">
                            <input type="text" name="hasta" class="form-control datepicker"
                              value="<?= date('Y-m-d') ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Almacen</label>
                          <input type="text" name="almacen" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Cliente</label>
                          <input type="text" name="cliente" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <!-- <div class="form-group">
                          <label class="control-label">Vendedor</label>
                          <input type="text" name="vendedor" class="form-control">
                        </div> -->
                        <div class="form-group">
                          <label class="control-label">Agentes:</label>
                          <?php if ($this->session->userdata('perfil') == 1): ?>
                            <select name="vendedor" class="form-control">
                              <option value="">Seleccione</option>
                              <?php foreach ($vendedores as $v): ?>
                                <option value="<?= $v->cod_usu ?>"><?= $v->apell_usu . ' ' . $v->nomb_usu ?></option>
                              <?php endforeach ?>
                            </select>
                          <?php endif ?>
                          <?php if ($this->session->userdata('perfil') != 1): ?>
                            <input type="text" id="vendedorcod" name="vendedorcod"
                              value="<?= $this->session->userdata('cod_usu') ?>" style="display:none">
                            <input type="text" name="vendedorname" readonly class="form-control"
                              value="<?= $this->session->userdata('nomb_usu') . ' ' . $this->session->userdata('apell_usu') ?>">
                          <?php endif ?>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <button style="margin-top:27px" type="submit" class="btn btn-success"><i
                              class=" fab fa-earlybirds m-r-5"></i>Filtrar</button>
                          <a id="ReporteVentasDetalladasExcel" href="#" class="btn btn-primary" style="margin-top:27px"
                            target="_blank"><i class="far fa-file-excel m-r-5"></i> Exportar a EXCEL</a>
                          <!-- Botón para abrir el modal de comparación -->
                          <button id="compararReporte" type="button" class="btn btn-warning" style="margin-top:27px"
                            data-toggle="modal" data-target="#miModal">
                            <i class="fas fa-exchange-alt m-r-5"></i> Comparar Reporte
                          </button>
                        </div>
                      </div>
                    </div>
              </div>
              </form>
              </fieldset>
              <br>
              <!-- <div class="table-responsive"> -->
              <table id="TableReporteDetalladoVentas" class="table table-striped table-sm mb-0" cellspacing="0"
                width="100%">

                <thead>
                  <tr class="btn-dark">
                    <th style="text-align: center; width: 50px">Fecha</th>
                    <th style="text-align: center;">Almacen</th>
                    <th style="text-align: center;">Punto de Venta</th>
                    <th style="text-align: center;">DNI-RUC</th>
                    <th style="text-align: center;">Cliente</th>
                    <th style="text-align: center;">Documento</th>
                    <th style="text-align: center;">Vendedor</th>
                    <th style="text-align: center;">Unidad.M.</th>
                    <th style="text-align: center;">Producto</th>
                    <th style="text-align: center;">ISDN</th>
                    <th style="text-align: center;">Serie</th>
                    <th style="text-align: center;">T.Pago</th>
                    <th style="text-align: center;">N.Opracion</th>
                    <th style="text-align: center;">Prec.Unid.</th>
                    <th style="text-align: center;">Descuento</th>
                    <th style="text-align: center;">Prec.Desc.</th>
                    <th class="bg-danger" tyle="text-align: center;">Cantidad</th>
                    <th style="text-align: center;">Subtotal</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th colspan="17" style="text-align:right">Total:</th>
                    <th><strong><span id="TotalReporteVentasDetalladas"></span></strong></th>
                  </tr>
                </tfoot>
              </table>

              <!-- </div> -->
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


<!-- Modal para mostrar los resultados de la comparación -->
<div class="modal" id="miModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white" id="exampleModalLabel">Comparación de ISDN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para subir el archivo -->
                <form id="form-comparacion" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Seleccione el archivo Excel</label>
                        <input type="file" class="form-control" id="archivo" name="archivo" accept=".xlsx, .xls">
                    </div>
                    <!-- Filtro de fecha -->
                    <div class="input-group">
                            <!-- <input type="text" name="desde" class="form-control datepicker" value="2020-07-23"> -->
                            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control datepicker"
                              value="<?= date('Y-m-d') ?>">
                            <input type="date" id="fecha_fin" name="fecha_fin" class="form-control datepicker"
                              value="<?= date('Y-m-d') ?>">
                              <button type="button" id="btnSubirYComparar" class="btn btn-primary">Comparar</button>
                          </div>                   
                    
                </form>
                <!-- Contenedor para mostrar el resultado de la comparación -->
                <div id="resultado-comparacion" style="margin-top: 20px;"></div>
            </div>
        </div>
    </div>
</div>


<!-- Activar el modal y cargar los resultados de la comparación -->
<script>
  $(document).ready(function () {
    $('#btnSubirYComparar').click(function () {
      var formData = new FormData();
      formData.append('archivo', $('#archivo')[0].files[0]);
      formData.append('fecha_inicio', $('#fecha_inicio').val());
      formData.append('fecha_fin', $('#fecha_fin').val());

      $.ajax({
        url: '<?php echo base_url("reportes/regreportedetallado/compararExcel"); ?>', // Cambia esta URL según la ruta de tu controlador
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          var data = JSON.parse(response);
          var mensaje = data.mensaje;
          var isdnData = data.isdnData;

          if (isdnData.length > 0) {
            // Crear tabla HTML dinámica
            var tablaHTML = "<strong>:</strong><br><br>";
            tablaHTML += "<table id='tablaISDN' class='table table-striped table-sm mb-0 dataTable'>";
            tablaHTML += "<thead><tr><th>ISDN</th><th>Código de Tienda</th><th>Tipo de Canal</th><th>Tipo de Transacción</th><th>Cantidad</th></tr></thead>";
            tablaHTML += "<tbody>";

            // Agregar filas a la tabla
            isdnData.forEach(function (item) {
              tablaHTML += "<tr><td>" + item.isdn + "</td><td>" + item.codigoTienda + "</td><td>" + item.tipoCanal + "</td><td>" + item.tipoTransaccion + "</td><td>" + item.cantidad + "</td></tr>";
            });

            tablaHTML += "</tbody>";

            // Agregar pie de tabla vacío (se actualizará dinámicamente)
            tablaHTML += "<tfoot><tr style='font-weight: bold;'><td colspan='4' style='text-align: right;'>Suma Total:</td><td id='sumaCantidad'>0.00</td></tr></tfoot>";
            tablaHTML += "</table>";
            tablaHTML += "<br><strong>Total de ISDN no registrados: " + isdnData.length + "</strong>";

            // Mostrar mensaje y la tabla
            $('#resultado-comparacion').html(mensaje + tablaHTML);

            // Inicializar DataTable
            var table = $('#tablaISDN').DataTable({
              "pageLength": 10 // Muestra 10 elementos por página
            });

            // Actualizar suma en tiempo real
            table.on('draw', function () {
              var sumaTotal = 0;

              // Iterar sobre las filas visibles actualmente
              table.rows({ search: 'applied' }).data().each(function (row) {
                sumaTotal += parseFloat(row[4] || 0); // La columna 'cantidad' está en el índice 4
              });

              // Actualizar el pie de tabla
              $('#sumaCantidad').text(sumaTotal.toFixed(2));
            });

            // Disparar evento para calcular la suma inicial
            table.draw();

          } else {
            $('#resultado-comparacion').html('<p>Todos los ISDN están registrados.</p>');
          }

          // Mostrar el modal
          $('#miModal').modal('show');
        },
        error: function () {
          $('#resultado-comparacion').html('<p style="color:red;">Ocurrió un error al procesar el archivo. Inténtelo de nuevo.</p>');
        }
      });
    });
  });
</script>
