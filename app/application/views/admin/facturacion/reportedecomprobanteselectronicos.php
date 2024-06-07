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
				'argb' => 'FF6C00',
		],
		'endColor' => [
				'argb' => 'FF6C00',
		],
	],
];
$objPHPExcel->getActiveSheet()
						->mergeCells('A1:K1');
$objPHPExcel->getActiveSheet()
						->getCell('A1')
						->setValue($empresa->razon_social);
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setSize(14)->setBold(true);
$objPHPExcel->getActiveSheet()
    ->getStyle('A1:K1')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()
	->getStyle("A1:K1")
	->getFill()
	->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setARGB("FFD100");


$objPHPExcel->getActiveSheet()
						->mergeCells('A2:K2');
$objPHPExcel->getActiveSheet()
						->getCell('A2')
						->setValue('REPORTE DE COMPROBANTES ELECTRONICOS');
$objPHPExcel->getActiveSheet()->getStyle('A2:K2')->getFont()->setSize(14)->setBold(true);
$objPHPExcel->getActiveSheet()
    ->getStyle('A2:K2')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()
	->getStyle("A2:K2")
	->getFill()
	->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setARGB("FFD100");

$objPHPExcel->getActiveSheet()
						->mergeCells('A3:K3');						
$objPHPExcel->getActiveSheet()
						->getCell('A3')
						->setValue('DESDE EL '.$data['desde'] = $this->input->get('desde').' HASTA EL '.$data['hasta'] = $this->input->get('hasta'));
$objPHPExcel->getActiveSheet()->getStyle('A3:K3')->getFont()->setSize(14)->setBold(false);
$objPHPExcel->getActiveSheet()
    ->getStyle('A3:K3')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()
	->getStyle("A3:K3")
	->getFill()
	->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setARGB("FFD100");


$date=date('d-m-Y');
$objPHPExcel->getActiveSheet()
						->mergeCells('A4:K4');						
$objPHPExcel->getActiveSheet()
						->getCell('A4')
						->setValue('FECHA  : '.$date);
$objPHPExcel->getActiveSheet()->getStyle('A4:K4')->getFont()->setSize(14)->setBold(false);
$objPHPExcel->getActiveSheet()
    ->getStyle('A4:K4')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()
	->getStyle("A4:K4")
	->getFill()
	->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setARGB("FFD100");


$time=date('H:i:s');
$objPHPExcel->getActiveSheet()
						->mergeCells('A5:K5');						
$objPHPExcel->getActiveSheet()
						->getCell('A5')
						->setValue('HORA   :      '.$time);
$objPHPExcel->getActiveSheet()->getStyle('A5:K5')->getFont()->setSize(14)->setBold(false);
$objPHPExcel->getActiveSheet()
    ->getStyle('A5:K5')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()
	->getStyle("A5:K5")
	->getFill()
	->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setARGB("FFD100");

$row = 6;

$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(1,$row,'FECHA')
->getStyleByColumnAndRow(1,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(2,$row,'DNI/RUC')
->getStyleByColumnAndRow(2,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(3,$row,'CLIENTE')
->getStyleByColumnAndRow(3,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(4,$row,'SUBT TOTAL')
->getStyleByColumnAndRow(4,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,'IGV')
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,'EXONERADA')
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(7,$row,'GRATUITA')
->getStyleByColumnAndRow(7,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(8,$row,'TOTAL')
->getStyleByColumnAndRow(8,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(9,$row,'TIPO DOC')
->getStyleByColumnAndRow(9,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(10,$row,'ESTADO')
->getStyleByColumnAndRow(10,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(11,$row,'MSJ_SUNAT')
->getStyleByColumnAndRow(11,$row)
->applyFromArray($styleBold);
$row++;
foreach ($datos as $d) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(1,$row,$d->fecha)
	->getStyleByColumnAndRow(1,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(2,$row,$d->doc_cliente)
	->getStyleByColumnAndRow(2,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(3,$row,$d->nomb_cliente)
	->getStyleByColumnAndRow(3,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,$d->subtotal)
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(5,$row,$d->igv)
	->getStyleByColumnAndRow(5,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(6,$row,$d->exonerada)
	->getStyleByColumnAndRow(6,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(7,$row,$d->free)
	->getStyleByColumnAndRow(7,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(8,$row,$d->total)
	->getStyleByColumnAndRow(8,$row)
	->applyFromArray($styleNormal);
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(9,$row,$d->tipo_documento.'-'.$d->serie.'-'.$d->numero)
	->getStyleByColumnAndRow(9,$row)
	->applyFromArray($styleNormal);
	
	if ($d->estado_doc == '2') {
		$estadoactual = 'Rechazado';
	} elseif ($d->estado_vent == 'A') {
		$estadoactual = 'Anulado';
	} elseif ($d->hash != '' || !is_null($d->hash)) {
		$estadoactual = 'Aceptado';
	} else {
		$estadoactual = 'Sin respuesta';
	}
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(10,$row,$estadoactual)
	->getStyleByColumnAndRow(10,$row)
	->applyFromArray($styleNormal);

	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(11,$row,$d->msj_sunat)
	->getStyleByColumnAndRow(11,$row)
	->applyFromArray($styleNormal);		

	
	
	$row++;
}


$writer = new Xlsx($objPHPExcel);
// Obtener la fecha actual en el formato deseado
$currentDate = date('Y-m-d\THis.u');

// Concatenar la fecha actual con el nombre del archivo
$fileName = "REPORTE DE CPE - $currentDate.xlsx";

// Establecer las cabeceras para la descarga del archivo
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Guardar el archivo en la salida
$writer->save("php://output");
exit;

?>