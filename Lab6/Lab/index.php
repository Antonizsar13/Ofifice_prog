<?php
require_once('bd/WorkWithBD.php');
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="style.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="main_div">
        <div class="head_div" style=" display: flex; justify-content: center; align-items: center;">
            <div class="text_mini_div" id="error" style="width: 100%;">
                Cтатус: <?= $checkConnect() ?>
            </div>
        </div>
        <div class="head_div">
            <div class="text_mini_div">
                <?php $createTable() ?>
            </div>
        </div>
    </div>
</body>

</html>