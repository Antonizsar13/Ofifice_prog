<?php

require_once('MyConnect.php');
MyConnectOtvet();

$checkConnect = function () use ($ConnectOtvet) {
    if (!$ConnectOtvet) {
        echo '<form method="post" action="">
            <div class="error_mes">
            ошибка соединения с сервером...
            <input autofocus class = "submit" type="submit" value="О б н о в и т ь">
            </div>';
        exit();
    } else {
        echo '<div class="good_mes">cервер подключен</div>';
    }
};


$SetGraph = function ($month, $names, $otdel) use ($mysqli) {

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
    $secondName = 'Отдел ' . $otdel;
    $htppQueryParams = http_build_query([
        'diagramName' => $diagramName,
        'diagramSecondName' => $secondName,
        'month' => $monthNew,
        'names' => $namesNew
    ]);
    $imageData = file_get_contents("http://lab8/Lab/diagram/GeneralDiagram.php?$htppQueryParams");

    $imageData = base64_encode($imageData);
    $query = "SELECT * FROM `graph_zarpl_otdel` where id_table = $otdel";

    if (!$mysqli->query($query)->num_rows) {
        $query = "INSERT INTO graph_zarpl_otdel (id_table, grafic) VALUES ('$otdel', '" . $imageData . "')";
    } else {
        $query = "UPDATE graph_zarpl_otdel SET grafic = '" . $mysqli->real_escape_string($imageData) . "' WHERE id_table = '$otdel'";
    }
    $res = $mysqli->query($query);
    if (!$res) {
        echo '<form method="post" action="">
            <div class="error_mes">
            Ошибка запроса
            <input autofocus class = "submit" type="submit" value="О б н о в и т ь">
            </div>';
        exit();
    }
};

$GetZarplOtdel = function ($otdel) use ($mysqli, $SetGraph) {
    $query_view = "SELECT SUM(Money) 'Sum', Month FROM `zarpl` WHERE God = 2018 and idSotr IN (select id FROM sotr where Otdel = $otdel) GROUP by Month ORDER by Month;";
    $res_view = $mysqli->query($query_view);

    if (!$res_view) {
        echo '<form method="post" action="">
            <div class="error_mes">
            Ошибка запроса
            <input autofocus class = "submit" type="submit" value="О б н о в и т ь">
            </div>';
    } else {
        if (!$res_view->num_rows) {
            echo " <div class='error_mes'>В данном($otdel) отделе нет данных за 2018 год</div>";
            return;
        }
        $month = array();
        $names = array();
        foreach ($res_view as $res) {
            array_push($month, $res['Month']);
            array_push($names, $res['Sum']);
        }

        $SetGraph($month, $names, $otdel);
    }
};


$getPhotoBd = function ($idOtdel) use ($mysqli) {
    $query_view = "SELECT * FROM `graph_zarpl_otdel` where id_table = $idOtdel";
    $res_view = $mysqli->query($query_view);

    if (!$res_view) {
        echo '<form method="post" action="">
            <div class="error_mes">
            Ошибка запроса
            <input autofocus class = "submit" type="submit" value="Обновить">
            </div>';
        exit();
    } else {
        if (!$res_view->num_rows) {
            echo '<img width="150px" src="NoHave.png" alt="Фото нету">';
        } else {
            $baseCode = ($res_view->fetch_assoc()['grafic']);
            echo '<img width="250px" src="data:image/jpeg;base64,' . $baseCode . '" alt="Увы">';
        }
    }
};

$GetTable = function () use ($mysqli, $getPhotoBd) {
    $query_view = "SELECT * FROM `otdels`";
    $res_view = $mysqli->query($query_view);

    if (!$res_view) {
        echo '<form method="post" action="">
            <div class="error_mes">
            Ошибка запроса
            <input autofocus class = "submit" type="submit" value="Обновить">
            </div>';
        exit();
    } else {
        if (!$res_view->num_rows) {
            echo " <div class='error_mes'>Нет отделов</div>";
            return;
        }
?>
        <table id="table">
            <tr>
                <th width="10%">Код отдела</th>
                <th width="20%">Наименование отдела</th>
                <th width="20%">Построение</th>
                <th width="20%">Посмотреть</th>
                <th width="30%">Диаграмма</th>
            </tr>
            <?php
            foreach ($res_view as $res) {
            ?>
                <tr>
                    <td style="text-align: center;"><?= $res['idOtdel'] ?></td>
                    <td><?= $res['NameOtdel'] ?></td>
                    <td style="text-align: center;"> <input class="btn-new" name="Перестроить" type="submit" value="Перестроить" onclick="createHiddenCode(<?= $res['idOtdel'] ?>)"></td>
                    <td style="text-align: center;"><input class="btn-new" name="Перестроить" type="button" value="Открыть" onclick="viewDiagram(<?= $res['idOtdel'] ?>)"> </td>
                    <td style="text-align: center;"> <?= $getPhotoBd($res['idOtdel']) ?> </td>
                </tr>
            <?php
            }
            ?>
        </table>
<?php
    }
};

$viewDiagram = function ($otdel) use ($mysqli){
    $query_view = "SELECT * FROM `graph_zarpl_otdel` WHERE `id_table` = $otdel;";
    $res_view = $mysqli->query($query_view);

    if (!$res_view) {
        echo '<form method="post" action="">
            <div class="error_mes">
            Ошибка запроса
            <input autofocus class = "submit" type="submit" value="Обновить">
            </div>';
    } else {
        if (!$res_view->num_rows) {
            echo " <div class='error_mes'>Нет отделов</div>";
            return;
        }
        $baseCode = ($res_view->fetch_assoc()['grafic']);
        echo '<img src="data:image/jpeg;base64,' . $baseCode . '" alt="Какие-то проблемы с генерацией диаграммы">';
    }

};

