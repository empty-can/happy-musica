<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");
require_once ("/xampp/htdocs/osaisen/timeline/body.php");

$max_id = getGetParam('max_id', '0');
$count = getGetParam('count', 50);

$screen_name = getPostParam('screen_name', "orenoyome");
$api = urldecode(getGetParam('api', ''));
$params = getPostParam('params', array());

$params["count"] = 200;

// echo json_encode($params);
// exit();

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');
$tweetList = new TweetList($accessToken, $accessTokenSecret, $api, $params, $max_id, $count, 5);

// echo json_encode($tweetList->getTweet4View());
// exit();

$targetTweets = $tweetList->getTweet4View();
$tweet_num = count($targetTweets);
$calledNum = $tweetList->getCalledNum();
$start = $tweetList->getStartTweetId();
$end = $tweetList->getEndTweetId();

$id_hisoty[$screen_name . ':' . $end] = $start;
setSessionParam('id_hisoty', $id_hisoty);

// ob_start();

$result = array();

for ($i = 0; $i < $tweet_num; $i ++) {
    $tweet = $targetTweets[$i];
    // myVarDump($tweet);
    
    $result[$i] = renderTweet($tweet);
}

// $str = ob_get_contents();
// ob_end_clean();
// myVarDump($str);

if($tweet_num==0) {
    $str = "";
    $end = -1;
}

$arr  = array(
"timeline" => $result,
"max_id" => $end
);

echo json_encode( $arr );