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
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(9)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(11)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(12)->setAutoSize(true);
$objPHPExcel->getActiveSheet()
						->mergeCells('A1:K1');
$objPHPExcel->getActiveSheet()
						->getCell('A1')
						->setValue('Inventario de productos con series');
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setSize(18)->setBold(true);
$objPHPExcel->getActiveSheet()
    ->getStyle('A1:K1')
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
->setCellValueByColumnAndRow(3,$row,'PRO.ESTADO')
->getStyleByColumnAndRow(3,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(4,$row,'SERIES')
->getStyleByColumnAndRow(4,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,'COD.COMPRA')
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,'COD.VENTA')
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(7,$row,'ESTADO SERIE')
->getStyleByColumnAndRow(7,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(8,$row,'HISTROIAL SERIE')
->getStyleByColumnAndRow(8,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(9,$row,'FECHA REGISTRO')
->getStyleByColumnAndRow(9,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(10,$row,'PRECIO COMP ACT.')
->getStyleByColumnAndRow(10,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(11,$row,'PRECIO VENT ACT.')
->getStyleByColumnAndRow(11,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(12,$row,'FECHA VENTA')
->getStyleByColumnAndRow(12,$row)
->applyFromArray($styleBold);
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
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(3,$row,$d->est_product=='1'?'ACTIVO':'DISCONTINUO')
->getStyleByColumnAndRow(3,$row)
->applyFromArray($styleNormal);
$objPHPExcel->getActiveSheet()->getCell('D'.$row)
	->setValueExplicit(
		$d->serie_descripcion,
			\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
	);

$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,$d->cod_comp)
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleNormal);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,$d->cod_vent)
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleNormal);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(7,$row,$d->serie_estado=='D'?'DISPONIBLE':'VENDIDO')
->getStyleByColumnAndRow(7,$row)
->applyFromArray($styleNormal);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(8,$row,$d->histcompstock_serie)
->getStyleByColumnAndRow(8,$row)
->applyFromArray($styleNormal);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(9,$row,$d->fecha_registro)
->getStyleByColumnAndRow(9,$row)
->applyFromArray($styleNormal);
// Obtener el valor del costo
$prec_costo = $d->prec_costo;
// Dar formato de moneda soles
$prec_costo_formatted = 'S/ ' . number_format($prec_costo, 2);

$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(10,$row,$prec_costo_formatted)
->getStyleByColumnAndRow(10,$row)
->applyFromArray($styleNormal);
// Obtener el valor del costo
$prec_venta = $d->prec_venta;
// Dar formato de moneda soles
$prec_venta_formatted = 'S/ ' . number_format($prec_venta, 2);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(11,$row,$prec_venta_formatted)
->getStyleByColumnAndRow(11,$row)
->applyFromArray($styleNormal);
//$fecha_vent_formateada = date('Y-m-d', strtotime($d->fecha_venta));
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(12,$row,$d->fecha_venta)
->getStyleByColumnAndRow(12,$row)
->applyFromArray($styleNormal);
	$row++;
}


$objPHPExcel->getActiveSheet()->setTitle('Reporte');
$objPHPExcel->getActiveSheet(0);
						
$writer = new Xlsx($objPHPExcel);
// Obtener la fecha actual en el formato deseado
$currentDate = date('Y-m-d\THis.u');

// Concatenar la fecha actual con el nombre del archivo
$fileName = "RIPS - $currentDate.xlsx";

// Establecer las cabeceras para la descarga del archivo
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Guardar el archivo en la salida
$writer->save("php://output");
exit;
?>