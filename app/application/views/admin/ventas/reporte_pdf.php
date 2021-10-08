<h4 class="text-center">VENTAS</h4>

<table class="table table-bordered">
	<tbody>
		<?php foreach ($datos->result() as $d): ?>
		<tr>
			<th>Id</th>
      <th>Cliente</th>
      <th>Monto</th>
      <th>Pago</th>
      <th>Fecha</th>
		</tr>
		<tr>
			<td><?= $d->cod_vent ?></td>
			<td><?= $d->nomb_cliente ?></td>
			<td><?= $d->monto_vent ?></td>
			<td><?= ($d->pago_vent=='CRE')?'Contado':'Crédito' ?></td>
			<td><?= $d->fecha_vent ?></td>
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
							<td><?= $t->cod_ventdet ?></td>
							<td><?= $t->producto_ventdet ?></td>
							<td><?= $t->nomb_marca ?></td>
							<td><?= $t->abreviatura_unid ?></td>
							<td><?= $t->cant_ventdet ?></td>
							<td><?= $t->precunit_ventdet ?></td>
							<td><?= ($t->tipo_ventdet=='V')?$t->descuento_ventdet:'' ?></td>
							<td><?= ($t->tipo_ventdet=='V')?$t->igv_ventdet:'' ?></td>
							<td><?= ($t->tipo_ventdet=='V')?$t->prec_ventdet:'' ?></td>
							<td><?= ($t->tipo_ventdet=='V')?$t->subtotal_ventdet:'' ?></td>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
