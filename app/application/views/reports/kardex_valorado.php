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
            <div class="card-header bg-success"><h3 class="my-0 text-white">Kardex valorizado </h3></div>
              <div class="card-body table-responsive">
                <fieldset>
                  <!-- <legend>Filtro</legend> -->
                  <form id="FormKardexFisico" action="" method="post" autocomplete="off">
                    <div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Producto</label>
								<input type="text" name="txtprod" id="txtprod" class="form-control ">
								<input type="hidden" name="txtidprod" id="txtidprod" class="form-control ">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">Fecha</label>
								<input type="text" name="fecha" id="fecha" class="form-control yearmonthpicker" value="<?= date('Y-m') ?>">
							</div>
						</div>
						  <!--<div class="col-md-2">
							<div class="form-group">
							  <label class="control-label">Almacén</label>
								<select name="almacen" id="almacen" class="form-control">
									<?php foreach($almacenes as $a): ?> 
									<option value="<?= $a->cod_almacen ?>"><?= $a->nomb_almacen ?></option>
									<?php endforeach ?>
								</select>
							</div>
						  </div>-->
						<div class="col-md-3">
							<div class="form-group">
							  <button style="margin-top:27px" type="button" id="btnbuscarvalorizado" class="btn btn-rounded btn-success">Filtrar</button>
							  <button style="margin-top:27px" id="btnexportarvalorizado" class="btn btn-rounded btn-info"><i class="far fa-file-excel"></i> Exportar</button>
							</div>
						</div>                  
                    </div>
                  </form>
                </fieldset>              
                <div>
				<div id="listado"></div>                 
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
