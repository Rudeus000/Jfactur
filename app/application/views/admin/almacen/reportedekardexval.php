<?php 
require APP_PATH.'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
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

$styleNormal = ['font' => ['bold' => false,],'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,]];

$styleBold = ['font' => ['bold' => true,],
	'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,],
	'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'rotation' => 90,
		'startColor' => ['argb' => 'FF6C00',],
		'endColor' => ['argb' => 'FF6C00',],
	],
];

$styleArray = array(
	'borders' => array(
		'allBorders' => array(
			'borderStyle' => Border::BORDER_THIN,
			'color' => array('argb' => '000000'),
		),
	),
);
 
$objPHPExcel ->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleArray);
$objPHPExcel ->getActiveSheet()->getStyle('A5:N5')->applyFromArray($styleArray);
$objPHPExcel ->getActiveSheet()->getStyle('A7:N7')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()
						->mergeCells('A1:N1');
$objPHPExcel->getActiveSheet()
						->getCell('A1')
						->setValue("MI EMPRESA");
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setSize(14)->setBold(true);
$objPHPExcel->getActiveSheet()
    ->getStyle('A1:H1')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()
	->getStyle("A1:H1")
	->getFill()
	->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setARGB("FFD100");


$objPHPExcel->getActiveSheet()
						->mergeCells('A2:N2');
$objPHPExcel->getActiveSheet()
						->getCell('A2')
						->setValue('KARDEX UNIDADES AL '.strval($fecha));
$objPHPExcel->getActiveSheet()->getStyle('A2:N2')->getFont()->setSize(14)->setBold(true);
$objPHPExcel->getActiveSheet()
    ->getStyle('A2:H2')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()
	->getStyle("A2:H2")
	->getFill()
	->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setARGB("FFD100");

$objPHPExcel->getActiveSheet()
						->mergeCells('A3:N3');						
$objPHPExcel->getActiveSheet()
						->getCell('A3')
						->setValue(' ');
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFont()->setSize(14)->setBold(false);
$objPHPExcel->getActiveSheet()
    ->getStyle('A3:H3')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()
	->getStyle("A3:H3")
	->getFill()
	->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setARGB("FFD100");


$date=date('d-m-Y');
$objPHPExcel->getActiveSheet()
						->mergeCells('A4:N4');						
$objPHPExcel->getActiveSheet()
						->getCell('A4')
						->setValue('FECHA  : '.$date);
$objPHPExcel->getActiveSheet()->getStyle('A4:N4')->getFont()->setSize(14)->setBold(false);
$objPHPExcel->getActiveSheet()
    ->getStyle('A4:H4')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()
	->getStyle("A4:N4")
	->getFill()
	->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setARGB("FFD100");


$time=date('H:i:s');
$objPHPExcel->getActiveSheet()
						->mergeCells('A5:N5');						
$objPHPExcel->getActiveSheet()
						->getCell('A5')
						->setValue('HORA   :      '.$time);
$objPHPExcel->getActiveSheet()->getStyle('A5:N5')->getFont()->setSize(14)->setBold(false);
$objPHPExcel->getActiveSheet()
    ->getStyle('A5:N5')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()
	->getStyle("A5:N5")
	->getFill()
	->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
	->getStartColor()
	->setARGB("FFD100");

$row = 6;
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(1,$row,'')
->getStyleByColumnAndRow(1,$row)
->applyFromArray($styleBold);

$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(2,$row,'')
->getStyleByColumnAndRow(2,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(3,$row,'')
->getStyleByColumnAndRow(3,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(4,$row,'')
->getStyleByColumnAndRow(4,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,'')
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,'INGRESO')
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleBold);

$objPHPExcel->getActiveSheet()->mergeCells("F".$row.":H".$row);

	
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(9,$row,'SALIDA')
->getStyleByColumnAndRow(9,$row)
->applyFromArray($styleBold);


