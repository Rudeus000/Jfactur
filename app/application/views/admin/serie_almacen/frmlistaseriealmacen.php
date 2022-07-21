        <!-- Begin page -->
		<script>
		var pathController="<?php echo base_url();?>administrador";
		</script>
		<script type="text/javascript" src="<?php echo base_url_app();?>assets/js/serie_almacen/frmlistaseriealmacen.js?v=<?php echo rand(0,5000);?>"></script>
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
                                    <!-- <h4 class="page-title float-left">Almacen</h4> -->
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="#">Serie almacén</a></li>
                                        <li class="breadcrumb-item active">Listado</li>
                                    </ol>
                           
                          </div>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                <div class="card-header bg-success"><h3 class="my-0 text-white">Lista de series almacen <a class="btn btn-rounded btn-pink float-right" id="btnnuevo" tabindex="0" aria-controls="datatable-buttons"><i class="fas fa-plus m-r-5"></i><span>Agregar</span></a> </h3></div>
                                    <div class="card-body table-responsive">				
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<div id="listado"></div>
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
		