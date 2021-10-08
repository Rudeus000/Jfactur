<?php 
require APP_PATH.'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setAutoSize(true);


$objPHPExcel->getActiveSheet()
						->mergeCells('A1:H1');
$objPHPExcel->getActiveSheet()
						->getCell('A1')
						->setValue('Inventario Inicial series - Ingresos');
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setSize(18)->setBold(true);
$objPHPExcel->getActiveSheet()
    ->getStyle('A1:H1')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

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
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	],
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'rotation' => 90,
		'startColor' => [
				'argb' => '01B9B5',
		],
		'endColor' => [
				'argb' => '01B9B5',
		],
	],
];

$row = 2;
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(1,$row,'ALMACEN')
->getStyleByColumnAndRow(1,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(2,$row,'PRODUCTO')
->getStyleByColumnAndRow(2,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(3,$row,'SERIES')
->getStyleByColumnAndRow(3,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(4,$row,'COD_COMPRA')
->getStyleByColumnAndRow(4,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,'COD_VENTA')
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,'ESTADO_SERIE')
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(7,$row,'HISTROIAL SERIE')
->getStyleByColumnAndRow(7,$row)
->applyFromArray($styleBold);
// $objPHPExcel->getActiveSheet()
// ->setCellValueByColumnAndRow(8,$row,'STOCK INICIAL')
// ->getStyleByColumnAndRow(8,$row)
// ->applyFromArray($styleBold);

$row++;
foreach ($datos as $d) {
	$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(1,$row,$d->nomb_almacen)
->getStyleByColumnAndRow(1,$row)
->applyFromArray($styleNormal);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(2,$row,$d->nomb_product)
->getStyleByColumnAndRow(2,$row)
->applyFromArray($styleNormal);
$objPHPExcel->getActiveSheet()->getCell('C'.$row)
	->setValueExplicit(
		$d->serie_descripcion,
			\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
	);

$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(4,$row,$d->cod_comp)
->getStyleByColumnAndRow(4,$row)
->applyFromArray($styleNormal);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,$d->cod_vent)
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleNormal);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,$d->serie_estado=='D'?'DISPONIBLE':'VENDIDO')
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleNormal);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(7,$row,$d->histcompstock_serie)
->getStyleByColumnAndRow(7,$row)
->applyFromArray($styleNormal);
// $objPHPExcel->getActiveSheet()
// ->setCellValueByColumnAndRow(8,$row,$d->stock_inicial)
// ->getStyleByColumnAndRow(8,$row)
// ->applyFromArray($styleNormal);
	$row++;
}


$objPHPExcel->getActiveSheet()->setTitle('Reporte');
$objPHPExcel->getActiveSheet(0);
						
$writer = new Xlsx($objPHPExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Inventario inicial(ingresos - series).xlsx"');
$writer->save("php://output");
exit;
?>
