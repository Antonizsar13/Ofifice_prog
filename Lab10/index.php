<?php
require_once 'vendor/autoload.php';
require_once('BD/WorkWithBD.php');

$phpWord = new  \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection(array('orientation' => 'landscape'));

$header = array('size' => 16, 'bold' => true);

$section->addText(htmlspecialchars('Отчет о приходе электротатаров на склад'), $header, array('align' => 'center'));
$section->addText(htmlspecialchars('Дата оформления отчета: ' . date('Y-m-d')), array('size' => 10), array('align' => 'center'));

$styleTable = array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80, 'valign' => 'center');
$styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '3cb371');

$styleCell = array('valign' => 'center');
$fontStyle = array('aligment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'color' => 'FFFFFF');

$phpWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);
$table = $section->addTable('Fancy Table');

$allCenter = array('align' => 'center', 'textAlignment' => 'center');
$table->addRow();
$table->addCell(1000, $styleCell)->addText(htmlspecialchars('Наименование  товара'), $fontStyle, $allCenter);
$table->addCell(1000, $styleCell)->addText(htmlspecialchars('Дата прихода'), $fontStyle, $allCenter);
$table->addCell(1000, $styleCell)->addText(htmlspecialchars('Цена за ед.товара, руб.'), $fontStyle, $allCenter);
$table->addCell(1000, $styleCell)->addText(htmlspecialchars('Кол-во'), $fontStyle, $allCenter);
$table->addCell(1000, $styleCell)->addText(htmlspecialchars('Сумма, руб.'), $fontStyle, $allCenter);
$table->addCell(1000, $styleCell)->addText(htmlspecialchars('Изображение'), $fontStyle, $allCenter);


$date = $getDate();
$styleFon = array('aligment' => 'center', 'valign' => 'center', 'bgColor' => 'ffffcf');
$color1 = array('color' => 'a22628', 'bold' => true);
$color2 = array('color' => '131390');

$itog = 0;
foreach ($date as $tovar) {
    $itog += $tovar['all_tovare'];
    $table->addRow();
    $table->addCell(4000, $styleFon)->addText(htmlspecialchars($tovar['tname']), $color1, array('textAlignment' => 'center'));
    $table->addCell(1500, $styleFon)->addText(htmlspecialchars($tovar['date_p']), $color2, $allCenter);
    $table->addCell(1500, $styleFon)->addText(htmlspecialchars(number_format($tovar['price'], 2, '.', ',')), $color1, array('align' => 'right'));
    $table->addCell(1500, $styleFon)->addText(htmlspecialchars($tovar['kol_vo']), $color2, $allCenter);
    $table->addCell(2000, $styleFon)->addText(htmlspecialchars(number_format($tovar['all_tovare'], 2, '.', ',')), $color1, array('align' => 'right'));

    $srcImage = 'img/image' . $tovar['kod'];
    file_put_contents($srcImage, $tovar['photo']);
    $styleImg = array(
        'alignment' => 'center',
        'horizontalAlignment' => 'center', 'bgColor' => 'ffffcf',
        'width' => 50, 'height' => 50
    );
    $table->addCell(2000, $styleFon)->addImage($srcImage, $styleImg);
}

$table->addRow();
$table->addCell(5000, array('bgColor' => '3cb371'))->addText(htmlspecialchars('Итого по складу:'), $color1, array('textAlignment' => 'center'));
$table->addCell(1500, array('bgColor' => '3cb371'));
$table->addCell(1500, array('bgColor' => '3cb371'));
$table->addCell(1500, array('bgColor' => '3cb371'));
$table->addCell(2000, array('bgColor' => '3cb371'))->addText(htmlspecialchars(number_format($itog, 2, '.', ',')), $color1, array('align' => 'right'));
$table->addCell(2000, array('bgColor' => '3cb371'));



$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('helloWorld.docx');
