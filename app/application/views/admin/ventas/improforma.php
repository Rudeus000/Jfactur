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

	<div class="w25 text-center" style="float: right; border: 1.5px solid #070707">
		<br>
		<b style="font-size: 12px;">R.U.C <?= $empresa->ruc_emp ?><b><p>
		<b  style="font-size: 12px;"><?= $ventas->nom_tipdocumento ?></b>
		<br>
		<b  style="font-size: 12px;"><?= $ventas->serie ?>-<?= $ventas->numero_vent ?></b>
		
	</div>
</div>
<br><br>

<div class="Com-Datos" style="background: #070707;color:white">
	DATOS CLIENTE
</div>


<br>
<div class="w100">
	<div class="w60">
		<p><b style="font-size: 11px;">Nombre/ Razon Social:</b> &nbsp;&nbsp;<?= $ventas->nomb_cliente ?></p>		
		<p><b><?= ($ventas->codsunat_tipdocucli=='6')?'RUC':'DNI' ?>:</b>&nbsp;&nbsp; <?= $ventas->doc_cliente ?></p>
		<p><b style="font-size: 11px;">Dirección:</b>&nbsp;&nbsp; <?= $ventas->direc_cliente ?></p>
		

	</div>
	<div class="w40">
		<p><b style="font-size: 11px;">Moneda:</b> &nbsp;&nbsp;SOLES</p>
		<p><b style="font-size: 11px;">Fecha Emisión:</b> &nbsp;&nbsp;<?= $ventas->fecha_vent ?></p>
		<!-- <p><b>Fecha de Vencimiento:</b> <?= $compras->fecvenc_comp?></p>
		<p><b>N° Dias Pago:</b> <?= $compras->dias_comp?></p> -->
	</div>
</div>


<div class="w100">
	   


 		  <table class="table table-bordered">
	<thead>	
		<tr>
			<th style="font-size: 10px; border:1px solid #070707; padding: 5px; text-align: center; width: 5px;" height="5">Item</th>
			<th style="font-size: 10px; border:1px solid #070707; padding: 5px; text-align: center; width: 5px;" height="5">Codigo</th>
			<th style="font-size: 10px; border:1px solid #070707; padding: 5px; text-align: center; width: 240px;">Nombre o Descripcion</th>
			<th style="font-size: 10px; border:1px solid #070707; padding: 0px; text-align: center; width: 50px;">Und.</th>
			<th style="font-size: 10px; border:1px solid #070707; padding: 0px; text-align: center; width: 60px">Cantidad</th>
			<th style="font-size: 10px; border:1px solid #070707; padding: 0px; text-align: center; width: 70px">V.Unitario</th>
			<th style="font-size: 10px; border:1px solid #070707; padding: 0px; text-align: center; width: 70px">Dscto</th>
			<th style="font-size: 10px; border:1px solid #070707; padding: 0px; text-align: center; width: 20px">Valor Total</th>

		</tr>
	</thead>
 	<tbody>
		<?php 
		$item = 1;
		$sumDescuento = 0;
		?>
		<?php foreach ($ventas->detalle as $dt): ?>
		<tr>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $item ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= (!is_null($dt->cod_producto)?$dt->cod_producto:$dt->cod_servicio) ?></td>
			<td style="border:1px solid #070707; padding: 6px;  "><?= $dt->producto_ventdet?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center;  "><?= $dt->unidad_ventdet ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dt->cant_ventdet ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dt->precunit_ventdet ?></td>
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dt->descuento_ventdet ?></td> 
			<td style="border:1px solid #070707; padding: 6px; text-align: center; "><?= $dt->subtotal_ventdet ?></td>  
		</tr>
		<?php 
			$sumDescuento += $dt->descuento_ventdet * $dt->cant_ventdet;
			$item++;
		?>
		<?php endforeach ?>
	</tbody> 
</table>

</div>

<div class="w100">

	<div class="w30" style="float: right; padding: 5px;border:2px solid #070707;">
		<div class="w100">
			<div class="w50"><b>Valor Venta</b></div>
			<div class="w50" style="text-align:right"><?= $ventas->subtotal_vent ?></div>
		</div>
		<div class="w100">
			<div class="w50"><b>Importe de encuesto</b></div>
			<div class="w50" style="text-align:right"><?= $ventas->igv_vent ?></div>
		</div>
		<div class="w100">
			<div class="w50"><b>Descuento</b></div>
			<div class="w50" style="text-align:right"><?= number_format($sumDescuento, 2, '.', '') ?></div>
		</div>
		<div class="w100" style="border-bottom:1px solid black;margin:5px 0">
		</div>
		<div class="w100">
			<div class="w50"><b>Importe Total</b></div>
			<div class="w50" style="text-align:right"><?= $ventas->total_vent ?></div>
		</div>
	</div>
</div>

<div class="w100" style="font-size:12px;">
	<?= strtoupper(convertir($ventas->total_vent)) ?> Y 00/100 SOLES
</div>
<br>
<div class="w100" style="border:1px solid black;padding:2px">
	<b style="font-size:13px">Observaciones</b><br>
	Este es una proforma de control interno, no tiene ningun validez para tramites tributarios, cambia por una boleta o factura electronica.
</div>

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

		<p style="font-size: 15px; padding-top: -8px;">Consideraciones Comerciales:</p>
		<br>
		<p style="font-size: 12px; padding-top: -8px;"><b>* Los precios estan expresados en SOLES e incluyen IGV.</b></p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* En el precio esta incluido los costos de envio.</p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* La oferta tiene validez 15 dias calendario.</p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* Forma de Pago: Deposito bancario</p>
		<p style="font-size: 12px; padding-top: -8px;"><b>* Plazo maximo de pago: AL CONTADO O CREDITO</p>

</div>

<div class="w100">
<barcode code="<?= $qr ?>" type="QR" class="barcode" size="1.5" error="M" disableborder="1" />
</div>
