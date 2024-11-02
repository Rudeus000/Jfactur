<?php 
require APP_PATH. 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(9)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(11)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(12)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(13)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(14)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(15)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(16)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(17)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(18)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(19)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(20)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(21)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(22)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(23)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(24)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(25)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(26)->setAutoSize(true);
$styleNormal = [
	'font' => [
			'bold' => false,
	],
	'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
	]
		
];

$styleBold = [
	'font' => [
			'bold' => true,
	],
	'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
	],
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'rotation' => 90,
		'startColor' => [
				'argb' => 'EFECEF',
		],
		'endColor' => [
				'argb' => 'EFECEF',
		],
	],
];



$row = 1;
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(1,$row,'CODIGO')
->getStyleByColumnAndRow(1,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(2,$row,'FECHA')
->getStyleByColumnAndRow(2,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(3,$row,'HORA')
->getStyleByColumnAndRow(3,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(4,$row,'TIPO VENTA')
->getStyleByColumnAndRow(4,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,'ALMACEN')
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,'PUNTO DE VENTA')
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(7,$row,'DNI-RUC')
->getStyleByColumnAndRow(7,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(8,$row,'CLIENTE')
->getStyleByColumnAndRow(8,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(9,$row,'DOCUMENTO')
->getStyleByColumnAndRow(9,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(10,$row,'VENDEDOR')
->getStyleByColumnAndRow(10,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(11,$row,'UNIDAD M.')
->getStyleByColumnAndRow(11,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(12,$row,'PRODUCTO')
->getStyleByColumnAndRow(12,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(13,$row,'ISDN')
->getStyleByColumnAndRow(13,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(14,$row,'SERIE')
->getStyleByColumnAndRow(14,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(15,$row,'T.PAGO')
->getStyleByColumnAndRow(15,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(16,$row,'N.OPERACION')
->getStyleByColumnAndRow(16,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(17,$row,'MONTO BD')
->getStyleByColumnAndRow(17,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()	
->setCellValueByColumnAndRow(18,$row,'PREC. UNID.')
->getStyleByColumnAndRow(18,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(19,$row,'DSCUENTO')
->getStyleByColumnAndRow(19,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(20,$row,'PREC. CON DESC.')
->getStyleByColumnAndRow(20,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(21,$row,'CANTIDAD')
->getStyleByColumnAndRow(21,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(22,$row,'SUBTOTAL')
->getStyleByColumnAndRow(22,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(23,$row,'ESTADO')
->getStyleByColumnAndRow(23,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(24,$row,'OBSERVACION')
->getStyleByColumnAndRow(24,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(25,$row,'MONTO_RETORNO')
->getStyleByColumnAndRow(25,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(26,$row,'MOTIVO_RETORNO')
->getStyleByColumnAndRow(26,$row)
->applyFromArray($styleBold);

$total = 0;
$retorno=0;
$totalbd=0;
$row++;
$idvent='V';
$ventas_ids = [];

foreach ($datos as $d) {	
	$codigovent= str_pad($d->codigo_venta, 7, "0", STR_PAD_LEFT);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,$idvent.''.$codigovent)
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,$d->fecha_vent)
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,$d->hora_vent)
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,$d->pago_vent=='CO'?'Contado':'Credito')
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,$d->nomb_almacen)
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,$d->nomb_puntoventa)
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,$d->doc_cliente)
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,$d->nomb_cliente)
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(9,$row,$d->nom_tipdocumento.'-'.$d->serie.'-'.$d->numero_vent)
	->getStyleByColumnAndRow(9,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(10,$row,$d->nombre_apellido)
	->getStyleByColumnAndRow(10,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(11,$row,$d->unidad_ventdet)
	->getStyleByColumnAndRow(11,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(12,$row,$d->producto_ventdet)
	->getStyleByColumnAndRow(12,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(13,$row,$d->producto_isdn)
	->getStyleByColumnAndRow(13,$row)
	->applyFromArray($styleNormal);	
	$objPHPExcel->getActiveSheet()->getCell('N'.$row)
	->setValueExplicit(
		$d->serie_ventdetserie,
			\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
	);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(15,$row,$d->nom_tipopago)
	->getStyleByColumnAndRow(15,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(16,$row,$d->operacion)
	->getStyleByColumnAndRow(16,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(17,$row,"0")
	->getStyleByColumnAndRow(17,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(18,$row,$d->precunit_ventdet)
	->getStyleByColumnAndRow(18,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(19,$row,$d->descuento_ventdet)
	->getStyleByColumnAndRow(19,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(20,$row,$d->precunit_con_descuento)
	->getStyleByColumnAndRow(20,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(21,$row,$d->cantidad)
	->getStyleByColumnAndRow(21,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(22,$row,$d->subtotal)
	->getStyleByColumnAndRow(22,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(23,$row,$d->estado_vent=='G'?'Generado':'Anulado')
	->getStyleByColumnAndRow(23,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(24,$row,$d->observacion_vent)
	->getStyleByColumnAndRow(24,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(25,$row,$d->return_bipay)
	->getStyleByColumnAndRow(25,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(26,$row,$d->descripcion_retorno_bipay)
	->getStyleByColumnAndRow(26,$row)
	->applyFromArray($styleNormal);
	
	$total += $d->subtotal;	
	$retorno+=$d->return_bipay;
	// $totalbd+=$d->total_monto_bd;
	$row++;
	if (!in_array($d->codigo_venta, $ventas_ids)) {
        $totalbd += $d->monto_bd;
        $ventas_ids[] = $d->codigo_venta;
    }
	$sub_total=$total-$totalbd;
}

$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(21,$row,'SUB TOTAL')
	->getStyleByColumnAndRow(21,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(22,$row,$sub_total)
	->getStyleByColumnAndRow(22,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(21,$row+1,'TOTAL YAPE')
	->getStyleByColumnAndRow(21,$row+1)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(22,$row+1,$totalbd)
	->getStyleByColumnAndRow(22,$row+1)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(21,$row+2,'TOTAL')
	->getStyleByColumnAndRow(21,$row+2)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(22,$row+2,$total)
	->getStyleByColumnAndRow(22,$row+2)
	->applyFromArray($styleBold);

	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(24,$row,'TOTAL_RETORNO')
	->getStyleByColumnAndRow(24,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(25,$row,$retorno)
	->getStyleByColumnAndRow(25,$row)
	->applyFromArray($styleBold);


if($this->session->userdata('movil_expert')=='0'){
	$objPHPExcel->getActiveSheet()->removeColumnByIndex(26);
	$objPHPExcel->getActiveSheet()->removeColumnByIndex(25);
	$objPHPExcel->getActiveSheet()->removeColumnByIndex(12);
}

$writer = new Xlsx($objPHPExcel);

// Obtener la fecha actual en el formato deseado
$currentDate = date('Y-m-d\THis.u');

// Concatenar la fecha actual con el nombre del archivo
$fileName = "RDV - $currentDate.xlsx";

// Establecer las cabeceras para la descarga del archivo
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Guardar el archivo en la salida
$writer->save("php://output");
exit;
?>