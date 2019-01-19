<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");


if (getGetParam('reset', '') == 'true') {
    setSessionParam('id_hisoty', array());
    $id_hisoty = array();
} else {
    $id_hisoty = getSessionParam('id_hisoty', array());
}

$api = 'statuses/home_timeline';
setSessionParam('api', $api);

$search = '';
$max_id = getGetParam('max_id', '0');
$count = getGetParam('count', 50);
$maxCount = 200;

$name = getGetParam('name', '');

$screen_name = getGetParam('screen_name', "あなたのホーム");

$params = array(
    "count" => $maxCount
);

$tweetList = new TweetList(PublicUserToken, PublicUserTokenSecret, $api, $params, $max_id, $count, 5);


$targetTweets = $tweetList->getTweet4View();
$tweet_num = count($targetTweets);
$calledNum = $tweetList->getCalledNum();
$start = $tweetList->getStartTweetId();
$end = $tweetList->getEndTweetId();

// var_dump($targetTweets);
$searchQuery = urlencode($search);

if(isset($id_hisoty[$search.':'.$max_id])){
    $backQueryString = 'search='.$searchQuery.'&count='.$count.'&max_id='.$id_hisoty[$search.':'.$max_id];
    $isBack = true;
} else {
    $isBack = false;
}

if($end == 0){
    $isNext = false;
} else {
	$nextQueryString = 'search='.$searchQuery.'&count='.$count.'&max_id='.$end;
    $isNext = true;
}

$id_hisoty[$search . ':' . $end] = $start;
setSessionParam('id_hisoty', $id_hisoty);

$resetQuery = 'search/?search='.$searchQuery.'&reset=true';

$search = preg_replace('/\"/', '&quot;', $search);

require_once(dirname(__FILE__)."/../view/view.php");
