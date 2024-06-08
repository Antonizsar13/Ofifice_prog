<?php

require_once('PHPWord.php');
require_once('../BD/WorkWithBD.php');
// use PHPWord\IOFactory;
// use PHPWord\Style\Cell;




$phpWord = new  PHPWord();
$section = $phpWord->createSection(array('orientation' => 'landscape'));

$header = array('size' => 16, 'bold' => true, 'alignment' => 'center');

$section->addText('Отчет о приходе электротатаров на склад', $header);

$styleTable = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80);
$styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '3cb371', 'alignment' => 'center');

$styleCell = array('valign' => 'center');
$fontStyle = array('bold' => true, 'alignment' => 'center', 'color' => 'FFFFFF');

$phpWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);

$table = $section->addTable('Fancy Table');
$table->addRow(300);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Наименование  товара'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Дата прихода'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Цена за ед.товара, руб.'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Кол-во'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Сумма, руб.'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Изображение'), $fontStyle);


$date = $getDate();
foreach ($date as $tovar) {
    $table->addRow();
    $table->addCell(4000)->addText(htmlspecialchars($tovar['tname']));
    $table->addCell(2000)->addText(htmlspecialchars($tovar['date_p']));
    $table->addCell(2000)->addText(htmlspecialchars($tovar['price']));
    $table->addCell(2000)->addText(htmlspecialchars($tovar['kol_vo']));
    $table->addCell(2000)->addText(htmlspecialchars($tovar['all_tovare']));

    $srcImage = 'img/image' . $tovar['kod'];
    file_put_contents($srcImage, $tovar['photo']);
    $table->addCell(50)->addImage($srcImage, array('width' => 50));
}



$objWriter = PHPWord_IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('helloWorld.docx');
