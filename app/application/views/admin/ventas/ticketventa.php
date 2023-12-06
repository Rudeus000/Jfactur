<div class="w100 text-center" style="font-size:12px">
	<div><img style="max-width: 120px" src="<?= base_url_app('assets/uploads/logo/' . $empresa->photo) ?>"></div>
	<div><b><?= $empresa->nombre_comercial ?></b></div>
	<div><b><?= $empresa->direcc_emp ?></b></div>

	<?php if ($ventas->direccion_puntoventa != $empresa->direcc_emp) : ?>
		<div><?= $ventas->direccion_puntoventa ?></div>
	<?php endif ?>

	<div>Ruc:<?= $empresa->ruc_emp ?></div>
	<div> Web: <?= WEBSITE ?> </div>
	<div><b><?= $ventas->nom_tipdocumento ?> </b></div>
	<div><?= $ventas->serie ?> - <?= str_pad($ventas->numero_vent, 7, "0", STR_PAD_LEFT); ?></div>
</div>
<div class="w100" style="font-size:12px">
	<div class="w30">
		<b><?= ($ventas->codsunat_tipdocucli == '6') ? 'RUC' : 'DNI' ?></b>
	</div>
	<div class="w70">: <?= $ventas->doc_cliente ?></div>
	<div class="w30">

		<b><?= ($ventas->codsunat_tipdocucli == '6') ? 'Razon Social' : 'Nombres' ?></b>
	</div>
	<div class="w70">: <?= character_limiter($ventas->nomb_cliente, 30, '...') ?></div>

	<div class="w30"><b>Vendedor</b></div>
	<div class="w70">: <?= character_limiter($ventas->nomb_usu . '***') ?></div>


	<div class="w30"><b>Fecha</b></div>
	<div class="w70">: <?= $ventas->fecha_vent ?></div>
	<div class="w30"><b>Hora</b></div>
	<div class="w70">: <?= $ventas->hora_vent ?></div>
</div>

<div>
	--------------------------------------------------------------------------------
</div>



<div class="w100">
	<table style="font-size:12px;width:100%" cellpadding="0" cellspacing="0">
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
			<?php
			$gravada = 0;
			$exonerada = 0;
			$descuentos = 0;
			?>
			<?php foreach ($ventas->detalle as $dt) : ?>
				<?php
				if ($dt->igv_ventdet > 0) {
					$gravada += $dt->prec_ventdet;
				} else {
					$exonerada += $dt->prec_ventdet;
				}
				$descuentos += $dt->descuento_ventdet;
				?>
				<tr>
					<td><?= character_limiter($dt->producto_ventdet, 38, '...') ?> <?= $dt->producto_isdn ?><br><?= $dt->serie_ventdetserie ?></td>
					<td class="text-center"><?= $dt->cantidad ?></td>
					<td class="text-right"><?= $dt->precunit_ventdet ?></td>
					<td class="text-right"><?= ($dt->tipo_ventdet == 'V' || $dt->tipo_ventdet == 'E') ? $dt->descuento_ventdet : '' ?></td>
					<td class="text-right"><?= ($dt->tipo_ventdet == 'V' || $dt->tipo_ventdet == 'E') ? $dt->subtotal : '' ?></td>
				</tr>
			<?php endforeach ?>

		</tbody>
	</table>
</div>


<div>
	--------------------------------------------------------------------------------
</div>


<div style="font-size:11px">
	<div class="w100">
		<div class="w1-3"><b>Gravada:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= number_format($ventas->gravada_vent, 2) ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Exonerada:</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= number_format($ventas->exonerada_vent, 2) ?></div>
	</div>
	<div class="w100">
		<div class="w1-3"><b>Descuentos(-):</b></div>
		<div class="w1-3 text-right">S/</div>
		<div class="w1-3 text-right"><?= number_format($descuentos, 2) ?></div>
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
		<div class="w2-3 text-right"><?= ($ventas->tipopago == 'CREDITO') ? 'Crédito' : 'Contado' ?></div>
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

<div class="w100 text-center" style="font-size:9px">
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


	<barcode code="<?= $qr ?>" type="QR" class="barcode" size="0.8" error="M" disableborder="1" />


</div>
<br>
<br>
<?php if (!is_null($ventas->observacion_vent) and $ventas->observacion_vent != '') : ?>

	<div class="w100" style="border:1px dotted black;padding:2px">
		<b style="font-size:13px">Observación</b><br>
		<?= $ventas->nom_tipdocumento ?><b style="font-size:12px"></b>, <?= $ventas->observacion_vent ?>
	</div>
	<br>
	<br>
<?php endif ?>
<?php if (!is_null($empresa->anuncio) and $empresa->anuncio != '') : ?>

	<div class="w100 text-center" style="border:1px dotted black;padding:2px">
		<!-- <b style="font-size:13px">Observación</b><br> -->
		<b style="font-size:14px"><?= $empresa->anuncio ?></b>
	</div>
	<br>
	<br>
<?php endif ?>
<?php if (!is_null($empresa->sorteo) and $empresa->sorteo != '') : ?>
	<div>
		----------------------------------------------------------------------------------
	</div>
	<br>
	<br>
	<div class="w100 text-center" style="border:1px dotted black;padding:2px">
		<div style="font-size:14px"><?= $empresa->sorteo ?></div>

		<div style="text-align: center;"><b><?= $ventas->serie ?> - <?= str_pad($ventas->numero_vent, 7, "0", STR_PAD_LEFT); ?></b></div>
		<div class="w20">
			<b><?= ($ventas->codsunat_tipdocucli == '6') ? 'Razon Social' : 'Nombres' ?></b>
		</div>
		<div class="w70">: <?= character_limiter($ventas->nomb_cliente, 30, '...') ?></div>


	</div>
	<br>
	<br>
<?php endif ?>