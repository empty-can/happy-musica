<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");

$mediaURL = urldecode(getGetParam('url', ''));

$suffix = end(explode('.', $mediaURL));

$im = null;

if($suffix=='jpeg') {
    /* オープンします */
    $im = @imagecreatefromjpeg($mediaURL);

} else if($suffix=='jpg') {
    /* オープンします */
    $im = @imagecreatefromjpeg($mediaURL);

} else if($suffix=='png') {
    /* オープンします */
    $im = @imagecreatefrompng($mediaURL);
}


/* 失敗したかどうかを調べます */
if(!$im)
{
    /* 空の画像を作成します */
    $im  = imagecreatetruecolor(150, 30);
    $bgc = imagecolorallocate($im, 255, 255, 255);
    $tc  = imagecolorallocate($im, 0, 0, 0);

    imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

    /* エラーメッセージを出力します */
    imagestring($im, 1, 5, 5, 'Error loading ' . $mediaURL, $tc);
}



if($suffix=='jpeg') {
    header('Content-Type: image/jpeg');

    imagejpeg($im, null, 30);

} else if($suffix=='jpg') {
    header('Content-Type: image/jpeg');

    imagejpeg($im, null, 30);

} else if($suffix=='png') {
    header('Content-Type: image/png');

    imagepng($im, null, 6);

}

imagedestroy($im);