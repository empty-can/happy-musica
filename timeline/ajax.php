<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");

$max_id = getGetParam('max_id', '0');
$count = getGetParam('count', 50);

$screen_name = getSessionParam('screen_name', "orenoyome");
$api = getSessionParam('api', array());
$param = getSessionParam('param', array());

$tweetList = new TweetList(PublicUserToken, PublicUserTokenSecret, $api, $param, $max_id, $count, 5);


$targetTweets = $tweetList->getTweet4View();
$tweet_num = count($targetTweets);
$calledNum = $tweetList->getCalledNum();
$start = $tweetList->getStartTweetId();
$end = $tweetList->getEndTweetId();

$id_hisoty[$screen_name . ':' . $end] = $start;
setSessionParam('id_hisoty', $id_hisoty);

ob_start();

for ($i = 0; $i<$tweet_num; $i++) {
    $tweet = $targetTweets[$i];
    ?>
            <div id="<?php echo $tweet->getId();?>" class="tweet <?php echo ($tweet->isRetweet()) ? "retweet" : "owntweet" ; ?>"
				data-has-media="true">
<?php  //echo 'https://twitter.com/'.$tweet->getScreenName().'/status/'.$tweet->getId();?>
                <div class="raw_tweet">
<?php  // echo json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=https%3A%2F%2Ftwitter.com%2F'.$tweet->getScreenName().'%2Fstatus%2F' . $tweet->getId()))->html;?>
                </div>
				<div id="tweet_media<?php echo $tweet->getId();?>" class="tweet_media">
					<div class="media_box">
						<div id="imgs_wrapper<?php echo $tweet->getId();?>" class="imgs_wrapper" onclick="openPopup('<?php echo $tweet->getId();?>');">
<?php

    foreach ($tweet->getAllMediaURL() as $mediaURL) {
        ?>
                        	<div class="img_wrapper">
								<img src="<?php
								// echo "/osaisen/view/resizeJpeg.php?url=".urlencode($mediaURL);
								echo $mediaURL;
								?>" class="<?php echo ($tweet->isRetweet()) ? "rt" : "own" ; ?>" />
							</div>
<?php
    }
    ?>
                		</div>
						<div id="imgs_wrapper_hidden<?php echo $tweet->getId();?>" class="imgs_wrapper" style="display:none;">
<?php

    foreach ($tweet->getAllMediaURL() as $mediaURL) {
        ?>
                        	<div class="img_wrapper">
								<img src="<?php echo $mediaURL; ?>" />
							</div>
<?php
    }
    ?>
                		</div>
					</div>
				</div>
				<div id="info_wrapper<?php echo $tweet->getId();?>" class="info_wrapper">
					<div class="info">
						<div class="user">
								<?php
								    if(isset($screen_name) && $tweet->isSameScreenName($screen_name)) {
								?>
								    <img src="<?php echo $tweet->getProfileImgURL();?>" />
								<?php
								} else {
								?>
								<a href="/osaisen/timeline/?name=<?php echo $tweet->getName();?>&screen_name=<?php echo $tweet->getScreenName();?>&count=50" target="_self">
									<img src="<?php echo $tweet->getProfileImgURL();?>" />
								</a>
                				<?php } ?>
						</div>
						<div class="tweet_body">
							<div class="user_info">
								<?php
								    if(isset($screen_name) && $tweet->isSameScreenName($screen_name)) {
					                   echo $tweet->getScreenName();
                                    } else { ?>
								<a  href="/osaisen/timeline/?name=<?php echo $tweet->getName();?>&screen_name=<?php echo $tweet->getScreenName();?>&count=50" target="_self">
                				<?php } ?>
                				<?php echo $tweet->getName();?>
                				<span style="color: gray; font-size: small;">@<?php echo $tweet->getScreenName();?></span>
							</a> <span style="color: gray; font-size: small;"><?php echo $tweet->getCreatedAt('Y年n月j日 H:i');?></span><br />
            				</div>
            				<div class="message">
            					<?php echo preg_replace('/<br \/>/', '　', linkHash($tweet->getText()));?>
            				</div>
            			</div>
					</div>
				</div>
				<div id="info_wrapper_hidden_<?php echo $tweet->getId();?>" style="display:none;">
					<div class="info">
						<div class="user">
								<img src="<?php echo $tweet->getProfileImgURL();?>" />
						</div>
						<div class="tweet_body">
							<div class="user_info">
                				<?php echo $tweet->getName();?>
                				<span style="color: gray; font-size: small;">@<?php echo $tweet->getScreenName();?></span>
            				</div>
            				<div class="tweet_info">
								<span style="color: gray; font-size: small;"><?php echo $tweet->getCreatedAt('Y年n月j日 H:i');?></span>
            					<br />
								<?php echo $tweet->getRetCount();?> <span style="color: gray; font-size: small;">件のリツイート</span>
            					　
								<?php echo $tweet->getFavCount();?> <span style="color: gray; font-size: small;">件のいいね</span>
            					<br />
            					<a href="<?php echo $tweet->getTweetURL();?>" target="_blank" style="color:white">＞＞ツイートへ飛ぶ</a>
            				</div>
            				<div class="message">
            					<?php echo linkHash($tweet->getText());?>
            				</div>
            			</div>
					</div>
				</div>
			</div>
<?php
}

$str = ob_get_contents();
ob_end_clean();
// myVarDump($str);

if($tweet_num==0) {
    $str = "";
    $end = -1;
}

$arr  = array(
"timeline" => $str,
"max_id" => $end
);

echo json_encode( $arr );