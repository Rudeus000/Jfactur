<h4 class="text-center">COMPRAS</h4>

<table class="table table-bordered">
	<tbody>
		<?php foreach ($datos->result() as $d): ?>
		<tr>
			<th>Id</th>
      <th>Cliente</th>
      <th>Tipo</th>
      <th>Monto</th>
      <th>Pago</th>
      <th>Fecha</th>
		</tr>
		<tr>
			<td><?= $d->cod_cot ?></td>
			<td><?= $d->nomb_cliente ?></td>
			<td><?= ($d->pago_cot=='CO')?'Cotización':'Proforma' ?></td>
			<td><?= $d->monto_cot ?></td>
			<td><?= ($d->pago_cot=='CRE')?'Contado':'Crédito' ?></td>
			<td><?= $d->fecha_cot ?></td>
		</tr>
		<tr>
			<td colspan="8">
				<table class="table table-bordered" width="100%">
					<thead>
						<tr>
							<th>Cod</th>
							<th>Artículo</th>
							<th>Marca</th>
							<th>Unidad</th>
							<th>Cant.</th>
							<th>P. Unid</th>
							<th>Desc.</th>
							<th>IGV</th>
							<th>P. Venta</th>
							<th>Subtotal</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($d->detalle as $t): ?>
						<tr>
							<td><?= $t->cod_cotdet ?></td>
							<td><?= $t->nomb_product ?></td>
							<td><?= $t->nomb_marca ?></td>
							<td><?= $t->abreviatura_unid ?></td>
							<td><?= $t->cant_cotdet ?></td>
							<td><?= $t->precunit_cotdet ?></td>
							<td><?= $t->descuento_cotdet ?></td>
							<td><?= $t->igv_cotdet ?></td>
							<td><?= $t->prec_cotdet ?></td>
							<td><?= $t->subtotal_cotdet ?></td>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>