<?php 
require APP_PATH.'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$objPHPExcel = new Spreadsheet();

for ($i=0; $i <= 37; $i++) { 
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

$headers = ["PERIODO" ,"COD_UNIC", "TIPO_REGIMEN", "F_EMISION","F_VENCIMIENTO","TIPO_DOCUMENTO","SERIE","NUMERO","NUM_MAQ_REG","T_DOC","NUMERO_CLIENTE","RAZON_SOCIAL","OP_EXPORT","OP_GRAVADA","DESCUENTO","IGV","DESC_IGV","OP_EXONERADA","OP_INAFECTA","ISC","OP_ARROZ_P","IMP_ARROZ_OP","ICB_PER","OTROS_TRIBUTOS","TOTAL","MONEDA","T_C","FECHA_COM_MODIF","TIPO_DOC_MODIF","SERIE_DOC_MODIF","NUM_DOC_MODIF","ID_CONTR","ERR_T_C","COMP_M_P","ESTADO","CAMP_LIB","ESTADO_COMP"];


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

$letras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK'];


foreach ($datos as $key => $value) {
	
	$indice = 1;
	$letra = 0;
  foreach ($value as $keyRow => $valueRow) {
  	
		//in_array($letras[$letra],array('W','AA'))
		if(1){
			//echo $letras[$letra].$row;
			$objPHPExcel->getActiveSheet()
            ->getCell($letras[$letra].$row)
            ->setValueExplicit(
								$valueRow,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
            );
		}else{
			$objPHPExcel->getActiveSheet()
			->setCellValueByColumnAndRow($indice,$row,0.00)
			->getStyleByColumnAndRow($indice,$row)
			->applyFromArray($styleNormal);
		}
		
    $indice++;
		$letra++;
  }
  
  $row++;
}

$objPHPExcel->getActiveSheet()->setTitle('Libro electrónico de ventas');
$objPHPExcel->getActiveSheet(0);

$writer = new Xlsx($objPHPExcel);



header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Libro_electronico_excel.xlsx"');
$writer->save("php://output");
exit;
?>