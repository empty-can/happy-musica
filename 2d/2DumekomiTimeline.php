<?php
require_once ("../lib/init.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>二次絵絶対拡散するタイムライン</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<style type="text/css">
<!--
.tweet {
    margin:auto;
    float:left;
    width:24%;
    transform:scale(1.0);
    border: 1px dotted gray;
}

.error {
    background-color:red;
}

.picture {
    border: dotted 1px #333;
    display:block;
    font-size:2em;
    position: absolute;
    bottom:0px;
    right:0px;
}
-->
</style>
<link href="../css/modaal.min.css" rel="stylesheet">
<script src="../js/jquery.js"></script>
<script src="../js/modaal.min.js"></script>
<script async src="https://platform.twitter.com/widgets.js"
	charset="utf-8">
</script>
</head>
<body>
<?php
$filename = pathinfo(__FILE__, PATHINFO_FILENAME);
include './2Dheader.php';

printErrorMessages("color:red;font-weight:bold;");

$connection = getTwitterConnection();
// 横島ボットのフォロワー一覧
$oObj = $connection->get("statuses/user_timeline", [
    "screen_name" => "orenoyome",
    "count" => "52"
]);

$counter = (int)0;
foreach ($oObj as $tweet) {
    echo '<!-- https://twitter.com/orenoyome/status/'.$tweet->id.' -->';

    if (!isset($tweet->entities)) {
        ?>
		<div class="tweet error">
			残念、中身の無いツイートでした。<br>
			<!-- <?php var_dump($tweet);?> -->
			<?php echo json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($url)))->html;?><br>
		</div>
        <?php
        continue;
    }

    if (!isset($tweet->retweeted_status)) {
        ?>
		<div class="tweet error">
			リツイートではなく普通のツイートでした。<br>
			<!-- <?php var_dump($tweet);?> -->
			<?php echo json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($url)))->html;?><br>
		</div>
        <?php
        continue;
    }

    $retweet = $tweet->retweeted_status;
    $user = $retweet->user;

    if (isset($retweet->entities)) {
        $entities = $tweet->entities;
        if (isset($entities->media)) {
            foreach ($entities->media as $media) {
                ?>
		<div class="tweet">
			<!-- <?php //var_dump($media);?> -->
			<blockquote class="twitter-tweet" data-lang="ja">
				<p lang="ja" dir="ltr">
				<?php echo nl2br(cutURL($retweet->text));?> <a href="<?php echo $media->url;?>"><?php echo $media->display_url;?></a>
				</p>
			&mdash; <?php echo $user->name;?> (@<?php echo $user->screen_name;?>) <a href="https://twitter.com/<?php echo $user->screen_name;?>/status/<?php echo $media->source_status_id;?>?ref_src=twsrc%5Etfw"><?php echo date ("Y年n月j日", strtotime($retweet->created_at));?></a>
			</blockquote>
			<a href="<?php echo $media->media_url_https;?>" class="modal picture">&#8599;</a>
		</div>
            	<?php
            }
        } else if(isset($retweet->entities->media)) {
            foreach ($retweet->entities->media as $media) {
                ?>
		<div class="tweet">
			<!-- <?php //var_dump($media);?> -->
			<?php $url = preg_replace('/\/photo\/[0-9]+$/', "", $media->expanded_url);?>
			<?php echo json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($url)))->html;?>
			<a href="<?php echo $media->media_url_https;?>" class="modal picture">&#8599;</a>
		</div>
            	<?php
            }
        } else {
            ?>
		<div class="tweet error">
			残念メディアが無いようです。<br>
			<!-- <?php var_dump($tweet);?> -->
            <?php $url = preg_replace('/\/photo\/[0-9]+$/', "", $media->expanded_url); ?>
			<?php echo json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($url)))->html;?><br>
		</div>
        <?php
        }
    } else if($tweet->entities) {
            foreach ($entities->media as $media) {
            ?>
		<div class="tweet">
			<blockquote class="twitter-tweet" data-lang="ja">
				<p lang="ja" dir="ltr">
				<?php echo nl2br(cutURL($retweet->text));?> <a href="<?php echo $media->url;?>"><?php echo $media->display_url;?></a>
				</p>
			&mdash; <?php echo $user->name;?> (@<?php echo $user->screen_name;?>) <a href="https://twitter.com/<?php echo $user->screen_name;?>/status/<?php echo $media->source_status_id;?>?ref_src=twsrc%5Etfw"><?php echo date ("Y年n月j日", strtotime($retweet->created_at));?></a>
			</blockquote>
		</div>
            <?php
            }
    } else {
        ?>
		<div class="tweet error">
			残念、リツイートにエンティティがありませんでした。<br>
			<!-- <?php var_dump($retweet);?> -->
			<?php echo json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($url)))->html;?><br>
		</div>
        <?php
    }

    $counter+=(int)1;

    if ($counter >= 4) {
        echo '<div style="clear: both"></div>';
        $counter = (int)0;
    }
}
?>
	<div style="clear: both"></div>
	<p>
		<a href="./" target="_self">トップページページ</a>
	</p>
<script type="text/javascript">
$('.modal').modaal({
	type: 'image',	// コンテンツのタイプを指定
	animation_speed: '500', 	// アニメーションのスピードをミリ秒単位で指定
	background: '#333',	// 背景の色を白に変更
	overlay_opacity: '0.75',	// 背景のオーバーレイの透明度を変更
	fullscreen: 'true',	// フルスクリーンモードにする
	background_scroll: 'true',	// 背景をスクロールさせるか否か
	loading_content: 'Now Loading, Please Wait.'	// 読み込み時のテキスト表示
});
</script>
</body>
</html>