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

$getDate = function () use ($mysqli) {
    $query_view = "SELECT * FROM sotr sot INNER JOIN otdels otd on otd.idOtdel = sot.Otdel order by otd.NameOtdel;";
    $res_view = $mysqli->query($query_view);

    if (!$res_view) {
        echo '<form method="post" action="">
            <div class="error_mes">
            Ошибка запроса
            <input autofocus class = "submit" type="submit" value="Обновить">
            </div>';
    } else {
        return $res_view;
    }
};

