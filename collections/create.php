<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");
use Abraham\TwitterOAuth\TwitterOAuth;

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');

$result = "";
$myCollections = array();
$message = "";

$isSubmitted = getPostParam('submit', '');

if (! empty($isSubmitted)) {
    $name = getPostParam('name', '');
    $url = getPostParam('url', '');
    $description = getPostParam('description', '');
    $timelineOrder = getPostParam('timeline_order', '');

    $param = array(
        'name' => $name,
        'url' => $url,
        'description' => $description,
        'timeline_order' => $timelineOrder
    );

    $result = postData($accessToken, $accessTokenSecret, "collections/create", $param);

    file_put_contents ('/xampp/htdocs/osaisen/tmp/collection'.time().'.json', json_encode($result));

    $message = "コレクションの作成に成功しました。";
}

$userInfo = getSessionParam('user_info', array());
$param = array(
    "screen_name" => $userInfo->{'screen_name'}
);

$collections = getTweetObjects($accessToken, $accessTokenSecret, 'collections/list', $param);
$myCollections[] = array();

//var_dump($collections->objects->timelines);
//var_dump($collections->response->results);

if (isset($collections->{'errors'})) {
    echo 'APIの上限を超えたようです。少々お待ちください。';
} else if(isset($collections->response->results) && empty($collections->response->results)) {
    echo '';
} else if(!is_string($collections)) {
    foreach ($collections->objects->timelines as $key => $value) {
        $tmp = [$key => $value];

        $myCollections[] = $tmp;
    }
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
<title>コレクションの追加ページ</title>
<script type="text/javascript">

</script>
</head>
<body id="body">
	<h3>現在のコレクション</h3>
	<ul>
	<?php
		foreach($myCollections as $collections) {
			foreach(array_values($collections) as $collection) {
				$tkn = explode('/', $collection->custom_timeline_url);
				
				$collectionId = $tkn[count($tkn)-1];
				
				?>
				<li><a href="<?php echo $collection->custom_timeline_url; ?>" target="_blank"><?php echo $collection->name?></a>　<a href="/osaisen/collections/add.php?collection_id=<?php echo $collectionId;?>&collection_name=<?php echo $collection->name;?>">&#x2795;ツイートを追加</a></li>
				<?php
			}
		}
	?>
	</ul>
	<hr />
	メッセージ：<span style="color:red;"><?php echo $message;?></span>
	<form method="post" action="/osaisen/collections/create.php">
		<input type="text" name="name" placeholder="コレクション名" /><br />
		<input type="text" name="description" placeholder="コレクションの説明" /><br />
		<input type="text" name="url" placeholder="https://関連URL" /><br />
		タイムラインのツイート順序<br />
		<input type="radio" name="timeline_order" value="tweet_reverse_chron" checked />新しい順序<br />
		<input type="radio" name="timeline_order" value="tweet_chron" />古い順<br />
		<input type="radio" name="timeline_order" value="curation_reverse_chron" />コレクションへの追加順<br />
		<button type="submit" name="submit" value="submit">登録</button>
	</form>
	<br />
	<a href="/osaisen/collections/list.php">コレクションの一覧ページ</a><br />
	<a href="/osaisen/">トップページへ戻る</a>
</body>
</html>