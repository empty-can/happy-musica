<?php
require_once ("./lib/init.php");
use Abraham\TwitterOAuth\TwitterOAuth;
include './lib/parts/header.php';

$userInfo = getSessionParam('user_info');
$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');

var_dump($userInfo);
?>
<title>マイページ</title>
<style>
</style>
</head>
<body>
	<div id="main">
		<h1>マイページ</h1>
	</div>
</body>
</html>
