<div class="w120">
	<div class="w30">
		<img style="max-width: 170px" src="<?= base_url_app('assets/uploads/logo/' . $empresa->photo) ?>">
	</div>
	<div class="w40 text-center">
		<p style="font-size: 18px; padding-left: -70px;"><b><?= $empresa->razon_social ?></b></p>
		<p style="font-size: 14px; padding-left: -70px; padding-top: -11px;"><b> <?= $empresa->direcc_emp ?></b> </p>
		<!-- <p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Telefono: <?= $empresa->telf_emp ?></b> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Email: <?= $empresa->email_emp ?> </b></p> -->
		<!-- <b style="font-size: 12px;"> AYACUHO - LIMA </b> -->
		<p style="font-size: 10px; padding-left: -70px; padding-top: -11px;"><b> <?= $ventas->direccion_puntoventa ?></b> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Telefono: <?= $ventas->telefono_puntoventa ?></b> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Email: <?= $ventas->email_puntoventa ?> </b></p>



	</div>

	<div class="w25 text-center" style="float: right; border: 1.5px solid #03A6BF;border-radius: 10px;">
		<br>
		<b style="font-size: 15px;">R.U.C <?= $empresa->ruc_emp ?><b>
				<p>
				<div class="Com-Datos" style="font-size: 15px; background: #03A6BF"><b><?= $ventas->nom_tipdocumento ?></b></div>
				<br>
				<b style="font-size: 15px;"><?= $ventas->serie ?>-<?= str_pad($ventas->numero_vent, 7, "0", STR_PAD_LEFT); ?></b>

	</div>
</div>
<br><br>

<div class="Com-Datos" style=" background: #03A6BF">
	<b>DATOS CLIENTE</b>
</div>


<br>
<div class="w100">
	<div class="w60">
		<p><b style="font-size: 11px;"><?= ($ventas->codsunat_tipdocucli == '6') ? 'Razon Social:' : 'Nombres:' ?></b> &nbsp;&nbsp;<?= $ventas->nomb_cliente ?></p>
		<p><b style="font-size: 11px;"><?= ($ventas->codsunat_tipdocucli == '6') ? 'RUC:' : 'DNI:' ?></b>&nbsp;&nbsp; <?= $ventas->doc_cliente ?></p>
		<p><b style="font-size: 11px;">Dirección:</b>&nbsp;&nbsp; <?= $ventas->direc_cliente ?></p>
		<p><b>Condición de pago:</b>&nbsp;&nbsp;<?= ($ventas->tipopago == 'CREDITO') ? 'Crédito' : 'Contado' ?></p>

	</div>
	<div class="w40">
		<p><b style="font-size: 11px;">Moneda:</b> &nbsp;&nbsp;SOLES</p>
		<p><b style="font-size: 11px;">Fecha Emisión:</b> &nbsp;&nbsp;<?= $ventas->fecha_vent ?></p>
		<?php if (!is_null($ventas->fechavenc_vent)) : ?>
			<p><b>Fecha de Vencimiento:</b> <?= $ventas->fechavenc_vent ?></p>
		<?php endif ?>
		<?php if (!is_null($ventas->num_cuotas_vent)) : ?>
			<p><b>Numero de cuotas:</b> <?= $ventas->num_cuotas_vent ?></p>
		<?php endif ?>
	</div>
</div>
<div class="w100" style="border-bottom:1px solid #03A6BF;margin:5px 0">
</div>

