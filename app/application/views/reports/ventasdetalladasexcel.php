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
->setCellValueByColumnAndRow(4,$row,'CLIENTE')
->getStyleByColumnAndRow(4,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,'DOCUMENTO')
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,'VENDEDOR')
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(7,$row,'PRODUCTO')
->getStyleByColumnAndRow(7,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(8,$row,'ISDN')
->getStyleByColumnAndRow(8,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(9,$row,'SERIE')
->getStyleByColumnAndRow(9,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()	
->setCellValueByColumnAndRow(10,$row,'PREC. UNID.')
->getStyleByColumnAndRow(10,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(11,$row,'DSCUENTO')
->getStyleByColumnAndRow(11,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(12,$row,'PREC. CON DESC.')
->getStyleByColumnAndRow(12,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(13,$row,'CANTIDAD')
->getStyleByColumnAndRow(13,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(14,$row,'SUBTOTAL')
->getStyleByColumnAndRow(14,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(15,$row,'ESTADO')
->getStyleByColumnAndRow(15,$row)
->applyFromArray($styleBold);

$total = 0;
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
	->setCellValueByColumnAndRow(4,$row,$d->nomb_cliente)
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,$d->nom_tipdocumento.'-'.$d->serie.'-'.$d->numero_vent)
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,$d->nombre_apellido)
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,$d->producto_ventdet)
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,$d->producto_isdn)
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleNormal);	
	$objPHPExcel->getActiveSheet()->getCell('I'.$row)
	->setValueExplicit(
		$d->serie_ventdetserie,
			\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
	);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(10,$row,$d->precunit_ventdet)
	->getStyleByColumnAndRow(10,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(11,$row,$d->descuento_ventdet)
	->getStyleByColumnAndRow(11,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(12,$row,$d->precunit_con_descuento)
	->getStyleByColumnAndRow(12,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(13,$row,$d->cantidad)
	->getStyleByColumnAndRow(13,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(14,$row,$d->subtotal)
	->getStyleByColumnAndRow(14,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(15,$row,$d->estado_vent=='G'?'Generado':'Anulado')
	->getStyleByColumnAndRow(15,$row)
	->applyFromArray($styleNormal);
	
	$total += $d->subtotal;
	$row++;
}

$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(13,$row,'TOTAL')
	->getStyleByColumnAndRow(13,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(14,$row,$total)
	->getStyleByColumnAndRow(14,$row)
	->applyFromArray($styleNormal);


if($this->session->userdata('movil_expert')=='0'){
	$objPHPExcel->getActiveSheet()->removeColumnByIndex(8);
}



$writer = new Xlsx($objPHPExcel);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Ventas Detalladas.xlsx"');
$writer->save("php://output");
exit;

?>
