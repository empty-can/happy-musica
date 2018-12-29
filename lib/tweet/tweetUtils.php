<?php
require_once (dirname(__FILE__) . "/Tweet.php"); // ユーティリティライブラリをロード
use lib\tweet\Tweet;

function obj2tweet($oObj)
{
    $result = array();

    if (isset($oObj->statuses))
         $targetObj = $oObj->statuses;
    else
        $targetObj = $oObj;

    if(isset($targetObj->error)) {
        $result = (array)$targetObj;
    } else {
        foreach ($targetObj as $tweetObj) {
            array_push($result, new Tweet($tweetObj));
        }
    }

    return $result;
}

function getTweetList($oObj, $lastTweetId)
{
    $tweetIds = array();
    $screenNames = array();

    if (isset($oObj)) {
        foreach ($oObj as $tweetObj) {
            $tweet = new Tweet($tweetObj);

            // if("1014558465832828928"==$tweet->getId()) {
            // echo "1014558465832828928<br>\r\n";
            // evar_dump($tweet->isMediaTweet());
            // }

            if ($tweet->isMediaTweet()) {
                array_push($tweetIds, $tweet->getId());
                $screenNames[$tweet->getId()] = $tweet->getScreenName();
                $lastTweetId = $tweet->getId();
            }

            // if ($tweet->isRetweet()) {
            // $retweet = $tweet->getRetweet();

            // var_dump($retweet);
            // exit();

            // if ($retweet->isMediaTweet()) {
            // array_push($tweetIds, $retweet->getId());
            // $screenNames[$retweet->getId()] = $retweet->getScreenName();
            // $lastTweetId = $tweet->getId();
            // }
            // }
        }
    }

    $result = array(
        'tweetIds' => $tweetIds,
        'screenNames' => $screenNames,
        'lastTweetId' => $lastTweetId
    );

    return $result;
}