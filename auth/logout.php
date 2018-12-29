<?php
require_once ("../lib/init.php");
header("Content-type: text/html; charset=utf-8");

//セッション変数を全て解除
$_SESSION = array();

//セッションクッキーの削除
if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
}
if (isset($_COOKIE["login_cookie_id"])) {
    setcookie("login_cookie_id", '', time() - 1800, '/osaisen/', 'www.yaruox.jp', false, false);
}

//セッションを破棄する
session_destroy();
?><!DOCTYPE <!DOCTYPE html>
<html>
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
	<p>ログアウトしました。</p>
	<?php ?>
	<p><a href="../" target="_self">トップページへ</a></p>
</body>