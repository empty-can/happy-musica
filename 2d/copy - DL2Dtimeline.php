<?php
require_once (dirname(__FILE__) . "/../lib/init.php");
require_once 'C:\xampp7\google_api\test.php';

// define("storage", 'C:/xampp/htdocs/matome/tmp/');
define("storage", 'C:/Users/iimay/Google ドライブ/');
define("TEST_STORAGE", 'C:/Users/iimay/test/');
define("tweets", storage . "tweets_jsons/");
define("images", storage . "imgs/");
define("test_tweets", TEST_STORAGE . "tweets_jsons/");
define("test_images", TEST_STORAGE . "imgs/");

date_default_timezone_set('Asia/Tokyo');

require_once 'C:\xampp\htdocs\osaisen\2d\lib\dllib.php';

//$max_id="1076456139095662593";
$max_id="0";

do {

if($max_id=="0") {
    $param = array(
        "screen_name" => "orenoyome",
        "count" => "200"
    );
} else {
    $param = array(
        "screen_name" => "orenoyome",
        "count" => "200",
        "max_id" => $max_id
    );
}

$connection = getTwitterConnection();
// 横島ボットのフォロワー一覧
$oObj = $connection->get("statuses/user_timeline", $param);

$retweetCounter = 0;
$imgCounter = 0;
$totalCounter = 0;

// var_dump($oObj);
$replyIds = array();
$tweetCounter = 0;

if(count($oObj)==0)
  break;

foreach ($oObj as $tweet) {
    $tweetCounter++;

    if(($max_id==0)||($max_id>$tweet->id)) {
        echo "max_id: ".$max_id." to: ".$tweet->id."\r\n";
        $max_id=$tweet->id;
    }

    if (isset($tweet->retweeted_status)) {
        $tmp = saveTweet($tweet->retweeted_status);

        if (isset($tmp) && ! empty($tmp)) {
            array_push($replyIds, $tmp);

            $tmp = null;
        }
    }
}

while (isset($replyIds[0]) && ! empty($replyIds[0])) {

    // var_dump($replyIds);

    $connection = getTwitterConnection();

    $targetTweet = $connection->get("statuses/show", [
        "id" => $replyIds[0],
        "trim_user" => false
    ]);

    $split = array_splice($replyIds, 0, 1);

    $tmp = saveTweet($targetTweet);

    if (isset($tmp) && ! empty($tmp)) {
        array_push($replyIds, $tmp);

        $tmp = null;
    }
}

// foreach (glob(test_images . '*') as $file) {
//     $tkn = split('\.', str_replace(test_images, '', $file));

//     if (searchFiles($service, TEST_IMG_DIR_ID, $tkn[0], $tkn[1]) == 0) {
//         // uploadFiles($service, TEST_IMG_DIR_ID, $tkn[0], $tkn[1]);
//         // searchFiles($service, TEST_IMG_DIR_ID, $tkn[0], $tkn[1]);
//     }
// }
// foreach (glob(test_tweets . '*') as $file) {
//     $tkn = split('\.', str_replace(test_tweets, '', $file));

//     if (searchFiles($service, TEST_JSON_DIR_ID, $tkn[0], $tkn[1]) == 0) {
//         // uploadFiles($service, TEST_JSON_DIR_ID, $tkn[0], $tkn[1]);
//         // searchFiles($service, TEST_JSON_DIR_ID, $tkn[0], $tkn[1]);
//     }
// }

} while(true);
//} while($imgCounter>0);

echo "---------------------------------------\r\n";
echo "読み込み Retweet数 : " . $tweetCounter . "\r\n";
echo "全 Retweet数 : " . $totalCounter . "\r\n";
echo "DL Retweet数 : " . $retweetCounter . "\r\n";
echo "DL image数 : " . $imgCounter . "\r\n";
echo date("Y/m/d G:i:s") . "\r\n";

