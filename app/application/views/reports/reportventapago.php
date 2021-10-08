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
              <!-- <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> REPORTE VENTA PAGO </h4> -->
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
            <div class="card-header bg-success"><h3 class="my-0 text-white">Reporte por forma de pago</h3></div>
              <div class="card-body table-responsive">
                        <fieldset>
                  <legend>Filtro</legend>
                  <form id="ReportventaspagosFormBusqueda" action="" method="post" autocomplete="off">
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
                                        <label class="control-label">Forma de Pago:</label>
                                        <select name="pago" class="form-control">
                                           <option value="">--Todos--</option>
                                           <option value="CO">Contado</option>
                                           <option value="CRE">Credito</option>     
                                        </select>
                                      </div>
                        </div>

               
                
                        <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Punto de venta</label>
                                        <select name="punto" class="form-control select2">
                                           <option value="">--Todos--</option>
                                                  <?php foreach ($punto as $p): ?>
                                                  <option value="<?= $p->cod_puntoventa ?>"><?= $p->nomb_puntoventa  ?></option>
                                                    <?php endforeach ?>
                                        </select>
                                      </div>
                        </div> 
                       
             
                   
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Estado</label>
                          <select name="estado" class="form-control">
                            <option value="">Todos</option>
                            <option value="G">Generado</option>
                            <option value="A">Anulado</option>
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
                  <table id="Tablereportventaspagos" class="table table-striped table-condensed" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th style="text-align: center;">Fecha</th>
                         <th style="text-align: center;">Documento</th>
                         <th style="text-align: center;">Ruc/Dni</th>
                         <th style="text-align: center;">Cliente</th>                         
                          <th style="text-align: center;">Forma de Pago</th>
                          <th style="text-align: center;">Estado</th>  
                           <th style="text-align: center;">Total</th>
                                                
                      </tr>
                    </thead>
                       <tfoot>
                         <tr>
                             <th colspan="6" style="text-align:right">Total:</th>
                              <th><strong><span id="TotalPagos"></span></strong></th>
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


