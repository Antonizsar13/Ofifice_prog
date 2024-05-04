<html>

<head>
  <title>Документ Excel</title>
  <style type="text/css">
    div {
      font-size: 18pt;
      font-family: tahoma;
      color: navy;
      background-color: rgb(250, 240, 130);
      margin: 5px;
      width: auto;
      padding: 5px;
      text-align: center;
    }

    table {
      border: solid thin silver;
      background-color: rgb(134, 251, 140);
    }

    td {
      border: solid thin navy;
      text-align: center;
      font-size: 12pt;
      vertical-align: middle;
      font-family: tahoma;
      height: 50px;
      color: navy;
    }

    th {
      text-align: center;
      font-style: normal;
      font-size: 14pt;
      font-family: tahoma;
      height: 50px;
      color: navy;
      background-color: rgb(234, 251, 140);
    }

    ._td {
      border: solid thin red;
      text-align: center;
      font-size: 12pt;
      vertical-align: middle;
      font-family: tahoma;
      height: 40px;
      color: navy;
      background-color: rgb(255, 198, 226);
    }

    .__td {
      border: solid thin red;
      text-align: right;
      font-size: 12pt;
      vertical-align: middle;
      font-family: tahoma;
      padding-right: 10px;
      height: 30px;
      color: blue;
      background-color: rgb(255, 198, 226);
    }

    input[type="submit"] {
      color: white;
      background-color: rgb(290, 140, 180);
      font-family: Arial;
      font-size: 15pt;
      border: 1px dotted navy;
      border-radius: 10px;
      margin: 10px;
      align-text: center;
      padding: 15px;
    }

    input[type="submit"]:hover {
      box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.39);
    }
  </style>
</head>

<body>
  <form method='post'>
    <table width="40%" align="center">
      <th>База данных "Отделы-Сотрудники"</th>
      <tr bgcolor="#88CC99">
        <td><input type="submit" size="40" name="form_select_button1" value="Выдать документ Excel-1">
        </td>
      </tr>
    </table>
  </form>
</body>

</html>

<?php

function CellAddress($num1, $num2)
{
  return chr(65 + $num1) . strval($num2);
}

