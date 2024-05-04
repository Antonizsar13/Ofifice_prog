<?php

function drawPartEllipse($image, $centerX, $centerY, $R1, $R2, $startAng, $endAng, $color, $filled = true)
{
    $points = array();

    for ($deg = $startAng; $deg <= $endAng; $deg += 0.11) {
        $radian = deg2rad($deg);
        $x = $R1 * sin($radian) + $centerX;
        $y = -$R2 * cos($radian)  + $centerY;

        array_push($points, $x, $y);
    }

    if ($filled) {
        imageFilledPolygon($image, $points, count($points) / 2, $color);
    } else {
        imagePolygon($image, $points, count($points) / 2, $color);
    }
}

function drawPartDiagramEllipse($image, $centerX, $centerY, $R1, $R2, $startAng, $endAng, $color)
{
    drawPartEllipse($image, $centerX, $centerY, $R1, $R2, $startAng, $endAng, $color);

    $points = array(
        $centerX, $centerY,
        $R1 * sin(deg2rad($startAng)) + $centerX, -$R2 * cos(deg2rad($startAng)) + $centerY,
        $R1 * sin(deg2rad($endAng)) + $centerX, -$R2 * cos(deg2rad($endAng)) + $centerY,
    );

    imageFilledPolygon($image, $points, 3, $color);
}

function drawDiagramElipse($image, $centerX, $centerY, $R1, $R2, $height, array $parts, array $colors, array $colorsShadow)
{
    if (array_sum($parts) != 100) {
        trigger_error("Sum parts != 100", E_USER_ERROR);
        // throw new Exception("Sum parts != 100");
    }

    for ($i = 1; $i <= $height; $i += 1) {
        $startAng = 0;
        $endAng = 0;
        foreach ($parts as $index => $part) {
            $endAng = $part / 100 * 360 + $startAng;
            if ($i == $height) {
                drawPartDiagramEllipse($image, $centerX, $centerY - $i, $R1, $R2, $startAng, $endAng, $colors[$index]);
            } else {
                if ((($startAng >= 90) && ($startAng <= 270)) || (($endAng >= 90) && ($endAng <= 270)) || (($startAng <= 90) && ($endAng >= 270))) {
                    drawPartDiagramEllipse($image, $centerX, $centerY - $i, $R1, $R2, $startAng, $endAng, $colorsShadow[$index]);
                }
            }
            $startAng = $endAng;
        }
    }
}


function drawRectangle($image, $centerX, $centerY, $width, $height, $colorRectangle, $colorBardor)
{
    $x1 = $centerX - $width / 2;
    $y1 = $centerY - $height / 2;
    $x2 = $centerX + $width / 2;
    $y2 = $centerY + $height / 2;
    imageFilledRectangle($image, $x1, $y1, $x2, $y2, $colorRectangle);
    imageRectangle($image, $x1, $y1, $x2, $y2, $colorBardor);
}

header('Content-type: image/png');

$monthName = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
// $results = [12, '5', '14', 15, 45, 45, 35, 5, 4, 66, 32, 11];
// $diagramName = "Названье";
// $secondName = "Второе названье";
// $heightGraph = 500;
// $widthGraph = 700;
// $paddingHorizont = 5;
// $paddingVertical = 5;

$diagramName = $_GET['diagramName'];
$secondName = $_GET['diagramSecondName'] . ' ' . date("Дата: Y-m-d Время: H:i:s");;
$month = ($_GET['month']);
$results = ($_GET['names']);



if (isset($_GET['heightGraph']))
    $heightGraph = $_GET['heightGraph'];
else
    $heightGraph = 500;
if (isset($_GET['widthGraph']))
    $widthGraph = $_GET['widthGraph'];
else
    $widthGraph = 700;

if (isset($_GET['paddingHorizont']))
    $paddingHorizont = $_GET['paddingHorizont'];
else
    $paddingHorizont = 5;

if (isset($_GET['paddingVertical']))
    $paddingVertical = $_GET['paddingVertical'];
else
    $paddingVertical = 5;


if ($paddingVertical < 15)
    $topPadding = 15;
else
    $topPadding = 0;



$width = $widthGraph + $paddingHorizont * 2;
$height = $heightGraph + $paddingVertical + $topPadding;

$image = imageCreateTrueColor($width, $height);
$black = imageColorAllocate($image, 0, 0, 0);

$backgroundColor = imageColorAllocate($image, 155, 206, 114);
imageFill($image, 0, 0, $backgroundColor);
$yellowFon = imageColorAllocate($image, 254, 255, 215);
imageRectangle($image, 0, 0, $width - 1, $height - 1, $black);

$blueFon = imageColorAllocate($image, 209, 222, 224);
imageFilledRectangle($image, $paddingHorizont, $paddingVertical + $topPadding, $width - $paddingHorizont, $height - $paddingVertical, $yellowFon);
imageRectangle($image, $paddingHorizont + 1, $paddingVertical + 1 + $topPadding, $width - $paddingHorizont - 1, $height - $paddingVertical - 1, $black);

$font = 'LTSuperior-Medium.otf';

imagettftext(
    $image,
    10,
    0,
    $paddingHorizont + 10,
    ($topPadding + $paddingVertical) / 2 + 5,
    $black,
    $font,
    $diagramName
);

imagettftext(
    $image,
    8,
    0,
    $paddingHorizont + 5,
    ($topPadding + $paddingVertical) + 13,
    $black,
    $font,
    $secondName
);




$indentation = 5;
$countColums = count($month);
$interval = ($widthGraph - $indentation * 2) / ($countColums * 2 + 1);

$startX = $paddingHorizont + $interval + $indentation;
$startY = $paddingVertical + $heightGraph - $indentation - 5;

$maxHeadColum = $heightGraph - $indentation * 10;

$coef = max($results) / $maxHeadColum;

$columnMiddleText = $interval / 2;
$sizeText = 10;
for ($i = 0; $i < $countColums; $i++) {
    $color = imageColorAllocate($image, rand(145, 255), rand(145, 255), rand(145, 255));

    $height = $results[$i] / $coef;
    imagefilledrectangle($image, $startX, $startY, $startX + $interval, $startY - $height, $color);
    imagerectangle($image, $startX, $startY, $startX + $interval, $startY - $height, $black);

    imagettftext(
        $image,
        $sizeText,
        90,
        $startX + $columnMiddleText + $sizeText / 2,
        $startY - 5,
        $black,
        $font,
        $monthName[$month[$i] - 1]
    );

    imagettftext(
        $image,
        8,
        0,
        $startX,
        $startY + 12,
        $black,
        $font,
        $results[$i]
    );

    $startX += $interval * 2;
}

imagepng($image);
imageDestroy($image);
