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
	->setCellValueByColumnAndRow(1,$row,'ID')
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,'FECHA')
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,'ALAMACEN ORIGEN')
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,'ALMACEN DESTINO')
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,'PRODUCTO')
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,'CANTIDAD')
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,'SERIES')
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,'USUARIO')
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(9,$row,'OBSERVACIONES')
	->getStyleByColumnAndRow(9,$row)
	->applyFromArray($styleBold);
	$row++;

foreach ($datos as $d) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,$d->cod_tras)
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,$d->fecha_tras)
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,$d->origen)
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,$d->destino)
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,$d->nomb_product)
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,$d->cant_trasdet)
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleNormal);
	
	$objPHPExcel->getActiveSheet()->getCell('G'.$row)
	->setValueExplicit(
		$d->serie_trasdet,
			\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
	);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,$d->usuario)
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(9,$row,$d->observacion_tras)
	->getStyleByColumnAndRow(9,$row)
	->applyFromArray($styleNormal);
	$row++;
}


$objPHPExcel->getActiveSheet()->setTitle('Reporte de traspasos');
$objPHPExcel->getActiveSheet(0);
						

$writer = new Xlsx($objPHPExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Reporte de traspasos.xlsx"');
$writer->save("php://output");
exit;
?>