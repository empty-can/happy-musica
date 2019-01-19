<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");
use Abraham\TwitterOAuth\TwitterOAuth;

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');

$isSubmitted = getPostParam('submit', '');
if (! empty($isSubmitted)) {
    $listName = getPostParam('name', '');
    $listDesc = getPostParam('desc', '');
    $listMode = getPostParam('mode', '');

    $param = array(
        'name' => $listName,
        'mode' => $listMode,
        'description' => $listDesc
    );

    $user_connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $accessToken, $accessTokenSecret);
     $user_connection->post('lists/create', $param);

    $myLists = getSessionParam('my_lists', array());

    $userInfo = getSessionParam('user_info', array());
    $param = array(
        "screen_name" => $userInfo->{'screen_name'}
    );

    $user_connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $accessToken, $accessTokenSecret);
    $listsInfo = $user_connection->get('lists/list', $param);

    $myLists = array();


    if (isset($myLists->{'errors'})) {
        echo 'APIの上限を超えたようです。少々お待ちください。';
    } else {
        foreach ($listsInfo as $list) {
            $tmp = array();
            $tmp['id'] = $list->id;
            $tmp['name'] = $list->name;

            $myLists[] = $tmp;
        }
    }

    setSessionParam('my_lists', $myLists);

    echo "リストの作成に成功しました。";
    echo '<a href="/osaisen/">トップページへ戻る</a>';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<title>リストの追加ページ</title>
<script type="text/javascript">

</script>
</head>
<body id="body">
	<form method="post" action="/osaisen/createList.php">
		<input type="text" name="name" placeholder="リスト名" /><br /> <input
			type="text" name="desc" placeholder="リストの説明" /><br /> リストの公開/非公開<br />
		<input type="radio" name="mode" value="private" checked />非公開 <input
			type="radio" name="mode" value="public" />公開<br>
		<button type="submit" name="submit" value="submit">登録</button>
	</form>
	<a href="/osaisen/">戻る</a>
</body>
</html>