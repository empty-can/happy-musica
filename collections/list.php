<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");
use Abraham\TwitterOAuth\TwitterOAuth;

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');

$result = "";
$myCollections = array();
$message = "";

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
<title>コレクション一覧ページ</title>
<script type="text/javascript">

</script>
</head>
<body id="body">
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
	<a href="/osaisen/collections/create.php">コレクションの追加ページへ進む</a><br />
	<a href="/osaisen/">トップページへ戻る</a>
</body>
</html>