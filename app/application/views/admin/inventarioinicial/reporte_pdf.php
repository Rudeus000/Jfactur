<h4 class="text-center">INVENTARIO INCIAL  - <?= $almacen->nomb_almacen ?></h4>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Nombre</th>
	    <th>Marca</th>
	    <th>Categoria</th>
	    <th>Unidad</th>
	    <th>P. Costo</th>
	    <th>P. Venta</th>
	    <th>Stock Actual</th>
	    <th>Stock Inicial</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($datos as $d): ?>
		<tr>
			<td><?= $d->nomb_product ?></td>
			<td><?= $d->nomb_marca ?></td>
			<td><?= $d->nomb_categoria ?></td>
			<td><?= $d->nomb_unid ?></td>
			<td><?= $d->prec_costo ?></td>
			<td><?= $d->prec_venta ?></td>
			<td><?= $d->stock + $d->stock_inicial ?></td>
			<td><?= $d->stock_inicial ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>