<?php

require_once('BD/WorkWithBD.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Отчет</title>
</head>

<body style="display: flex; flex-direction: column; width:900px">
    <div class="main_div">
        <div class="head_div">
            <div class="img_div">
                <img src="book.png" class="img_zag" alt="BOOK">
            </div>
            <div class=" text_div">
                Отчет о заработанной плате сотрудников в отделе под номером <?= $_GET['otdel'] ?> за 2018 год
            </div>
        </div>

        <div class="head_div" style=" display: flex; justify-content: center; align-items: center;">
            <div class="text_mini_div" id="error" style="width: 100%;">
                Cтатус: <?= $checkConnect() ?>
            </div>
        </div>

        <div class="head_div" style="padding: 2px; display: flex; align-items: center; justify-content: center;">
            <?= $viewDiagram($_GET['otdel']) ?>
        </div>

    </div>

</body>

</html>