<div class="120" >
<div class="w30">
		<img style="max-width: 170px" src="<?= base_url_app('assets/uploads/logo/'.$empresa->photo) ?>" > 
	</div>
<div class="w100">
  <div class="w40 text-center">
    <p style="font-size: 18px; padding-left: -70px;"><b><?= $empresa->razon_social ?></b></p>
    <p style="font-size: 14px; padding-left: -70px; padding-top: -11px;"><b> <?= $empresa->direcc_emp ?></b> </p>
   
		
  </div>
  <div class="Comp-Marco">
    
        <h5><b>RUC: <?= $empresa->ruc_emp ?></b></h5>
        <h5 class="Com-Datos"><b>GUIA DE REMISIÓN ELECTRÓNICA</b></h5>        
        <h5><b><?= $guia->serie_guia.'-'.$guia->secuencia_guia ?></b></h5>
  
   
  </div>
</div>
<br>

<div class="centrado">

  <h5 class="Com-Datos"><b>DATOS DEL INICIO DEL TRASLADO</b></h5>

</div>
<div class="w100">
  <div class="w50"><b>Fecha:</b></div>
  <div class="w50"><?= $guia->fecha_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>Motivo de traslado:</b></div>
  <div class="w50"><?= $guia->motivo_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>Tipo de transporte:</b></div>
  <div class="w50"><?= $guia->tipotransporte_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>N° de paquetes:</b></div>
  <div class="w50"><?= $guia->numpaq_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>Peso (Kilos):</b></div>
  <div class="w50"><?= $guia->peso_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>Razon social:</b></div>
  <div class="w50"><?= $guia->razontransp_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>Doc. de transporte:</b></div>
  <div class="w50"><?= $guia->doctransporte_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>N° Doc. de transporte:</b></div>
  <div class="w50"><?= $guia->numdoctransp_guia ?></div>
</div>

<div class="linea-marco centrado">
  <h5 class="Com-Datos"><b>DATOS DEL DESTINATARIO</b></h5>
</div>
<div class="w100">
  <div class="w50"><b>Nombres y Apellidos:</b></div>
  <div class="w50"><?= $guia->nomb_cliente ?></div>
</div>
<div class="w100">
  <div class="w50"><b>Tipo Documento:</b></div>
  <div class="w50"><?= $guia->nom_tipdocucli ?></div>
</div>
<div class="w100">
  <div class="w50"><b>N° de Documento:</b></div>
  <div class="w50"><?= $guia->doc_cliente ?></div>
</div>

<div class="linea-marco centrado">
  <h5 class="Com-Datos"><b>DATOS DE PUNTO DE PARTIDA Y PUNTO DE DESTINO</b></h5>
</div>
<div class="w100">
  <div class="w50"><b>Ubigeo Partida:</b></div>
  <div class="w50"><?= $guia->ubigpartida_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>Dirección Partida:</b></div>
  <div class="w50"><?= $guia->direcpartida_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>Ubigeo Destino:</b></div>
  <div class="w50"><?= $guia->ubigdestino_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>Dirección Destino:</b></div>
  <div class="w50"><?= $guia->direcdestino_guia ?></div>
</div>
<div class="w100">
  <div class="w50"><b>Nota:</b></div>
  <div class="w50"><?= $guia->nota_guia ?></div>
</div>


<div class="linea-marco centrado">
  <h5 class="Com-Datos"><b>DATOS DE LOS BIENES:</b></h5>
</div>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th>Producto</th>
      <th>Marca</th>
      <th>Unidad</th>
      <th>Cantidad</th>
    </tr>
  </thead>
  <tbody>
    <?php $n = 1; ?>
    <?php foreach($guia->detalle as $d): ?>
      <tr>
        <td><?= $n ?></td>
        <td><?= $d->nomb_product ?></td>
        <td><?= $d->nomb_marca ?></td>
        <td><?= $d->nomb_unid ?></td>
        <td><?= $d->cant_ventdet ?></td>
      </tr>
    <?php $n++ ?>
    <?php endforeach ?>
  </tbody>
</table>


</div>