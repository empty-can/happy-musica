<?php
session_save_path('C:\xampp\htdocs\tmp');
// ini_set( 'session.gc_maxlifetime', 1 );
// ini_set( 'session.gc_probability', 1 );  // 分子(デフォルト:1)
// ini_set( 'session.gc_divisor', 1 );  // 分母(デフォルト:100)
// if (isset($_COOKIE["PHPSESSID"])) {
//     setcookie("PHPSESSID", '', time() - 1800, '/');
// }

ini_set('error_reporting', E_ALL);
//error_reporting(E_ALL & ~E_NOTICE);

session_start();

require_once(dirname(__FILE__)."./twitteroauth/load.php"); // twitteroauthのライブラリをロード
require_once(dirname(__FILE__)."./util.php"); // ユーティリティライブラリをロード
require_once(dirname(__FILE__)."./dao.php"); // データアクセスライブラリをロード
require_once(dirname(__FILE__)."./tweet/Tweet.php"); // ユーティリティライブラリをロード
require_once(dirname(__FILE__)."./tweet/CollectionsTweetList.php"); // ライブラリをロード
require_once(dirname(__FILE__)."./tweet/TweetList.php"); // ライブラリをロード
require_once(dirname(__FILE__)."./tweet/tweetUtils.php"); // ユーティリティライブラリをロード
require_once(dirname(__FILE__)."./myTweetAPI.php"); // カスタムTwitterAPIライブラリをロード
require_once(dirname(__FILE__)."./accessKeys.php"); // アクセスキーを取得
require_once(dirname(__FILE__)."./Smarty/Smarty.class.php"); // Smartyライブラリをロード

$smarty = new Smarty();
$smarty->template_dir = 'templates/';
$smarty->compile_dir  = 'templates/c/';


// 各種定数の設定

define("UserToken", "access_token");
define("UserTokenSecret", "access_token_secret");
define("TwitterConnection", "twitter_conn");

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');

define("PageContext", "/osaisen");
define("ErrorMessage", "error_message");

$privileges = array(
    "/"
    , "/auth"
    , "/public"
    , "/test"
    , "/timeline"
    , "/followers"
);

setServerParam("PrivilegedPath", $privileges);





/**
 * ログインチェック
 *
 */
//     if(!isTopPage() && !isAuthPages() && !isLogin() && !isPublic()) {
if(!isExceptionPage() && !isLogin()) {
    setSessionParam(ErrorMessage, "まずログインしてください。");
    header('Location: /osaisen/');
} else if (isLogin()) {
    $connection = getTwitterConnection();
}