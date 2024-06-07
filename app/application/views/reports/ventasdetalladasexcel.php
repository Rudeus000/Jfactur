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
->setCellValueByColumnAndRow(1,$row,'FECHA')
->getStyleByColumnAndRow(1,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(2,$row,'ALMACEN')
->getStyleByColumnAndRow(2,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(3,$row,'PUNTO DE VENTA')
->getStyleByColumnAndRow(3,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(4,$row,'DNI-RUC')
->getStyleByColumnAndRow(4,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,'CLIENTE')
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,'DOCUMENTO')
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(7,$row,'VENDEDOR')
->getStyleByColumnAndRow(7,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(8,$row,'UNIDAD M.')
->getStyleByColumnAndRow(8,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(9,$row,'PRODUCTO')
->getStyleByColumnAndRow(9,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(10,$row,'ISDN')
->getStyleByColumnAndRow(10,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(11,$row,'SERIE')
->getStyleByColumnAndRow(11,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(12,$row,'T.PAGO')
->getStyleByColumnAndRow(12,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()	
->setCellValueByColumnAndRow(13,$row,'PREC. UNID.')
->getStyleByColumnAndRow(13,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(14,$row,'DSCUENTO')
->getStyleByColumnAndRow(14,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(15,$row,'PREC. CON DESC.')
->getStyleByColumnAndRow(15,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(16,$row,'CANTIDAD')
->getStyleByColumnAndRow(16,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(17,$row,'SUBTOTAL')
->getStyleByColumnAndRow(17,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(18,$row,'ESTADO')
->getStyleByColumnAndRow(18,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(19,$row,'OBSERVACION')
->getStyleByColumnAndRow(19,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(20,$row,'MONTO_RETORNO')
->getStyleByColumnAndRow(20,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(21,$row,'MOTIVO_RETORNO')
->getStyleByColumnAndRow(21,$row)
->applyFromArray($styleBold);

$total = 0;
$retorno=0;
$row++;
foreach ($datos as $d) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,$d->fecha_vent)
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,$d->nomb_almacen)
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,$d->nomb_puntoventa)
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,$d->doc_cliente)
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,$d->nomb_cliente)
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,$d->nom_tipdocumento.'-'.$d->serie.'-'.$d->numero_vent)
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,$d->nombre_apellido)
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,$d->unidad_ventdet)
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(9,$row,$d->producto_ventdet)
	->getStyleByColumnAndRow(9,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(10,$row,$d->producto_isdn)
	->getStyleByColumnAndRow(10,$row)
	->applyFromArray($styleNormal);	
	$objPHPExcel->getActiveSheet()->getCell('K'.$row)
	->setValueExplicit(
		$d->serie_ventdetserie,
			\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
	);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(12,$row,$d->nom_tipopago)
	->getStyleByColumnAndRow(12,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(13,$row,$d->precunit_ventdet)
	->getStyleByColumnAndRow(13,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(14,$row,$d->descuento_ventdet)
	->getStyleByColumnAndRow(14,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(15,$row,$d->precunit_con_descuento)
	->getStyleByColumnAndRow(15,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(16,$row,$d->cantidad)
	->getStyleByColumnAndRow(16,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(17,$row,$d->subtotal)
	->getStyleByColumnAndRow(17,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(18,$row,$d->estado_vent=='G'?'Generado':'Anulado')
	->getStyleByColumnAndRow(18,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(19,$row,$d->observacion_vent)
	->getStyleByColumnAndRow(19,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(20,$row,$d->return_bipay)
	->getStyleByColumnAndRow(20,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(21,$row,$d->descripcion_retorno_bipay)
	->getStyleByColumnAndRow(21,$row)
	->applyFromArray($styleNormal);
	
	$total += $d->subtotal;
	$retorno+=$d->return_bipay;
	$row++;
}

$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(16,$row,'TOTAL')
	->getStyleByColumnAndRow(16,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(17,$row,$total)
	->getStyleByColumnAndRow(17,$row)
	->applyFromArray($styleNormal);

	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(19,$row,'TOTAL_RETORNO')
	->getStyleByColumnAndRow(19,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(20,$row,$retorno)
	->getStyleByColumnAndRow(20,$row)
	->applyFromArray($styleNormal);


if($this->session->userdata('movil_expert')=='0'){
	$objPHPExcel->getActiveSheet()->removeColumnByIndex(21);
	$objPHPExcel->getActiveSheet()->removeColumnByIndex(20);
	$objPHPExcel->getActiveSheet()->removeColumnByIndex(10);
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