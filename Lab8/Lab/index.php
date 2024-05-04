<?php

require_once('BD/WorkWithBD.php');

if (isset($_POST['Перестроить'])) {
    $GetZarplOtdel($_POST['numberOtdel']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Choose diagram</title>
</head>

<body style="display: flex; flex-direction: column;">
    <div class="main_div" id="div_blur">
        <div class="head_div">
            <div class="img_div">
                <img src="book.png" class="img_zag" alt="BOOK">
            </div>
            <div class=" text_div">
                Отчеты о заработанной плате сотрудников за 2018 год
            </div>
        </div>

        <div class="head_div" style=" display: flex; justify-content: center; align-items: center;">
            <div class="text_mini_div" id="error" style="width: 100%;">
                Cтатус: <?= $checkConnect() ?>
            </div>
        </div>

        <form action="" method="post" class="head_div" style="padding: 2px; display: flex; align-items: center; justify-content: center;">
            <?= $GetTable() ?>
        </form>

    </div>

    <div class="div_under" id="under" style="display: none;">

    </div>

</body>

</html>