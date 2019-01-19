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



/**
 * リツイートの json を取得する
 *
 * @param Object $retweet
 */
function saveTweetJson($tweet = null)
{
    global $service;

    if (empty($tweet))
        return;

        $jsonPath = $tweet->id . '.json';

        if (searchFiles($service, TEST_JSON_DIR_ID, $tweet->id, 'json') == 0) {
            echo "★upload json:" . $tweet->id . "\r\n";
            uploadFiles($service, TEST_JSON_DIR_ID, json_encode($tweet), $tweet->id, 'json');
            return 1;
            //     }

            //     if (! file_exists($jsonPath)) {
            //         // if (! file_exists($jsonPath . '.zip')) {
            //         echo "★DL " . $jsonPath . "\r\n";
            //         file_put_contents($jsonPath, json_encode($tweet));
            //         return 1;
            // } else if (file_exists($jsonPath)) {
            // echo "Retweet info is already exists.\r\n";
            // file2Zip($jsonPath);
            // unlink($jsonPath);
        } else {
            return 0;
        }
}

/**
 * リツイートの 画像 を取得する
 *
 * @param Object $retweet
 */
function saveImg($media = null)
{
    global $service;

    if (empty($media))
        return;

        $imgPathTkn = explode("/", $media->media_url_https);
        $imgName = end($imgPathTkn);

        $tkn = explode('.', $imgName);

        if (searchFiles($service, TEST_IMG_DIR_ID, $tkn[0], $tkn[1]) == 0) {
            echo "★upload image:" . $media->media_url_https . "\r\n";
            uploadFiles($service, TEST_IMG_DIR_ID, file_get_contents($media->media_url_https), $tkn[0], $tkn[1]);
            return 1;
            //     }

            //     $imgPath = images . $imgName;

            //     if (! file_exists($imgPath)) {
            //         echo "★DL " . $imgPath . "\r\n";
            //         file_put_contents($imgPath, file_get_contents($media->media_url_https));
            //         return 1;
        } else {
            // echo $imgName . " is already exists.\r\n";
            return 0;
        }
}

function saveTweet($tweet)
{
    global $retweetCounter, $imgCounter, $totalCounter;

    $totalCounter ++;

    if (! isset($tweet->entities) && (! isset($tweet->entities->urls) || empty($tweet->entities->urls))) {
        echo "★★★no media tweet!★★★ " . getTweetUrlByTweet($tweet) . "\r\n";
    }

    $retweetCounter += saveTweetJson($tweet);

    if (isset($tweet->extended_entities)) {
        $extended_entities = $tweet->extended_entities;

        foreach ($extended_entities->media as $extended_media) {
            $imgCounter += saveImg($extended_media);
        }
    }

    if (isset($tweet->in_reply_to_status_id)) {
        // echo getTweetUrl($retweet->in_reply_to_screen_name, $retweet->in_reply_to_status_id);
        // echo getTweetUrl($tweet->in_reply_to_screen_name, $tweet->in_reply_to_status_id)."\r\n";
        return $tweet->in_reply_to_status_id;
    } else {
        return null;
    }
}
$max_id="1076194069422854144";
//$max_id="0";

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

