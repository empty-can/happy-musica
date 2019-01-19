<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');
$userInfo = getSessionParam('user_info', array());

if(empty($userInfo)) {
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">';
	echo '<head></head>';
	echo '<body>';
	echo 'このページを表示するためにはTwitterアカウントを使ってアプリにログインする必要があります。<br>';
	echo '<a href="/osaisen/">ログインページへ</a>';
	echo '</body>';
	echo '</html>';
	exit();
}

$search = "";
$max_id = getGetParam('max_id', '0');
$count = getGetParam('count', 50);
$maxCount = 200;

$api = 'collections/entries';

$id = 'custom-'.getGetParam('collection_id', '');
$name = getGetParam('collection_name', '');

if(!empty($name)) {
    $name = preg_split("/[@＠]/", $name)[0];
} else {
    $name = getSessionParam('name', "二次");
}

$screen_name = $userInfo->{'screen_name'};

$params = array(
    "id" => $id,
    "count" => $count
);

$result = getTweetObjects($accessToken, $accessTokenSecret, $api, $params);

// myVarDump($result);

$tweetList = new CollectionsTweetList($result->objects);

$targetTweets = $tweetList->getTweet4View();
usort($targetTweets, function ($a, $b) {
        return $a->getCreatedAt('YmdHis') < $b->getCreatedAt('YmdHis') ? -1 : 1;
});
// myVarDump($targetTweets);
$tweet_num = count($targetTweets);
$calledNum = 1;
$start = $tweetList->getStartTweetId();
$count = -1;
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

require_once(dirname(__FILE__)."/../view/view.php");
