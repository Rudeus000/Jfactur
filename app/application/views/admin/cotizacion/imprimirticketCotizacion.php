<div class="w100 text-center" style="font-size:12px">
		<div><b><?= $empresa->razon_social ?></b></div>
		<div> <?= $empresa->direcc_emp ?> </div>
		<div>Ruc:<?= $empresa->ruc_emp ?></div>
		<div> Web: <?= WEBSITE ?> </div>
		<div><b><?= $cotizacion->nom_tipdocumento ?> </b></div>
		<div><?= $cotizacion->serie ?> -  <?= $cotizacion->numero_cot ?></div>
</div>
<div class="w100" style="font-size:12px">
		<div class="w30">
			<b><?= ($ventas->codsunat_tipdocucli=='6')?'RUC':'DNI' ?></b>
		</div> <div class="w70">: <?= $cotizacion->doc_cliente?></div>
		<div class="w30">
		<b><?= ($cotizacion->codsunat_tipdocucli=='6')?'Razon Social':'Nombres' ?></b></div>
		<div class="w70">: <?= character_limiter($cotizacion->nomb_cliente,30,'...') ?></div>

		<div class="w30"><b>Vendedor</b></div>
		<div class="w70">: <?= character_limiter($cotizacion->nomb_usu.' '.$cotizacion->apell_usu,30,'...') ?></div>

		
		<div class="w30"><b>Fecha</b></div> <div class="w70">: <?= $cotizacion->fecha_cot ?></div>
		<div class="w30"><b>Hora</b></div> <div class="w70">: <?= date('H:i:s', time()); ?></div>
</div>

<div>
	------------------------------------------------------------------------------
</div>



<div class="w100">
 	<table style="font-size:12px;width:100%" cellpadding="0" cellspacing="0">
		<thead>	
			<tr>
				<th class="text-left">Descripción</th>
				<th class="text-center">Cant.</th>
				<th class="text-right">P.Uni</th>
				<th class="text-right">Desc.</th>
				<th class="text-right">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($cotizacion->detalle as $dt): ?>
			<tr>
				<td><?= character_limiter($dt->nomb_product,38,'...')?></td>
				<td class="text-center"><?= $dt->cant_cotdet ?></td>
				<td class="text-right"><?= $dt->precunit_cotdet ?></td>
				<td class="text-right"><?= $dt->descuento_cotdet ?></td> 
				<td class="text-right"><?= $dt->subtotal_cotdet ?></td>  
			</tr>
			<?php endforeach ?>	
			
		</tbody> 
	</table>
</div>


<div>
------------------------------------------------------------------------------
</div>


<div style="font-size:12px">
	<div class="w100">
		<div class="w1-3"><b>Subtotal:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right" ><?= $cotizacion->subtotal_cot?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>IGV:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= $cotizacion->igv_cot ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Total:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= $cotizacion->total_cot ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Tipo de venta:</b></div>
		<div class="w2-3 text-right"><?= ($cotizacion->tipopago=='CREDITO')?'Crédito':'Contado' ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Monto a pagar:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= $cotizacion->total_cot ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Monto Recibido:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= $cotizacion->total_cot ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Vuelto:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= $cotizacion->vuelto_vent ?></div>
	</div>
</div>
				

<div>
------------------------------------------------------------------------------
</div>

<br>
<div class="w120" style="font-size: 14px;">
<b>Consideraciones Comerciales:</b>
</div>
<br>
<div class="w100" style="font-size:12px">
* Los precios estan expresados en SOLES e incluyen <br>
* En el precio esta incluido los costos de envio.<br>
* La oferta tiene validez 15 dias calendario.<br>
* Forma de Pago: Deposito bancario<br>
* Plazo maximo de pago: AL CONTADO O CREDITO
</div>

<div class="w120 text-center" style="font-size: 12px;">
<b>www.bfacturas.com</b>
</div>

	

</div>

