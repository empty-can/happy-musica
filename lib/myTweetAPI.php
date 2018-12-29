<?php
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * ユーザータイムラインを取得する
 *
 */
function getUserTimeLine($user_token, $user_token_secret, $param = array()) {
return getTweetObjects($user_token, $user_token_secret, "statuses/user_timeline", $param);
}

/**
 * 指定された API で Tweet オブジェクト取得する
 *
 */
function getTweetObjects($user_token, $user_token_secret, $api_url,  $param = array()) {

//     myVarDump((new TwitterOAuth(Consumer_Key, Consumer_Secret, $user_token, $user_token_secret))->get($api_url, $param));
//     myVarDump((new TwitterOAuth(Consumer_Key, Consumer_Secret, $user_token, $user_token_secret))->get("statuses/show", array("id" =>"1024161979122864128")));
  return (new TwitterOAuth(Consumer_Key, Consumer_Secret, $user_token, $user_token_secret))->get($api_url, $param);
}