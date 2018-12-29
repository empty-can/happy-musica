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
<title><?php echo webPrint($name) ?>絵 絶対拡散するタイムライン</title>
<link rel="stylesheet" type="text/css"
	href="<?php echo PageContext;?>/css/common.css?<?php echo date('Ymd-Hi'); ?>" />
<link rel="stylesheet" type="text/css"
	href="<?php echo PageContext;?>/view/timeline.css?<?php echo date('Ymd-Hi'); ?>" />
<link rel="stylesheet" type="text/css" media="only screen and (min-width: 1024px)" href="<?php echo PageContext;?>/css/pc_timeline.css?<?php echo date('Ymd-Hi'); ?>"/>
<script type="text/javascript"
	src="<?php echo PageContext;?>/js/common.js?<?php echo date('Ymd-His'); ?>"></script>
<script type="text/javascript"
	src="<?php echo PageContext;?>/js/timeline.js?<?php echo date('Ymd-His'); ?>"></script>
<script type="text/javascript"
	src="<?php echo PageContext;?>/js/jquery.js?<?php echo date('Ymd-His'); ?>"></script>
<!-- script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script -->
</head>
<body id="body" onload="loadTimeLine();">
	<div id="bottom_menu">
		<!-- div id="img_button" class="button" onclick="imgTL();"><img src="<?php echo PageContext;?>/imgs/media.png" /></div -->
		<!-- div id="tweet_button" class="disabled_button" onclick="normalTL();"><img src="<?php echo PageContext;?>/imgs/Twitter-icon.png" /></div  -->

		<div id="toHome" class="button">
			<a href="<?php echo PageContext;?>/">&#x1f3e0;</a>
		</div>

		<div id="toTop" class="button">
			<a href="/osaisen/<?php echo $resetQuery;?>">&#x1F504;</a>
		</div>

		<div id="toTop" class="button" onclick="toTop('wrapper');">▲</div>

		<form id="search" action="/osaisen/search/" method="get"
			style="display: inline-block;">
			<input id="searchText" type="text" name="search" size="40"
				maxlength="726" style="width: 25vw;" value="<?php echo $search;?>" />
		</form>

		<div id="searchButton" class="button">
			<a onclick="document.getElementById('search').submit();">&#x1f50d;</a>
		</div>

		<div id="searchHashButton" class="button">
			<a
				onclick="document.getElementById('searchText').value='#'+document.getElementById('searchText').value;document.getElementById('search').submit();">＃</a>
		</div>

	</div>
	<div id="wrapper">
		<?php echo getRequestParam('errorMessage', ''); ?>
		<?php if(!empty(getRequestParam('errorMessages', ''))) {
		?>
		<div style="font-size:xx-large;color:pink;text-align:center;"><?php echo getRequestParam('errorMessages', ''); ?></div>
		<?php } ?>
		<h1><?php echo webPrint($name) ?>絵 絶対拡散するタイムライン</h1>
		<div id="profile_image">
			<?php if(isset($profile_image_url_https)) { ?><img src="<?php echo $profile_image_url_https;?>" /><?php echo $user_name; } ?>
		</div>
		<div id="timeline">
<?php
for ($i = 0; $i<$tweet_num; $i++) {
    $tweet = $targetTweets[$i];
    ?>
            <div id="<?php echo $tweet->getId();?>" class="tweet"
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
								<img src="/osaisen/view/resizeJpeg.php?url=<?php
								echo urlencode($mediaURL);
								// echo $mediaURL;
								?>" style="border-color:<?php echo (isset($screen_name) && $tweet->isSameScreenName($screen_name)) ? "lightyellow" : "gray" ; ?>;" />
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
<?php } else { ?>
			<div id="next" class="button" style="color: gray;">◀</div>
<?php } ?>
				　　<?php echo $tweet_num;?> / <?php echo $calledNum;?>*<?php echo $maxCount;?>
			　　　　
<?php if($isNext) {?>
			<div id="back" class="button">
					<a
						href="./?<?php echo $nextQueryString;?>">▶</a>
				</div>
			</div>
<?php } else { ?>
			<div id="next" class="button" style="color: gray;">▶</div>
<?php } ?>
	</div>
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
	<div id="dummy"></div>
	<div id="bottom"></div>
</body>
</html>