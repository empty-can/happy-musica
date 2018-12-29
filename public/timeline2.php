<?php
include './logic/timeline2.php';
include '../lib/parts/header.php';
?>
<title>二次絵絶対拡散するタイムライン</title>
<link rel="stylesheet" type="text/css" href="<?php echo PageContext;?>/css/timeline.css?<?php echo date('Ymd-Hi'); ?>" />
<script type="text/javascript" src="<?php echo PageContext;?>/js/timeline.js?<?php echo date('Ymd-Hi'); ?>"></script>
<!-- script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 -->
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
			getRequest(tweetIds[i]);
		}

		//console.log("★ tweetsAndMedia:\t"+JSON.stringify(tweetsAndMedia));
		//tweetsAndMedia = JSON.parse(js_array['tweets']);
		//console.log("★ tweets:\t"+JSON.stringify(tweetsAndMedia));
		//tweets = Object.assign(tweets, js_array['tweetIds']);
		//console.log("★ tweets:\t"+JSON.stringify(tweetsAndMedia));
		//console.log("★ js_array:\t"+js_array['tweets']);
		//console.log("★ tweetsAndMedia:\t"+JSON.stringify(tweetsAndMedia));
		//console.log("★ media:\t"+Object.keys(tweetsAndMedia));
		//console.log("★ tweetsAndMedia:\t"+JSON.parse(tweetsAndMedia));
		//console.log("☆ js_array:\t"+js_array['lastTweetId']);
		//console.log("♥ js_array:\t"+js_array['tweetIds']);
		//console.log("☆ lastTweetId:\t"+lastTweetId);
		//console.log("★ lastTweetId:\t"+lastTweetId);
	}

	request.open("GET", "./ajax_parts/getNextTweet.php?count="+count, true);
	request.send();
}


// 次のツイート一覧を読み込む
function nextLoad() {

	var request = new XMLHttpRequest();

	request.onload = function(e) {
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
			getRequest(tweetIds[i]);
		}
	}

	// console.log("getRequest: ./ajax_parts/getNextTweet.php?count="+count+"&lastTweetId="+lastTweetId);

	request.open("GET", "./ajax_parts/getNextTweet.php?count="+count+"&lastTweetId="+lastTweetId, true);

	request.send();
}

</script>
</head>
<body onload="loadTimeLine();">
	<h1>二次絵絶対拡散するタイムライン</h1>
	<p class="top">
		<a href="../">トップページ</a><br>
	</p>
	<div id="media"></div>
	<div id="base" class="parent">
    	<div class="tweet_frame">
    		<div class="tweet"></div>
    		<div class="album_button"><button>メディアを開く</button></div>
    	</div>
	</div>
	<div id="timeline"></div>
	<p style="z-index: 100;" class="bottom">
		<button onclick="clearMedia();" ontouchend="clearMedia();">×</button>
		　　
		<button onclick="nextLoad();">▼</button>
		　　　　　
	</p>
</body>
</html>