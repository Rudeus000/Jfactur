<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BFacturas - SMS</title>
        <meta content="Sistema de gestión de ventas con facturacion electrónica" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favicon.ico">

        <!-- App css -->
        <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/app.css" rel="stylesheet" type="text/css" />
        <!-- your custom css -->
        <link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet" type="text/css" />

        <script src="<?= base_url() ?>assets/js/modernizr.min.js"></script>

    </head>


    <body class="pb-0">

			<div class="content">
				
					<div class="container-fluid mt-2">
						<div class="row">
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<h4 class="header-title m-t-0 mb-4">Datos del empleado</h4>
										<div class="media-main">
											<a class="float-left mr-3" href="#">
													<img class="thumb-lg rounded-circle bx-s" src="<?= base_url() ?>assets/images/users/avatar-2.jpg" alt="">
											</a>
										
											<div class="info">
													<h4 class="pt-2"><?= $usuario->nomb_usu.' '.$usuario->apell_usu ?></h4>
													<p class="text-muted"><?= $perfil->nomb_perfil ?></p>
											</div>
										</div>

										<ul class="list-group">
											<li class="list-group-item d-flex justify-content-between align-items-left">
												<p class="m-0">
													<b>Documento:</b> <?= $usuario->docum_usu ?>
												</p>
												<span class="fa fa-id-card"></span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center">
												<p class="m-0">
													<b>Teléfono:</b>  <?= $usuario->telf_usu ?>
												</p>
												<span class="fa fa-phone"></span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center">
												<p class="m-0">
													<b>Dirección:</b>  <?= $usuario->direcc_usu ?>
												</p>
												<span class="fa fa-shipping-fast"></span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center">
												<p class="m-0">
													<b>Email:</b> <?= $usuario->email_usu ?>
												</p>
												<span class="fa fa-envelope-open"></span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center">
												<p class="m0">
													<b>Usuario:</b> <?= $usuario->login_usu ?>
												</p>
												<span class="fa fa-user"></span>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="col-md-12">
									<div class="card">
										<div class="card-body">
											<h4 class="header-title m-t-0 mb-4">Acceso a las sucursales</h4>
											<table class="table table-bordered table-condensed">
												<thead>
													<tr>
														<th>Opciones</th>
														<th>Por Defecto</th>
														<th>Descripción</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach($sucursales as $s): ?> 
														<tr>
															<td>
																<a href="<?= base_url('auth/setPuntoVenta/'.$s->cod_puntoventa) ?>" class="btn btn-primary btn-sm">Acceder</a>
															</td>
															<td>
																<?php if($s->cod_puntoventa==$this->session->userdata('puntoventa_reportes')): ?> 
																<i class="fa fa-check"></i>
																<?php endif ?>
															</td>
															<td><?= $s->nomb_puntoventa ?></td>
														</tr>
														<?php endforeach ?>
														<?php if($perfil->cod_perfil==1): ?> 
														<tr>
															<td>
																<a href="<?= base_url('auth/setPuntoVenta/admin') ?>" class="btn btn-primary btn-sm">Acceder</a>
															</td>
															<td>
																<?php if($this->session->userdata('puntoventa_reportes')=='admin'): ?> 
																	<i class="fa fa-check"></i>
																<?php endif ?>
															</td>
															<td>Admin</td>
														</tr>
														<?php endif ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<h3>¿Qué hay de nuevo?</h3>
									</div>
								</div>										
								<?php foreach($nuevos as $n): ?> 
								<div class="col-md-12">
									<div class="card">
										<div class="card-body quehaydenuevo-content">											
											<h2 style="color:#333;text-decoration:underline"><?= $n->titulo ?></h2>
											<div class="row">
												<div class="col-md-12">
													<?= $n->contenido ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php endforeach ?>
							</div>
						</div>
					</div>

			</div>




        <!-- jQuery  -->
        <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/metisMenu.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/waves.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.backstretch.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/scripts.js"></script>
        <!-- App js -->
        <script src="<?php echo base_url();?>assets/js/jquery.core.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.app.js"></script>

    </body>
</html>
