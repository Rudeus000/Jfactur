<!-- Begin page -->
<div id="wrapper" class="alerta-modal" data-stockminimos="<?= $this->session->userdata('alerta_stock') ?>"
    data-vencimiento="<?= $this->session->userdata('alerta_vencimiento') ?>">


    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">


                <!-- end row -->
                <div class="row">
                    <!-- <div class="col-lg-3 col-md-2"> -->

                    <div class="col-xl-3 col-md-6">
                        <div class="widget-panel widget-style-2 bg-success">
                            <i class="ion-md-cash"></i>
                            <h2 class="m-0 text-white" data-plugin="counterup"><?php echo $dia; ?></h2>
                            <div>Ventas contado</div>
                        </div>
                    </div>


                    <div class="col-xl-3 col-md-6">
                        <div class="widget-panel widget-style-2 bg-purple">
                            <i class="ion-md-card"></i>
                            <h2 class="m-0 text-white" data-plugin="counterup"><?php echo $credito; ?></h2>
                            <div>Ventas a credito</div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="widget-panel widget-style-2 bg-info">
                            <i class="ion-md-cart"></i>
                            <h2 class="m-0 text-white" data-plugin="counterup"><?php echo $compras; ?></h2>
                            <div>Compras dia</div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="widget-panel widget-style-2 bg-primary">
                            <i class="ion-md-contacts"></i>
                            <h2 class="m-0 text-white" data-plugin="counterup"><?php echo $clientes; ?></h2>
                            <div>Clientes</div>
                        </div>
                    </div>
                </div> <!-- end row -->

                <!-- update new chart -->

                <div class="row">
                    <!--  Line Chart -->
                    <div class="col-sm-12">
                        <div class="portlet"><!-- /primary heading -->
                            <div class="portlet-heading">
                                <h3 class="portlet-title">
                                    VENTAS COBRADAS POR MES
                                </h3>
                                <div class="portlet-widgets">
                                    <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#portlet1"><i
                                            class="mdi mdi-minus"></i></a>
                                    <span class="divider"></span>
                                    <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="card">
                                <div class="card-body table-responsive">
                                    <form id="FormFiltroReporteVentasMes" class="form-horizontal" action="">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">

                                                    <select name="mes" class="form-control select2">
                                                        <?php foreach ($meses as $m): ?>
                                                            <option value="<?= $m->mes ?>"><?= $m->mes ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-12" style="height: 300px">
                                            <div id="ContentVentasMes">
                                                <canvas id="VentasMes"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /Portlet -->
                    </div>
                </div> <!-- End row-->

                <div class="row">

                    <!-- Area Chart -->
                    <div class="col-lg-6">
                        <div class="portlet"><!-- /primary heading -->
                            <div class="portlet-heading">
                                <h3 class="portlet-title">
                                    <select name="year" id="year" class="form-control select2">
                                        <?php foreach ($years as $year): ?>
                                            <option value="<?php echo $year->year; ?>">
                                                <?php echo $year->year; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </h3>
                                <div class="portlet-widgets">
                                    <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i
                                            class="mdi mdi-minus"></i></a>
                                    <span class="divider"></span>
                                    <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="portlet2" class="panel-collapse collapse show">
                                <div class="portlet-body">
                                    <div id="grafico" style="height: 365px;"></div>
                                </div>
                            </div>
                        </div> <!-- /Portlet -->
                    </div>

                    <!-- Donut Chart -->
                    <div class="col-lg-6">
                        <div class="portlet"><!-- /primary heading -->
                            <div id="formVentasTopProductos" class="portlet-heading">
                                <h3 class="portlet-title">
                                    <select name="mesVentasTopProd" id="mesVentasTopProd" class="form-control select2">
                                        <?php foreach ($product as $p): ?>
                                            <option value="<?php echo $p->mes; ?>">
                                                <?php echo $p->mes; ?>
                                            </option>>
                                        <?php endforeach ?>
                                    </select>
                                </h3>
                                <div class="portlet-widgets">
                                    <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#portlet3"><i
                                            class="mdi mdi-minus"></i></a>
                                    <span class="divider"></span>
                                    <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div id="portlet3" class="panel-collapse collapse show">
                                <div class="portlet-body">
                                    <div id="container" style="height: 365px;"></div>
                                </div>
                            </div>
                        </div> <!-- /Portlet -->
                    </div>
                </div> <!-- End row-->
                <!-- End new chart -->

            </div> <!-- container -->

        </div> <!-- content -->



    </div>


</div>

<?php $this->load->view('reports/modal_alertas') ?>

