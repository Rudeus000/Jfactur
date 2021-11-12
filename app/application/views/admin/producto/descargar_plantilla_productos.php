<?php
//require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
//$spreadsheet->getActiveSheet()->getColumnDimensionByColumn(0)->setAutoSize(true);


$spreadsheet = new Spreadsheet;

$numPestana = 0;
foreach ($pestanas as $nombrePestana => $valueP) {
  $spreadsheet->createSheet();
  $spreadsheet->setActiveSheetIndex($numPestana);
  $spreadsheet->getActiveSheet()->setTitle($nombrePestana);
  
  $spreadsheet->getActiveSheet()
  ->setCellValueByColumnAndRow(1,1,'NOMBRE');
  $spreadsheet->getActiveSheet()
  ->setCellValueByColumnAndRow(2,1,'CODIGO');
  
  
  foreach ($valueP['datos'] as $keyDatos => $valuePestana) {
    $spreadsheet->getActiveSheet()
    ->setCellValueByColumnAndRow(1,$valueP['row'],preg_replace('([^A-Za-z0-9 ])', '',$valuePestana->nombre).' - '.$valuePestana->codigo);
    $spreadsheet->getActiveSheet()
    ->setCellValueByColumnAndRow(2,$valueP['row'],$valuePestana->codigo);
    $valueP['row']++;
  }

  $spreadsheet->getActiveSheet()
  ->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);


  $pestanas[$nombrePestana] = $valueP;
  $numPestana++;
}

$spreadsheet->setActiveSheetIndex($numPestana);
$spreadsheet->getActiveSheet()->setTitle('PRODUCTOS');

$columnaArray = [1 => 'A',2 => 'B',3 => 'C',4 => 'D',5 => 'E',6 => 'F',7 => 'G',8 => 'H',9 => 'I',10 => 'J',11 => 'K',12 => 'L',13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T'];

$columna = 1;

foreach ($pestanas as $nombrePestana => $valueP) {
  $spreadsheet->getActiveSheet()
  ->setCellValueByColumnAndRow($columna,1,$nombrePestana)
  ->getStyleByColumnAndRow($columna,1)
  ->applyFromArray($styleBold);
  
  foreach ($valueP['datos'] as $keyDatos => $valuePestana) {
    
    $row = 2;
    foreach (range($row,100) as $key => $value) {
      $validation = $spreadsheet->getActiveSheet()->getCell($columnaArray[$columna].$value)
      ->getDataValidation();
      $validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
      $validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
      $validation->setAllowBlank(false);
      $validation->setShowInputMessage(true);
      $validation->setShowErrorMessage(true);
      $validation->setShowDropDown(true);
      //$validation->setPrompt('SELECCIONE '.$nombrePestana);
      $validation->setFormula1($nombrePestana.'!$A$2:$A$'.($valueP['row']-1));
      $spreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(($columna+1),$value,'=VLOOKUP('.$columnaArray[$columna].$value.','.$nombrePestana.'!A2:B'.($valueP['row']-1).',2,FALSE)');
    }
  }
  $spreadsheet->getActiveSheet()->getColumnDimension($columnaArray[$columna])->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension($columnaArray[($columna+1)])->setWidth(0);
  $columna = $columna + 2;
}


$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(13,1,'NOMBRE PRODUCTO')
->getStyleByColumnAndRow(13,1)
->applyFromArray($styleBold);
$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(30);
$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(14,1,'PRECIO COMPRA')
->getStyleByColumnAndRow(14,1)
->applyFromArray($styleBold);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(15,1,'PRECIO VENTA')
->getStyleByColumnAndRow(15,1)
->applyFromArray($styleBold);
$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(16,1,'PRECIO MAYOR')
->getStyleByColumnAndRow(16,1)
->applyFromArray($styleBold);
$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(17,1,'PRECIO ESPECIAL')
->getStyleByColumnAndRow(17,1)
->applyFromArray($styleBold);
$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(18,1,'STOCK MÍNIMO')
->getStyleByColumnAndRow(18,1)
->applyFromArray($styleBold);
$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(19,1,'STOCK')
->getStyleByColumnAndRow(19,1)
->applyFromArray($styleBold);
$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);

$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(20,1,'CÓDIGO DE BARRAS')
->getStyleByColumnAndRow(20,1)
->applyFromArray($styleBold);
$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);



$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Plantilla_Productos_'.time().'.xlsx"');
$writer->save("php://output");
exit;



?>