<?php 
require 'vendor/autoload.php';

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
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setAutoSize(true);


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
				'argb' => 'EFECEF',
		],
		'endColor' => [
				'argb' => 'EFECEF',
		],
	],
];



$row = 1;
foreach ($datos->result() as $d) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(0,$row,'ID')
	->getStyleByColumnAndRow(0,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,'CLIENTE')
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,'TIPO')
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,'MONTO')
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,'PAGO')
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,'FECHA')
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleBold);
	$row++;

	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(0,$row,$d->cod_cot)
	->getStyleByColumnAndRow(0,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,$d->nomb_cliente)
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,($d->tipo_cot=='CO')?'Cotización':'Proforma')
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,$d->monto_cot)
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,($d->pago_cot=='CO')?'Contado':'Crédito')
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,$d->fecha_cot)
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleNormal);

	$row++;
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(0,$row,'COD')
	->getStyleByColumnAndRow(0,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,'ARTÍCULO')
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,'MARCA')
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,'UNIDAD')
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,'CANT.')
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,'P. UNID.')
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,'DESC.')
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,'IGV')
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,'P. VENTA')
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(9,$row,'SUBTOTAL')
	->getStyleByColumnAndRow(9,$row)
	->applyFromArray($styleBold);

	$row++;
	foreach ($d->detalle as $t) {
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(0,$row,$t->cod_cotdet)
		->getStyleByColumnAndRow(0,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(1,$row,$t->nomb_product)
		->getStyleByColumnAndRow(1,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(2,$row,$t->nomb_marca)
		->getStyleByColumnAndRow(2,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(3,$row,$t->abreviatura_unid)
		->getStyleByColumnAndRow(3,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(4,$row,$t->cant_cotdet)
		->getStyleByColumnAndRow(4,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(5,$row,$t->precunit_cotdet)
		->getStyleByColumnAndRow(5,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(6,$row,$t->descuento_cotdet)
		->getStyleByColumnAndRow(6,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(7,$row,$t->igv_cotdet)
		->getStyleByColumnAndRow(7,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(8,$row,$t->prec_cotdet)
		->getStyleByColumnAndRow(8,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(9,$row,$t->subtotal_cotdet)
		->getStyleByColumnAndRow(9,$row)
		->applyFromArray($styleNormal);
		$row++;
	}


	$row++;
	$row++;
}


$objPHPExcel->getActiveSheet()->setTitle('Reporte');
$objPHPExcel->getActiveSheet(0);
						
$writer = new Xlsx($objPHPExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Reporte.xlsx"');
$writer->save("php://output");
exit;
?>