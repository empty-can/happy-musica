<?php
setGetParam("count", $count);

include dirname(__FILE__).'/header.php';
?>
<title><?php echo $title; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo PageContext;?>/css/orenoyome.css?<?php echo date('Ymd-Hi'); ?>" />
<script type="text/javascript" src="<?php echo PageContext;?>/js/orenoyome.js?<?php echo date('Ymd-Hi'); ?>"></script>
<!-- script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script -->
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

var screenNames = JSON.parse('<?php echo json_encode($screenNames); ?>');

function loadTimeLine() {
	var request = new XMLHttpRequest();

	request.onload = function(e) {
		// console.log(request.responseText);

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
		// sconsole.log('!!!'+JSON.stringify(tweetInfo));
		tweetsAndMedia = Object.assign(tweetsAndMedia, tweetInfo);
		// console.log("×"+JSON.stringify(tweetsAndMedia));
		createDivs(tweetIds);
		// console.log(tweetIds);

		for (var i in tweetIds) {
			// console.log(screenNames[tweetIds[i]]);
			getRequest(tweetIds[i], screenNames[tweetIds[i]]);
		}
	}

	// console.log("getRequest: <?php echo PageContext;?>/parts/getNextTweet.php?count="+count);

	request.open("GET", "<?php echo PageContext;?>/parts/getNextTweet.php?count="+count, true);
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
			// console.log("loadTimeLine"+tweets[i]);
			// console.log(screenNames[tweetIds[i]]);
			getRequest(tweetIds[i], screenNames[tweetIds[i]]);
		}
	}

	// console.log("getRequest: <?php echo PageContext;?>/parts/getNextTweet.php?count="+count+"&lastTweetId="+lastTweetId);

	request.open("GET", "<?php echo PageContext;?>/parts/getNextTweet.php?count="+count+"&lastTweetId="+lastTweetId, true);

	request.send();
}

</script>
</head>
<body onload="loadTimeLine();">
	<div id="wrapper">
    	<h1><?php echo $title; ?></h1>
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