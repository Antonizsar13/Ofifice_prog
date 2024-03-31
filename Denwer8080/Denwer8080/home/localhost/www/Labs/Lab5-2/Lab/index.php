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


$query_view = 'select (select NameOtdel from otdels Otd where Otd.idOtdel = Sot.Otdel) Otdel, LastName, FirstName, Date_R, Dolzn from sotr Sot order by Otdel, LastName, FirstName;';

$res_view = $mysqli->query($query_view);
if (!$res_view) {
    echo '<div>Ошибка обращения к базе данных на сервере...';
    die();
}
$xls = new PHPExcel();

$xls->setActiveSheetIndex(0);

$sheet = $xls->getActiveSheet();
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


$styleBlueText = array(
    'font' => array(
        'color' => array('rgb' => '1778ff'),
        'bold' => true,
    ),
);

$sheet->getStyle('C1:D1')->applyFromArray($styleBlueText);
// $sheet->mergeCells('С1:D1');
$sheet->setCellValue('C1', 'Список сотрудников отдела');

$styleBlue = array(
    'font' => array(
        'bold' => true,
        'color' => array('rgb' => 'ffffff'),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => '1199cc'),
    ),
);

$sheet->getStyle('C3:G3')->applyFromArray($styleBlue);

$sheet->setCellValue('C3', 'Отдел');
$sheet->setCellValue('D3', 'Фамилия');
$sheet->setCellValue('E3', 'Имя');
$sheet->setCellValue('F3', 'Дата рождения');
$sheet->setCellValue('G3', 'Должность');

// $sheet->getColumnDimension('C')->setWidth(25);
// $sheet->getColumnDimension('D')->setWidth(15);
// $sheet->getColumnDimension('E')->setWidth(15);
// $sheet->getColumnDimension('F')->setWidth(15);
// $sheet->getColumnDimension('G')->setWidth(25);

$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);


// $styleCenter = array(
//     'alignment' => array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//         'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//     )
// );


$styleWhiteGreen = array(
    'font' => array(
        'color' => array('rgb' => '4e3399'),
        'bold' => true,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'ecfcec'),
    ),
);

$styleBlackGreen = array(
    'font' => array(
        'color' => array('rgb' => '4e3399'),
        'bold' => true,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'ccfccc'),
    ),
);

$index = 5;
while ($row = $res_view->fetch_assoc()) {
    if ($index % 2) {
        $sheet->getStyle('C' . $index . ':' . 'G' . $index)->applyFromArray($styleWhiteGreen);
    } else {
        $sheet->getStyle('C' . $index . ':' . 'G' . $index)->applyFromArray($styleBlackGreen);
    }
    $sheet->setCellValueByColumnAndRow(2, $index, $row['Otdel']);
    $sheet->getStyle('C' .  $index)->applyFromArray(array('font' => array('size' => 9)));
    $sheet->setCellValueByColumnAndRow(3, $index, $row['LastName']);
    $sheet->setCellValueByColumnAndRow(4, $index, $row['FirstName']);
    $sheet->setCellValueByColumnAndRow(5, $index, $row['Date_R']);
    $sheet->getStyle('F' .  $index)->getNumberFormat()
        ->setFormatCode('yyyy-mm-dd');
    $sheet->getStyle('F' .  $index)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValueByColumnAndRow(6, $index, $row['Dolzn']);
    $index++;
}



$objWriter = new PHPExcel_Writer_Excel5($xls);
$objWriter->save('List_sotr.xls');
echo 'Ok...';