<div class="w100">

	<table class="table table-hover">
		<thead>
			<tr>
				<th style="font-size: 10px; border:1px solid #03A6BF; padding: 5px; text-align: center; width: 5px; border-radius: 10px;" height="5">Item</th>
				<th style="font-size: 10px; border:1px solid #03A6BF; padding: 5px; text-align: center; width: 5px; border-radius: 10px;" height="5">Codigo</th>
				<th style="font-size: 10px; border:1px solid #03A6BF; padding: 5px; text-align: center; width: 240px; border-radius: 10px">Nombre o Descripcion</th>
				<th style="font-size: 10px; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 50px; border-radius: 10px;">Und.</th>
				<th style="font-size: 10px; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 60px; border-radius: 10px;">Cantidad</th>
				<th style="font-size: 10px; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 70px; border-radius: 10px;">P.Unitario</th>
				<th style="font-size: 10px; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 70px; border-radius: 10px;">Descuento</th>
				<th style="font-size: 10px; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 20px; border-radius: 10px;">Importe</th>

			</tr>
		</thead>
		<tbody>
			<?php
			$item = 1;

			$gravada = 0;
			$exonerada = 0;
			$descuentos = 0;

			?>
			<?php foreach ($ventas->detalle as $dt) : ?>
				<tr>
					<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $item ?></td>
					<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= (!is_null($dt->cod_producto) ? $dt->cod_producto : $dt->cod_servicio) ?></td>
					<td style="border:1px solid #03A6BF; padding: 6px;  "><?= $dt->producto_ventdet ?></td>
					<td style="border:1px solid #03A6BF; padding: 6px; text-align: center;  "><?= $dt->unidad_ventdet ?></td>
					<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $dt->cantidad ?></td>
					<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $dt->precunit_ventdet ?></td>
					<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= ($dt->tipo_ventdet == 'V' or $dt->tipo_ventdet == 'E') ? $dt->descuento_ventdet : '' ?></td>
					<td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= ($dt->tipo_ventdet == 'V' or $dt->tipo_ventdet == 'E') ? $dt->subtotal : '' ?></td>
				</tr>
				<?php

				if ($dt->igv_ventdet > 0) {
					$gravada += $dt->prec_ventdet;
				} else {
					$exonerada += $dt->prec_ventdet;
				}
				$descuentos += $dt->descuento_ventdet * $dt->cant_ventdet;

				$item++;
				?>
			<?php endforeach ?>
		</tbody>
	</table>

</div>

<div class="w100">
	<div class="w30" style="float: right; padding: 5px;border:2px solid #03A6BF; border-radius: 10px;">
		<div class="w100">
			<div class="w50"><b>Gravada</b></div>
			<div class="w50" style="text-align:right"><?= number_format($ventas->gravada_vent, 2) ?></div>
		</div>
		<div class="w100">
			<div class="w50"><b>Exonerada</b></div>
			<div class="w50" style="text-align:right"><?= number_format($ventas->exonerada_vent, 2) ?></div>
		</div>
		<div class="w100">
			<div class="w50"><b>Descuentos (-)</b></div>
			<div class="w50" style="text-align:right"><?= number_format($descuentos, 2) ?></div>
		</div>
		<div class="w100">
			<div class="w50"><b>IGV</b></div>
			<div class="w50" style="text-align:right"><?= $ventas->igv_vent ?></div>
		</div>
		<div class="w100" style="border-bottom:1px solid #03A6BF;margin:5px 0">
		</div>
		<div class="w100">
			<div class="w50"><b>TOTAL</b></div>
			<div class="w50" style="text-align:right"><?= $ventas->total_vent ?></div>
		</div>
	</div>
</div>

<div class="w100" style="font-size:12px;">
	<?= strtoupper(convertir($ventas->total_vent)) ?>
</div>
<br>
<?php if (!is_null($ventas->observacion_vent) and $ventas->observacion_vent != '') : ?>

	<div class="w100" style="border:1px solid black;padding:2px; border-radius: 10px">
		<b style="font-size:13px">Observación</b><br>
		<?= $ventas->nom_tipdocumento ?><b style="font-size:12px"></b>, <?= $ventas->observacion_vent ?>
	</div>
	<br>
	<br>
<?php endif ?>

<?php if ($ventas->id_cod_detraccion != null and $ventas->id_mediopago != null) : ?>

	<div class="w100">
		<div class="w100" style=" padding: 5px;border:2px solid #03A6BF; border-radius: 10px;">
			<div class="w100">
				<div class="w100"><b style="font-size:13px">Informacion de la detraccion:</b></div>

			</div>
			<div class="w60">
				<div class="w40"><b>Tipo Operación:</b></div>
				<div class="w40" style="text-align:left">1001 Operación Sujeta a Detracción</div>
			</div>
			<div class="w60">
				<div class="w40"><b>Bien o servicio</b></div>
				<div class="w40" style="text-align:left"><?= $ventas->id_cod_detraccion . ' ' . $ventas->detraccion_bien_descripcion ?></div>
			</div>
			<div class="w60">
				<div class="w40"><b>Medio de pago</b></div>
				<div class="w40" style="text-align:left"><?= $ventas->id_mediopago . ' ' . $ventas->detraccion_medio_descripcion ?></div>
			</div>
			<div class="w60">
				<div class="w40"><b>Nro. Cta. Banco de la Nación:</b></div>
				<div class="w40" style="text-align:left"><?= $ventas->detraccion_cuenta ?></div>
			</div>

			<div class="w60">
				<div class="w40"><b>Porcentaje de detracción: </b></div>
				<div class="w40" style="text-align:left"><?= $ventas->detraccion_porcentaje ?></div>
			</div>
			<div class="w40">
				<div class="w40"><b>Monto detracción: </b></div>
				<div class="w40" style="text-align:right"><?= $ventas->detraccion_monto ?></div>
			</div>
		</div>
	</div>
	<br>
	<br>