$objPHPExcel->getActiveSheet()->mergeCells("I".$row.":K".$row);	
	
	
	
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(12,$row,'SALDO')
->getStyleByColumnAndRow(12,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()->mergeCells("L".$row.":N".$row);	
	
$row++;
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(1,$row,'FECHA')
->getStyleByColumnAndRow(1,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(2,$row,'BOLETA')
->getStyleByColumnAndRow(2,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(3,$row,'REFERENCIA')
->getStyleByColumnAndRow(3,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(4,$row,'NRO. REFERENCIA')
->getStyleByColumnAndRow(4,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(5,$row,'OPERACION')
->getStyleByColumnAndRow(5,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(6,$row,'CANTIDAD')
->getStyleByColumnAndRow(6,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(7,$row,'COSTO')
->getStyleByColumnAndRow(7,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(8,$row,'TOTAL')
->getStyleByColumnAndRow(8,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(9,$row,'CANTIDAD')
->getStyleByColumnAndRow(9,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(10,$row,'COSTO')
->getStyleByColumnAndRow(10,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(11,$row,'TOTAL')
->getStyleByColumnAndRow(11,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(12,$row,'CANTIDAD')
->getStyleByColumnAndRow(12,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(13,$row,'COSTO')
->getStyleByColumnAndRow(13,$row)
->applyFromArray($styleBold);
$objPHPExcel->getActiveSheet()
->setCellValueByColumnAndRow(14,$row,'TOTAL')
->getStyleByColumnAndRow(14,$row)
->applyFromArray($styleBold);
$row++;
foreach ($datos as $v=>$fila) {
	/*$objPHPExcel->getActiveSheet()
	->setCellValueByColumnAndRow(4,$row,$v)
	->getStyleByColumnAndRow(4,$row)
	->applyFromArray($styleNormal);	*/
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':H'.$row);
	$objPHPExcel->getActiveSheet()->getCell('A'.$row)->setValue($v);	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->getFont()->setBold(true);	
	$row++;
	foreach ($fila as $v_fila=>$filad) {
		/*$objPHPExcel->getActiveSheet()
		->setCellValueByColumnAndRow(4,$row,$v_fila)
		->getStyleByColumnAndRow(4,$row)
		->applyFromArray($styleNormal);*/
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':H'.$row.'');
		$objPHPExcel->getActiveSheet()->getCell('A'.$row.'')->setValue($v_fila);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->getFont()->setBold(true);			
		$row++;
		foreach ($filad as $ind=>$val) {
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(1,$row,$val['fecha'])
			->getStyleByColumnAndRow(1,$row)
			->applyFromArray($styleNormal);
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(2,$row,$val['boleta'])
			->getStyleByColumnAndRow(2,$row)
			->applyFromArray($styleNormal);
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(3,$row,$val['referencia'])
			->getStyleByColumnAndRow(3,$row)
			->applyFromArray($styleNormal);
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(4,$row,$val['nroreferencia'])
			->getStyleByColumnAndRow(4,$row)
			->applyFromArray($styleNormal);
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(5,$row,$val['operacion'])
			->getStyleByColumnAndRow(5,$row)
			->applyFromArray($styleNormal);
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(6,$row,$val['cantingreso'])
			->getStyleByColumnAndRow(6,$row)
			->applyFromArray($styleNormal);
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(7,$row,$val['costoingreso'])
			->getStyleByColumnAndRow(7,$row)
			->applyFromArray($styleNormal);
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(8,$row,$val['totalingreso'])
			->getStyleByColumnAndRow(8,$row)
			->applyFromArray($styleNormal);
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(9,$row,$val['cantsalida'])
			->getStyleByColumnAndRow(9,$row)
			->applyFromArray($styleNormal);	
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(10,$row,$val['costosalida'])
			->getStyleByColumnAndRow(10,$row)
			->applyFromArray($styleNormal);	
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(11,$row,$val['totalsalida'])
			->getStyleByColumnAndRow(11,$row)
			->applyFromArray($styleNormal);	
			
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(12,$row,$val['cntsaldo'])
			->getStyleByColumnAndRow(12,$row)
			->applyFromArray($styleNormal);	
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(13,$row,$val['costosaldo'])
			->getStyleByColumnAndRow(13,$row)
			->applyFromArray($styleNormal);	
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow(14,$row,$val['totalsaldo'])
			->getStyleByColumnAndRow(14,$row)
			->applyFromArray($styleNormal);	
			$row++;
		}
	}
}


$writer = new Xlsx($objPHPExcel);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="kardex valorizado.xlsx"');
$writer->save("php://output");
exit;

?>
