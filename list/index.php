<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");

if (getGetParam('reset', '') == 'true') {
    setSessionParam('id_hisoty', array());
    $id_hisoty = array();
} else {
    $id_hisoty = getSessionParam('id_hisoty', array());
}

$api = 'lists/statuses';
setSessionParam('api', $api);

$search="";

$listId = getGetParam('list_id', '');
$max_id = getGetParam('max_id', '0');
$count = getGetParam('count', 50);
$maxCount = 200;

$name = getGetParam('name', '');

$screen_name = getGetParam('screen_name', "あなたのリスト");

$param = array(
    "list_id" => $listId,
    "count" => $maxCount
);

setSessionParam('param', $param);

$tweetList = new TweetList(PublicUserToken, PublicUserTokenSecret, $api, $param, $max_id, $count, 5);


$targetTweets = $tweetList->getTweet4View();
$tweet_num = count($targetTweets);
$calledNum = $tweetList->getCalledNum();
$start = $tweetList->getStartTweetId();
$end = $tweetList->getEndTweetId();

if(isset($id_hisoty[$screen_name.'screen_name:'.$max_id])){
    $backQueryString = 'screen_name='.$screen_name.'&count='.$count.'&max_id='.$id_hisoty[$screen_name.':'.$end];
    $isBack = true;
} else {
    $isBack = false;
}

if($end == 0 || ($end==$max_id)){
    $isNext = false;
} else {
    $isNext = true;
    $nextQueryString = 'screen_name='.$screen_name.'&count='.$count.'&max_id='.$end;
}

$id_hisoty[$screen_name . ':' . $end] = $start;
setSessionParam('id_hisoty', $id_hisoty);

//$user = getTweetObjects(PublicUserToken, PublicUserTokenSecret, "users/show", $param);
//$profile_image_url_https = $user->profile_image_url_https;
//$user_name = $user->name;

$resetQuery = 'timeline/?screen_name='.$screen_name.'&name='.$name.'&reset=true';

require_once(dirname(__FILE__)."/../view/view.php");
