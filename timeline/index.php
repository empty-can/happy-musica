<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");


if (getGetParam('reset', '') == 'true') {
    setSessionParam('id_hisoty', array());
    $id_hisoty = array();
} else {
    $id_hisoty = getSessionParam('id_hisoty', array());
}

$api = 'statuses/user_timeline';
setSessionParam('api', $api);

$search = getGetParam('search', '');;
$max_id = getGetParam('max_id', '0');
$count = getGetParam('count', 50);
$maxCount = 200;

$name = getGetParam('name', '');

if(!empty($name)) {
    $name = mb_split("/[@＠]/", $name)[0];
} else if(!empty($search)) {
    $name = html_entity_decode($search);
} else {
    $name = getSessionParam('name', "二次");
}

$screen_name = getSessionParam('screen_name', "orenoyome");
$screen_name = getGetParam('screen_name', $screen_name);
setSessionParam('screen_name', $screen_name);

$param = array(
    "screen_name" => $screen_name,
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

$user = getTweetObjects(PublicUserToken, PublicUserTokenSecret, "users/show", $param);
$profile_image_url_https = $user->profile_image_url_https;
$user_name = $user->name;

$resetQuery = 'timeline/?screen_name='.$screen_name.'&name='.$name.'&reset=true';

require_once(dirname(__FILE__)."/../view/view.php");
