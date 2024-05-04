<?php

require_once('BD/WorkWithBD.php');

$GetDiagram = function () use ($GetZarplOtdel) {
    if (!empty($_POST['submit'])) {
        $month = array();
        $names = array();
        $GetZarplOtdel($month, $names, $_POST['otdel'][0]);

        $index = 0;
        $monthNew = array();
        $namesNew = array();
        for ($i = 1; $i <= 12; $i++) {
            array_push($monthNew, $i);
            if (isset($month[$index]))
                if ($i == $month[$index]) {
                    array_push($namesNew, $names[$index]);
                    $index++;
                } else {
                    array_push($namesNew, 0);
                }
            else
                array_push($namesNew, 0);
        }


        $diagramName = "Отчет о доходах сотрудников за 2018 год";
        $secondName = 'Отдел ' . $_POST['otdel'];
        $htppQueryParams = http_build_query([
            'diagramName' => $diagramName,
            'diagramSecondName' => $secondName,
            'month' => $monthNew,
            'names' => $namesNew
        ]);
?>
        <img src="GeneralDiagram.php?<?= $htppQueryParams ?>" alt="Какие-то проблемы с генерацией диаграммы">
    <?php

    } else {
    ?>
        <div class='good_mes'>Выберите данные для генерации таблицы и нажмите кнопку Сгенерировать</div>
<?php
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Choose diagram</title>
</head>

<body style="display: flex; flex-direction: column;">
    <div class="main_div">
        <div class="head_div">
            <div class="img_div">
                <img src="book.jpg" class="img_zag" alt="BOOK">
            </div>
            <div class=" text_div">
                Отчет о заработанной плате сотрудников за 2018 год
            </div>
        </div>

        <div class="head_div" style=" display: flex; justify-content: center; align-items: center;">
            <div class="text_mini_div" id="error" style="width: 100%;">
                Cтатус: <?= $checkConnect() ?>
            </div>
        </div>

        <form action="" method="post">
            <div class="head_div">
                <div class="div_obsh">

                    <div class=" text_mini_div" style="text-align: right; width:300px">
                        Выберите отдел:
                        <?= $OtdelSelect() ?>
                        <input type="submit" name="submit" class="btn-new" value="Отправить" style="height: 30px; width:400px; margin-right:10px">

                    </div>
                </div>
            </div>
        </form>
        <div class="head_div" style="padding: 2px; display: flex; align-items: center; justify-content: center;">
            <?= $GetDiagram() ?>
        </div>

    </div>

</body>

</html>