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
              <!-- <h4 class="page-title float-left">¿Qué hay de nuevo?</h4> -->
            </div>
          </div>
        </div>

        <!-- end row -->


    <!-- Vertical Steps Example -->
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
					<div class="card-header bg-primary"><h3 class="my-0 text-white">Administrar publicación<button  class="btn btn-pink waves-effect w-md waves-light float-right" id="agregarNuevoModal" ><i class="fa fa-plus m-r-5"></i>Crear</button></h3></div> 
						<div class="card-body table-responsive">

							<!-- <div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<a id="agregarNuevoModal" class="btn btn-primary"><i class="fas fa-user-plus"></i> Agregar</a>
									</div>
								</div>
							</div> -->
							<!-- <br> -->

							<table id="TableNuevo" class="table  table-striped" cellspacing="0" width="100%">
								<thead>
									<tr class="bg-primary text-white">
										<th>Fecha</th>
										<th>Título</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($datos as $d): ?> 
										<tr>
											<td><?= $d->fecha ?></td>
											<td><?= $d->titulo ?></td>
											<td>
												<button data-id="<?= $d->id ?>" class="btn btn-warning btn-sm editarNuevo"><i class="fa fa-edit"></i></button>
												<button data-id="<?= $d->id ?>" class="btn btn-danger btn-sm eliminarNuevo"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
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

<!-- Modal -->
<div class="modal fade" id="ModalAgregarNuevo"  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
			<form id="FormAgregarNuevo" action="<?= base_url('administrador/regnuevo/agregar') ?>" autocomplete="off" method="post">
				<div class="modal-header bg-primary">
					<h5 class="modal-title text-white" id="exampleModalLabel">Agregar</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Título</label>
								<input type="text" name="titulo" class="form-control">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<textarea name="contenido" style="display:none"></textarea>
								<label class="control-label">Contenido</label>
								<textarea id="contenidoTiny"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Guardar</button>
				</div>
			</form>
    </div>
  </div>
</div>




<div class="modal fade" id="ModalEditarNuevo" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
			<form id="FormEditarNuevo" action="<?= base_url('administrador/regnuevo/editar') ?>" autocomplete="off" method="post">
				<input type="hidden" name="id">
				<div class="modal-header bg-primary">
					<h5 class="modal-title text-white" id="exampleModalLabel">Editar</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Título</label>
								<input type="text" name="titulo" class="form-control">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<textarea name="contenido" style="display:none"></textarea>
								<label class="control-label">Contenido</label>
								<textarea id="contenidoTinyEdit"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Guardar</button>
				</div>
			</form>
    </div>
  </div>
</div>

