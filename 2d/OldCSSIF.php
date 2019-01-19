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
    "count" => "50"
]);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="../css/test.css?<?php echo time();?>" rel="stylesheet" />
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
        $tweetCounter = (int)0;
        ?>
        <div class="siema">
        <?php
        foreach ($oObj as $tweet) {
            if(isset($tweet->retweeted_status)) {
                $retweet = new Tweet($tweet->retweeted_status);
                $allMediaURL = $retweet->getAllMediaURL();

//                 $testValues = array(
//                     'id '=> $retweet->getId()
//                     , 'text' => $retweet->getText()
//                     , 'created_at' =>$retweet->getCreatedAt()
//                     , 'screen_name' => '@'.$retweet->getScreenName()
//                     , 'location' => $retweet->getLocation()
//                     , 'description' => $retweet->getDescription()
//                     , 'tweet_url' => '<a href="'.$retweet->getTweetURL().'" target="_blank">'.$retweet->getTweetURL().'</a>'
//                     , 'getProfileImgURL' => '<img src="'.$retweet->getProfileImgURL().'" />'
//                     , 'name' => $retweet->getName()
//                 );

                if(!empty($allMediaURL)) {
                    ?><!-- div id="<?php echo $tweetCounter;?>" class="parent" -->
                    <!--div class="child siema" -->
                    <?php
                    $mediaCounter = (int)0;
                    foreach ($allMediaURL as $mediaURL) {
                        ?><!-- div id="<?php echo $tweetCounter."-".$mediaCounter;?>" class="img"><img src="<?php echo $mediaURL;?>" / -->
                        <div>
                            <img id="<?php echo $tweetCounter."-".$mediaCounter; ?>" class="media" src="<?php echo $mediaURL; ?>" /><br />
                            <div class="tweet">
                            	<!-- div style="background-color:white;bottom:0px;float:left;"></div -->
                            	<?php echo '<img class="icon" src="'.$retweet->getProfileImgURL().'" />'; ?><br />
                            	<?php echo '@'.$retweet->getScreenName(); ?><br />
                            	<?php echo $retweet->getText(); ?><br />
                            </div>
                        </div>
                        <?php
                        $mediaCounter += (int)1;
                    }
                    ?>
                    <!-- /div -->
                    <!-- /div -->
                    <?php
                }
            } else {
                $tweet = new Tweet($tweet);
                if("orenoyome"!==$tweet->getScreenName()) {
                    //myVarDump($tweet->getScreenName());  // 横島ボット以外の非RTがあれば表示
                    //myVarDump($tweet);  // 横島ボット以外の非RTがあれば表示
                }
            }

            $tweetCounter += (int)1;
        }
        ?>
         </div>
         <?php
    }
?>
<script src="../js/siema.min.js"></script>
<script>
  new Siema();
</script>
<?php include './2Dheader.php';?>
</body>
</html>