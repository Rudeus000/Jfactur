<div class="w120">
	<div class="w30">
		<!-- <img src="<?= base_url('assets/uploads/logo/'.$this->session->userdata('foto')) ?>" style="max-width: 100px"> -->
		<img src="<?= base_url_app('assets/uploads/logo/'.$empresa->photo) ?>" style="max-width: 120px;">
	</div>
	<div class="w40 text-center">
		<p style="font-size: 18px; padding-left: -70px;"><?= $empresa->razon_social ?></p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"> Ruc: <?= $empresa->ruc_emp ?> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -11px;"> Dirección: <?= $empresa->direcc_emp ?> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"> Telefono: <?= $empresa->telf_emp ?> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"> Email: <?= $empresa->email_emp ?> </p> 
		<!-- <b style="font-size: 12px;"> AYACUHO - LIMA </b>		 -->
	</div>

	<div class="w30">
		<div class="Comp-Marco" style="float: right; border: 1.5px solid #03A6BF">
			<div class="Com-Marco-RUC"></div>
			<div class="Com-Marco-Recibo" style="font-size: 12px; background: #03A6BF"><?= $cotizacion->nom_tipdocumento ?>&nbsp;&nbsp;<?= $cotizacion->serie ?> - N° <?= $cotizacion->numero_cot ?></div>
			<!-- <div class="Com-Marco-Serie"><?= $compras->numdocumento_comp ?></div>	 -->
			<div class="Com-Marco-RUC"></div>	
		</div>

	</div>

	<div class="w100" style="padding-top: -55px;">
	<!-- <div class="w25 text-center" style="float: right; border: 1.5px solid #03A6BF;border-radius: 10px;"> -->
		<div class="Comp-Marco" style="float: right; border: 1.5px solid #03A6BF">
			<div class="Com-Marco-RUC" style="padding-top: 5px;"><b>FECHA<b></div>
			<div class="w100" style="border-bottom:1.5px solid #03A6BF;margin:5px 0"></div>
			<div class="Com-Marco-Serie"><?= $cotizacion->fecha_cot ?></div>
		</div>

	</div>
</div>
<br><br>

<div class="Com-Datos" style="background: #03A6BF;color:white">
	CLIENTE
</div>


<br>
<div class="w100">

		<p style="font-size: 11px; padding-top: -8px;"><b>Razon Social:</b>&nbsp;&nbsp;<?= $cotizacion->nomb_cliente ?></p>
		<p style="font-size: 11px; padding-top: -8px;"><b>Dirección:</b>&nbsp;&nbsp; <?= $cotizacion->direc_cliente ?></p>
		<p style="font-size: 11px; padding-top: -8px;"><b>RUC o DNI:</b>&nbsp;&nbsp; <?= $cotizacion->doc_cliente ?></p>
		<p style="font-size: 11px; padding-top: -8px;"><b>Email:</b>&nbsp;&nbsp; <?= $cotizacion->email_cliente ?></p>
		<p style="font-size: 11px; padding-top: -8px;"><b>Telefono:</b>&nbsp;&nbsp; <?= $cotizacion->telf_cliente ?></p>
		<p style="font-size: 11px; padding-top: -8px;"><b>Contacto:</b>&nbsp;&nbsp; <?= $cotizacion->contac_cliente ?></p>

</div>
<div class="w100" style="border-bottom:1px solid #03A6BF;margin:5px 0">
		</div>
<br>

<div class="w100">
	   


 		  <table class="table table-bordered">
	<thead>	
		<tr>
			<th style="font-size: 10px; background: #03A6BF;color:white; border:1px solid #03A6BF; padding: 5px; text-align: center; width: 5px;" height="5">CODIGO</th>
			<th style="font-size: 10px; background: #03A6BF;color:white; border:1px solid #03A6BF; padding: 5px; text-align: center; width: 240px;">DESCRIPCION</th>
			<th style="font-size: 10px; background: #03A6BF;color:white; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 50px;">UNIDAD</th>
			<th style="font-size: 10px; background: #03A6BF;color:white; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 60px">CANTIDAD</th>
			<th style="font-size: 10px; background: #03A6BF;color:white; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 70px">P.UNITARIO</th>
			<th style="font-size: 10px; background: #03A6BF;color:white; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 70px">DSCTO</th>
			<th style="font-size: 10px; background: #03A6BF;color:white; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 20px">SUBTOTAL</th>

		</tr>
	</thead>
 	<tbody>
		<?php foreach ($cotizacion->detalle as $dt): ?>
		<tr>
			<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $dt->cod_producto ?></td>
			 <td style="border:1px solid #03A6BF; padding: 6px;  "><?= $dt->nomb_product?></td>
			<td style="border:1px solid #03A6BF; padding: 6px; text-align: center;  "><?= $dt->abreviatura_unid ?></td>
			<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $dt->cant_cotdet ?></td>
			<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $dt->precunit_cotdet ?></td>
			<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $dt->descuento_cotdet ?></td> 
			<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $dt->subtotal_cotdet ?></td>  
		</tr>
		<?php endforeach ?>

	
		
		
	</tbody> 


</table>

</div>

	<div class="w100">
	<div class="w30" style="float: right; padding: 5px;border:2px solid #03A6BF; border-radius: 10px;">
		<div class="w100">
			<div class="w50"><b>Gravada</b></div>
			<div class="w50" style="text-align:right"><?= number_format($cotizacion->subtotal_cot,2) ?></div>
		</div>

		<div class="w100">
			<div class="w50"><b>IGV</b></div>
			<div class="w50" style="text-align:right"><?= $cotizacion->igv_cot ?></div>
		</div>
		<div class="w100" style="border-bottom:1px solid #03A6BF;margin:5px 0">
		</div>
		<div class="w100">
			<div class="w50"><b>TOTAL</b></div>
			<div class="w50" style="text-align:right"><?= $cotizacion->total_cot ?></div>
		</div>
	</div>
</div>			


<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div class="w100">

		<p style="font-size: 15px; padding-top: -8px;">Consideraciones Comerciales:</p>
		<br>
		<p style="font-size: 12px; padding-top: -8px;"><b>* Los precios estan expresados en SOLES e incluyen IGV.</b></p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* En el precio no esta incluido los costos de envio.</p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* La oferta tiene validez 15 dias calendario.</p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* Forma de Pago: Deposito bancario o pago en efectivo</p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* Plazo maximo de pago:..............................</p>

</div>
	

<!-- <h2 class="text-center">Plan de Tratamiento</h2> -->


