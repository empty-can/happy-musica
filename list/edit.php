<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");

$title = getGetParam('title', '');
$type = getGetParam('type', '');

$listId = getGetParam('id', '');
$targets = getGetParam('targets', '');
$count = getGetParam('count', '500');

if(strcmp($type,'delete')==0) {

	$api = 'lists/members/destroy';
	$max_id = 0;

	foreach($targets as $target) {
		$params = array(
		    "list_id" => $listId,
		    "user_id" => $target
		);

		$result = postData($accessToken, $accessTokenSecret, $api, $params, $max_id, $count, 5);
	}
}

$result = array();

$api = 'lists/members';
$param = array(
    "list_id" => $listId,
    "count" => $count
);

var_dump(!empty($accessToken));
var_dump(!empty($accessTokenSecret));
var_dump($param);

$members = getTweetObjects($accessToken, $accessTokenSecret, $api, $param);

if (isset($myLists->{'errors'})) {
    echo 'APIの上限を超えたようです。少々お待ちください。';
    exit(0);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<title>リスト <?php echo $title; ?> の編集ページ</title>
<script type="text/javascript">

</script>
</head>
<body id="body">
<h1>リスト <?php echo $title; ?> の編集ページ</h1>
<div style="margin-bottom:10vh;">
	<form method="get" action="/osaisen/list/edit.php">
<?php
if(isset($members->users)) {

	$members = json_decode(json_encode($members), true)['users'];

	function cmp_follower_num( $a , $b) {
		return $a['followers_count'] < $b['followers_count']; //フォロワー数を比較
	}

	usort( $members , "cmp_follower_num" );

	foreach ($members as $member) {
    ?>
        <input type="checkbox" name="targets[]" value="<?php echo $member['id']; ?>" /><img src="<?php echo $member['profile_image_url_https']; ?>" />
        	<a href="/osaisen/timeline/?name=<?php echo $member['name']; ?>&screen_name=<?php echo $member['screen_name']; ?>&count=50" target="_blankf"><?php echo $member['name']; ?>@<?php echo $member['screen_name']; ?></a>↗️
        	<span style="<?php if($member['followers_count']<1000) echo "color:red;" ?>">（フォロワー数：<?php echo $member['followers_count']; ?>）</span></br>
        <?php
	}
} else {
	echo "このリストにはメンバーが居ません。";
}
?>
		<hr />
		</div>
		<div style="text-align:left;position:fixed; bottom:0px;background-color:azure;padding:1em;width:100%;">
			<input type="hidden" name="title" value="<?php echo $title; ?>" />
			<input type="hidden" name="id" value="<?php echo $listId; ?>" />
			<input type="hidden" name="count" value="<?php echo $count; ?>" />
			<button type="submit" name="type" value="delete">選択したユーザをリストから削除</button>
			<br />
			<a href="/osaisen/#your_list">戻る</a>
			<br />
		</div>
	</form>
</body>
</html>