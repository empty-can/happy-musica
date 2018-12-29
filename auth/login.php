<?php
require_once ("../lib/init.php");
use Abraham\TwitterOAuth\TwitterOAuth;


if (empty(getPostParam("submit"))) {
    setSessionParam(ErrorMessage, "不正なアクセスです。");
    header('Location: /osaisen/');
}

$account = getPostParam("account");
$password = getPostParam("password");

if (empty($account) || empty($password)) {
    setSessionParam(ErrorMessage, "アカウント名とパスワードはどちらも入力してください。");
    header('Location: /osaisen/');
}

$password = hash("sha256", $password);

setSessionParam('account', $account);
setSessionParam('password', $password);

$isTamikusa = isTamikusa($account);

if($isTamikusa === true) {
  if($password == getTamikusaPassword($account)) {
      $userInfo = getTamikusa($account);

      $access_token = $userInfo[2];
      $access_token_secret = $userInfo[3];

      $user_connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $access_token, $access_token_secret);
      setSessionParam('user_info', $user_connection->get('account/verify_credentials'));
      setSessionParam('access_token', $userInfo[2]);
      setSessionParam('access_token_secret', $userInfo[3]);

      setSessionParam(ErrorMessage, "");
      
      $loginCookieId = hash ("sha256", $account.time());
      setTamikusaLoginInfo($account, $loginCookieId);
      // setCookieParam("login_cookie_id", $loginCookieId);
      setcookie("login_cookie_id", $loginCookieId, time()+60*60*24*7, '/osaisen/', 'www.yaruox.jp', false, false);

      // myVarDump($_COOKIE);

      $_SESSION['logined'] = true;

      header('Location: /osaisen/index.php');
  } else {
      setSessionParam(ErrorMessage, "アカウントかパスワードが間違っています。");
      header('Location: /osaisen/');
  }
} else {
?><html>
<head>
<meta charset="UTF-8" />
<title>HTML 5 complete</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>

<![endif]-->
<style>
</style>
</head>
<body>
    このアカウントは未登録です。<br>
	<a href="./auth.php">このアカウントを登録して Twitter と連携する</a>
	<br>
	<a href="../">トップページに戻る</a>
<?php
}
?>
</body>
</html>