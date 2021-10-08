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
              <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> BALANCE</h4>
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Utilidad</a></li>
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
              <div class="card-body table-responsive">
                        <fieldset>
                  <legend>Filtro</legend>
                  <form id="FormBalanceFiltro" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-3">                       
                      
                       <div class="form-group">
                          <label>Desde</label>
                            <div>
                              <div class="input-group">
                                <input type="text" name="desde" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                                <div class="input-group-append">
                                 <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                              </div><!-- input-group -->
                            </div>
                          </div>
                      </div>
                       <div class="col-md-3">
                           <div class="form-group">
                          <label>Hasta</label>
                            <div>
                              <div class="input-group">
                                <input type="text" name="hasta" class="form-control datepicker" value="<?= date('Y-m-d') ?>" >
                                <div class="input-group-append">
                                 <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                              </div><!-- input-group -->
                            </div>
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
                      
               <div class="col-md-3">
                                      <div class="form-group">
                                        <label>Exportar</label>
                                        <div>
                                        <a id="CuentasCobrarReportePdf" href="#" class="btn btn-pink" target="_blank"><i class="far fa-file-pdf"></i> PDF</a>
                                        <a id="Reportutilidadexcel" href="#" class="btn btn-purple" target="_blank"><i class="far fa-file-excel"></i> EXCEL</a>
                                      </div>
                                      </div>

                                      
                        </div>             
              
              
                  
                    </div>
                  </form>
                </fieldset>
               
            
                  <table id="TableBalance" class="table table-bordered table-condensed"  cellspacing="0" width="100%">                   
                    <thead>
                      <tr class="btn-primary btn-xs">
                          <th style="text-align: center">Tipo</th>
                          <th style="text-align: center">Fecha</th>
                          <th style="text-align: center">Sede</th>
                          <th style="text-align: center">Categoría</th>
                          <th style="text-align: center">Descripción</th>
                          <th style="text-align: center">Total</th>
                        </tr>
                    </thead>                     

                  </table>

               
                <!-- End #wizard-vertical -->

                   <div class="col-md-4">
                    <br>
                    <table class="table table-bordered">
                      <tbody>
                        <tr class="btn-purple">
                          <th>Ingresos:</th>
                          <td id="ingresos"></td>
                        </tr>
                        <tr class="btn-danger">
                          <th>Egresos:</th>
                          <td id="egresos"></td>
                        </tr>
                        <tr class="btn-info">
                          <th>Total:</th>
                          <td id="balance"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
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


