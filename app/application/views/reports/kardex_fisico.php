<!-- Begin page -->
<script>
var pathController="<?php echo base_url();?>administrador";
</script>
<script type="text/javascript" src="<?php echo base_url_app();?>assets/js/Kardex/FrmKardex.js?v=<?php echo rand(0,5000);?>"></script>
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
              <!-- <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> Movimiento Almacen Físico</h4> -->
            </div>
          </div>
        </div>

        <!-- end row -->

        <!-- Vertical Steps Example -->
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
            <div class="card-header bg-success"><h3 class="my-0 text-white">Kardex fisico </h3></div>
              <div class="card-body table-responsive">
                <fieldset>
                  <!-- <legend>Filtro</legend> -->
                  <form id="FormKardexFisico" action="" method="post" autocomplete="off">
                    <div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">Producto</label>
								<input type="text" name="txtprod" id="txtprod" class="form-control ">
								<input type="hidden" name="txtidprod" id="txtidprod" class="form-control ">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label">Fecha</label>
								<input type="text" name="fecha" id="fecha" class="form-control yearmonthpicker" value="<?= date('Y-m') ?>">
							</div>
						</div>
						  <div class="col-md-2">
							<div class="form-group">
							  <label class="control-label">Almacén</label>
								<select name="almacen" id="almacen" class="form-control">
									<?php foreach($almacenes as $a): ?> 
									<option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
									<?php endforeach ?>
								</select>
							</div>
						  </div>
						<div class="col-md-3">
							<div class="form-group">
							  <button style="margin-top:27px" type="button" id="btnbuscar" class="btn btn-rounded btn-success">Filtrar</button>
							  <button style="margin-top:27px" id="btnexportar" class="btn btn-rounded btn-info"><i class="far fa-file-excel"></i> Exportar</button>
							</div>
						</div>                  
                    </div>
                  </form>
                </fieldset>              
                <div>
				<div id="listado"></div>
                  <!--<table id="TableKardexFisico" class="table table-striped " cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th style="text-align: center;">Descripción</th>
												<th style="text-align: center;">Tipo</th>
												<th style="text-align: center;">Unidad</th>
												<th style="text-align: center;">Saldo Inicial</th>
												<th style="text-align: center;">(+)Ingresos</th>
												<th style="text-align: center;">(+)Transf. Dep.</th>
												<th style="text-align: center;">(-)Venta Total</th>
												<th style="text-align: center;">(-)Transf. Dep.</th>
												<th style="text-align: center;">(-)Obsequios</th>
												<th style="text-align: center;">(-)Bonificación</th>
												<th style="text-align: center;">Saldo Final</th>
                      </tr>
                    </thead>
										<tbody></tbody>
                  </table>-->

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
