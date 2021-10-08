<h4 class="text-center">CUENTAS POR PAGAR</h4>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Proveedor</th>
			<th>DNI/RUC</th>
			<th>Monto</th>
			<th>Abonos</th>
			<th>Saldo</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($datos as $d): ?>
		<tr>
			<td><?= $d->tb_proveedor_nom ?></td>
			<td><?= $d->tb_proveedor_doc ?></td>
			<td><?= $d->monto ?></td>
			<td><?= $d->abono ?></td>
			<td><?= $d->saldo ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>