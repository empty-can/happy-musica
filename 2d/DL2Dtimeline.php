<?php
require_once (dirname(__FILE__) . "/../lib/init.php");
require_once 'C:\xampp7\google_api\test.php';

function exception_error_handler($severity, $message, $file, $line) {
//     if (!(error_reporting() & $severity)) {
//         // このエラーコードが error_reporting に含まれていない場合
//         return;
//     }
    echo $severity;
    echo "<br/>";
    echo $message;
    echo "<br/>";
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

// define("storage", 'C:/xampp/htdocs/matome/tmp/');
define("storage", 'C:/Users/iimay/Google ドライブ/');
define("TEST_STORAGE", 'C:/Users/iimay/test/');
define("tweets", storage . "tweets_jsons/");
define("images", storage . "imgs/");
define("test_tweets", TEST_STORAGE . "tweets_jsons/");
define("test_images", TEST_STORAGE . "imgs/");

date_default_timezone_set('Asia/Tokyo');

require_once 'C:\xampp\htdocs\osaisen\2d\lib\dllib.php';

// $max_id="1075193066967818240";
$max_id = "0";

do {
    try {

        if ($max_id == "0") {
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

        if (count($oObj) == 0) {
            echo "result size is 0\r\n";
            break;
        }

        foreach ($oObj as $tweet) {
            $tweetCounter ++;

            if (($max_id == 0) || ($max_id > $tweet->id)) {
                echo "max_id: " . $max_id . " to: " . $tweet->id . "\r\n";
                $max_id = $tweet->id;
            }

            if (isset($tweet->retweeted_status)) {
                echo "Created at: ".$tweet->created_at."\r\n";
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
    } catch (Exception $e) {
        var_dump($e->getTraceAsString());
        // エラーの原因を出力
        $previous = $e->getPrevious();
//         var_dump($previous);
//         debug_print_backtrace();
        //printf("%s %s(%d)\n", $e->getMessage(), $e->getFile(), $e->getLine());
        //printf("# %s %s(%d)\n", $previous->getMessage(), $previous->getFile(), $previous->getLine());
        exit(-1);
    }
// } while (true);
} while($imgCounter>0);

echo "---------------------------------------\r\n";
echo "読み込み Retweet数 : " . $tweetCounter . "\r\n";
echo "全 Retweet数 : " . $totalCounter . "\r\n";
echo "DL Retweet数 : " . $retweetCounter . "\r\n";
echo "DL image数 : " . $imgCounter . "\r\n";
echo date("Y/m/d G:i:s") . "\r\n";

