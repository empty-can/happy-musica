<?php
use lib\Tweet;

require_once ("../lib/init.php");

$targetURL = getPostParam("targetURL");

$filename = pathinfo(__FILE__, PATHINFO_FILENAME);

printErrorMessages("color:red;font-weight:bold;");

$connection = getTwitterConnection();
// 横島ボットのリツイート一覧
$oObj = $connection->get("statuses/user_timeline", [
    "screen_name" => "orenoyome",
    "count" => "200"
]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<link href="../css/scroll.css?<?php echo time();?>" rel="stylesheet" />
<title>CSSインタフェーステスト</title>
</head>
<body>
<?php
// オブジェクトを展開
if (isset($oObj->{'errors'}) && $oObj->{'errors'} != '') {
    ?>
    取得に失敗しました。<br /> エラー内容：
	<br />
    <?php myVarDump($oObj); ?>
    <?php
} else {
    $tweetCounter = (int) 0;

    foreach ($oObj as $tweet) {
        if (isset($tweet->retweeted_status)) {
            $retweet = new Tweet($tweet->retweeted_status);
            $allMediaURL = $retweet->getAllMediaURL();

            // $testValues = array(
            // 'id '=> $retweet->getId()
            // , 'text' => $retweet->getText()
            // , 'created_at' =>$retweet->getCreatedAt()
            // , 'screen_name' => '@'.$retweet->getScreenName()
            // , 'location' => $retweet->getLocation()
            // , 'description' => $retweet->getDescription()
            // , 'tweet_url' => '<a href="'.$retweet->getTweetURL().'" target="_blank">'.$retweet->getTweetURL().'</a>'
            // , 'getProfileImgURL' => '<img src="'.$retweet->getProfileImgURL().'" />'
            // , 'name' => $retweet->getName()
            // );

            if (! empty($allMediaURL)) {
?>
	<div id="<?php echo $tweetCounter;?>" class="horizontal_scroll_wrap">
		<div class="tweet">
			<div class="icon">
                <?php echo '<img src="'.$retweet->getProfileImgURL().'" />'; ?>
			</div>
			<div class="profile">
                <div class="name"><?php echo $retweet->getName(); ?></div>
                <div class="screen_name"><?php echo '@'.$retweet->getScreenName(); ?></div>
			</div>
			<div class="text">
            <?php echo $retweet->getText(); ?>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="scroll_lst">
<?php
                $mediaCounter = (int) 0;
                foreach ($allMediaURL as $mediaURL) {
?>
			<div id="<?php echo $tweetCounter."-".$mediaCounter;?>" class="scroll_item">
				<div class="scroll_item_thum">
					<img src="<?php echo $mediaURL; ?>" /><br />
				</div>
			</div>
<?php
                    $mediaCounter += (int) 1;
                }
?>
		</div>
<?php
            }
        } else {
            $tweet = new Tweet($tweet);
            if ("orenoyome" !== $tweet->getScreenName()) {
                // myVarDump($tweet->getScreenName()); // 横島ボット以外の非RTがあれば表示
                // myVarDump($tweet); // 横島ボット以外の非RTがあれば表示
            }
        }

        $tweetCounter += (int) 1;
?>
		<div class="jump">
			<a href="#0">Top</a>

			<a href="#<?php echo $tweetCounter-(int)2;?>">▲</a>

			<a href="#<?php echo $tweetCounter;?>">▼</a>
		</div>
	</div>
<?php
    }
}

include './2Dheader.php';
?>
</body>
</html>