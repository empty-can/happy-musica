<?php
use lib\tweet\Tweet;

require_once ("../../lib/init.php");

if ( (!empty(getSessionParam("api"))) && (!empty(getGetParam("count"))) ) {
    $oObj = null;

    $param = getSessionParam("param");

    $param['count'] = getGetParam("count") + (int)1;

    if(!empty(getSessionParam("screen_name"))) {
        $param["screen_name"] = getSessionParam("screen_name");
    }

    $maxId = 0;
    if(!empty(getGetParam("lastTweetId"))) {
        $param["max_id"] = getGetParam("lastTweetId");
        $maxId = (int)getGetParam("lastTweetId");
    }

    // 横島ボットのリツイート一覧
    $oObj = getTwitterConnection()->get(getSessionParam("api"), $param);

    $tweetIds = array();
    $tweets = array();

    $lastTweetId = 0;

    if (isset($oObj)) {
        foreach ($oObj as $tweetObj) {
            $tweet = new Tweet($tweetObj);

            if ($tweet->isMediaTweet()) {
                if ($maxId !== $tweet->getId()) {
                    array_push($tweetIds, $tweet->getId());
                    $tweets[$tweet->getId()] = $tweet->getAllMediaURL();
                    $lastTweetId = $tweet->getId();
                }
            }
        }
    }

    $result = array(
        'tweetIds' => $tweetIds
        ,'tweets' => json_encode($tweets)
        ,'lastTweetId' => $lastTweetId
    );

    echo json_encode($result);
} else {
    echo json_encode("noparam");
}