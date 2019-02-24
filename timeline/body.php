<?php
function renderTweet($tweet) {
ob_start();
?>
            <div id="<?php echo $tweet->getId();?>"
				class="tweet <?php echo ($tweet->isRetweet()) ? "retweet" : "owntweet" ; ?>"
				data-has-media="true">
<?php  //echo 'https://twitter.com/'.$tweet->getScreenName().'/status/'.$tweet->getId();?>
                <div class="raw_tweet">
<?php  // echo json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=https%3A%2F%2Ftwitter.com%2F'.$tweet->getScreenName().'%2Fstatus%2F' . $tweet->getId()))->html;?>
                </div>
				<div id="tweet_media<?php echo $tweet->getId();?>"
					class="tweet_media">
					<div class="media_box">
						<div id="imgs_wrapper<?php echo $tweet->getId();?>"
							class="imgs_wrapper"
							onclick="openPopup('<?php echo $tweet->getId();?>');">
<?php

    foreach ($tweet->getAllMediaURL() as $mediaURL) {
        ?>
                        	<div class="img_wrapper">
								<img
									src="<?php
        // echo "/osaisen/view/resizeJpeg.php?url=".urlencode($mediaURL);
        echo $mediaURL;
        ?>"
									class="<?php echo ($tweet->isRetweet()) ? "rt" : "own" ; ?>" />
							</div>
<?php
    }
    ?>
                		</div>
						<div id="imgs_wrapper_hidden<?php echo $tweet->getId();?>"
							class="imgs_wrapper" style="display: none;">
<?php
    if ($tweet->isVideoTweet()) {
        ?>
    <div class="img_wrapper">
								<video src="<?php echo $tweet->getVideoURL(); ?>"
									poster="<?php echo $tweet->getAllMediaURL()[0]; ?>" controls>
								<p>動画を再生するには、videoタグをサポートしたブラウザが必要です。</p>
								</video>

							</div>
<?php
    } else {
        foreach ($tweet->getAllMediaURL() as $mediaURL) {
            ?>
                        	<div class="img_wrapper">
								<img src="<?php echo $mediaURL; ?>" />
							</div>
<?php
        }
    }
    ?>
                		</div>
					</div>
				</div>
				<div id="info_wrapper<?php echo $tweet->getId();?>"
					class="info_wrapper">
					<div class="info">
						<div class="user" style="position:relative;" onclick="popListMenu('<?php echo $tweet->getScreenName();?>');">
							<?php if(isset($members[$tweet->getScreenName()])) { ?><div class="star">★</div><?php } ?>
							<img src="<?php echo $tweet->getProfileImgURL();?>"
								/>
								<!-- onclick="addFollowee(' echo $tweet->getScreenName();', 'echo $osaisenListId;')" / -->
						</div>
						<div class="tweet_body">
							<div class="user_info">
								<a
									href="/osaisen/timeline/?name=<?php echo $tweet->getName();?>&screen_name=<?php echo $tweet->getScreenName();?>&count=50"
									target="_blank">
                					<?php echo $tweet->getName();?>
                					<span style="color: gray; font-size: small;">@<?php echo $tweet->getScreenName();?></span>
								</a> <span style="color: gray; font-size: small;"><?php echo $tweet->getCreatedAt('Y年n月j日 H:i');?></span><br />
							</div>
							<div class="message">
            					<?php echo preg_replace('/<br \/>/', '　', linkHash($tweet->getText()));?>
            				</div>
						</div>
						<div class="toggle" onclick="toggleInfo('<?php echo $tweet->getId();?>');">
        					▼
        				</div>
					</div>
				</div>
				<div id="info_wrapper_hidden_<?php echo $tweet->getId();?>"
					style="display: none;">
					<div class="info">
						<div class="user" style="position:relative;" onclick="popListMenu('<?php echo $tweet->getScreenName();?>');">
							<?php if(isset($members[$tweet->getScreenName()])) { ?><div class="star">★</div><?php } ?>
							<img src="<?php echo $tweet->getProfileImgURL();?>"
								/>
								<!-- onclick="addFollowee('echo $tweet->getScreenName();', 'echo $osaisenListId;')" / -->
						</div>
						<div class="tweet_body">
							<div class="user_info">
								<a
									href="/osaisen/timeline/?name=<?php echo $tweet->getName();?>&screen_name=<?php echo $tweet->getScreenName();?>&count=50"
									target="_blank">
                					<?php echo $tweet->getName();?>
                					<span style="color: gray; font-size: small;">@<?php echo $tweet->getScreenName();?></span>
								</a>
							</div>
							<div class="tweet_info">
								<span style="color: gray; font-size: small;"><?php echo $tweet->getCreatedAt('Y年n月j日 H:i');?></span>
								<br />
								<?php echo $tweet->getRetCount();?> <span
									style="color: gray; font-size: small;">件のリツイート</span>
            					　
								<?php echo $tweet->getFavCount();?> <span
									style="color: gray; font-size: small;">件のいいね</span> <br /> <a
									href="<?php echo $tweet->getTweetURL();?>" target="_blank"
									style="color: white">ツイートへ飛ぶ↗️</a>
							</div>
							<div class="message">
            					<?php echo linkHash($tweet->getText());?>
            				</div>
						</div>
					</div>
				</div>
			</div>
<?php
$str = ob_get_contents();
ob_end_clean();

return $str;
}