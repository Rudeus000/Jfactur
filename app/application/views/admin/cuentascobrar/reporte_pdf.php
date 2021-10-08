<h4 class="text-center">CUENTAS POR COBRAR</h4>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Cliente</th>
			<th>DNI/RUC</th>
			<th>Monto</th>
			<th>Abonos</th>
			<th>Saldo</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($datos as $d): ?>
		<tr>
			<td><?= $d->nomb_cliente ?></td>
			<td><?= $d->doc_cliente ?></td>
			<td><?= $d->monto ?></td>
			<td><?= $d->abono ?></td>
			<td><?= $d->saldo ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>