<h4 class="text-center">DETALLE DE CUENTAS POR COBRAR</h4>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Detalle</th>
	    <th>Fecha</th>
	    <th>Fec. Venc.</th>
	    <th>Estado</th>
	    <th>Monto</th>
	    <th>Abonos</th>
	    <th>Saldo</th>
	    <th>Cobros</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($datos as $d): ?>
		<tr>
			<td><?= $d->nom_tipdocumento.'-'.$d->serie.'-'.$d->numero_vent ?></td>
      <td><?= $d->fecha_vent ?></td>
      <td><?= $d->fechavenc_vent ?></td>
      <td>
        <?php 
            if (date('Y-m-d') > $d->fechavenc_vent) {
              echo 'Vencido';
            }else{
              echo 'Por vencer';
            }
         ?>
      </td>
      <td><?= $d->saldo_vent ?></td>
      <td><?= $d->abono ?></td>
      <td><?= number_format($d->saldo_vent - $d->abono, 2, '.', ',') ?></td>
      <td>
        <table class="table table-bordered table-condensed">
          <tr>
            <th>Fecha</th>
            <th>Caja</th>
            <th>Monto</th>
          </tr>
          <?php foreach ($d->cobros as $p): ?>
          <tr>
            <td><?= $p->fecha_cobro ?></td>
            <td><?= $p->nomb_caja ?></td>
            <td><?= $p->monto_cobro ?></td>
          </tr>
          <?php endforeach ?>
        </table>
      </td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>