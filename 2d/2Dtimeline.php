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
<style>
</style>
<link href="../css/lity.min.css" rel="stylesheet">
<script src="../js/jquery.js"></script>
<script src="../js/lity.min.js"></script>
</head>
<body>
<?php
$filename = pathinfo(__FILE__, PATHINFO_FILENAME);
include './2Dheader.php';

printErrorMessages("color:red;font-weight:bold;");
?>
<table border="1">
<?php

$connection = getTwitterConnection();
// 横島ボットのフォロワー一覧
$oObj = $connection->get("statuses/user_timeline", [
    "screen_name" => "orenoyome",
    "count" => "200"
]);

// var_dump($oObj);

foreach ($oObj as $tweet) {
    // $entities = $tweet->entities;
    // $extended_entities = $tweet->extended_entities;
    $retweet = $tweet->retweeted_status;
    //     var_dump($retweet);
    $user = $retweet->user;
    ?>
            <tr>
			<td><img src="<?php echo $user->profile_image_url_https;?>"
				alt="<?php echo $user->name;?>" />
            <?php echo $user->name;?>　
            @<?php echo $user->screen_name;?>
            	</td>
		</tr>
		<tr>
			<td>
            <?php echo $retweet->id;?>
            /
            <?php echo $retweet->text;?>
            	</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td>
							<table>
								<!--  tr>
									<td>拡張エンティティ</td>
								</tr -->
								<tr>
            	<?php
    if ((isset($retweet->extended_entities))) {
        $extended_entities = $retweet->extended_entities;
        foreach ($extended_entities->media as $extended_media) {
            ?>

            	<td>
										<!-- <?php var_dump($extended_media); ?> --> <a
										href="<?php echo $extended_media->media_url_https;?>"
										data-lity><img
											src="<?php echo $extended_media->media_url_https;?>"
											alt="<?php echo $retweet->text;?>" height="250" /></a><br> <a
										href="<?php echo $extended_media->media_url_https;?>"
										target="_blank">↗</a>
									</td>
            	<?php
        }
    }
    ?>
            </tr>
							</table>
						</td>
						<td style="vertical-align: bottom;">
							<table>
								<tr>
									<td>エンティティ</td>
								</tr>
								<tr>
            	<?php
    if (isset($retweet->entities) && isset($retweet->entities->media)) {
        $entities = $retweet->entities;
        foreach ($entities->media as $media) {
            ?>
            	<?php //var_dump($media); ?>


									<td><a href="<?php echo $media->media_url_https;?>"
										target="_blank" data-lity> <img
											src="<?php echo $media->media_url_https;?>"
											alt="<?php echo $retweet->text;?>" height="50" />
									</a><br> <a href="<?php echo $media->media_url_https;?>"
										target="_blank">↗</a></td>
            	<?php
        }
    }
    ?>
            </tr>
							</table>

				</table>
			</td>
		</tr>
            <?php
}
?>
    </table>
	<p>
		<a href="./" target="_self">トップページページ</a>
	</p>

</body>
</html>