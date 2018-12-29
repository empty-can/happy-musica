<?php
require_once ("../lib/init.php");
require_once ("../lib/tweet/tweetUtils.php");
require_once ("./auth.php");
require_once ("../lib/tweet/tweetUtils.php");

$title = "二次元絶対拡散するタイムライン";

setSessionParam("screen_name", "orenoyome");
setSessionParam("api", "statuses/user_timeline");
setSessionParam("isPublic", true);

$count = getGetParam("count", "10");

$param = array(
    "screen_name" => "orenoyome"
    , "count" => $count
);

setSessionParam("param", $param);


// 横島ボットのリツイート一覧
$oObj = getTwitterConnection()->get(getSessionParam("api"), $param);

if(isset($oObj->errors)) {
    echo $oObj->errors[0]->message;
    exit();
}

$tweetList = getTweetList($oObj, 0);

$lastTweetId = $tweetList['lastTweetId'];
$tweetIdList = $tweetList['tweetIds'];
$screenNames = $tweetList['screenNames'];
$listSize = count($tweetIdList);


include '../parts/timeline.php';