<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");

if (getGetParam('reset', '') == 'true') {
    setSessionParam('id_hisoty', array());
    $id_hisoty = array();
} else {
    $id_hisoty = getSessionParam('id_hisoty', array());
}

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');

$listId = getGetParam('list_id', '');
$max_id = getGetParam('max_id', '0');
$count = getGetParam('count', 50);
$maxCount = 200;

$api = 'lists/members';
$params = array(
    "list_id" => $listId,
    "count" => 5000
);
$memberList = getTweetObjects($accessToken, $accessTokenSecret, $api, $params);

$members = array();
foreach($memberList->users as $member) {
    $members[$member->screen_name] = true;
}

setSessionParam('list_'.$listId, $members);
//myVarDump($memberList->users);

$api = 'lists/statuses';

$search="";

$name = getGetParam('name', '');

$screen_name = getGetParam('screen_name', "あなたのリスト");

$params = array(
    "list_id" => $listId,
    "count" => $maxCount
);

$tweetList = new TweetList($accessToken, $accessTokenSecret, $api, $params, $max_id, $count, 5);


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

//$user = getTweetObjects(PublicUserToken, PublicUserTokenSecret, "users/show", $params);
//$profile_image_url_https = $user->profile_image_url_https;
//$user_name = $user->name;

$resetQuery = 'timeline/?screen_name='.$screen_name.'&name='.$name.'&reset=true';

require_once(dirname(__FILE__)."/../view/view.php");
