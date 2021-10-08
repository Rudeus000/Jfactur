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
              <!-- <h4 class="page-title float-left"><i class="fas fa-arrows-alt-h" aria-hidden="true"></i> Traspasos</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Almacen</a></li>
                <li class="breadcrumb-item"><a href="#">Traspaso</a></li>
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
            <div class="card-header bg-success"><h3 class="my-0 text-white">Lista de traspasos<a href="<?= base_url('administrador/regtraspasos/agregar') ?>" class="btn btn-rounded btn-pink float-right" ><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
              <div class="card-body table-responsive">
               
                <fieldset>
                  <!-- <legend>Filtro</legend> -->
                  <form id="FormTraspasosFiltro" action="" method="post" autocomplete="off">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Fecha:</label>
                          <div class="input-group">
                            <input type="text" name="desde" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                            <input type="text" name="hasta" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Origen:</label>
                          <select name="origen" class="form-control select2">
                            <option value="">Seleccione</option>
                            <?php foreach ($almacenes as $a): ?>
                            <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Destino:</label>
                          <select name="destino" class="form-control select2">
                            <option value="">Seleccione</option>
                            <?php foreach ($almacenes as $a): ?>
                            <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                            <?php endforeach ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-rounded btn-success" style="margin-top: 29px"><i class="fab fa-earlybirds m-r-5"></i>Buscar</button>
                      </div>
                    </div>
                  </form>
                </fieldset>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <a id="TraspasosReportePdf" href="#" class="btn btn-rounded btn-pink" target="_blank"><i
                                                             class="far fa-file-pdf"></i> PDF</a>
                    <a id="TraspasosReporteExcel" href="#" class="btn btn-rounded btn-purple" target="_blank"><i class="far fa-file-excel"></i> EXCEL</a>
                  </div>
                </div>
                <br>
                <div>
                  <table id="TableTraspasos" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th></th>
                        <th style="text-align: center;">Id</th>
                        <th style="text-align: center;">Fecha</th>
                        <th style="text-align: center;">Almacen Origen</th>
                        <th style="text-align: center;">Almace Destino</th>
                        <th style="text-align: center;">Producto</th>
                        <th style="text-align: center;">Cantidad</th> 
                        <th style="text-align: center;">Usuario</th>  
                        <th style="text-align: center;">Observación</th>                        
                        <th style="text-align: center;"></th>
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