function SelectButtonFun1()
{
  $db_host = 'localhost';
  $db_name = 'sotrzarpl';
  $db_username = 'root';
  $db_password = '';
  try {
    $link = @mysqli_connect($db_host, $db_username, $db_password, $db_name);
    if (!$link) {
      throw new Exception('error...');
      exit();
    }
  } catch (Exception $e) {
    echo '<div>Ошибка подключения к базе данных на сервере...</div>';
    die();
  }
  mysqli_query($link, 'SET NAMES UTF8');
  // Здесь надо записать текст запроса (см. задание лабораторной работы №5-1:
  $query_zarpl = 'SELECT id, Otdel, LastName, FirstName, zar.sumz Su, (SELECT NameOtdel from otdels ot where ot.idOtdel= so.Otdel) OName from sotr so left outer JOIN (select idSotr, sum(Money) sumz from zarpl where God = 2018 GROUP by idSotr) zar on zar.idSotr = so.id ORDER by OName, LastName, FirstName;';

  $res = mysqli_query($link, $query_zarpl);
  if (!$res) {
    echo '<div>Ошибка обращения к базе данных на сервере...';
    die();
  }

  //Число строк результата выборки данных из БД - известно
  $num_rows = mysqli_num_rows($res);

  //Подключение библиотек PHPExcel:
  require_once('PHPExcel/Classes/PHPExcel.php');
  require_once('PHPExcel/Classes/PHPExcel/Writer/Excel5.php');
  //Создание нового документа электронной таблицы - книги:
  $xls = new PHPExcel();
  //Установка номера листа книги:
  $xls->setActiveSheetIndex(0);

  //Работаем с текущим листом книги:
  $sheet = $xls->getActiveSheet();
  $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
  $sheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

  //Заголовок для листа:
  $sheet->setTitle('Доходы сотрудников за 2018 год"');

  //Настройки шрифта по умолчанию:
  $sheet->getDefaultStyle()->getFont()->setName('Tahoma');

  //Установка размера шрифта по умолчанию:
  $sheet->getDefaultStyle()->getFont()->setSize(12);

  //Параметры закраски для верхней строки таблицы:
  $fill_array = array(
    'font' => array(
      'bold' => false,
      'name' => 'Arial', 'size' => 16,
      'color' => array('rgb' => 'ffffff')
    ),
    'fill' => array(
      'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
      'color' => array('rgb' => '9001B0')
    )
  );

  //Закраска верхней строки и вывод заголовка таблицы:
  $sheet->getStyle(CellAddress(3, 2) . ':' . CellAddress(7, 2))->applyFromArray($fill_array);
  $sheet->setCellValueByColumnAndRow(3, 2, "Отчет по заработной плате сотрудников отделов за 2018 год");

  //Установка стиля для вывода значения в клетку с выравниванием влево:
  $style_1 = array('alignment' => array(
    'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
    'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER
  ));

  //Установка стиля для вывода значения в клетку с выравниванием по центру:
  $style_2 = array('alignment' => array(
    'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
    'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER
  ));

  //Установка стиля для вывода значения в клетку с выравниванием вправо:
  $style_3 = array(
    'alignment' => array(
      'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT,
      'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER
    ),
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'FFFF80')
    )
  );

  //Установка стиля для вывода значения в клетку с выравниванием вправо:
  //для вывода итогов по отделу:
  $style_4 = array(
    'alignment' => array(
      'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT,
      'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER
    ),
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb' => 'BBEBEB')
    )
  );

  //Установка-1 параметров закраски клеток:
  $fill_array = array(
    'alignment' => array(
      'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT,
      'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER
    ),
    'font' => array(
      'bold' => true,
      'name' => 'Arial', 'size' => 12,
      'color' => array('rgb' => 'FFFFFF')
    ),
    'fill' => array(
      'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
      'startcolor' => array('rgb' => '01B050')
    )
  );

  //Установка-2 параметров закраски клеток:
  $fill_cells_array = array(
    'font' => array(
      'bold' => false,
      'name' => 'Tahoma', 'size' => 12,
      'color' => array('rgb' => '0C0CE5')
    ),
    'fill' => array(
      'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
      'startcolor' => array('rgb' => 'FFD3E5')
    )
  );

  //Установка ширины для клеток таблицы:
  $sheet->getColumnDimension('A')->setWidth(2);
  $sheet->getColumnDimension('B')->setWidth(2);
  $sheet->getColumnDimension('C')->setWidth(2);
  $sheet->getColumnDimension('D')->setWidth(30);
  $sheet->getColumnDimension('E')->setWidth(20);
  $sheet->getColumnDimension('F')->setWidth(15);
  $sheet->getColumnDimension('G')->setWidth(12);
  $sheet->getColumnDimension('H')->setWidth(16);

  //Настройка параметров для текста и закраски верхней строки таблицы:
  $sheet->getStyle(CellAddress(3, 3) . ':' . CellAddress(7, 3))->getFont()->getColor()->applyFromArray(array('rgb' => '009966', 'bold' => true));
  $sheet->getStyle(CellAddress(3, 3) . ':' . CellAddress(7, 3))->applyFromArray($fill_array);

  //Настройка стиля для клеток заголовков таблицы:
  $sheet->getStyle(CellAddress(3, 3) . ':' . CellAddress(5, 3))->applyFromArray($style_1);
  $sheet->getStyle(CellAddress(6, 3) . ':' . CellAddress(6, 3))->getAlignment()->applyFromArray($style_2);
  $sheet->getStyle(CellAddress(7, 3) . ':' . CellAddress(7, 3))->getAlignment()->applyFromArray($style_4);

  //Заголовки таблицы с данными:
  $sheet->setCellValueByColumnAndRow(3, 3, "Отдел");
  $sheet->setCellValueByColumnAndRow(4, 3, "Фамилия");
  $sheet->setCellValueByColumnAndRow(5, 3, "Имя");
  $sheet->setCellValueByColumnAndRow(6, 3, "Зарплата");
  $sheet->setCellValueByColumnAndRow(7, 3, "Итого по отделу");

  //Вывод части данных в браузер:
  echo '<table width="80%" align="center">';
  echo '<th>Сведения о доходах сотрудников за 2018 год</th>';
  echo '</table>';
  echo '<table width="80%" align="center">';
  echo '<tr>';
  echo '<td class ="th">' . 'idСотр' . '</td>';
  echo '<td class ="th">' . 'Отдел' . '</td>';
  echo '<td class ="th">' . 'Фамилия' . '</td>';
  echo '<td class ="th">' . 'Имя' . '</td>';
  echo '<td class ="th">' . 'Зарплата' . '</td>';
  echo '</tr>';

  $i = 1; //Счетчик строк результата запроса

  //Установка цветовых параметров для групп клеток рабочих строк таблицы:
  //Это можно сделать, так как число строк результата выборки данных из БД - известно
  //Оно хранится в переменной $num_rows
  $sheet->getStyle(CellAddress(3, $i + 3) . ':' . CellAddress(5, $i + 3 + $num_rows - 1))
    ->applyFromArray($fill_cells_array);
  $sheet->getStyle(CellAddress(6, $i + 3) . ':' . CellAddress(6, $i + 3 + $num_rows - 1))->applyFromArray($style_3);
  $sheet->getStyle(CellAddress(7, $i + 3) . ':' . CellAddress(7, $i + 3 + $num_rows - 1))->applyFromArray($style_4);

  $sheet->getStyle(CellAddress(6, $i + 3) . ':' . CellAddress(6, $i + 3 + $num_rows - 1))->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

  $sheet->getStyle(CellAddress(7, $i + 3) . ':' . CellAddress(7, $i + 3 + $num_rows - 1))->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


  $sum_otdel_total = 0.0; // Общая сумма зарплат сотрудников по всем отделам

  //Цикл обработки результата запроса:
  $oName = '';
  while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {
    //Зарплата очередного работника:
    if (!isset($row['Su'])) {
      $su = 0.0;
    } else {
      $su = $row['Su'];
    }
    if ($i == 1) // Самая первая по порядку строка обработки
    {
      $sum_otdel = $su; // начинаем накапливать сумму по отделу
      $otdel = $row['Otdel'];
    } else //Все последующие строки обработки
    {
      if ($row['Otdel'] == $otdel) {
        $sum_otdel += $su;
      } //Если отдел тот же - суммируем зарплату
      else // иначе - выводим справа в таблице накопленную сумму по отделу:
      {
        //Цветовые настройки для суммы зарплат по отделу:
        $sheet->getStyle(CellAddress(7, $i + 2))->getFont()->getColor()->applyFromArray(array('rgb' => 'FF0000', 'bold' => false));

        //Вывод суммы зарплат по отделу:                 
        $sheet->setCellValueByColumnAndRow(7, $i + 2, $sum_otdel);
        $otdel = $row['Otdel'];
        $sum_otdel = $su; //Начинаем накапливать новую сумму по отделу
      }
    }

    //Выводим очередную строку результата запроса - в браузер:
    echo '<tr>';
    echo '<td class ="_td">' . $row['id'] . '</td>';
    if (!($oName == $row['OName'])) {
      echo '<td class ="_td">' . $row['OName'] . '</td>';
    } else {
      echo '<td class ="_td"></td>';
    }

    echo '<td class ="_td">' . $row['LastName'] . '</td>';
    echo '<td class ="_td">' . $row['FirstName'] . '</td>';
    echo '<td class ="__td">' . number_format($su, 2) . '</td>';
    echo '</tr>';

    //Выводим очередную строку результата запроса - в документ Excel:
    $sheet->getDefaultStyle()->getFont()->getColor()->applyFromArray(array('rgb' => '000000'));
    if (!($oName == $row['OName'])) {
      $sheet->setCellValueByColumnAndRow(3, $i + 3, $row['OName']);
      $oName = $row['OName'];
    }



    $sheet->setCellValueByColumnAndRow(4, $i + 3, $row['LastName']);
    $sheet->setCellValueByColumnAndRow(5, $i + 3, $row['FirstName']);
    $sheet->setCellValueByColumnAndRow(6, $i + 3, $su);

    $i++; //увеличиваем счетчик
    $sum_otdel_total += $su; //зарплату очередного работника - добавляем в общую сумму
  }

  echo '</table>';

  //Цветовые для суммы зарплат по последнему отделу:
  $sheet->getStyle(CellAddress(7, $i + 2))->getFont()->getColor()->applyFromArray(array('rgb' => 'FF0000', 'bold' => false));

  //Вывод суммы зарплат по отделу:                 
  $sheet->setCellValueByColumnAndRow(7, $i + 2, $sum_otdel);

  //Цветовые настройки для суммы зарплат по учреждению:
  $sheet->getStyle(CellAddress(3, $i + 3) . ':' . CellAddress(7, $i + 3))->applyFromArray($fill_array);

  $sheet->getStyle(CellAddress(3, $i + 3))->getFont()->applyFromArray(array('bold' => true));
  $sheet->getStyle(CellAddress(3, $i + 3) . ':' . CellAddress(3, $i + 3))->applyFromArray($style_1);

  $sheet->setCellValueByColumnAndRow(3, $i + 3, 'Всего по учреждению:');
  $sheet->getStyle(CellAddress(7, $i + 3))->getFont()->applyFromArray(array('bold' => true));
  $sheet->getStyle(CellAddress(7, $i + 3))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

  //Вывод суммы зарплат по учреждению:                 
  $sheet->setCellValueByColumnAndRow(7, $i + 3, $sum_otdel_total);

  //Создание нового документа Excel на диске:
  $objWriter = new PHPExcel_Writer_Excel5($xls);
  $objWriter->save('excel-1.xls');
}

//Если нажали на кнопку "Выдать документ Excel-1":
if (isset($_POST['form_select_button1'])) {
  SelectButtonFun1();
}

?>
</body>

</html>