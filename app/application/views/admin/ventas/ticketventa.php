<div class="w100 text-center" style="font-size:12px">
		<div><b><?= $empresa->razon_social ?></b></div>
		<div><b><?= $empresa->direcc_emp ?></b></div>
		<div><?= $ventas->direccion_puntoventa ?></div>
		<div>Ruc:<?= $empresa->ruc_emp ?></div>
		<div> Web: <?= WEBSITE ?> </div>
		<div><b><?= $ventas->nom_tipdocumento ?> </b></div>
		<div><?= $ventas->serie ?> -  <?= $ventas->numero_vent ?></div>
</div>
<div class="w100" style="font-size:12px">
		<div class="w30">
			<b><?= ($ventas->codsunat_tipdocucli=='6')?'RUC':'DNI' ?></b>
		</div> <div class="w70">: <?= $ventas->doc_cliente?></div>
		<div class="w30">
		<b><?= ($ventas->codsunat_tipdocucli=='6')?'Razon Social':'Nombres' ?></b></div>
		<div class="w70">: <?= character_limiter($ventas->nomb_cliente,30,'...') ?></div>

		<div class="w30"><b>Vendedor</b></div>
		<div class="w70">: <?= character_limiter($ventas->nomb_usu.'***') ?></div>

		
		<div class="w30"><b>Fecha</b></div> <div class="w70">: <?= $ventas->fecha_vent ?></div>
		<div class="w30"><b>Hora</b></div> <div class="w70">: <?= $ventas->hora_vent ?></div>
</div>

<div>
	--------------------------------------------------------------------------------
</div>



<div class="w100">
 	<table style="font-size:8px;width:100%" cellpadding="0" cellspacing="0">
		<thead>	
			<tr>
				<th class="text-left">Descripción</th>
				<th class="text-center">Cant.</th>
				<th class="text-right">P. Unit</th>
				<th class="text-right">Desc.</th>
				<th class="text-right">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($ventas->detalle as $dt): ?>
			<tr>
				<td><?= character_limiter($dt->producto_ventdet,38,'...')?> <?= $dt->producto_isdn ?></td>				
				<td class="text-center"><?= $dt->cant_ventdet ?></td>
				<td class="text-right"><?= $dt->precunit_ventdet ?></td>
				<td class="text-right"><?= ($dt->tipo_ventdet=='V')?$dt->descuento_ventdet:'' ?></td> 
				<td class="text-right"><?= ($dt->tipo_ventdet=='V')?$dt->subtotal_ventdet:'' ?></td>  
			</tr>
			<?php endforeach ?>	
			
		</tbody> 
	</table>
</div>


<div>
--------------------------------------------------------------------------------
</div>


<div style="font-size:12px">
	<div class="w100">
		<div class="w1-3"><b>Subtotal:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right" ><?= $ventas->subtotal_vent ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>IGV:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= $ventas->igv_vent ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Total:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= $ventas->total_vent ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Tipo de venta:</b></div>
		<div class="w2-3 text-right"><?= ($ventas->tipopago=='CREDITO')?'Crédito':'Contado' ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Monto a pagar:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= $ventas->monto_vent ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Monto Recibido:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= $ventas->montorecibido_vent ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Vuelto:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= $ventas->vuelto_vent ?></div>
	</div>
</div>
				

<div>
--------------------------------------------------------------------------------
</div>

<br>

<div class="w100 text-center" style="font-size:12px">
	Autorizado a ser emisor electrónico
	mediante R.I. SUNAT N° 018-005-
	0002378 Representación impresa
	de la boleta de venta electrónica
	consulte su documento en
	<?= WEBSITE ?>
	<p>Codigo de seguridad (Hash): <?= $ventas->hash_vent ?></p>
</div>
<br>

<div class="w120 text-center">
	
		
<barcode code="<?= $qr ?>" type="QR" class="barcode" size="1.3" error="M" disableborder="1" />
	

</div>
<br>
<br>
<?php if(!is_null($ventas->observacion_vent) AND $ventas->observacion_vent!=''): ?>

<div class="w100" style="border:1px solid black;padding:2px">
	<b style="font-size:13px">Observación</b><br>
	<?= $ventas->nom_tipdocumento ?><b style="font-size:12px"></b>, <?= $ventas->observacion_vent ?>
</div>
<br>
<br>
<?php endif ?>

