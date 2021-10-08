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
foreach ($datos as $d) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(0,$row,'FECHA')
	->getStyleByColumnAndRow(0,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,'DOCUMENTO')
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,'PROVEEDOR')
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,'RUC/DNI')
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,'ALMACEN')
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,'TOTAL')
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,'PAGOS')
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,'SALDO')
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleBold);
	$row++;

	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(0,$row,$d->fecha_comp)
	->getStyleByColumnAndRow(0,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,$d->documento_comp)
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,$d->tb_proveedor_nom)
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,$d->numdocumento_comp)
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,$d->nomb_almacen)
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,$d->total_comp)
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,$d->efectivo_comp)
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,$d->saldo_comp)
	->getStyleByColumnAndRow(7,$row)
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
	->setCellValueByColumnAndRow(6,$row,'IGV')
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,'P. VENTA')
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,'SUBTOTAL')
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleBold);

	$row++;
	foreach ($d->detalle as $t) {
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(0,$row,$t->cod_comp)
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
		->setCellValueByColumnAndRow(4,$row,$t->cant_compdet)
		->getStyleByColumnAndRow(4,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(5,$row,$t->precunit_compdet)
		->getStyleByColumnAndRow(5,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(6,$row,$t->igv_compdet)
		->getStyleByColumnAndRow(6,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(7,$row,$t->precventa_compdet)
		->getStyleByColumnAndRow(7,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(8,$row,$t->subtotal_compdet)
		->getStyleByColumnAndRow(8,$row)
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
