<?php
require_once ("../lib/init.php");
require_once ("../lib/tweet/tweetUtils.php");
require_once ("./auth.php");

$count = getGetParam("count", "10");

setSessionParam("api", "statuses/user_timeline");
setSessionParam("isPublic", true);

$param = array(
    "screen_name" => "orenoyome"
    , "count" => $count
);

setSessionParam("param", $param);


// 横島ボットのリツイート一覧
$oObj = getTwitterConnection()->get(getSessionParam("api"), $param);
$tweetList = getTweetList($oObj, 0);

$lastTweetId = $tweetList['lastTweetId'];
$tweetIdList = $tweetList['tweetIds'];
$listSize = count($tweetIdList);

include '../lib/parts/header.php';
?>
<title>二次絵絶対拡散するタイムライン</title>
<link rel="stylesheet" type="text/css" href="<?php echo PageContext;?>/css/orenoyome.css?<?php echo date('Ymd-Hi'); ?>" />
<script type="text/javascript" src="<?php echo PageContext;?>/js/orenoyome.js?<?php echo date('Ymd-Hi'); ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
var lastTweetId = '<?php echo $lastTweetId;?>';
var count = <?php echo $count; ?>;

var tweetsAndMedia = {};
var tweets = new Array(<?php echo $listSize; ?>);
<?php
    $counter = (int) 0;
    foreach ($tweetIdList as $tweetURL) {
?>
tweets[<?php echo $counter; ?>] = `<?php echo $tweetURL; ?>`;
<?php
        $counter += (int) 1;
    }
?>

function loadTimeLine() {
	var request = new XMLHttpRequest();

	request.onload = function(e) {
		console.log(request.responseText);
		var js_array = JSON.parse(request.responseText);
		var tweetInfo = JSON.parse(js_array['tweets']);
		var tweetIds = Object.keys(tweetInfo).sort(
				function(a,b){
					if( a < b ) return 1;
					if( a > b ) return -1;
					return 0;
				}
		);

		lastTweetId = tweetIds[tweetIds.length-1];
		tweetsAndMedia = Object.assign(tweetsAndMedia, tweetInfo);

		createDivs(tweetIds);

		for (var i in tweetIds) {
			getRequest(tweetIds[i], 'orenoyome');
		}
	}

	request.open("GET", "./ajax_parts/getNextTweet.php?count="+count, true);
	request.send();
}


// 次のツイート一覧を読み込む
function nextLoad() {

	var request = new XMLHttpRequest();

	request.onload = function(e) {
		//console.log(request.responseText);
		var js_array = JSON.parse(request.responseText);
		var tweetInfo = JSON.parse(js_array['tweets']);
		var tweetIds = Object.keys(tweetInfo).sort(
				function(a,b){
					if( a < b ) return 1;
					if( a > b ) return -1;
					return 0;
				}
		);

		lastTweetId = tweetIds[tweetIds.length-1];
		tweetsAndMedia = Object.assign(tweetsAndMedia, tweetInfo);

		createDivs(tweetIds);

		for (var i in tweetIds) {
			//console.log("loadTimeLine"+tweets[i]);
			getRequest(tweetIds[i], 'orenoyome');
		}
	}

	// console.log("getRequest: ./ajax_parts/getNextTweet.php?count="+count+"&lastTweetId="+lastTweetId);

	request.open("GET", "./ajax_parts/getNextTweet.php?count="+count+"&lastTweetId="+lastTweetId, true);

	request.send();
}

</script>
</head>
<body onload="loadTimeLine();">
	<div id="wrapper">
    	<h1>二次絵絶対拡散するタイムライン</h1>
    	<div id="base" class="parent">
        	<div class="tweet_frame">
        		<div class="tweet"></div>
        		<div class="button">メディアを開く</div>
        	</div>
    	</div>
    	<div id="timeline"></div>
	</div>
	<div id="bottom_menu">
		<div id="toTop" ontouchstart="" class="button" ontouchstart="" onclick="toTop('wrapper');">&#x1f53a;</div>
		<div id="toHome" ontouchstart="" class="button"><a href="/osaisen/">&#x1f3e0;</a></div>
		<div id="closeMedia" ontouchstart="" class="disabled_button" onclick="closeMedia();">&#x2716;</div>
		<div id="next"class="button" ontouchstart="" onclick="nextLoad();">&#x1f53b;</div>
	</div>
    <div id="media"></div>
	<div id="dummy"></div>
</body>
</html>