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
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setAutoSize(true);


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

$objPHPExcel->getActiveSheet()->mergeCells('H1:J1');

$row = 1;
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(0,$row,'Detalle')
	->getStyleByColumnAndRow(0,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,'Fecha')
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,'Fec. Venc.')
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,'Estado')
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,'Monto')
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,'Abonos')
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,'Saldo')
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,'Cobros')
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleBold);
	$row++;

foreach ($datos as $d) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(0,$row,$d->nom_tipdocumento.'-'.$d->serie.'-'.$d->numero_vent)
	->getStyleByColumnAndRow(0,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,$d->fecha_vent)
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,$d->fechavenc_vent)
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleNormal);
	if (date('Y-m-d') > $d->fechavenc_vent) {
    $estado =  'Vencido';
  }else{
    $estado = 'Por vencer';
  }
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,$estado)
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,number_format($d->saldo_vent, 2, '.', ','))
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,number_format($d->abono, 2, '.', ','))
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,number_format($d->saldo_vent - $d->abono, 2, '.', ','))
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,'Fecha')
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,'Caja')
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleBold);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(9,$row,'Monto')
	->getStyleByColumnAndRow(9,$row)
	->applyFromArray($styleBold);
	foreach ($d->cobros as $p) {
		$row++;
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(7,$row,$p->fecha_cobro)
		->getStyleByColumnAndRow(7,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(8,$row,$p->nomb_caja)
		->getStyleByColumnAndRow(8,$row)
		->applyFromArray($styleNormal);
		$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(9,$row,$p->monto_cobro)
		->getStyleByColumnAndRow(9,$row)
		->applyFromArray($styleNormal);
	}
	$row++;
}

$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setTitle('Reporte');
$objPHPExcel->getActiveSheet(0);
						
$writer = new Xlsx($objPHPExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Reporte.xlsx"');
$writer->save("php://output");
exit;
?>