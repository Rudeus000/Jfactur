
<div class="w120">
	<div class="w30">
		<img style="max-width: 100px" src="<?= base_url_app('assets/uploads/logo/'.$empresa->photo) ?>" > 
	</div>
	<div class="w40 text-center">
		<p style="font-size: 13px; padding-left: -70px;"><b><?= $empresa->razon_social ?></b></p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -11px;"><b> <?= $empresa->direcc_emp ?></b> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Telefono: <?= $empresa->telf_emp ?></b> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Email: <?= $empresa->email_emp ?> </b></p> 
			<!-- <b style="font-size: 12px;"> AYACUHO - LIMA </b> -->
			
			
		
		
	</div>

	<div class="w25 text-center" style="float: right; border: 1.5px solid #03ABC2">
		<br>
		<b style="font-size: 12px;">R.U.C <?= $empresa->ruc_emp ?><b><p>
		<b  style="font-size: 12px;">NOTA DE CRÉDITO</b>
		<b  style="font-size: 12px;">ELECTRÓNICA</b>
		<br>		
		<b  style="font-size: 12px;"><?= $nota->seriecomp_nota ?> -  <?= $nota->numcomp_nota ?></b>
		
	</div>
</div>
<br><br>

<div class="Com-Datos" style="background: #03ABC2;color:white">
	<b>DATOS CLIENTE</b>
</div>


<br>
<div class="w100">
	<div class="w60">
		<p><b style="font-size: 11px;">Nombre/ Razon Social:</b> &nbsp;&nbsp;<?= $nota->nomb_cliente ?></p>
		<p><b style="font-size: 11px;">R.U.C:</b>&nbsp;&nbsp; <?= $nota->doc_cliente ?></p>
		<p><b style="font-size: 11px;">Dirección:</b>&nbsp;&nbsp; <?= $nota->direc_cliente ?></p>
		

	</div>
	<div class="w40">
		<p><b style="font-size: 11px;">Moneda:</b> &nbsp;&nbsp;SOLES</p>
		<p><b style="font-size: 11px;">Fecha Emisión:</b> &nbsp;&nbsp;<?= $nota->fecha_nota ?></p>
		<!-- <p><b>Fecha de Vencimiento:</b> <?= $compras->fecvenc_comp?></p>
		<p><b>N° Dias Pago:</b> <?= $compras->dias_comp?></p> -->
	</div>
</div>


<div class="w100">
	   


 <table class="table table-bordered">
	<thead>	
		<tr>
			<th style="font-size: 10px; border:1px solid #03ABC2; padding: 5px; text-align: center; width: 5px;" height="5">Codigo</th>
			<th style="font-size: 10px; border:1px solid #03ABC2; padding: 5px; text-align: center; width: 240px;">Nombre o Descripcion</th>
			<th style="font-size: 10px; border:1px solid #03ABC2; padding: 0px; text-align: center; width: 50px;">Und.</th>
			<th style="font-size: 10px; border:1px solid #03ABC2; padding: 0px; text-align: center; width: 60px">Cantidad</th>
			<th style="font-size: 10px; border:1px solid #03ABC2; padding: 0px; text-align: center; width: 70px">V.Unitario</th>
      		<th style="font-size: 10px; border:1px solid #03ABC2; padding: 0px; text-align: center; width: 70px">IGV</th>
			<th style="font-size: 10px; border:1px solid #03ABC2; padding: 0px; text-align: center; width: 20px">Valor Total</th>
		</tr>
	</thead>
 	<tbody>
		<?php foreach ($nota->detalle as $dt): ?>
		<tr>
			<td style="border:1px solid #03ABC2; padding: 6px; text-align: center; "><?= $dt->coddet_notdet ?></td>
			<td style="border:1px solid #03ABC2; padding: 6px;  "><?= $dt->descripcion_notdet?></td>
			<td style="border:1px solid #03ABC2; padding: 6px; text-align: center;  "><?= $dt->unimed_notdet ?></td>
			<td style="border:1px solid #03ABC2; padding: 6px; text-align: center; "><?= $dt->cant_notdet ?></td>
			<td style="border:1px solid #03ABC2; padding: 6px; text-align: center; "><?= round($dt->preciosinigv_notdet,2) ?></td>
      		<td style="border:1px solid #03ABC2; padding: 6px; text-align: center; "><?= $dt->igv_notdet ?></td>
			<td style="border:1px solid #03ABC2; padding: 6px; text-align: center; "><?= round((($dt->preciosinigv_notdet * $dt->cant_notdet)+$dt->igv_notdet),2) ?></td>  
		</tr>
		<?php endforeach ?>

	
		
		
	</tbody> 


</table>

</div>

<div class="w100">

	<div class="w30" style="float: right; padding: 5px;border:2px solid #03ABC2;">
		<div class="w100">
			<div class="w50"><b>Op. Exoneradas</b></div>
			<div class="w50" style="text-align:right">0.00</div>
		</div>
		<div class="w100">
			<div class="w50"><b>Op. Gravadas</b></div>
			<div class="w50" style="text-align:right"><?= $nota->totalgravadas_nota ?></div>
		</div>
		<div class="w100">
			<div class="w50"><b>IGV (18%)</b></div>
			<div class="w50" style="text-align:right"><?= $nota->totaligv_nota ?></div>
		</div>		
		<div class="w100" style="border-bottom:1px solid #03ABC2;margin:5px 0">
		</div>
		<div class="w100">
			<div class="w50"><b>Importe Total</b></div>
			<div class="w50" style="text-align:right"><?= $nota->total_nota ?></div>
		</div>
	</div>
</div>

<div class="w100" style="font-size:12px;">
	<?= strtoupper(convertir($nota->total_nota)) ?>
</div>


<br>
<br>
<br>
<br>
<br>
<br>

<div class="w100">

		
		<p style="font-size: 10px; padding-top: -10px;">&nbsp;Autorizado mediante Resolucion de</p>
		<p style="font-size: 10px; padding-top: -10px;">Superintendecia N° 182 - 2016 SUNAT</p>
        <p style="font-size: 10px; padding-top: -10px;">&nbsp;&nbsp;&nbsp;&nbsp;representacion impresa de la</p>
        <p style="font-size: 10px; padding-top: -10px;">&nbsp;&nbsp;&nbsp;Nota de Crédito</p>

		<p>Codigo de seguridad (Hash): <?= $nota->hash_nota ?></p>
</div>

<div class="w100">
<barcode code="<?= $qr ?>" type="QR" class="barcode" size="1.5" error="M" disableborder="1" />
</div>
<br>
<div class="w100">
<p style="font-size: 14;">
	<b>Motivo de emisión: </b><?= $nota->codmotivo_nota.' '.$nota->motivo_nota ?>
</p>
<p style="font-size: 14;">Documento relacionado: <?= ($nota->codsunat_tipdocu=="01")?"Factura":"Boleta de venta"  ?> <?= $nota->serie.'|'.$nota->numero_vent.'|'.$nota->fecha_vent ?></p>
</div>
<!-- <div class="w100">

		<p style="font-size: 15px; padding-top: -8px;">Consideraciones Comerciales:</p>
		<p style="font-size: 12px; padding-top: -6px;"><b>* Los precios estan expresados en SOLES e incluyen IGV.</b></p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* En el precio esta incluido los costos de envio.</p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* La oferta tiene validez 15 dias calendario.</p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* Forma de Pago: Deposito bancario</p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* Plazo maximo de pago: AL CONTADO O CREDITO</p>

</div>
	 -->

<!-- <h2 class="text-center">Plan de Tratamiento</h2> -->


