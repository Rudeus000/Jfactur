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
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(11)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(12)->setAutoSize(true);

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
->setCellValueByColumnAndRow(3,$row,'PROVEEDOR')
->getStyleByColumnAndRow(3,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(4,$row,'DOCUMENTO')
->getStyleByColumnAndRow(4,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,'N° DOC.')
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,'PRODUCTO')
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(7,$row,'SERIE')
->getStyleByColumnAndRow(7,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(8,$row,'INGRESO')
->getStyleByColumnAndRow(8,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(9,$row,'STOCK')
->getStyleByColumnAndRow(9,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(10,$row,'VENTAS')
->getStyleByColumnAndRow(10,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(11,$row,'PREC. UNID.')
->getStyleByColumnAndRow(11,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(12,$row,'SUBTOTAL')
->getStyleByColumnAndRow(12,$row)
->applyFromArray($styleBold);

$total = 0;
$row++;
foreach ($datos as $d) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,$d->fecha_comp)
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,$d->nomb_almacen)
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,$d->tb_proveedor_nom)
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,$d->documento_comp)
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,$d->numdocumento_comp)
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,$d->nomb_product)
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleNormal);

	$objPHPExcel->getActiveSheet()->getCell('G'.$row)
	->setValueExplicit(
		$d->serie_descripcion,
			\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
	);
		
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,$d->ingreso)
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(9,$row,$d->stock)
	->getStyleByColumnAndRow(9,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(10,$row,$d->ventas)
	->getStyleByColumnAndRow(10,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(11,$row,$d->precunit_compdet)
	->getStyleByColumnAndRow(11,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(12,$row,$d->subtotal)
	->getStyleByColumnAndRow(12,$row)
	->applyFromArray($styleNormal);

	if(is_null($d->serie_descripcion)){
		$row++;
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(1,$row,'HISTORIAL')
		->getStyleByColumnAndRow(1,$row)
		->applyFromArray($styleBold);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(2,$row,'FECHA')
		->getStyleByColumnAndRow(2,$row)
		->applyFromArray($styleBold);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(3,$row,'CANTIDAD')
		->getStyleByColumnAndRow(3,$row)
		->applyFromArray($styleBold);
		foreach ($d->historial as $h) {
			$row++;
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(2,$row,$h->fecha_comp)
			->getStyleByColumnAndRow(2,$row)
			->applyFromArray($styleNormal);
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(3,$row,$h->cant_compdet)
			->getStyleByColumnAndRow(3,$row)
			->applyFromArray($styleNormal);
		}
	}
	$total += $d->subtotal;
	
	$row++;
}

$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(11,$row,'TOTAL')
	->getStyleByColumnAndRow(11,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(12,$row,$total)
	->getStyleByColumnAndRow(12,$row)
	->applyFromArray($styleNormal);

$writer = new Xlsx($objPHPExcel);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Compras Detalladas.xlsx"');
$writer->save("php://output");
exit;

?>
