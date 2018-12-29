<?php
require_once ("./index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<title>二次絵絶対拡散するタイムライン</title>
<link rel="stylesheet" type="text/css"
	href="<?php echo PageContext;?>/css/common.css?<?php echo date('Ymd-Hi'); ?>" />
<link rel="stylesheet" type="text/css"
	href="./timeline.css?<?php echo date('Ymd-Hi'); ?>" />
<script type="text/javascript"
	src="<?php echo PageContext;?>/js/common.js?<?php echo date('Ymd-Hi'); ?>"></script>
<script type="text/javascript"
	src="<?php echo PageContext;?>/js/timeline.js?<?php echo date('Ymd-Hi'); ?>"></script>
<!-- script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script -->
</head>
<body onload="loadTimeLine();">
	<div id="wrapper">
		<h1>二次絵絶対拡散するタイムライン</h1>
		<div id="timeline">
<?php
for ($i = 0; $i<$tweet_num-1; $i++) {
    $tweet = $targetTweets[$i];
    ?>
            <div id="<?php echo $tweet->getId();?>" class="tweet"
				data-has-media="true">
<?php  //echo 'https://twitter.com/'.$tweet->getScreenName().'/status/'.$tweet->getId();?>
                <div class="raw_tweet">
<?php  // echo json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=https%3A%2F%2Ftwitter.com%2F'.$tweet->getScreenName().'%2Fstatus%2F' . $tweet->getId()))->html;?>
                </div>
				<div class="tweet_media">
                  <?php //var_dump($tweet);?>
                  <?php //var_dump($mediaURLs);?>
                </div>
				<div class="tweet_media">
					<div class="media_box">
						<div class="imgs_wrapper" onclick="openPopup(this);">
<?php

    foreach ($tweet->getAllMediaURL() as $mediaURL) {
        ?>
                        	<div class="img_wrapper">
								<img src="<?php echo $mediaURL;?>" style="border-color:<?php echo ($screen_name == $tweet->getScreenName()) ? "lightyellow" : "gray" ; ?>;" />
							</div>
<?php
    }
    ?>
                		</div>
					</div>
				</div>
				<div class="info_wrapper">
					<div class="info">
						<div class="user">
								<?php
								if($screen_name == $tweet->getScreenName()) {
								?>
								    <img src="<?php echo $tweet->getProfileImgURL();?>" />
								<?php
								} else {
								?>
								<a href="./?screen_name=<?php echo $tweet->getScreenName();?>&count=50" target="_self">
									<img src="<?php echo $tweet->getProfileImgURL();?>" />
								</a>
                				<?php } ?>
						</div>
						<div class="tweet_body">
							<div class="user_info">
								<?php
								    if($screen_name == $tweet->getScreenName()) {
					                   echo $tweet->getScreenName();
                                    } else { ?>
								<a  href="./?screen_name=<?php echo $tweet->getScreenName();?>&count=50" target="_self">
                				<?php } ?>
                				<?php echo $tweet->getName();?>
                				<span style="color: gray; font-size: small;">@<?php echo $tweet->getScreenName();?></span>
							</a> <span style="color: gray; font-size: small;"><?php echo $tweet->getCreatedAt('Y年n月j日 H:i');?></span><br />
            				</div>
            				<div class="message">
            					<?php echo preg_replace('/<br \/>/', '　', $tweet->getText());?>
            				</div>
            			</div>
					</div>
				</div>
			</div>
<?php
}
// var_dump($id_hisoty);
?>
    	</div>
		<div id="navigation_menu">
			<div id="menu_wrapper">
<?php if($isBack) {?>
			<div id="next" class="button">
					<a
						href="./?<?php echo $backQueryString;?>">◀</a>
				</div>
				　　<?php echo $tweet_num;?> / <?php echo $calledNum;?>*200
<?php } else { ?>
			<div id="next" class="button" style="color: gray;">◀</div>
<?php } ?>
			　　　　
			<div id="back" class="button">
					<a
						href="./?<?php echo $nextQueryString;?>">▶</a>
				</div>
			</div>
		</div>
		<div id="bottom_menu">
			<div id="toHome" class="button">
				<a href="<?php echo PageContext;?>/">&#x1f3e0;</a>
			</div>

			<div id="followerList" class="button">
				<a href="/osaisen/followers/?screen_name=<?php echo $screen_name;?>&reset=true" target="_blank">Ｆ</a>
			</div>

			<div id="toTop" class="button">
				<a href="./?screen_name=<?php echo $screen_name;?>&reset=true">&#x1F504;</a>
			</div>

			<div id="toTop" class="button" onclick="toTop('wrapper');">▲</div>

			<!-- div id="img_button" class="button" onclick="imgTL();"><img src="<?php echo PageContext;?>/imgs/media.png" /></div -->
			<!-- div id="tweet_button" class="disabled_button" onclick="normalTL();"><img src="<?php echo PageContext;?>/imgs/Twitter-icon.png" /></div  -->
		</div>
	</div>
	<div id="dummy"></div>
	<div id="base" class="parent">
		<div class="tweet">
			<div class="raw_tweet"></div>
			<div class="media"></div>
			<div class="button">メディアを開く</div>
		</div>
	</div>
	<div id="popup"></div>
	<div id="closePopup" onclick="closePopup();"
		ontouchstart="closePopup();">
		<span>Close</span>
	</div>
</body>
</html>