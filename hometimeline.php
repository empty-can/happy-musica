<?php
require_once ("./lib/init.php");
require_once ("./lib/tweet/tweetUtils.php");

$title = "マイタイムライン";

setSessionParam("screen_name", "");
setSessionParam("api", "statuses/home_timeline");
setSessionParam("exclude_replies", true);
setSessionParam("isMedia", true);

$count = getGetParam("count", "50");

$param = array(
    "count" => $count
    , "exclude_replies" => true
);

setSessionParam("param", $param);

// 自分のツイート一覧
$oObj = getTwitterConnection()->get(getSessionParam("api"), $param);

if(isset($oObj->errors)) {
    echo $oObj->errors[0]->message;
    exit();
}

$tweetList = getTweetList($oObj, 0);

// var_dump($tweetList);

//echo "-----------<br>\r\n";

//var_dump($oObj);

$lastTweetId = $tweetList['lastTweetId'];
$tweetIdList = $tweetList['tweetIds'];
$screenNames = $tweetList['screenNames'];
$listSize = count($tweetIdList);

//var_dump($screenNames);

//echo "-----------\r\n";

include './parts/timeline.php';