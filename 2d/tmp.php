<?php
require_once (dirname(__FILE__) . "/../lib/init.php");

define("storage", 'C:/Users/iimay/Google ドライブ/RT log/tmp/');

$connection = getTwitterConnection();
// 横島ボットのフォロワー一覧
$oObj = $connection->get("statuses/user_timeline", [
    "screen_name" => "orenoyome",
    "count" => "5"
]);

/**
 * zip ファイルを作成する
 *
 * @param string $files
 * @throws Exception
 */
function file2Zip($targetfile = "")
{

    $command = '"C:\Program Files\7-Zip\7z.exe" a "' . $targetfile . '.zip"' . ' "' . $targetfile .'"';
    //     echo "▼" . $command ."\r\n";

    if (! file_exists($targetfile))
        return;

        system($command);
}

$jsons = glob(storage . 'tweets/{*.json}',GLOB_BRACE);

foreach ($jsons as $json) {
    file2Zip($json);
    unlink($json);
}