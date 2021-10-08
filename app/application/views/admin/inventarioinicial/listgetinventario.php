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
                            <!-- <h4 class="page-title float-left"><i class="fas fa-dolly"></i>  Inventario Inicial (Ingresos)</h4> -->
                            <ol class="breadcrumb float-right">
                               
                                <li class="breadcrumb-item"><a href="#">Inventario Inicial</a></li>
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
                        <div class="card-header bg-success"><h3 class="my-0 text-white">Inventario incial</h3></div>
                            <div class="card-body table-responsive">
                                <form id="FormAlmacenInventarioInicialFiltro" action="" method="post" autocomplete="off">
                                  <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Almacen:</label>
                                            <select name="almacen" class="form-control select2">
                                                <?php foreach ($almacenes as $a): ?>
                                                <option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Producto:</label>
                                            <input type="text" name="producto" class="form-control" placeholder="Escriba y presione enter">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Categoria:</label>
                                            <select name="categoria" class="form-control select2">
                                                <option value="">Seleccione</option>
                                                <?php foreach ($categorias as $c): ?>
                                                <option value="<?= $c->cod_categoria ?>"><?= $c->nomb_categoria ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Marca:</label>
                                            <select name="marca" class="form-control select2">
                                                <option value="">Seleccione</option>
                                                <?php foreach ($marcas as $m): ?>
                                                <option value="<?= $m->cod_marca ?>"><?= $m->nomb_marca ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                  </div>
                                </form>

                                <br>
                                <div class="row float-right">
                                  <div class="col-md-12">
                                    <a id="InventarioInicialReportePdf" href="#" class="btn btn-rounded btn-pink" target="_blank"><i class="far fa-file-pdf"></i> PDF</a>
                                                             
                                    <a id="InventarioInicialReporteExcel" href="#" class="btn btn-rounded btn-purple" target="_blank"><i class="far fa-file-excel"></i> EXCEL</a>

                                    <a id="InventarioInicialReporteExcelSeries" href="#" class="btn btn-rounded btn-primary" target="_blank"><i class="far fa-file-excel"></i> Exportar series</a>
                                                            
                                  </div>
                                </div>
                                <br>
                                <div>
                                    <table id="TableAlmacenInventarioInicial" class="table  table-striped" cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="bg-success text-white">
                                                <th style="text-align: center;">Producto</th>
                                                <th style="text-align: center;">Marca</th>
                                                <th style="text-align: center;">Categoria</th>
                                                <th style="text-align: center;">Unidad</th>
                                                <th style="text-align: center;">P. Costo</th>
                                                <th style="text-align: center;">P. Venta</th>
                                                <th style="text-align: center;">Stock Actual</th>
                                                <th style="text-align: center;">Stock Inicial</th>
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


<div id="ModalInventarioSeries" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Series</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="TableInventarioSeries" class="table table-bordered table-sm">
					<thead>
						<tr>
							<th>Serie</th>
							<th>Estado</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
      </div>
    </div>
  </div>
</div>
