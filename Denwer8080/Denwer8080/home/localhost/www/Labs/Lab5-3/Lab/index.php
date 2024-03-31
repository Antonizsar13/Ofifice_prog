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


$query_view = 'select (select CategName from category cat where cat.CategId = deli.CategId) Категория, (select SubCategName from subcategory sub where sub.SubCategId = deli.SubCategId and sub.CategId = deli.CategId) Подкатегория, (select ProductName from product_names products where products.SubCategId = deli.SubCategId and products.CategId = deli.CategId and products.ProductId = deli.ProductId) Продукт, sum(Price * Quantity) `Сумма, руб.` from product_deliveries deli where year(DeliveryDate) = 2021 group by CategId, SubCategId, ProductId order by Категория, Подкатегория, Продукт;';

$res_view = $mysqli->query($query_view);
if (!$res_view) {
    echo '<div>Ошибка обращения к базе данных на сервере...';
    die();
}
$xls = new PHPExcel();

$sheet = $xls->getActiveSheet();
$sheet->setTitle('Поставка_овощи-фрукты_2021_год');
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$newSheet = $xls->createSheet();
$newSheet->setTitle('Сводка_за_2021_год');
$newSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$newSheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$styleWhilet = array(
    'font' => array(
        'size' => 15,
        'color' => array('rgb' => 'ffffff'),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => '9001b0'),
    ),
);

$sheet->getStyle('B2:F2')->applyFromArray($styleWhilet);
$sheet->mergeCells('B2:F2');
$sheet->setCellValue('B2', 'Отчет о поставках овощей-фруктов в 2021 году');

$styleGreen = array(
    'font' => array(
        'color' => array('rgb' => 'ffffff'),
        'bold' => true,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => '01b050'),
    ),
);

$sheet->getStyle('B3:F3')->applyFromArray($styleGreen);
$sheet->setCellValue('B3', 'Категория');
$sheet->setCellValue('C3', 'Подкатегория');
$sheet->setCellValue('D3', 'Продукт');
$sheet->setCellValue('E3', 'Сумма, руб.');
$sheet->setCellValue('F3', 'Итого по КатПодкат руб.');

$sheet->getStyle('E3:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
// $sheet->getColumnDimension('C')->setWidth(25);



$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);


$newSheet->getStyle('B2:D2')->applyFromArray($styleGreen);
$newSheet->setCellValue('B2', 'Категория');
$newSheet->setCellValue('C2', 'Подкатегория');
$newSheet->setCellValue('D2', 'Сумма, руб.');

$newSheet->getColumnDimension('B')->setAutoSize(true);
$newSheet->getColumnDimension('C')->setAutoSize(true);
$newSheet->getColumnDimension('D')->setAutoSize(true);


$stylePink = array(
    'font' => array(
        'color' => array('rgb' => '4a24e5'),
        'bold' => true,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'ffd3e5'),
    ),
);

$styleYellow = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'ffffb5'),
    ),
);

$styleBlue = array(
    'font' => array(
        'color' => array('rgb' => 'ff0000'),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'bbebeb'),
    ),
);

$index = 4;
$newIndex = 3;
$kateg = '';
$podKateg = '';
$sumPodKateg = 0;
$sum2021 = 0;
while ($row = $res_view->fetch_assoc()) {

    if ($podKateg != $row['Подкатегория']) {
        if ($podKateg != '') {
            $sheet->setCellValueByColumnAndRow(5, $index - 1, $sumPodKateg);


            $newSheet->setCellValueByColumnAndRow(2, $newIndex, $podKateg);
            $newSheet->setCellValueByColumnAndRow(3, $newIndex, $sumPodKateg);
            $newIndex++;
        }
        $sum2021 += $sumPodKateg;
        $sumPodKateg = $row['Сумма, руб.'];

        $sheet->setCellValueByColumnAndRow(2, $index, $row['Подкатегория']);
        $podKateg = $row['Подкатегория'];
    } else {
        $sumPodKateg += $row['Сумма, руб.'];
    }

    if ($kateg != $row['Категория']) {
        $sheet->setCellValueByColumnAndRow(1, $index, $row['Категория']);
        $newSheet->setCellValueByColumnAndRow(1, $newIndex, $row['Категория']);
        $kateg = $row['Категория'];
    }

    $sheet->setCellValueByColumnAndRow(3, $index, $row['Продукт']);
    $sheet->setCellValueByColumnAndRow(4, $index, $row['Сумма, руб.']);

    $index++;
}
$sheet->setCellValueByColumnAndRow(5, $index - 1, $sumPodKateg);

$sheet->mergeCells('B' . $index  . ':E' . $index);
$sheet->setCellValue('B' . $index, 'Всего по 2021 году:');
$sum2021 += $sumPodKateg;
$sheet->setCellValueByColumnAndRow(5, $index, $sum2021);
$sheet->getStyle('B' . $index  . ':F' . $index)->applyFromArray($styleGreen)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$sheet->getStyle('B4:D' . ($index - 1))->applyFromArray($stylePink);
$sheet->getStyle('E4:E' . ($index - 1))->applyFromArray($styleYellow)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->getStyle('F4:F' . ($index - 1))->applyFromArray($styleBlue)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$newSheet->setCellValueByColumnAndRow(2, $newIndex, $podKateg);
$newSheet->setCellValueByColumnAndRow(3, $newIndex, $sumPodKateg);
$newIndex++;


$newSheet->getStyle('B3:D' . ($newIndex - 1))->applyFromArray($styleYellow)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$newSheet->setCellValueByColumnAndRow(3, $newIndex, $sum2021);
$newSheet->getStyle('B' . $newIndex  . ':D' . $newIndex)->applyFromArray($styleGreen)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$newSheet->mergeCells('B' . $newIndex . ':C' . $newIndex);
$newSheet->setCellValueByColumnAndRow(1, $newIndex, 'Итого за 2021 год:');



$xls->setActiveSheetIndex(0);


$objWriter = new PHPExcel_Writer_Excel5($xls);
$objWriter->save('Products.xls');
echo 'Ok...';
