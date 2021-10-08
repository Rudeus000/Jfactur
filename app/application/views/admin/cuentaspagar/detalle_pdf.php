<h4 class="text-center">DETALLE DE CUENTAS POR PAGAR</h4>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Detalle</th>
	    <th>Fecha</th>
	    <th>FeC. Venc.</th>
	    <th>Estado</th>
	    <th>Monto</th>
	    <th>Abonos</th>
	    <th>Saldo</th>
	    <th>Pagos</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($datos as $d): ?>
		<tr>
			<td><?= $d->documento_comp.': '.$d->numdocumento_comp ?></td>
      <td><?= $d->fecha_comp ?></td>
      <td><?= $d->fecvenc_comp ?></td>
      <td>
        <?php 
            if (date('Y-m-d') > $d->fecvenc_comp) {
              echo 'Vencido';
            }else{
              echo 'Por vencer';
            }
         ?>
      </td>
      <td><?= $d->saldo_comp ?></td>
      <td><?= $d->abono ?></td>
      <td><?= number_format($d->saldo_comp - $d->abono, 2, '.', ',') ?></td>
      <td>
        <table class="table table-bordered table-condensed">
          <tr>
            <th>Fecha</th>
            <th>Caja</th>
            <th>Monto</th>
          </tr>
          <?php foreach ($d->pagos as $p): ?>
          <tr>
            <td><?= $p->fecha_pago ?></td>
            <td><?= $p->nomb_caja ?></td>
            <td><?= $p->monto_pago ?></td>
          </tr>
          <?php endforeach ?>
        </table>
      </td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>