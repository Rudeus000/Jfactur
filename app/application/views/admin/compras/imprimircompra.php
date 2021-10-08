<div class="w120">
	<div class="w30">
		<!-- <img src="<?= base_url('assets/uploads/logo/'.$this->session->userdata('foto')) ?>" style="max-width: 100px"> -->
		<img src="<?= base_url('assets/images/logo/bitel.jpg') ?>" style="max-width: 120px;">
	</div>
	<div class="w40 text-center">
		<h5 style="font-weight: bold;"><?= $empresa->razon_social ?></h5>
		
			<p style="font-size: 10px; font-weight: bold;">  <?= $empresa->direcc_emp ?>
		 
			<!-- <b style="font-size: 12px;"> AYACUHO - LIMA </b> -->
			</p>
			
		
		
	</div>
	<div class="w30">
		<div class="Comp-Marco">
			<div class="Com-Marco-RUC"><b>R.U.C <?= $empresa->ruc_emp ?><b></div>
			<div class="Com-Marco-Recibo"><?= $compras->documento_comp ?></div>
			<div class="Com-Marco-Serie"><?= $compras->numdocumento_comp ?></div>
		</div>
	</div>
</div>
<br><br>

<div class="Com-Datos">
	Datos Del Proveedor
</div>

<br>
<div class="w100">
	<div class="w60">
		<p><b>Nombre/ Razon Social:</b> &nbsp;&nbsp;<?= $compras->tb_proveedor_nom ?></p>
		<p><b>Dirección:</b>&nbsp;&nbsp; <?= $compras->tb_proveedor_dir ?></p>
		<p><b>RUC:</b>&nbsp;&nbsp; <?= $compras->tb_proveedor_doc ?></p>
		<p><b>Contacto:</b>&nbsp;&nbsp; <?= $compras->tb_proveedor_con ?></p>

	</div>
	<div class="w40">
		<p><b>Moneda:</b> &nbsp;&nbsp;SOLES  &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; <b>Tipo compra:</b> <?= $compras->tipopago?></p>
		<p><b>Fecha Emisión:</b> &nbsp;&nbsp;<?= $compras->fecha_comp ?></p>
		<p><b>Fecha de Vencimiento:</b> <?= $compras->fecvenc_comp?></p>
		<p><b>N° Dias Pago:</b> <?= $compras->dias_comp?></p>
	</div>
</div>
<br>

<div class="w100">
	   


 		  <table class="table table-bordered">
	<thead>	
		<tr>
			<th style="border:1px solid #070707; padding: 5px; text-align: center; width: 5px;" height="5">Codigo</th>
			<th style="border:1px solid #070707; padding: 0px; text-align: center; width: 240px;">Descripcion</th>
			<th style="border:1px solid #070707; padding: 0px; text-align: center; width: 50px;">Unidad</th>
			<th style="border:1px solid #070707; padding: 0px; text-align: center; width: 60px">Cantidad</th>
			<th style="border:1px solid #070707; padding: 0px; text-align: center; width: 70px">V.Unitario</th>
			<th style="border:1px solid #070707; padding: 0px; text-align: center; width: 70px">Impuesto</th>
			<th style="border:1px solid #070707; padding: 0px; text-align: center; width: 20px">Valor Total</th>

		</tr>
	</thead>
 	<tbody>
		<?php foreach ($compras->detalle as $dt): ?>
		<tr>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dt->cod_producto ?></td>
			 <td style="border:1px solid #070707; padding: 6px;  "><?= $dt->nomb_product?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center;  "><?= $dt->abreviatura_unid ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dt->cant_compdet ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dt->precunit_compdet ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dt->igv_compdet ?></td> 
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dt->precventa_compdet ?></td>  
		</tr>
		<?php endforeach ?>

	
		
		
	</tbody> 


</table>

</div>

<div class="w30" style="float: right; border: 1px solid #070707">
	   		

			
			<div style="border:1px solid #070707;  padding:0px; font-size: 10;" height="10">
				 <b> SubTotal: </b> &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a><?= $compras->subtotal_comp ?> </a>
				 <p>
				<p> 
				<b> IGV: </b> &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $compras->igv_comp ?> 
				<p>
				<b> Total: </b>	 &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $compras->total_comp ?>  
		
					
			</div>

				

	

</div>
	

<!-- <h2 class="text-center">Plan de Tratamiento</h2> -->