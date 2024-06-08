<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ячейки документа PDF</title>
</head>
<style type='text/css'>
    div {
        font-size: 18pt;
        color: navy;
        background-color: rgb(250, 240, 130);
        margin: 10px;
        width: auto;
        padding: 10px;
    }

    td {
        color: navy;
        background-color: rgb(190, 240, 180);
        font-family: Arial;
        font-size: 17pt;
        border: 1px dotted navy;
        height: 90px;
    }

    table {
        background-color: rgb(252, 251, 152);
        font-family: Tahoma;
        font-size: 14pt;
    }

    input[type="submit"] {
        color: white;
        background-color: rgb(290, 140, 180);
        font-family: Arial;
        font-size: 17pt;
        border: 1px dotted navy;
        border-radius: 10px;
        margin: 10px;
        padding: 15px;
    }
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
    if (isset($_POST['btn1'])) {
        define('FPDF_FONTPATH', "fpdf/font/");
        require_once('fpdf/fpdf.php');

        require_once('BD/WorkWithBD.php');

        $pdf = new FPDF('P', 'pt', 'A4');

        $pdf->SetAuthor('A. Abramov');
        $pdf->SetTitle('Table document');

        $pdf->AddFont('comic', '', 'comic.php');
        $pdf->SetFont('comic');
        $pdf->SetFontSize(15);
        $pdf->AddPage();
        $pdf->SetDisplayMode('real', 'default');

        $pdf->SetTextColor(100, 0, 200);

        $pdf->SetFillColor(152, 251, 152);
        $pdf->SetDrawColor(220, 4, 254);
        $pdf->SetLineWidth(1);
        $pdf->Cell(45, 40, $pdf->Image('doc.jpg', $pdf->GetX() + 2, $pdf->GetY() + 3, 37, 37), 'LTBR', 0, 'С', false);
        $pdf->Cell(
            500,
            40,
            iconv('utf-8', 'windows-1251', 'Годовой отчет о сотрудниках учреждения. Список сотрудников'),
            'LTRB',
            1,
            'C',
            true
        );
        $pdf->Ln(5);

        $pdf->Cell(100, 40, iconv('utf-8', 'windows-1251', 'Отдел'), 'LBT', 0, 'C', true);
        $pdf->Cell(100, 40, iconv('utf-8', 'windows-1251', 'id Сотр'), 'LBT', 0, 'C', true);
        $pdf->Cell(172, 40, iconv('utf-8', 'windows-1251', 'Фамилия'), 'LBT', 0, 'C', true);
        $pdf->Cell(172, 40, iconv('utf-8', 'windows-1251', 'Имя'), 'LBT', 0, 'C', true);
        $pdf->Ln();
        $nameOtdel = '';
        $colvoSotr = 0;
        $data = $getDate();
        foreach ($data as $index => $user) {
            if ($nameOtdel != $user['NameOtdel']) {
                if (($index)) {
                    $pdf->Cell(372, 40, iconv('utf-8', 'windows-1251', 'Количество людей в отделе:'), 'LBT', 0, 'C', true);

                    $pdf->Cell(172, 40, iconv('utf-8', 'windows-1251', $colvoSotr), 'LBT', 0, 'C', true);
                    $pdf->Ln();
                }
                $colvoSotr = 0;
                $nameOtdel = $user['NameOtdel'];
                $pdf->SetFillColor(183, 55, 55);
                $pdf->SetTextColor(255, 255, 255);

                $pdf->Cell(544, 40, iconv('utf-8', 'windows-1251', $nameOtdel), 'LBT', 0, 'C', true);
                $pdf->Ln();

                $pdf->SetTextColor(100, 0, 200);
            }
            $colvoSotr++;
            if ($index % 2) {
                $pdf->SetFillColor(183, 255, 255);
            } else {
                $pdf->SetFillColor(183, 255, 127);
            }
            $pdf->Cell(100, 40, iconv('utf-8', 'windows-1251', $user['Otdel']), 'LBT', 0, 'C', true);
            $pdf->Cell(100, 40, iconv('utf-8', 'windows-1251', $user['id']), 'LBT', 0, 'C', true);
            $pdf->Cell(172, 40, iconv('utf-8', 'windows-1251', $user['LastName']), 'LBT', 0, 'C', true);
            $pdf->Cell(172, 40, iconv('utf-8', 'windows-1251', $user['FirstName']), 'LBT', 0, 'C', true);
            $pdf->Ln();
        }
        $pdf->Cell(372, 40, iconv('utf-8', 'windows-1251', 'Количество людей в отделе:'), 'LBT', 0, 'C', true);

        $pdf->Cell(172, 40, iconv('utf-8', 'windows-1251', $colvoSotr), 'LBT', 0, 'C', true);
        $pdf->Ln();


        $pdf->SetFontSize(11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(20);
        $pdf->Write(
            0,
            iconv('utf-8', 'windows-1251', 'Дата формирования документа: ' . date('d-m-Y'))
        );
        $file_name = "PDF-Cells-0-3-test.pdf";
        $pdf->Output("$file_name", "F");
    }
    ?>
</body>

</html>