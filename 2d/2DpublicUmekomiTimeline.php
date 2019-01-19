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
	margin: auto;
	float: left;
	width: 24%;
	transform: scale(1.0);
	border: 1px dotted gray;
}
-->
</style>
<link href="../css/lity.min.css" rel="stylesheet">
<script src="../js/jquery.js"></script>
<script src="../js/lity.min.js"></script>
<script async src="https://platform.twitter.com/widgets.js"
	charset="utf-8"></script>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</head>
<body>
<?php
$filename = pathinfo(__FILE__, PATHINFO_FILENAME);
include './2Dheader.php';

printErrorMessages("color:red;font-weight:bold;");

$connection = getTwitterConnection();
// 横島ボットのリツイート一覧
$oObj = $connection->get("statuses/user_timeline", [
    "screen_name" => "orenoyome",
    "count" => "10"
]);

$counter = (int) 0;

if (isset($oObj)) {
    $html = "";
    foreach ($oObj as $tweet) {
        if (isset($tweet->entities) && isset($tweet->entities->media)) {
            $html = $html . "\r\n" . '<div style="float:left;overflow: scroll; overflow-style: scrollbar;">' . (json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=' . urlencode(getTweetUrlByTweet($tweet))))->html) . "</div>\r\n";
            $counter += (int) 1;

            if ($counter >= (int) 1) {
                echo $html . "<br>\r\n" . '<div style="clear: both"></div>' . "\r\n";
                $html = "";
                $counter = (int) 0;
            }
        }
    }
}

if(!empty($html))
    echo $html . "<br>\r\n" . '<div style="clear: both"></div>' . "\r\n";

?>
	<div style="clear: both"></div>
	<p>
		<a href="./" target="_self">トップページページ</a>
	</p>
</body>
</html>