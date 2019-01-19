<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");


if (getGetParam('reset', '') == 'true') {
    setSessionParam('id_hisoty', array());
    $id_hisoty = array();
} else {
    $id_hisoty = getSessionParam('id_hisoty', array());
}

$api = 'search/tweets';
setSessionParam('api', $api);

$search = preg_replace("/[#]+/i", "#", getGetParam('search', ''));
$max_id = getGetParam('max_id', '0');
$count = getGetParam('count', 50);
$maxCount = 200;

$name = getGetParam('name', '');

if(!empty($name)) {
    $name = preg_split("/[@＠]/", $name)[0];
} else if(!empty($search)) {
    $name = html_entity_decode($search);
} else {
    $name = getSessionParam('name', "二次");
}

$screen_name = getSessionParam('screen_name', "orenoyome");
$screen_name = getGetParam('screen_name', $screen_name);

setSessionParam('screen_name', $screen_name);

$params = array(
    "q" => $search,
    "count" => $maxCount,
    "result_type" => "mixed"
);

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');
$tweetList = new TweetList($accessToken, $accessTokenSecret, $api, $params, $max_id, $count, 5);


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
