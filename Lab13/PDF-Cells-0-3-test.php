<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>Ячейки документа PDF</title>
 </head>
 <style type='text/css'>
    div { 
          font-size: 18pt;           
          color: navy; background-color: rgb(250,240,130); 
          margin: 10px; width: auto; padding: 10px; }
    td  { 
          color: navy; background-color: rgb(190,240,180);
          font-family: Arial; font-size: 17pt; border: 1px dotted navy; 
          height: 90px; }
    table { 
            background-color: rgb(252, 251, 152); 
            font-family: Tahoma; font-size: 14pt; }
    input[type="submit"] { 
          color: white; background-color: rgb(290,140,180);
          font-family: Arial; font-size: 17pt; border: 1px dotted navy; 
          border-radius: 10px; margin: 10px; 
          align-text: center; padding: 15px; }
 </style>
<body>
<?php
    echo "<table width='20%' bgcolor='green' border=solid align='center'>";
    echo '<td align="center">
          <form method="POST">
          <input type="submit" name="btn1" value="Выдать документ PDF"/>
          </form>
          </td>';
    echo "</table>";
    if( isset( $_POST['btn1'] ) )
    {
      define('FPDF_FONTPATH',"/fpdf/font/");
      require_once("/fpdf/fpdf.php");
 
      $pdf = new FPDF('P', 'pt', 'A4');

      $pdf->SetAuthor('A. Smoljanov');
      $pdf->SetTitle('Table document');   

      $pdf->AddFont('comic','','comic.php');
      $pdf->SetFont('comic');
      $pdf->SetFontSize(15);
      $pdf->AddPage();
      $pdf->SetDisplayMode('real', 'default');
	
      $pdf->SetTextColor(100, 0, 200);
	
      $pdf->SetFillColor(152, 251, 152);
      $pdf->SetDrawColor(220,4,254);
      $pdf->SetLineWidth(1);
      $pdf->Cell( 45, 40, $pdf->Image('doc.jpg',$pdf->GetX()+2, $pdf->GetY()+3,37,37), 'LTBR', 0, 'С', false);
      $pdf->Cell(450, 40, iconv('utf-8', 'windows-1251', 'Работа с ячейками документа PDF'), 
                          'LTRB', 1, 'C', true);

      $pdf->Ln(5);    
      $pdf->Write(25, iconv('utf-8', 'windows-1251', "В цикле в ячейках документа изменяем:"));
      $pdf->Ln(25);    
      $pdf->Write(25, iconv('utf-8', 'windows-1251', "- цвет текста в клетке,"));
      $pdf->Ln(25);    
      $pdf->Write(25, iconv('utf-8', 'windows-1251', "- цвет закраски клетки,"));
      $pdf->Ln(25);    
      $pdf->Write(25, iconv('utf-8', 'windows-1251', "- цвет рамки клетки,"));
      $pdf->Ln(25);    
      $pdf->Write(25, iconv('utf-8', 'windows-1251', "- размер шрифта,"));
      $pdf->Ln(25);    
      $pdf->Write(25, iconv('utf-8', 'windows-1251', "- толщину рамки клетки."));
      $pdf->Ln(30);    

      $pdf->SetTextColor(255, 255, 255);
      $pdf->SetFillColor(151, 252, 152);
      $LineWidth = 0.5;
      $pdf->SetLineWidth($LineWidth);
      $FontSize = 22;
      $pdf->SetFontSize($FontSize);
      $pdf->Cell(45, 40, ' ', 1, 0, 'C', 1);
      $pdf->SetFillColor(251, 152, 152);
      for ($i=1; $i<=9; $i++)
        {
           $pdf->Cell(45, 40, $i, 'LBT', 0, 'C', true);
           // Изменяем параметры внешего вида документа:
           $pdf->SetTextColor(255-20*$i, 255-20*$i, 255-20*$i);
           $pdf->SetFillColor(251-8*$i, 152+8*$i, 152-8*$i);
           $pdf->SetDrawColor(220-10*$i,4,254-15*$i);
           $FontSize -= 0.3 * $i;
           $pdf->SetFontSize($FontSize);
           $LineWidth += 0.08 * $i;
           $pdf->SetLineWidth($LineWidth);
        }
      $pdf->Cell(45, 40, $i, 'LBTR', 1, 'C', true);

      $pdf->SetFontSize(11);
      $pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(20);
      $pdf->Write(0,
           iconv('utf-8', 'windows-1251','Дата формирования документа: '.date('d-m-Y')));
      $file_name = "PDF-Cells-0-3-test.pdf";
      $pdf->Output("$file_name", "F");
}
?>
</body>
</html>