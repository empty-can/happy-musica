<?php
require_once ("../lib/init.php");

//Callback URL
define('Callback', 'http://www.yaruox.jp/osaisen/auth/callback.php');

//ライブラリを読み込む
require_once ("../lib/twitteroauth/load.php");
use Abraham\TwitterOAuth\TwitterOAuth;

//oauth_tokenとoauth_verifierを取得
if($_SESSION['oauth_token'] == $_GET['oauth_token'] and $_GET['oauth_verifier']){

    //Twitterからアクセストークンを取得する
    $connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $_GET['oauth_verifier'], 'oauth_token'=> $_GET['oauth_token']));

    //取得したアクセストークンでユーザ情報を取得
    $user_connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $_SESSION['user_info'] = $user_connection->get('account/verify_credentials');

    //各値をセッションに入れる
    $_SESSION['access_token'] = $access_token['oauth_token'];
    $_SESSION['access_token_secret'] = $access_token['oauth_token_secret'];
    
    
    $_SESSION['logined'] = true;

    $account = getSessionParam("account");
    $password = getSessionParam("password");

    insertTamikusa($account, $password, $access_token['oauth_token'], $access_token['oauth_token_secret']);

    header('Location: /osaisen/index.php');
    exit();
}else{
    header('Location: /osaisen/index.php');
    exit();
}