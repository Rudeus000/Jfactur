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
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(0,$row,'Proveedor')
	->getStyleByColumnAndRow(0,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,'DNI/RUC')
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,'Monto')
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,'Abonos')
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,'Saldo')
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleBold);
	$row++;

foreach ($datos as $d) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(0,$row,$d->tb_proveedor_nom)
	->getStyleByColumnAndRow(0,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,$d->tb_proveedor_doc)
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,$d->monto)
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,$d->abono)
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,$d->saldo)
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
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
