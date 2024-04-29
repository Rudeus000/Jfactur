<div class="w120">
    <div class="w30">
        <img style="max-width: 170px" src="<?= base_url_app('assets/uploads/logo/' . $empresa->photo) ?>">
    </div>
    <div class="w40 text-center">
        <p style="font-size: 18px; padding-left: -70px;"><b><?= $empresa->razon_social ?></b></p>
        <p style="font-size: 14px; padding-left: -70px; padding-top: -11px;"><b> <?= $empresa->direcc_emp ?></b> </p>
        <!-- <p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Telefono: <?= $empresa->telf_emp ?></b> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Email: <?= $empresa->email_emp ?> </b></p> -->
        <!-- <b style="font-size: 12px;"> AYACUHO - LIMA </b> -->
        <!-- <p style="font-size: 10px; padding-left: -70px; padding-top: -11px;"><b> <?= $ventas->direccion_puntoventa ?></b> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Telefono: <?= $ventas->telefono_puntoventa ?></b> </p>
		<p style="font-size: 10px; padding-left: -70px; padding-top: -9px;"><b> Email: <?= $ventas->email_puntoventa ?> </b></p> -->



    </div>

    <div class="w25 text-center" style="float: right; border: 1.5px solid #03A6BF;border-radius: 10px;">
        <br>
        <b style="font-size: 15px;">R.U.C <?= $empresa->ruc_emp ?><b>
        <p>
				<div class="Com-Datos" style="font-size: 15px; background: #03A6BF">NOTA DE TRASPASO</b></div>
				<br>
                <b style="font-size: 15px;">N001-<?= str_pad($traspaso->cod_tras,7,"0",STR_PAD_LEFT); ?></b>


    </div>
</div>
<br><br>

<div class="Com-Datos" style=" background: #03A6BF">
    <b>Detalles del traspaso</b>
</div>

<br>
<div class="w100">
    <div class="w60">
        <p><b style="font-size: 11px;">Usuario que transfiere</b> &nbsp;&nbsp;<?= $traspaso->usuario ?></p>
        <p><b style="font-size: 11px;">Fecha de Traspaso:</b>&nbsp;&nbsp; <?= $traspaso->fecha_tras ?></p>
        <p><b style="font-size: 11px;">Almacén Origen:</b>&nbsp;&nbsp; <?= $traspaso->origen ?></p>
        <p><b style="font-size: 11px;">Almacén Destino: </b>&nbsp;&nbsp;<?= $traspaso->destino ?></p>

    </div>

</div>
<div class="w100" style="border-bottom:1px solid #03A6BF;margin:5px 0">
</div>
<div class="w100">

    <table class="table table-hover">
        <thead>
            <tr>
                <th style="font-size: 10px; border:1px solid #03A6BF; padding: 5px; text-align: center; width: 5px; border-radius: 10px;" height="5">Item</th>
                <th style="font-size: 10px; border:1px solid #03A6BF; padding: 5px; text-align: center; width: 5px; border-radius: 10px;" height="5">Codigo</th>
                <th style="font-size: 10px; border:1px solid #03A6BF; padding: 5px; text-align: center; width: 240px; border-radius: 10px">Producto</th>
                <th style="font-size: 10px; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 50px; border-radius: 10px;">Serie</th>
                <th style="font-size: 10px; border:1px solid #03A6BF; padding: 0px; text-align: center; width: 60px; border-radius: 10px;">Cantidad</th>

            </tr>
        </thead>
        <tbody>
            <?php
            $item = 1;

            $gravada = 0;
            $exonerada = 0;
            $descuentos = 0;

            ?>
            <?php foreach ($detalles as $detalle) : ?>
                <?php $series = explode(',', $detalle->serie_trasdet); ?>
                <tr>
                    <?php if ($detalle->serie_trasdet == "") : ?>
                        <td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $item ?></td>
                        <td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $traspaso->cod_tras ?></td>
                        <td style="border:1px solid #03A6BF; padding: 6px;  "><?= $detalle->nomb_product ?></td>
                        <td style="border:1px solid #03A6BF; padding: 6px; text-align: center;  ">No aplica (Producto sin serie)</td>
                        <td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $detalle->cant_trasdet ?></td>

                    <?php else : ?>
                        <?php foreach ($series as $serie) : ?>
                            <td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $item ?></td>
                            <td style="border:1px solid #03A6BF; padding: 6px; text-align: center; "><?= $traspaso->cod_tras ?></td>
                            <td style="border:1px solid #03A6BF; padding: 6px;  "><?= $detalle->nomb_product ?></td>
                            <td style="border:1px solid #03A6BF; padding: 6px; text-align: center;  "><?= $serie ?></td>
                            <td style="border:1px solid #03A6BF; padding: 6px; text-align: center; ">1</td>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </tr>
                <?php

                $item++;
                ?>
            <?php endforeach ?>
        </tbody>
    </table>

</div>

<div class="w100">
    <div class="w30" style="float: right; padding: 5px;border:2px solid #03A6BF; border-radius: 10px;">

        <div class="w100">
            <div class="w50"><b>TOTAL</b></div>
            <?php if ($detalle->serie_trasdet == "") : ?>
                <div class="w50" style="text-align:right"> <?= array_sum(array_column($detalles, 'cant_trasdet')) ?></div>
            <?php else : ?>
                <?php foreach ($series as $serie) : ?>
                    <div class="w50" style="text-align:right"> <?= count($detalles) ?></div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<br>
<br>
<br>

<div class="w20" style="border-bottom:1px solid #03A6BF;margin:5px 0">
</div>
<br>
<div class="w100">
    <div class="w60">
        <p><b style="font-size: 11px;">Usuario que transfiere</b> &nbsp;&nbsp;<?= $traspaso->usuario ?></p>   

    </div>
</div>

<div class="w20" style="border-bottom:1px solid #03A6BF;margin:5px 0;float: right;">
</div>
<br>
<div class="w100">
    <div class="w15" style="float: right;">
        <p><b style="font-size: 11px;">Usuario que recibe</p>   

    </div>

</div>