<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");

if (getGetParam('reset', '') == 'true') {
    setSessionParam('id_hisoty', array());
    $id_hisoty = array();
} else {
    $id_hisoty = getSessionParam('id_hisoty', array());
}

$count = getGetParam('count', 50);
$max_id = getGetParam('max_id', '0');

$screen_name = getSessionParam('screen_name', "orenoyome");
$screen_name = getGetParam('screen_name', $screen_name);

setSessionParam('screen_name', $screen_name);

$param = array(
    "count" => 200,
    "screen_name" => $screen_name
);

// $testData = getSessionParam("test_data");
// var_dump($id_hisoty);

$tweets = array();
$targetTweets = array();

$start = 0;
$end = 0;
$counter = ((int) 0);
$callNum = ((int) 0);

$index = array();

do {
    echo "<!-- get -->\r\n";

    if ($max_id > 0)
        $param['max_id'] = $max_id;

    $tweets = array_merge($tweets, obj2tweet(getTweetObjects(PublicUserToken, PublicUserTokenSecret, "statuses/user_timeline", $param)));

    foreach ($tweets as $tweet) {
        $originalTweet = $tweet->getOriginalTweet();

        if (isset($index['ID:' . $originalTweet->getId()]))
            continue;
        else
            $index['ID:' . $originalTweet->getId()] = 'true';

        if (! $originalTweet->isMediaTweet())
            continue;
        else if (! empty($tweet->mediaURLs) && empty($tweet->mediaURLs['error']))
            continue;

        $counter = ((int) $counter) + 1;

        array_push($targetTweets, $originalTweet);

        $id = $tweet->getId();

        if ($start == 0)
            $start = $id;

        $end = $id;

        $index['ID:' . $id] = 'true';
    }

    $callNum = ((int) $callNum) + 1;

    // var_dump($max_id);
} while (($counter < $count)&&($callNum < 5));


$id_hisoty[$screen_name . ':' . $end] = $start;
setSessionParam('id_hisoty', $id_hisoty);

$tweet_num = count($targetTweets);

// var_dump($targetTweets);
