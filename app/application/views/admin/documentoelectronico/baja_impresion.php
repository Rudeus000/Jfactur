
<div style="padding:20px 30px;font-size:12px">
<div class="w100">
  <div class="w50">
  <div class="w30">
    <img style="max-width: 170px" src="<?= base_url_app('assets/uploads/logo/'.$empresa->photo) ?>" > 
  </div>
    <div class="w100 text-center">
      <h3 style="text-aling:center"><b><?= $empresa->razon_social ?></b></h3>
    </div>
    <div class="w100 text-center"> 
      <p><b>Dirección: </b><?= $empresa->direcc_emp ?></p>
    </div>
  </div>
  <div class="w50 text-center">
    <div style="padding:0px 40px">
      <div style="border:1.5px solid black">
        <h5><b>COMUNICACIÓN DE BAJA</b></h5>
        <h5><b>RUC: <?= $empresa->ruc_emp ?></b></h5>
        <h5><b><?= $baja->codigo_baja.'-'.$baja->serie_baja.'-'.$baja->secuencia_baja ?></b></h5>
      </div>
    </div>
  </div>
</div>

<br><br>
<div class="w100">
  <div class="w40"><b>Fecha de referencia:</b> <?= $baja->fecha_baja ?></div>
  <div class="w40"><b>Tipo de comprobante:</b> <?= $baja->nom_tipdocumento ?></div>
</div>

<br><br>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Tipo de Documentro</th>
      <th>Número de Documento</th>
      <th>Razon social</th>
      <th>Motivo</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?= $baja->nom_tipdocumento ?></td>
      <td><?= $baja->numero_vent ?></td>
      <td><?= $baja->nomb_cliente ?></td>
      <td><?= $baja->motivo_baja ?></td>
    </tr>
  </tbody>
</table>

</div>
