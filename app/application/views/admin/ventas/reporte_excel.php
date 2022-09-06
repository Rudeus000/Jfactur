<?php 
require APP_PATH.'vendor/autoload.php';

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
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
	]
		
];

$styleBold = [
	'font' => [
			'bold' => true,
	],
	'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
	],
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'rotation' => 90,
		'startColor' => [
				'argb' => '4489e4',
		],
		'endColor' => [
				'argb' => 'EFECEF',
		],
	],
];



$row = 1;
// foreach ($datos->result() as $d) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,'DOCUMENTO')
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,'FECHA VENTA')
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,'CLIENTE')
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,'DOCUMENTO CLIENTE')
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,'MONEDA')
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,'TOTAL')
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,'ESTADO')
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,'COBRO')
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(9,$row,'SALDO')
	->getStyleByColumnAndRow(9,$row)
	->applyFromArray($styleBold);
	
	$row++;
	foreach ($datos->result() as $d) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,$d->nom_tipdocumento)
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,$d->fecha_vent)
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,$d->nomb_cliente)
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,$d->doc_cliente)
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,$d->moneda_vent=='S'?'Soles':'Dolares')
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,$d->total_vent)
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,$d->estado_vent=='G'?'Generado':'Anulado')
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,$d->total_vent)
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(9,$row,$d->pendiente_vent)
	->getStyleByColumnAndRow(9,$row)
	->applyFromArray($styleNormal);

	// $row++;
	// $objPHPExcel->getActiveSheet()
	// ->setCellValueByColumnAndRow(1,$row,'COD')
	// ->getStyleByColumnAndRow(1,$row)
	// ->applyFromArray($styleBold);
	// $objPHPExcel->getActiveSheet()
	// ->setCellValueByColumnAndRow(2,$row,'ARTÍCULO')
	// ->getStyleByColumnAndRow(2,$row)
	// ->applyFromArray($styleBold);
	// $objPHPExcel->getActiveSheet()
	// ->setCellValueByColumnAndRow(3,$row,'MARCA')
	// ->getStyleByColumnAndRow(3,$row)
	// ->applyFromArray($styleBold);
	// $objPHPExcel->getActiveSheet()
	// ->setCellValueByColumnAndRow(4,$row,'UNIDAD')
	// ->getStyleByColumnAndRow(4,$row)
	// ->applyFromArray($styleBold);
	// $objPHPExcel->getActiveSheet()
	// ->setCellValueByColumnAndRow(5,$row,'CANT.')
	// ->getStyleByColumnAndRow(5,$row)
	// ->applyFromArray($styleBold);
	// $objPHPExcel->getActiveSheet()
	// ->setCellValueByColumnAndRow(6,$row,'P. UNID.')
	// ->getStyleByColumnAndRow(6,$row)
	// ->applyFromArray($styleBold);
	// $objPHPExcel->getActiveSheet()
	// ->setCellValueByColumnAndRow(7,$row,'DESC.')
	// ->getStyleByColumnAndRow(7,$row)
	// ->applyFromArray($styleBold);
	// $objPHPExcel->getActiveSheet()
	// ->setCellValueByColumnAndRow(8,$row,'IGV')
	// ->getStyleByColumnAndRow(8,$row)
	// ->applyFromArray($styleBold);
	// $objPHPExcel->getActiveSheet()
	// ->setCellValueByColumnAndRow(9,$row,'P. VENTA')
	// ->getStyleByColumnAndRow(9,$row)
	// ->applyFromArray($styleBold);
	// $objPHPExcel->getActiveSheet()
	// ->setCellValueByColumnAndRow(10,$row,'SUBTOTAL')
	// ->getStyleByColumnAndRow(10,$row)
	// ->applyFromArray($styleBold);

	// $row++;
	// foreach ($d->detalle as $t) {
	// 	$objPHPExcel->getActiveSheet()
	// 	->setCellValueByColumnAndRow(1,$row,$t->cod_ventdet)
	// 	->getStyleByColumnAndRow(1,$row)
	// 	->applyFromArray($styleNormal);
	// 	$objPHPExcel->getActiveSheet()
	// 	->setCellValueByColumnAndRow(2,$row,$t->nomb_product)
	// 	->getStyleByColumnAndRow(2,$row)
	// 	->applyFromArray($styleNormal);
	// 	$objPHPExcel->getActiveSheet()
	// 	->setCellValueByColumnAndRow(3,$row,$t->nomb_marca)
	// 	->getStyleByColumnAndRow(3,$row)
	// 	->applyFromArray($styleNormal);
	// 	$objPHPExcel->getActiveSheet()
	// 	->setCellValueByColumnAndRow(4,$row,$t->abreviatura_unid)
	// 	->getStyleByColumnAndRow(4,$row)
	// 	->applyFromArray($styleNormal);
	// 	$objPHPExcel->getActiveSheet()
	// 	->setCellValueByColumnAndRow(5,$row,$t->cant_ventdet)
	// 	->getStyleByColumnAndRow(5,$row)
	// 	->applyFromArray($styleNormal);
	// 	$objPHPExcel->getActiveSheet()
	// 	->setCellValueByColumnAndRow(6,$row,$t->precunit_ventdet)
	// 	->getStyleByColumnAndRow(6,$row)
	// 	->applyFromArray($styleNormal);
	// 	$objPHPExcel->getActiveSheet()
	// 	->setCellValueByColumnAndRow(7,$row,($t->tipo_ventdet=='V')?$t->descuento_ventdet:'')
	// 	->getStyleByColumnAndRow(7,$row)
	// 	->applyFromArray($styleNormal);
	// 	$objPHPExcel->getActiveSheet()
	// 	->setCellValueByColumnAndRow(8,$row,($t->tipo_ventdet=='V')?$t->igv_ventdet:'')
	// 	->getStyleByColumnAndRow(8,$row)
	// 	->applyFromArray($styleNormal);
	// 	$objPHPExcel->getActiveSheet()
	// 	->setCellValueByColumnAndRow(9,$row,($t->tipo_ventdet=='V')?$t->prec_ventdet:'')
	// 	->getStyleByColumnAndRow(9,$row)
	// 	->applyFromArray($styleNormal);
	// 	$objPHPExcel->getActiveSheet()
	// 	->setCellValueByColumnAndRow(10,$row,($t->tipo_ventdet=='V')?$t->subtotal_ventdet:'')
	// 	->getStyleByColumnAndRow(10,$row)
	// 	->applyFromArray($styleNormal);
	// 	$row++;
	//}


	//$row++;
	$row++;
}

$writer = new Xlsx($objPHPExcel);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Lista_de_ventas_general.xlsx"');
$writer->save("php://output");
exit;
?>