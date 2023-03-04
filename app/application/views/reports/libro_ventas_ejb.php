<?php 
require APP_PATH.'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$objPHPExcel = new Spreadsheet();

for ($i=0; $i <= 23; $i++) { 
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
}
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
				'argb' => '333',
		],
	],
];

$headers = ["RUC", "Tipo Documento", "Serie Documento", "Numero Documento", "Fecha Documento", "Fecha Vencimiento", "Moneda", "Importe IGV", "Importe Documento", "Importe Inafecto", "Iimporte ISC", "Otros", "Cuenta de Venta", "Fecha Documento Ref.", "Tipo Documento Ref.", "Serie Documento Ref.", "Número Documento Ref.", "Centro Costo", "Subsidiario", "Cuenta por Cobrar", "Glosa de Comprobante", "Anexo de Venta"];


$row = 1;

$indice = 1;
foreach ($headers as $key => $value) {
	$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow($indice,$row,$value)
	->getStyleByColumnAndRow($indice,$row)
	->applyFromArray($styleBold);
  $indice++;
}
	
$row++;


foreach ($datos as $key => $value) {
  
  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(1,$row,$value['NUMERO_CLIENTE'])
  ->getStyleByColumnAndRow(1,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(2,$row,$value['TIPO_DOCUMENTO'])
  ->getStyleByColumnAndRow(2,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(3,$row,$value['SERIE'])
  ->getStyleByColumnAndRow(3,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(4,$row,$value['NUMERO'])
  ->getStyleByColumnAndRow(4,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(5,$row,$value['F_EMISION'])
  ->getStyleByColumnAndRow(5,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(6,$row,$value['F_VENCIMIENTO'])
  ->getStyleByColumnAndRow(6,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(7,$row,$value['MONEDA']=='PEN'?'MN':'ME')
  ->getStyleByColumnAndRow(7,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(8,$row,$value['IGV'])
  ->getStyleByColumnAndRow(8,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(9,$row,$value['TOTAL'])
  ->getStyleByColumnAndRow(9,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(10,$row,$value['OP_INAFECTA'])
  ->getStyleByColumnAndRow(10,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(11,$row,$value['ISC'])
  ->getStyleByColumnAndRow(11,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(12,$row,$value['OTROS_TRIBUTOS'])
  ->getStyleByColumnAndRow(12,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(13,$row,'701212')
  ->getStyleByColumnAndRow(13,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(14,$row,$value['FECHA_COM_MODIF'])
  ->getStyleByColumnAndRow(14,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(15,$row,$value['TIPO_DOC_MODIF'])
  ->getStyleByColumnAndRow(15,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(16,$row,$value['SERIE_DOC_MODIF'])
  ->getStyleByColumnAndRow(16,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(17,$row,$value['NUM_DOC_MODIF'])
  ->getStyleByColumnAndRow(17,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(18,$row,'')
  ->getStyleByColumnAndRow(18,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(19,$row,'05')
  ->getStyleByColumnAndRow(19,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(20,$row,'121201')
  ->getStyleByColumnAndRow(20,$row)
  ->applyFromArray($styleNormal);


  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(21,$row,'¿?')
  ->getStyleByColumnAndRow(21,$row)
  ->applyFromArray($styleNormal);

  $objPHPExcel->getActiveSheet()
  ->setCellValueByColumnAndRow(22,$row,'¿?')
  ->getStyleByColumnAndRow(22,$row)
  ->applyFromArray($styleNormal);


  $row++;
}

$objPHPExcel->getActiveSheet()->setTitle('Libro electrónico EJB');
$objPHPExcel->getActiveSheet(0);

$writer = new Xlsx($objPHPExcel);


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Libro_electronico_EJB.xlsx"');
$writer->save("php://output");
exit;
?>