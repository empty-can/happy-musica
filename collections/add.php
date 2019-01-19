<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");
use Abraham\TwitterOAuth\TwitterOAuth;

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');

$result = "";
$myCollections = array();
$message = "";

$collectionId = getGetParam('collection_id', '');
$collectionName = getGetParam('collection_name', '');
$userInfo = getSessionParam('user_info', array());

$isSubmitted = getPostParam('submit', '');

if (! empty($isSubmitted)) {
    $collectionId = getPostParam('collection_id', '');
    $tweetUrls = getPostParam('tweet_urls', '');
    
    if(!empty($tweetUrls)) {
        $urlsTkn = preg_split('/\s/', $tweetUrls);
        
        $request = ["id" => "custom-".$collectionId];
        
        $changes = array();
        
        foreach($urlsTkn as $url) {
          if(empty($url))
            continue;
          
          $urlTkn = explode('/', $url);

          $tweetId = $urlTkn[count($urlTkn)-1];
          
          $changes[] = ["op" => "add", "tweet_id" => $tweetId];
        }
        
        $request["changes"] = $changes;
        
        $request_json = json_encode($request);

        $result = cureateCollections($accessToken, $accessTokenSecret, $request_json);
    	
    	// myVarDump($result);
    	
    	if((isset($result->response->errors) && !empty($result->response->errors))) 
        	$message = "ツイートの登録に失敗しました。<br/>\r\n原因：".$result->response->errors[0]->reason;
        else
        	$message = "ツイートの登録に成功しました。";
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
<title>ツイートの追加ページ</title>
<script type="text/javascript">

</script>
</head>
<body id="body">
	メッセージ：<?php echo $message; ?>
	<br/>
	<br/>
	コレクション名：<a href="https://twitter.com/<?php echo $userInfo->{'screen_name'}; ?>/timelines/<?php echo $collectionId; ?>" target="_blank"><?php echo $collectionName; ?></a>
	<form method="post" action="/osaisen/collections/add.php?collection_id=<?php echo $collectionId;?>&collection_name=<?php echo $collectionName;?>">
		<textarea name="tweet_urls" cols="128" rows="4" placeholder="ツイートURL（複数行可）" value="" ></textarea><br />
		<input type="hidden" name="collection_id" value="<?php echo $collectionId; ?>" />
		<input type="hidden" name="collection_name" value="<?php echo $collectionName; ?>" />
		<button type="submit" name="submit" value="submit">登録</button>
	</form>
	<br />
	<a href="/osaisen/collections/list.php">コレクションの一覧ページ</a><br />
	<a href="/osaisen/">トップページへ戻る</a>
</body>
</html>