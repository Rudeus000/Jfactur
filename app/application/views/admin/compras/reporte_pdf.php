<h4 class="text-center">COMPRAS</h4>
<table class="table table-bordered">
	<tbody>
		<?php foreach ($datos as $d): ?>
		<tr>
			<th>FECHA</th>
			<th>DOCUMENTO</th>
			<th>PROVEEDOR</th>
			<th>RUC/DNI</th>
			<th>ALMACEN</th>
			<th>TOTAL</th>
			<th>PAGOS</th>
			<th>SALDO</th>
		</tr>
		<tr>
			<td><?= $d->fecha_comp ?></td>
			<td><?= $d->documento_comp ?></td>
			<td><?= $d->tb_proveedor_nom ?></td>
			<td><?= $d->numdocumento_comp ?></td>
			<td><?= $d->nomb_almacen ?></td>
			<td><?= $d->total_comp ?></td>
			<td><?= $d->efectivo_comp ?></td>
			<td><?= $d->saldo_comp ?></td>
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
							<th>IGV</th>
							<th>P. Venta</th>
							<th>Subtotal</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($d->detalle as $t): ?>
						<tr>
							<td><?= $t->cod_comp ?></td>
							<td><?= $t->nomb_product ?></td>
							<td><?= $t->nomb_marca ?></td>
							<td><?= $t->abreviatura_unid ?></td>
							<td><?= $t->cant_compdet ?></td>
							<td><?= $t->precunit_compdet ?></td>
							<td><?= $t->igv_compdet ?></td>
							<td><?= $t->precventa_compdet ?></td>
							<td><?= $t->subtotal_compdet ?></td>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>