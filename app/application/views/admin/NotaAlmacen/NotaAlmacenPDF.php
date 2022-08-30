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
		<!-- <p style="font-size: 10px; padding-left: -70px; padding-top: -11px;"><b> <?= $ventas->direccion_puntoventa ?></b> </p> -->
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Telefono: <?= $empresa->telf_emp ?></b> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Email: <?= $empresa->email_emp ?> </b></p>



	</div>

	<div class="w25 text-center" style="float: right; border: 1.5px solid #03A6BF;border-radius: 10px;">
		<br>
		<b style="font-size: 15px;">R.U.C <?= $empresa->ruc_emp ?><b>
				<p>
				<div class="Com-Datos" style="font-size: 15px; background: #03A6BF"><?= ($datos[0]->Tipo_Nota == "I")?'NOTA DE INGRESO':'NOTA DE SALIDA' ?></div>
				<br>
				<b style="font-size: 15px;"><?= $datos[0]->Serie_Nota ?>-<?= $datos[0]->Num_Nota ?></b>

	</div>
</div>


<!-- <img width="150px" id="logo-archivo" src="<?= base_url_app('assets/uploads/logo/' . $logo) ?>" alt="Logo"> -->
<!-- <div><?php echo $empresa->nombre_comercial; ?></div>
<div><?php echo $empresa->ruc_emp; ?></div>
<div><?php echo $empresa->direcc_emp . ' ' . $empresa->ubicacion; ?></div> -->
<div>&nbsp;</div>

<!-- <div class="Com-Datos" style=" background: #03A6BF">
	<b>TIPO MONEDA</b>
</div> -->
<div class="w100" style="border-bottom:1px solid #03A6BF;margin:5px 0">
		</div>
<div class="w100">
	<div class="w40">
		<p><b style="font-size: 11px;">MONEDA:</b>&nbsp;<?= ($datos[0]->ccod_mon=="S")?'SOLES:':'DOLARES:' ?></p>		
	</div>
	<div class="w30">
	<p><b style="font-size: 11px;">TIPO CAMBIO: </b>&nbsp;<?= $datos[0]->nt_cambio; ?></p>

	</div>
	<div class="w30">
	<p><b style="font-size: 11px;">FECHA:</b>&nbsp; <?= $datos[0]->Fecha_Nota; ?></p>
		
	</div>
</div>
<div class="w100" style="border-bottom:1px solid #03A6BF;margin:5px 0">
		</div>
		<div class="w100" style="border-bottom:1px solid #03A6BF;margin:5px 0">
		</div>
<div class="w100">
	<div class="w40">
		<p><b style="font-size: 11px;">RAZON SOCIAL:</b>&nbsp;<?= $datos[0]->nomb_cliente; ?></p>		
	</div>
	<div class="w30">
	<p><b style="font-size: 11px;">MOTIVO: </b>&nbsp;<?= $datos[0]->des_motivo; ?></p>

	</div>
	<div class="w30">
	<p><b style="font-size: 11px;">DOC. REFERENCIA:</b>&nbsp; <?php echo $datos[0]->nom_tipdocumento; ?> <?php echo $datos[0]->serie_doc_ref; ?> - <?php echo $datos[0]->num_doc_ref;?></p>
		
	</div>
</div>
<div class="w100" style="border-bottom:1px solid #03A6BF;margin:5px 0">
		</div>
<table class="table table-bordered">
	<tbody>
		<!-- <tr>
			<td colspan="7" align="center">
				<h2><?php
					if ($datos[0]->Tipo_Nota == "I") {
					?>
						Nota de ingreso <?php echo $datos[0]->Serie_Nota; ?>-<?php echo $datos[0]->Num_Nota; ?>
					<?php
					} else {
					?>
						Nota de salida <?php echo $datos[0]->Serie_Nota; ?>-<?php echo $datos[0]->Num_Nota; ?>
					<?php
					}
					?></h2>
			</td>

		</tr> -->

<!-- 
		<tr>

			<td colspan="4">Moneda</td>
			<td><?php
				if ($datos[0]->ccod_mon == "S") {
					echo "Soles";
				} else {
					echo "Dolares";
				}
				?></td>
			<td>Tipo cambio</td>
			<td><?php echo $datos[0]->nt_cambio; ?></td>
		</tr>
		<tr>
			<td>Fecha</td>
			<td colspan="6"><?php echo $datos[0]->Fecha_Nota; ?></td>
		</tr>
		
		<tr>
			<td>Razón social</td>
			<td colspan="6"><?php echo $datos[0]->doc_cliente; ?> - <?php echo $datos[0]->nomb_cliente; ?></td>
		</tr>
		<tr>
			<td>Motivo</td>
			<td colspan="6"><?php echo $datos[0]->des_motivo; ?></td>
		</tr>
		<tr>
			<td>Doc. Referencia</td>
			<td colspan="6"><?php echo $datos[0]->nom_tipdocumento; ?> - <?php echo $datos[0]->serie_doc_ref; ?> - <?php echo $datos[0]->num_doc_ref; ?></td>
		</tr> -->
		<tr>
			<th align="center">ITEM</th>
			<th align="center">CODIGO</th>
			<th align="center">DESCRIPCION</th>
			<th align="center">UNIDAD</th>
			<th align="center">CANTIDAD</th>
			<th align="center">COSTO</th>
			<th align="center">SUBTOTAL</th>
		</tr>
		<?php
		$item = 1;
		$total = 0;
		foreach ($datos as $d) : ?>
			<tr>
				<td><?php echo $item; ?></td>
				<td><?php echo $d->ccod_art; ?></td>
				<td><?php echo $d->cdsc_art; ?></td>
				<td><?php echo $d->nomb_tipunidad; ?></td>
				<td align="right"><?php echo number_format($d->nund, 3, ".", ","); ?></td>
				<td align="right"><?php echo number_format($d->ncosto, 3, ".", ","); ?></td>
				<td align="right"><?php echo number_format(($d->ncosto * $d->nund), 3, ".", ","); ?></td>
			</tr>
		<?php
			$total = $total + ($d->ncosto * $d->nund);
			$item++;
		endforeach
		?>
		<!-- <tr>
			<td>TOTAL</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td align="right"><?php echo number_format($total, 3, ".", ","); ?></td>
		</tr> -->
	</tbody>
</table>
<div class="w100">
	<div class="w30" style="float: right; padding: 5px;border:2px solid #03A6BF; border-radius: 10px;">
		<!-- <div class="w100">
			<div class="w50"><b>Gravada</b></div>
			<div class="w50" style="text-align:right"><?= number_format($ventas->gravada_vent,2) ?></div>
		</div>
		<div class="w100">
			<div class="w50"><b>Exonerada</b></div>
			<div class="w50" style="text-align:right"><?= number_format($ventas->exonerada_vent,2) ?></div>
		</div>
		<div class="w100">
			<div class="w50"><b>Descuentos (-)</b></div>
			<div class="w50" style="text-align:right"><?= number_format($descuentos,2) ?></div>
		</div>
		<div class="w100">
			<div class="w50"><b>IGV</b></div>
			<div class="w50" style="text-align:right"><?= $ventas->igv_vent ?></div>
		</div>
		<div class="w100" style="border-bottom:1px solid #03A6BF;margin:5px 0">
		</div> -->
		<div class="w100">
			<div class="w50"><b>TOTAL</b></div>
			<div class="w50" style="text-align:right"><?= number_format($total, 3, ".", ","); ?></div>
		</div>
	</div>
</div>