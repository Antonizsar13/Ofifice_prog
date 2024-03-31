<?php
$param0 = $_GET['param'];
$filename = 'Saransk2018.jpg';
$info = getimagesize($filename);
$width = $info[0];
$height = $info[1];
$type = $info[2];
switch ($type) {
    case 1:
        $img = imageCreateFromGif($filename);
        imageSaveAlpha($img, true);
        break;
    case 2:
        $img = imageCreateFromJpeg($filename);
        break;
    case 3:
        $img = imageCreateFromPng($filename);
        imageSaveAlpha($img, true);
        break;
}
$font_file = 'arial.ttf';
$ColorLines = imagecolorallocate($img, 0, 0, 0);
$ColorFill = imagecolorallocate($img, 250, 250, 160);
$color_text = imagecolorallocate($img, 20, 10, 10);
imagefilledrectangle($img, 10, 10, 150, 82, $ColorFill);
imagerectangle($img, 9, 9, 154, 86, $ColorLines);
imagettftext(
    $img,
    36,
    0,
    35,
    66,
    $color_text,
    $font_file,
    $param0
);
header('Content-type: image/jpeg');
imagefilter($img, IMG_FILTER_CONTRAST, $param0);
imagejpeg($img, null, 100);
imagedestroy($img);
