<?php
use lib\tweet\Tweet;

require_once ("../lib/init.php");

$targetURL = getPostParam("targetURL")

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ツイート構造確認ページ</title>
</head>
<body>
	<a href="/osaisen/" target="_self">トップページ</a>
	<form action="" method="post">
		ツイートのURLを入力するのじゃ！：<input type="text" name="targetURL" /><br />
		<input type="submit" name="送信"/>
	</form>

<?php
if(!empty($targetURL)) {
    $tkn = explode('/', $targetURL);
    $screenName = $tkn[3];
    $tweetId = $tkn[5];

    echo $targetURL."<br>";
    echo json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($targetURL)))->html;
    echo "<!-- ".json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($targetURL)))->html." -->";

    ?>
    <hr />
<?php

    // Twitterからjson形式でデータが返ってくる
    // $content = $connection->get("account/verify_credentials");
    $connection = getTwitterConnection();

    // Twitterからjson形式でデータが返ってくる
    $oObj = $connection->get("statuses/show", [
    "id" => $tweetId
    , "trim_user" => false
    , "include_my_retweet" => true
    , "include_entities" => true
    ]);

// オブジェクトを展開
    if (isset($oObj->{'errors'}) && $oObj->{'errors'} != '') {
    ?>
    取得に失敗しました。<br /> エラー内容：
	<br />
	<pre>
    <?php var_dump($oObj); ?>
    </pre>
    <?php
    } else {
        $tweet = new Tweet($oObj);
        $testValues = array(
            'id '=>$tweet->getId()
            , 'text' => $tweet->getText()
            , 'created_at' =>$tweet->getCreatedAt()
            , 'screen_name' => '@'.$tweet->getScreenName()
            , 'location' => $tweet->getLocation()
            , 'description' => $tweet->getDescription()
            , 'tweet_url' => '<a href="'.$tweet->getTweetURL().'" target="_blank">'.$tweet->getTweetURL().'</a>'
            , 'getProfileImgURL' => '<img src="'.$tweet->getProfileImgURL().'" />'
            , 'name' => $tweet->getName()
        );
        foreach ($testValues as $key => $value) {
            echo $key.'=>'.$value;
            echo "\r\n<hr />\r\n";
        }
        foreach ($tweet->getAllMediaURL() as $mediaURL) {
            echo $mediaURL;
            echo "\r\n<hr />\r\n";
        }

        var_dump($tweet->getAllMediaURL());

        echo "isMediaTweet";
        var_dump($tweet->isMediaTweet());
        ?>
	<pre>
    <?php var_dump($oObj); ?>
    </pre>
    <?php
    }
}
?>

</body>
</html>