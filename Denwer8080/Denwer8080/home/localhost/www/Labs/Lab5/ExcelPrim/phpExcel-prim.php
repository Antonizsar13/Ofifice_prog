<?php
require_once('PHPExcel/Classes/PHPExcel.php');
require_once('PHPExcel/Classes/PHPExcel/Writer/Excel5.php');
$xls = new PHPExcel();
$xls->setActiveSheetIndex(0);
$sheet = $xls->getActiveSheet();
$sheet->setTitle('Example1');
$sheet->setCellValueByColumnAndRow(1,1, "Ардатовский район");
$sheet->setCellValueByColumnAndRow(1,2, "Атяшевский район");
$sheet->setCellValueByColumnAndRow(1,3, "Чамзинский район");
$sheet->setCellValueByColumnAndRow(1,4, "Ромодановский район");
$sheet->setCellValueByColumnAndRow(1,5, "Итого");
$sheet->setCellValueByColumnAndRow(2,1, 2034);
$sheet->setCellValueByColumnAndRow(2,2, 702);
$sheet->setCellValueByColumnAndRow(2,3, 6202);
$sheet->setCellValueByColumnAndRow(2,4, 90);
$sheet->getColumnDimension('B')->setWidth(35);
$sheet->getColumnDimension('c')->setWidth(20);
$sheet->setCellValue('C5', '=SUM(C1:C4)');
$objWriter = new PHPExcel_Writer_Excel5($xls);
$objWriter->save('output-1.xls');
echo "Ok...";
?>
