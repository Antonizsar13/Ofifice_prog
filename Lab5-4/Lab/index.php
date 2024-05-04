<?php

ini_set('memory_limit', '256M');

require_once('MyConnect.php');
require_once('PHPExcel/Classes/PHPExcel.php');
require_once('PHPExcel/Classes/PHPExcel/Writer/Excel5.php');

MyConnectOtvet();
if (MyConnectOtvet()) {
    echo 'Подключено к БД<br>';
} else {
    echo 'Ошибка подключеняи к БД';
    die();
}


$query_view = 'SELECT tname, kod, date_p, price, kol_vo, (price*kol_vo) `all_tovare`, photo FROM `eltovary` order by kod;';
$res_view = $mysqli->query($query_view);
if (!$res_view) {
    echo '<div>Ошибка обращения к базе данных на сервере...';
    die();
}
$xls = new PHPExcel();

$sheet = $xls->getActiveSheet();
$sheet->setTitle('Приход товаров');
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$styleRed = array(
    'font' => array(
        'size' => 15,
        'color' => array('rgb' => 'ff0000'),
    ),
);

$styleYellowBlue = array(
    'font' => array(
        'bold' => true,
        'color' => array('rgb' => '1914fc'),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'ffffb2'),
    ),
);

$sheet->mergeCells('B1:H1');
$sheet->setCellValue('B1', 'Приход товаров (Электротатары)');
$sheet->getStyle('B1:C1')->applyFromArray($styleRed);

$sheet->getStyle('B2:H2')->applyFromArray($styleYellowBlue);
$sheet->setCellValue('B2', 'Код');
$sheet->setCellValue('C2', 'Наименование');
$sheet->setCellValue('D2', 'Дата прихода');
$sheet->setCellValue('E2', 'Цена');
$sheet->setCellValue('F2', 'Кол-во');
$sheet->setCellValue('G2', 'Итого по товару');
$sheet->setCellValue('H2', 'Товар');

$sheet->getStyle('B2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// $sheet->getColumnDimension('H')->setWidth(25);



$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);
$sheet->getColumnDimension('H')->setWidth(14.5);

// $sheet->getColumnDimension('H')->setAutoSize(true);

$styleGreenBlack = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'bfff80'),
    ),
    'alignment' => array(
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleGreenWhite = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'd1ffa4'),
    ),
    'alignment' => array(
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);
$index = 3;
$sum = 0;

while ($row = $res_view->fetch_assoc()) {
    if ($index % 2) {
        $sheet->getStyle('B' . $index . ':' . 'G' . $index)->applyFromArray($styleGreenWhite);
    } else {
        $sheet->getStyle('B' . $index . ':' . 'G' . $index)->applyFromArray($styleGreenBlack);
    }
    $sheet->setCellValueByColumnAndRow(1, $index, $row['kod']);
    $sheet->setCellValueByColumnAndRow(2, $index, $row['tname']);
    $sheet->setCellValueByColumnAndRow(3, $index, $row['date_p']);
    $sheet->setCellValueByColumnAndRow(4, $index, $row['price']);
    $sheet->setCellValueByColumnAndRow(5, $index, $row['kol_vo']);
    $sheet->setCellValueByColumnAndRow(6, $index, $row['all_tovare']);

    file_put_contents('img\image' . $index . '.jpg', $row['photo']);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setPath('img\image' . $index . '.jpg');
    $objDrawing->setCoordinates('H' . $index);
    $objDrawing->setWidth(800); // Установить новую ширину для изображения
    $objDrawing->setHeight(60);
    // $objDrawing->setOffsetX(0); // Горизонтальное смещение
    // $objDrawing->setOffsetY(0);
    $objDrawing->setWorksheet($sheet);
    // $sheet->setCellValue('H' . $index, '=HYPERLINK("img\image' . $index . '.jpg")');

    $sheet->getRowDimension($index)->setRowHeight(50);
    $sum += $row['all_tovare'];
    $index++;
}


$styleYellowBlack = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'ffffb2'),
    ),
);

$sheet->mergeCells('B' . $index  . ':F' . $index);
$sheet->setCellValue('B' . $index, 'Всего по электротоварам:');
$sheet->getStyle('G' . $index)->applyFromArray($styleYellowBlue);

$sheet->setCellValueByColumnAndRow(6, $index, $sum);
$sheet->getStyle('B' . $index  . ':F' . $index)->applyFromArray($styleYellowBlack);
$sheet->getStyle('H' . $index)->applyFromArray($styleYellowBlack);

$sheet->getStyle('E3:E' . ($index - 1))->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->getStyle('G3:G' . ($index))->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$xls->setActiveSheetIndex(0);


$objWriter = new PHPExcel_Writer_Excel5($xls);
$objWriter->save('Tovary.xls');
echo 'Ok...';