<?php endif ?>

<?php if ($ventas->retencion_base_imp != null and $ventas->retencion_porcentaje != null) : ?>
	<?php
	$porcentaje = $ventas->retencion_porcentaje * 100;
	?>

	<div class="w100">
		<div class="w100" style=" padding: 5px;border:2px solid #03A6BF; border-radius: 10px;">
			<div class="w100">
				<div class="w100"><b style="font-size:13px">Informacion de la retencion:</b></div>

			</div>
			<div class="w60">
				<div class="w40"><b>Base imponible de la retencion:</b></div>
				<div class="w40" style="text-align:left"><?= $ventas->retencion_base_imp ?></div>
			</div>
			<div class="w60">
				<div class="w40"><b>Porcentaje de retencion</b></div>
				<div class="w40" style="text-align:left"><?= number_format($porcentaje, 2) ?> %</div>
			</div>
			<div class="w40">
				<div class="w40"><b>Monto de la retencion: </b></div>
				<div class="w40" style="text-align:right"><?= $ventas->retencion_monto ?></div>
			</div>
		</div>
	</div>
	<br>
	<br>
<?php endif ?>

<?php
if (!is_null($ventas->retencion_monto) and !is_null($ventas->cuotas)) {
	foreach ($ventas->cuotas as $key => $value);
	$monto_deuda = $value->monto_ventcuo - $ventas->retencion_monto;
}

?>

<?php if (!is_null($ventas->cuotas)) : ?>
	<div class="w100">
		<p style="font-size: 12px; padding-top: -10px;"><b>CONDICIÓN DE PAGO: Crédito Cuotas</b></p>
		<?php foreach ($ventas->cuotas as $key => $value) : ?>
			<p style="font-size: 12px; padding-top: -10px;">• Cuota #<?= $key + 1 ?> / Fecha: <?= $value->fecha_ventcuo ?>
				<?php if (!is_null($ventas->retencion_monto)) : ?>
					/ Monto deuda: <?= $value->monto_ventcuo ?></p>

		<?php else : ?>
			/ Monto: <?= $value->monto_ventcuo ?></p>
		<?php endif ?>
	<?php endforeach ?>
	</div>
	<br>
<?php endif ?>
<!-- <div class="w100" style="border:1px solid #03A6BF; padding:2px; border-radius: 10px;">
		<b style="font-size:13px">Observaciones SUNAT</b><br>
		<?= $ventas->nom_tipdocumento ?><b style="font-size:12px"> <?= $ventas->serie ?>-<?= $ventas->numero_vent ?></b>, ha sido aceptada.
	</div> -->

<!-- <table class="table table-bordered" style="width:70%;margin-top:10px">
	<tr>
		<td style="border:1px solid #070707"><b>1</b></td>
		<td style="border:1px solid #070707">Detracciones: NÚMERO DE CUENTA EN BN</td>
		<td style="border:1px solid #070707">00-098-139710</td>
	</tr>
	<tr>
		<td style="border:1px solid #070707"><b>2</b></td>
		<td style="border:1px solid #070707">Detracciones: CODIGO DE BB Y SS SUJETOS A DETRACCIÓN </td>
		<td style="border:1px solid #070707">037</td>
	</tr>
</table> -->


<br>
<br>
<br>
<div class="w100">
	<p style="font-size: 12px; padding-top: -10px;">Autorizado a ser emisor electrónico mediante <b>R.I. N° 182 - 2016 SUNAT</b></p>
	<p style="font-size: 12px; padding-top: -10px;">Representacion impresa de su Factura electronica, este puede ser consultado en <b><?= WEBSITE ?></b></p>

	<p>Codigo de seguridad (Hash): <?= $ventas->hash_vent ?></p>
</div>

<div class="w100">
	<barcode code="<?= $qr ?>" type="QR" class="barcode" size="1.5" error="M" disableborder="1" />
</div>