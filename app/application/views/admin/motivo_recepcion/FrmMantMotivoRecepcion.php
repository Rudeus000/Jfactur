		<script src="<?php echo base_url();?>/assets/AdminLTE/plugins/jquery-validation/jquery.validate.min.js"></script>		<script src="<?php echo base_url();?>/assets/AdminLTE/plugins/jquery-validation/additional-methods.min.js"></script>		<script src="<?php echo base_url();?>/assets/js/FrmMantMotivoRecepcion.js?ver=<?php echo rand(5, 50000)?>"></script>		<script>var pathController="<?php echo base_url();?>/index.php";</script>
<div class="row"><div class="col-md-12">&nbsp;</div></div>		<div class="card">
			<div class="card-header">
				<strong></strong>
			</div>
			<div class="card-body">
			<form id="frmMant">				<input type="hidden" id="t_codigo" name="t_codigo" value="<?php echo ($update==1?$id:'')?>" /> 
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="des_motivo">Descripcion<span style="color:red;">*</span></label>
<input type="text" id="t_des_motivo" class="form-control" name="t_des_motivo" value="<?php echo ($update==1?$entity['des_motivo']:''); ?>"  />
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="tipo_operacion">Tipo<span style="color:red;">*</span></label>
<select id="cbo_tipo_operacion" class="form-control" name="cbo_tipo_operacion"><option value="I">Ingreso</option>
<option value="S">Salida</option></select>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="cod_transaccion">Transaccion SUNAT<span style="color:red;">*</span></label>
<input type="text" id="t_cod_transaccion" class="form-control" name="t_cod_transaccion" value="<?php echo ($update==1?$entity['cod_transaccion']:''); ?>"  />
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<input type="submit" class="btn btn-primary" id="btnsave" value="Grabar" />&nbsp;&nbsp;<input type="button" class="btn btn-primary" id="btncancel" value="Cancelar" />
				</form>			</div>
		</div>
<script>
<?php if($update==1){ 
?>$('#cbo_tipo_operacion').val('<?php echo trim($entity['tipo_operacion']); ?>');
<?php } else {
 ?><?php }
 ?></script>