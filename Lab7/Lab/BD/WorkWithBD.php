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


$GetZarplOtdel = function (&$month, &$names, $otdel) use ($mysqli) {
    $query_view = "SELECT SUM(Money) 'Sum', Month FROM `zarpl` WHERE God = 2018 and idSotr IN (select id FROM sotr where Otdel = $otdel) GROUP by Month ORDER by Month;";
    $res_view = $mysqli->query($query_view);

    if (!$res_view) {
        echo '<form method="post" action="">
            <div class="error_mes">
            Ошибка запроса
            <input autofocus class = "submit" type="submit" value="О б н о в и т ь">
            </div>';
        exit();
    } else {
        if (!$res_view->num_rows) {
            echo "В данном отделе нет данных за 2018 год";
            exit;
        }
        foreach ($res_view as $res) {
            array_push($month, $res['Month']);
            array_push($names, $res['Sum']);
        }
    }
};

$OtdelSelect = function () use ($mysqli) {
    $query_view = "SELECT * FROM `otdels`";
    $res_view = $mysqli->query($query_view);

    if (!$res_view) {
        echo '<form method="post" action="">
            <div class="error_mes">
            Ошибка запроса
            <input autofocus class = "submit" type="submit" value="О б н о в и т ь">
            </div>';
        exit();
    } else {
?>
        <select name="otdel" style="height: 50px; width:400px;">
            <?php
            foreach ($res_view as $res) {
            ?>
                <option value="<?= $res['idOtdel'] . ' - ' . $res['NameOtdel'] ?>" <?php if (!empty($_POST['otdel'])) if ($_POST['otdel'][0] == $res['idOtdel']) {
                                                                                        echo 'selected';
                                                                                    } ?>> <?= $res['idOtdel'] . ' - ' . $res['NameOtdel'] ?>
                </option>
            <?php
            }
            ?>
        </select>
<?php
    }
};
