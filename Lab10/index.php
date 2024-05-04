<?php
require_once 'vendor/autoload.php';

// $phpWord = new \PhpOffice\PhpWord\PhpWord();


// $sectionStyle = array(
//     'orientation' => 'landscape',
// );
// $section = $phpWord->addSection($sectionStyle);

// $tableStyle = array(
//     'borderColor' => '006699',
//     'borderSize'  => 6,
//     'cellMargin'  => 50
// );
// $firstRowStyle = array('bgColor' => '66BBFF');
// $phpWord->addTableStyle('myTable', $tableStyle, $firstRowStyle);

// $table = $section->addTable('myTable');
// // $table->addRow(200);
// $table->addRow(200);
// $cell = $table->addCell(1000,);

// $fontStyleName = 'headStyle';
// $phpWord->addFontStyle(
//     $fontStyleName,
//     array('name' => 'Tahoma', 'size' => 10, 'color' => 'FFFFFF',)
// );
// $section->addText(
//     '"The greatest accomplishment is not in never falling, '
//         . 'but in rising again after you fall." '
//         . '(Vince Lamborghini)',
//     $fontStyleName
// );

// // Adding Text element with font customized using explicitly created font style object...
// // $fontStyle = new \PhpOffice\PhpWord\Style\Font();
// // $fontStyle->setBold(true);
// // $fontStyle->setName('Tahoma');
// // $fontStyle->setSize(13);
// // $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
// // $myTextElement->setFontStyle($fontStyle);

// $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
// $objWriter->save('helloWorld.docx');

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection(array('orientation' => 'landscape'));
$header = array('size' => 16, 'bold' => true, '');


// 2. Advanced table

$section->addTextBreak(1);
$section->addText(htmlspecialchars('Отчет о приходе электротатаров на склад'), $header);

$styleTable = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80);
$styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
$styleCell = array('valign' => 'center');
$styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
$fontStyle = array('bold' => true, 'align' => 'center');
$phpWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);
$table = $section->addTable('Fancy Table');
$table->addRow(900);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 1'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 2'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 3'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 4'), $fontStyle);
$table->addCell(500, $styleCellBTLR)->addText(htmlspecialchars('Row 5'), $fontStyle);
for ($i = 1; $i <= 8; $i++) {
    $table->addRow();
    $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}"));
    $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}"));
    $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}"));
    $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}"));
    $text = (0 == $i % 2) ? 'X' : '';
    $table->addCell(500)->addText(htmlspecialchars($text));
}



$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('helloWorld.docx');
