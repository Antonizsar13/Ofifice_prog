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

$createTable = function () use ($mysqli) {
    $query_view = 'SELECT tname, kod, date_p, price, kol_vo, (price*kol_vo) `all_tovare`, photo FROM `eltovary` order by kod;';
    $res_view = $mysqli->query($query_view);


    if (!$res_view) {
        echo '<form method="post" action="">
            <div class="error_mes">
            Ошибка чтения данных с сервера
            <input autofocus class = "submit" type="submit" value="О б н о в и т ь">
            </div>';
        exit();
    } else {
?>
        <table border="1">
            <caption>Сведения об электротатарах</caption>
            <tr>
                <th width="45 %">Наименование</th>
                <th width="15%">Дата поступления</th>
                <th width="10%">Цена за ед., руб.</th>
                <th width="10%">Кол-во</th>
                <th width="15%">Сумма</th>
                <th width="5%">Фото</th>
            </tr>
            <?php
            $sum = 0;
            foreach ($res_view as $tovar) {
                // file_put_contents('img\image' . $tovar['kod'] . '.jpg', $tovar['photo']);
                $image_base64 = base64_encode($tovar['photo']);
                $sum += $tovar['all_tovare'];
            ?>
                <tr>
                    <td><?= $tovar['tname'] ?></td>
                    <td><?= $tovar['date_p'] ?></td>
                    <td><?= $tovar['price'] ?></td>
                    <td><?= $tovar['kol_vo'] ?></td>
                    <td><?= number_format($tovar['all_tovare'], 2,) ?></td>


                    <td><img src="data:image/jpeg;base64,<?php echo $image_base64; ?>" alt="Увы"></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td> Итого по электротатарам: </td>
                <td></td>
                <td></td>
                <td></td>
                <td><?= number_format($sum, 2,) ?></td>
                <td></td>
            </tr>
    <?php
    }
};

function base64ToImage($base64_string, $output_file)
{
    $file = fopen($output_file, "wb");

    $data = explode(',', $base64_string);

    fwrite($file, ($data[1]));
    fclose($file);

    return $output_file;
}
