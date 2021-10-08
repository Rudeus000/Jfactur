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
              <!-- <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> Reportes Cotizacion Productos </h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Cuentas por pagar</a></li>
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
            <div class="card-header bg-success"><h3 class="my-0 text-white">Reporte cotizacion por producto</h3></div>
              <div class="card-body table-responsive">
                        <fieldset>
                  <!-- <legend>Filtro</legend> -->
                  <form id="ReportcotiproductosFormBusqueda" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Fecha</label>
                          <div class="input-group">
                            <input type="text" name="desde" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                            <input type="text" name="hasta" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                          </div>
                        </div>
                      </div>
                
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Producto</label>
                          <input type="text" name="tb_producto" class="form-control">
                        </div>
                      </div>

                        <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Marca:</label>
                                        <select name="tb_marca" class="form-control select2">
                                           <option value="">--Todos--</option>
                                                  <?php foreach ($marca as $m): ?>
                                                  <option value="<?= $m->cod_marca ?>"><?= $m->nomb_marca  ?></option>
                                                    <?php endforeach ?>
                                        </select>
                                      </div>
                        </div>

                         <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Categoria:</label>
                                        <select name="tb_categoria" class="form-control select2">
                                           <option value="">--Todos--</option>
                                                  <?php foreach ($categoria as $c): ?>
                                                  <option value="<?= $c->cod_categoria ?>"><?= $c->nomb_categoria  ?></option>
                                                    <?php endforeach ?>
                                        </select>
                                      </div>
                        </div>  
          
                    </div>
                  </form>
                </fieldset>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <a id="CuentasCobrarReportePdf" href="#" class="btn btn-pink" target="_blank"><i class="far fa-file-pdf"></i> PDF</a>
                    <a id="CuentasCobrarReporteExcel" href="#" class="btn btn-purple" target="_blank"><i class="far fa-file-excel"></i> EXCEL</a>
                  </div>
                </div>
                <br>
                <div>
                  <table id="Tablereportcotiproductos" class="table table-bordered table-condensed table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th style="text-align: center; width: 50px">Codigo</th>
                         <th style="text-align: center;">Productos</th>
                         <th style="text-align: center;">Marca</th>
                         <th style="text-align: center;">Categoria</th>
                         <th style="text-align: center;">Unidad </th>
                         <th style="text-align: center; width: 60px">Cantidad</th>
                          <th style="text-align: center; width: 90px">Monto</th> 

                       <!--  <th style="text-align: center;">Monto</th>
                        <th style="text-align: center;">Abonos</th>
                        <th style="text-align: center;">Abonos</th>
                        
                        <th></th> -->
                      </tr>
                    </thead>
                       <tfoot>
                         <tr>
                             <th colspan="5" style="text-align:right">Total:</th>
                             <th><strong><span id="TotalPagosProductos"></span></strong></th>
                              <th><strong><span id="TotalPagosTotales"></span></strong></th>
                         </tr>
                     </tfoot>

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


