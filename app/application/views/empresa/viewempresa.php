<div class="content-page">
    <!-- Start content -->
    <div class="content">
      <div class="container-fluid">
				<div class="row">
					<div class="col-12 mb-2">
						<div class="page-title-box">
							<h4 class="page-title float-left"> <i class="fas fa-box-open"></i> Informacion de Empresa</h4>
						</div>
					</div>
				</div>
        <div class="card">
          <div class="card-body">
            <form id="FormEmpresa" action="<?= base_url('empresa/Regempresa/guardarDatos') ?>"  enctype="multipart/form-data" method="POST">           	

              <div class="form-body">
              	
								<div class="row">
									<div class="dropzone" id="dropzone">
									<div class="col-md-12">
										<div class="form-group">
											<!-- <img width="150px" id="logo-archivo" src="<?= base_url_app('assets/images/logo/'.$empresa->photo) ?>" alt="Logo"> -->
											<img width="150px" id="logo-archivo" src="<?= base_url_app('assets/uploads/logo/'.$empresa->photo) ?>" alt="Logo">
										</div>
									
										<label class="control-label mt-4"></label>
										
										<div class="custom-file">
											<input type="file" class="custom-file-input " id="customFileLogo" name="logo">
												<label class="custom-file-label" for="customFileLogo">Seleccionar Logo</label>
											</div>
											</div>									
									</div>
								</div>

								<div class="row">								
								<div class="col-md-4" hidden="">
					              <div class="form-group">
					                <label class="control-label">Tipo:</label>
					                <select name="tipo" id="tipo_documento" class="form-control">
					                  <option value="4">RUC</option>
					                  <option value="2">DNI</option>
					                </select>
					              </div>
					            </div>																		

																	
                                    <div class="col-md-6">
                                    	<div id="capa_load"></div>
                                      <label class="control-label">RUC</label>
                                        <div class="input-group">                                                        	
                                            <input type="text" class="form-control" name="RUC" value="<?= $empresa->ruc_emp ?>" id="txt_documento">
                                               <div class="input-group-append">
                                                  <button class="btn btn-primary waves-effect waves-light" type="button" onclick="buscar();">sunat</button>
                                                </div>
                                        </div>
                                    </div>                                         
                                              
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Razon Social</label>
											<input type="text" class="form-control" name="razon_social" value="<?= $empresa->razon_social ?>" id="txt_nombre">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Nombre Comercial</label>
											<input type="text" class="form-control" name="nombre_comercial" value="<?= $empresa->nombre_comercial ?>" id="txt_nombre_comercial">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Teléfono</label>
											<input type="text" class="form-control" name="telefono" value="<?= $empresa->telf_emp ?>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Email</label>
											<input type="text" class="form-control" name="email" value="<?= $empresa->email_emp ?>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Ubigeo</label>
											<select name="ubigeo" class="form-control select2">
												<option value="">Seleccione</option>
												<?php foreach($ubigeos as $u): ?> 
												<option value="<?= $u->ubigeo ?>" <?= $u->ubigeo==$empresa->ubigeo_emp?'selected':'' ?>><?= $u->departamento.' - '.$u->provincia.' - '.$u->distrito ?></option>
												<?php endforeach ?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Urbanización</label>
											<input type="text" class="form-control" name="urbanizacion" value="<?= $empresa->urbanizacion_emp ?>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Direccion Fiscal</label>
											<input type="text" class="form-control" name="direccion" value="<?= $empresa->direcc_emp ?>" id="txt_direccion">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Regimen</label>
											<select name="regimen" class="form-control">
												<option value="Si" <?= $empresa->regimen_emp=='Si'?'selected':'' ?> >Si</option>
												<option value="No" <?= $empresa->regimen_emp=='No'?'selected':'' ?>>No</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">¿Restricción de stock?</label>
											<select name="restriccion_stock" class="form-control">
												<option value="Si" <?= $empresa->restriccion_stock_emp=='Si'?'selected':'' ?> >Si</option>
												<option value="No" <?= $empresa->restriccion_stock_emp=='No'?'selected':'' ?>>No</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">¿Multialmacen?</label>
											<select name="multialmacen" class="form-control">
												<option value="Si" <?= $empresa->multialmacen_stock_emp=='Si'?'selected':'' ?> >Si</option>
												<option value="No" <?= $empresa->multialmacen_stock_emp=='No'?'selected':'' ?>>No</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">¿Rest. Precio Mínimo?</label>
											<select name="restriccion_precio_minimo" class="form-control">
												<option value="Si" <?= $empresa->restriccion_precio_minimo_emp=='Si'?'selected':'' ?> >Si</option>
												<option value="No" <?= $empresa->restriccion_precio_minimo_emp=='No'?'selected':'' ?>>No</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="alert alert-secondary text-center" role="alert">
											Selecciona el modo de envio de CE por defecto:
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label d-block">¿Envio automatico de factura a SUNAT?</label>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="enviar_factura_emp" id="inlineRadio1" value="1" <?= ($empresa->enviar_factura_emp=='1')?'checked':'' ?>>
												<label class="form-check-label" for="inlineRadio1">Enviar a SUNAT automatico</label>
											</div>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="enviar_factura_emp" id="inlineRadio2" value="0" <?= ($empresa->enviar_factura_emp=='0')?'checked':'' ?>>
												<label class="form-check-label" for="inlineRadio2">Solo firmar</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="alert alert-secondary text-center" role="alert">
											Usuario SOL
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Usuario SOL</label>
											<input type="text" class="form-control" name="usuario_sol" value="<?= $empresa->usuario_sol_emp ?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Contraseña SOL</label>
											<input type="password" class="form-control" name="contrasena_sol" value="<?= $empresa->contrasena_sol_emp ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="alert alert-secondary text-center" role="alert">
											Certificado
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Certificado</label>
											<span class="badge badge-primary" id="certificado-archivo"><?= $empresa->certificado_emp ?></span>
											<div class="custom-file">
												<input type="file" class="custom-file-input" id="customFile" name="certificado">
												<label class="custom-file-label" for="customFile">Seleccionar Certificado</label>
											</div>
										</div>
									</div>						

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Contraseña Certificado</label>
											<input type="password" class="form-control" name="contrasena_certificado" value="<?= $empresa->contrasena_certificado_emp ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-primary btn-md mt-4">Guardar Cambios</button>
									</div>
								</div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- container -->         
    </div>
    <!-- content -->
  </div>
