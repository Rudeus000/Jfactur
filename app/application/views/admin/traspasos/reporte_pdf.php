<h4 class="text-center">TRASPASOS</h4>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Fecha</th>
			<th>Almacen Origen</th>
			<th>Almacen Destino</th>
			<th>Observación</th>
			<th>Id</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($datos as $d): ?>
		<tr>
			<td><?= $d->fecha_tras ?></td>
			<td><?= $d->origen ?></td>
			<td><?= $d->destino ?></td>
			<td><?= $d->observacion_tras ?></td>
			<td><?= $d->cod_tras ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>