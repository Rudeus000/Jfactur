<h4 class="text-center">GASTOS ADMINISTRATIVOS</h4>
<br>
<h7><b>Desde:</b>  <?= date('Y/m/d',strtotime($_GET['desde'])) ?> - <?= date('Y/m/d',strtotime($_GET['hasta'])) ?></h7><br>
			
<h7><b>Estado: <?= $_GET['estado'] ?></h7><br>

<br>
<br>
<table class="table table-bordered">
	  <thead>

	  	<tr>
	  		
		      <th style="text-align: center; background-color: #AAA6AC">Nombre gastos</th>
		      <th style="text-align: center; background-color: #AAA6AC">Tipo gastos</th>
		      <th style="text-align: center; background-color: #AAA6AC">Banco</th>
		      <th style="text-align: center; background-color: #AAA6AC">Fecha</th>
		      <th style="text-align: center; background-color: #AAA6AC">Cuenta</th>
		      <th style="text-align: center; background-color: #AAA6AC">Persona</th>
		      <th style="text-align: center;  background-color: #AAA6AC">Operacion</th>
		      <th style="text-align: center;  background-color: #AAA6AC">Documento</th>
		      <th style="text-align: center;  background-color: #AAA6AC">Total</th>
		      <th style="text-align: center;  background-color: #AAA6AC">Observacion</th>
		      <th style="text-align: center;  background-color: #AAA6AC">Estado</th>
	  	</tr>
	  </thead>
	<tbody>
		<?php  $totalGastadosGeneral = 0; ?>
		<?php foreach ($datos as $d): ?>
	
		<tr>
			
			<td><?= $d->nomb_gastos ?></td>
			<td><?= $d->descripcion ?></td>
			<td><?= $d->nomb_ban ?></td>
			<td><?= $d->fecha_registro ?></td>
			<td><?= $d->cuenta_gastos ?></td>
			<td><?= $d->persona_gastos ?></td>
			<td><?= $d->oper_gastos ?></td>
			<td><?= $d->documento_gastos ?></td>
			<td><?= $d->total_gastos ?></td>
			<td><?= $d->observacion_gastos ?></td>
			<td><?= $d->estado ?></td>
			<!-- <td><?= $d->nomb_cliente ?></td>
			<td><?= $d->monto_vent ?></td>
			<td><?= ($d->pago_vent=='CRE')?'Contado':'Crédito' ?></td>
			<td><?= $d->fecha_vent ?></td> -->
		</tr>
		<?php $totalGastadosGeneral += $d->total_gastos ?>
	
		<?php endforeach ?>
	</tbody>
</table>

<h6><b> Total Gastado:</b> S/. <?= number_format(round($totalGastadosGeneral,2)) ?></h7><br>