<!-- Modal -->
<div id="custom-modal" class="modal-demo" style="display:none">
    <button type="button" class="close" onclick="Custombox.close();">
        <span>&times;</span><span class="sr-only">Close</span>
    </button>
    <h4 class="custom-modal-title">Oferta por tiempo limitado</h4>
    <div class="custom-modal-text">
        <h5>Estimado socio estamos muy contentos de ser su aliado de gestion comercial por eso queremos premiarlo con 3 meses gratis.</h5>
        <br>
        Para acceder al beneficio de 3 meses gratis debe seguir los siguientes pasos.
        <br>
        -Registrar 2 recomendados a usar el servicio de bfacturas
        <br>
        -Escanea el codigo QR.
        <br>
        -Rellene los datos solicitados.
        <br>       
    </div>
    <div class="modal-body text-center">
        <!-- Aquí mostramos el código QR -->
        <iframe src="https://qr-codes-svg-wapp.s3.amazonaws.com/bmTIVM.svg?1733223794025" width="50%" height="200px" frameborder="0"></iframe>
        <!-- Aquí mostramos el código aleatorio generado -->
        <p id="codigoAleatorio" class="mt-3"></p>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Función para generar un código aleatorio
        const generarCodigo = () => {
            const randomNum = Math.floor(100000 + Math.random() * 900000); // Genera un número de 6 dígitos
            return `BE${randomNum}`; // Añade las iniciales BE al código generado
        };

        // Función para abrir el modal y mostrar el código aleatorio
        var showModal = <?php echo json_encode($this->session->userdata('show_modal')); ?>;
        const abrirModal = () => {
            // Generar el código aleatorio
            const codigo = generarCodigo();

            // Actualizar el contenido del modal con el código generado
            document.getElementById('codigoAleatorio').textContent = `Código referido: ${codigo}`;

            // Abrir el modal con Custombox
            if (showModal) {
            Custombox.open({
                target: '#custom-modal',
                effect: 'newspaper', // Puedes cambiar el efecto si lo deseas
                overlayColor: 'rgba(0, 0, 0, 0.7)'
            });
            }
        };

        // Llamamos a la función para abrir el modal al cargar la página
        abrirModal();
    });
</script>




<script type="text/javascript">

    $(document).ready(function () {
        var base_url = "<?php echo base_url(); ?>";
        var year = (new Date).getFullYear();
        var d = new Date();
        var month = new Array();
        month[0] = "Enero";
        month[1] = "Febrero";
        month[2] = "Marzo";
        month[3] = "Abril";
        month[4] = "Mayo";
        month[5] = "Junio";
        month[6] = "Julio";
        month[7] = "Agosto";
        month[8] = "Septiembre";
        month[9] = "Octubre";
        month[10] = "Noviembre";
        month[11] = "Diciembre";

        var n = month[d.getMonth()];

        datagrafico(base_url, year);
        datagraficoVentasTopProd(base_url, n); // inicialmente sacar del mes actual
        $("#year").on("change", function () {

            yearselect = $(this).val();
            datagrafico(base_url, yearselect);
        });

        $("#mesVentasTopProd").on("change", function () {

            mesSelected = $(this).val();
            datagraficoVentasTopProd(base_url, mesSelected);
        });


        $('.datepicker').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            todayHighlight: true,
            orientation: "top auto",

            todayBtn: true,
            todayHighlight: true,
        });


        function datagrafico(base_url, year) {
            namesMonth = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Set", "Oct", "Nov", "Dic"];
            $.ajax({
                url: base_url + "reportes/Regdashboard/getData",
                type: "POST",
                data: { year: year },
                dataType: "json",
                success: function (data) {
                    var meses = new Array();
                    var montos = new Array();
                    $.each(data, function (key, value) {
                        meses.push(namesMonth[value.mes - 1]);
                        valor = Number(value.montos);
                        montos.push(valor);
                    });
                    graficar(meses, montos, year);
                }
            });
        }
        function graficar(meses, montos, year) {
            Highcharts.chart('grafico', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Ventas Totales'
                },
                subtitle: {
                    text: 'Año:' + year
                },
                xAxis: {
                    categories: meses,

                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total en Soles'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        colorByPoint: true,
                        pointPadding: 0.2,
                        borderWidth: 0
                    },
                    series: {
                        dataLabels: {
                            enabled: true,
                            formatter: function () {
                                return Highcharts.numberFormat(this.y, 2)
                            }

                        }
                    }
                },
                series: [{
                    name: 'Ventas en soles ',
                    data: montos

                }

                ]


            });


            // $("#mes").on("change",function(){
            //     //poner grafico de cobros mes
            // }

        }


    })

    function datagraficoVentasTopProd(base_url, mes) {
        $.ajax({
            url: base_url + "reportes/Regdashboard/vendidosProduct",
            type: "GET",
            data: { mes: mes },
            dataType: "json",
            success: function (data) {
                var dataPie = [];
                $.each(data, function (key, value) {
                    if (key == 0) {
                        dataPie.push({
                            name: value.nombreProduct,
                            y: parseFloat(value.cantVentaProducto),
                            sliced: true,
                            selected: true
                        })
                    } else {
                        dataPie.push({
                            name: value.nombreProduct,
                            y: parseFloat(value.cantVentaProducto)
                        })
                    }
                });
                graficarVentasTopProd(dataPie);
            }
        });
    }


    function graficarVentasTopProd(data) {
        Highcharts.chart('container', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Productos Mas Vendidos'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'topProductos',
                colorByPoint: true,
                data: data
            }]
        });


        // $("#mes").on("change",function(){
        //     //poner grafico de cobros mes
        // }

    }


</script>