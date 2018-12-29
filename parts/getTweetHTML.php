<?php
require_once ("../lib/init.php");

$url = "https://twitter.com/".getGetParam('screen_name')."/status/" . getGetParam('tweetId');

$oObj = getTwitterConnection()->get("statuses/oembed", [
    "url" => $url
]);

// var_dump($oObj);

if(isset($oObj->errors)) {
    echo json_encode($oObj->errors[0]->message);
} else if(isset($oObj->error)){
    echo json_encode($oObj->error);
} else {
    echo json_encode($oObj->html);
}