<!-- Begin page -->
<div id="wrapper" data-stockminimos="1">


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
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#portlet1"><i class="mdi mdi-minus"></i></a>
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
                                            <option value="<?= $m->mes ?>"><?= $m->mes?></option>
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
                                            <?php foreach ($years as $year):?> 
                                                <option value="<?php echo $year->year;?>">
                                                    <?php echo $year->year;?>
                                                </option>
                                                <?php endforeach;?>                               
                                    </select>
                                </h3>
                                <div class="portlet-widgets">
                                    <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="mdi mdi-minus"></i></a>
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
                                    <select name="mesVentasTopProd" id="mesVentasTopProd"  class="form-control select2">
                                        <?php foreach ($product as $p): ?>
                                            <option value="<?php echo $p->mes;?>">
                                                           <?php echo $p->mes;?>
                                                </option>>
                                        <?php endforeach ?>
                                    </select>                                             
                                </h3>
                                <div class="portlet-widgets">
                                    <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#portlet3"><i class="mdi mdi-minus"></i></a>
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

<div id="ModalStockMinimos" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg">
  <div class="card-header bg-success"><h3 class="my-0 text-white">Alerta de productos con stock mínimo <i class="spinner-grow text-pink float-right"></i></h3></div>
    <div class="modal-content">
   
      <!-- <div class="modal-header">      
        <h4 class="modal-title"> <i class="fas fa-hourglass-half"></i> Alerta de productos con stock mínimo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
      <div class="modal-body">
            <table id="TableStockMinimos" class="table table-striped  tblstockminimo">
                <thead>
                    <tr class="bg-success text-white">
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
        <button type="button" class="btn btn-pink btn-rounded" id="posponer-stockminimo"><span class="m-r-5">Posponer</span><i class="fas fa-undo"></i></button>
        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button> -->
      </div>
    </div>
  </div>
</div>




<script type="text/javascript">

$(document).ready(function () {
 var base_url="<?php echo base_url();?>"; 
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
 datagraficoVentasTopProd(base_url,n); // inicialmente sacar del mes actual
 $("#year").on("change",function(){

    yearselect = $(this).val();
    datagrafico(base_url,yearselect);
 });

 $("#mesVentasTopProd").on("change",function(){

    mesSelected = $(this).val();
    datagraficoVentasTopProd(base_url,mesSelected);
 });


    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",

        todayBtn: true,
        todayHighlight: true,  
    });


function datagrafico(base_url,year){
    namesMonth= ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Set","Oct","Nov","Dic"];
    $.ajax({
        url: base_url + "reportes/Regdashboard/getData",
        type:"POST",
        data:{year: year},
        dataType:"json",
        success:function(data){
            var meses = new Array();
            var montos = new Array();
            $.each(data,function(key, value){
                meses.push(namesMonth[value.mes - 1]);
                valor = Number(value.montos);
                montos.push(valor);
            });
            graficar(meses,montos,year);
        }
    });
}
function graficar(meses, montos, year ) {
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
          series:{
            dataLabels:{
                enabled:true,
                formatter:function(){
                    return Highcharts.numberFormat(this.y,2)
                }

            }
        }
    },
    series: [{
        name: 'Ventas en soles ' ,
        data: montos

    }

     ]

   
});


    // $("#mes").on("change",function(){
    //     //poner grafico de cobros mes
    // }

}


})

function datagraficoVentasTopProd(base_url,mes){   
    $.ajax({
        url: base_url + "reportes/Regdashboard/vendidosProduct",
        type:"GET",
        data:{mes: mes},
        dataType:"json",
        success:function(data){
            var dataPie = [];
            $.each(data,function(key, value){
                if(key==0){
                    dataPie.push({
                        name : value.nombreProduct,
                        y : parseFloat(value.cantVentaProducto),
                        sliced : true,
                        selected : true
                    })
                }else{
                   dataPie.push({
                        name : value.nombreProduct,
                        y : parseFloat(value.cantVentaProducto)
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
