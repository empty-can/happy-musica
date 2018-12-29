<?php
require_once ("./logic.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<title>二次絵絶対拡散するフォロワーリスト</title>
</head>
<body>
	<div id="wrapper">
		<h1>二次絵絶対拡散するフォロワーリスト</h1>
		<div id="followerlist">
<?php
foreach ($list as $follower) {
    ?>
		<div id="follower">
			<a href="/osaisen/timeline/?screen_name=<?php echo $follower->screen_name;?>&count=50" target="_self">
				<img src="<?php echo $follower->profile_image_url_https;?>" />
			</a><br />
			<div>
			<a href="/osaisen/timeline/?screen_name=<?php echo $follower->screen_name;?>&count=50" target="_self">
				<?php echo $follower->name;?>
				<?php echo $follower->screen_name;?>
			</a>
			</div>
			<div>
				<?php echo $follower->description;?>
			</div>
		</div>
<?php
}
// var_dump($id_hisoty);
?>
</body>
</html>