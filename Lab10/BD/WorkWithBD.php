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
    $query_view = "SELECT tname, kod, date_p, price, kol_vo, (price*kol_vo) `all_tovare`, photo FROM `eltovary` order by kod;";
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

