var screen_name = '';
var max_id = 0;
var count = 0;
var api = '';
var showRT = true;

function switchShowTweet() {
	if(showRT) {
		hideReTweet();
		showRT = false;
	} else {
		showReTweet();
		showRT = true;
	}
}

function showReTweet() {
	var all_retweets = document.getElementsByClassName('retweet');

	for (var i = 0; i < all_retweets.length; i++) {
		var tmp = all_retweets[i];
		tmp.style.display = 'block';
	}
}

function hideReTweet() {
	var all_retweets = document.getElementsByClassName('retweet');

	for (var i = 0; i < all_retweets.length; i++) {
		var tmp = all_retweets[i];
		tmp.style.display = 'none';
	}
}

setInterval( function() {
	if(document.getElementById("bottom") != null) {
		var bottom = document.getElementById("bottom")
		var rect =bottom.getBoundingClientRect().top;
		var bh = document.getElementById("body").clientHeight ;

		if((bh-rect)>0) {
//			bottom.style.color = 'white';
//			bottom.style.textAlign = 'center';
//			bottom.innerHTML = '画面の下まで来ました。';

			callAjax();
		}
	}
}, 1000 ) ;

var sleepInterval = 500;

function loadTimeLine() {
	closePopup();
}

function openPopup(target) {
	setTimeout(function(){hidePopup()},0);
	setTimeout(function(){visiblePopup(target)},sleepInterval);
}

var visiblePopup = function(targetId) {
	document.getElementById('wrapper').style.overflow = 'hidden';

	var popup = document.getElementById('popup');
	var ndList = document.getElementById('imgs_wrapper_hidden'+targetId).childNodes;

	for (var i = 0; i < ndList.length; i++) {
		var tmp = ndList[i];

		if(tmp.className=='img_wrapper') {
			popup.appendChild(tmp.lastElementChild.cloneNode(true));
		}
	}

	var tweetInfo = document.getElementById('info_wrapper_hidden_'+targetId).cloneNode(true);
	tweetInfo.id = 'info_wrapper_hidden';
	tweetInfo.style.display = 'block';
	tweetInfo.style.height = 'auto';
//	tweetInfo.style.paddingBottom = '25vh';

	var tweet_body = tweetInfo.firstElementChild.lastElementChild.firstElementChild;
//	tweet_body.style.overflow = 'hidden'
	tweet_body.style.whiteSpace = 'normal';

	var message = tweetInfo.firstElementChild.lastElementChild.lastElementChild;
//	message.style.overflow = 'hidden'
	message.style.whiteSpace = 'normal';
//	message.style.backgroundColor = 'lightgray';
	message.style.paddingBottom = '20vh';

	popup.appendChild(tweetInfo);

	popup.style.zIndex = '100';
	popup.style.display = 'block';
//	popup.style.paddingBottom = '25vh';
	popup.scrollTop = 0;

	var closePopup = document.getElementById('closePopup');
	closePopup.style.zIndex = '100';
	closePopup.style.display = 'flex';

}

function closePopup() {
	setTimeout(hidePopup,sleepInterval);
}

var hidePopup = function() {
	document.getElementById('wrapper').style.overflow = 'auto';

	var popup = document.getElementById('popup');

	var child = popup.firstElementChild;

	while(child!=null) {
		popup.removeChild(child);
		child = popup.firstElementChild;
	}

	popup.style.zIndex = '0';
	popup.style.display = 'none';

	var closePopup = document.getElementById('closePopup');
	closePopup.style.zIndex = '0';
	closePopup.style.display = 'none';
}

//画像ツイートにする
function imgTL() {
	document.body.style.backgroundColor = '#000';

	var all_tweets = document.getElementsByClassName('tweet');
	var raw_tweets = document.getElementsByClassName('raw_tweet');
	var tweet_media = document.getElementsByClassName('tweet_media');

	for (var i = 0; i < raw_tweets.length; i++) {
		raw_tweets[i].style.display = 'none';
	}

	for (var i = 0; i < all_tweets.length; i++) {
		if(all_tweets[i].dataset.hasMedia=='false') {
			all_tweets[i].style.display = 'none';
		}
	}

	for (var i = 0; i < tweet_media.length; i++) {
		tweet_media[i].style.display = 'inline-flex';
	}

	document.getElementById('img_button').style.display = 'none';
	document.getElementById('tweet_button').style.display = 'inline-block';
}

//画像ツイートにする
function normalTL() {
	document.body.style.backgroundColor = '#FFF';

	var all_tweets = document.getElementsByClassName('tweet');
	var raw_tweets = document.getElementsByClassName('raw_tweet');
	var tweet_media = document.getElementsByClassName('tweet_media');

	for (var i = 0; i < all_tweets.length; i++) {
		all_tweets[i].style.display = 'block';
	}

	for (var i = 0; i < raw_tweets.length; i++) {
		raw_tweets[i].style.display = 'block';
	}

	for (var i = 0; i < tweet_media.length; i++) {
		tweet_media[i].style.display = 'none';
	}

	document.getElementById('tweet_button').style.display = 'none';
	document.getElementById('img_button').style.display = 'inline-block';
}


//先にツイートを入れるガワだけ作る関数
function createDivs(tweetIdList) {
	// id を降順にソート
	tweetIdList.sort(
			function(a,b){
				if( a < b ) return 1;
				if( a > b ) return -1;
				return 0;
			}
	);

	var parentDiv = document.getElementById("timeline");

	for (var i in tweetIdList) {
		if(null == document.getElementById(tweetIdList[i])) {	// 既に div が存在する場合は何もしない
			var targetDiv = document.getElementById("base").cloneNode(true);
			targetDiv.id = tweetIdList[i];
			parentDiv.appendChild(targetDiv);
		}
	}
}

/**
 * ツイートの div を生成
 *
 * @param tweetId
 * @param html
 * @returns
 */
function reflectTweet(tweetId, html) {

	var targetDiv = document.getElementById(tweetId);

	var tweetElement = targetDiv.children[0].children[0];
	var alubmElement = targetDiv.children[0].children[1];

	// tweet データの作成
	tweetElement.innerHTML = html;

	// twitter の js を挿入しないと、HTMLからTwitterの埋め込みウィジェットにならない。
	var newScr = document.createElement("script");
	newScr.type = 'text/javascript';
	newScr.src = 'https://platform.twitter.com/widgets.js';
	newScr.charset = 'utf-8';

	tweetElement.appendChild(newScr);

	alubmElement.addEventListener("click", function() {
		showMedias(tweetId); //関数「showMedias」に引数を指定
		}, false);
	alubmElement.addEventListener("touchend", function() {
		showMedias(tweetId); //関数「showMedias」に引数を指定
		}, false);

	targetDiv.style.visibility = 'visible';
}

/**
 * ツイートに関連するメディアを表示
 *
 * @param tweetId
 * @returns
 */
function showMedias(tweetId) {
	console.log(tweetsAndMedia[tweetId]);

	var mediaElement = document.getElementById('media');
	mediaElement.style.visibility = 'visible';
	mediaElement.style.zIndex = '20';

	mediaElement.innerHTML = tweetsAndMedia[tweetId].length;

	for(var i=0; i<tweetsAndMedia[tweetId].length; i++) {
		var img = document.createElement('img');
		img.src = tweetsAndMedia[tweetId][i];
		mediaElement.appendChild(img);
	}
}

/**
 *
 * @param target
 * @returns
 */
function clearMedia() {

	var mediaElement = document.getElementById('media');

	while (mediaElement.firstChild) {
		mediaElement.removeChild(mediaElement.firstChild);
	}

	mediaElement.style.visibility = 'hidden';
	mediaElement.style.zIndex = '0';
}

/**
 * 指定の id のツイートを取得
 *
 * @param tweetId
 * @returns
 */
function getRequest(tweetId) {
	var request = new XMLHttpRequest();

	request.onload = function(e) {
		reflectTweet(tweetId, request.responseText);
	}

	request.open("GET", "./ajax_parts/getTweetHTML.php?tweetId="+tweetId+"screen_name="+$screen_name, true);

	request.send();
}


onload = function(){

	//タッチイベント登録
	document.addEventListener('touchstart', touchStart, false);//タップされた瞬間
	document.addEventListener('touchmove', touchMove, false);//指を動かしている
	document.addEventListener('touchend', touchEnd, false);//指が画面から離れた
	addEventListener('touchcancel', onTouchCancel, false);
}

// 長押し以外の場合のタイマーのキャンセル
function touchMove(){}
function touchEnd(){}
function onTouchCancel(){}
function clearFunction(){}


var wait = false;

function callAjax() {
	if(wait==true)
		return;

	bottom.style.fontSize = '5em';
	bottom.innerHTML = '&#x1F504;';

	wait = true;
    $.ajax({
        url : "/osaisen/timeline/ajax.php?count="+count+"&max_id="+max_id,
        type : "POST",
        dataType:"json",
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("ajax通信に失敗しました");
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
			bottom.innerHTML = '';
        	wait = false;
        },
        success : function(response) {
            console.log("ajax通信に成功しました");
//            console.log(response[0]);
            console.log(response['max_id']);
//            $('#timeline').after('<p>'+response['max_id']+'</p>');
//          console.log(response['timeline']);
            max_id = response['max_id'];

            if(max_id<0) {
            	bottom.style.fontSize = '1em';
    			bottom.innerHTML = '最後まで来ました。';
            	wait = true;
            } else {
                $('#timeline').append(response['timeline']);

            	if(showRT) {
            		showReTweet();
            	} else {
            		hideReTweet();
            	}

    			bottom.innerHTML = '';
            	wait = false;
            }
        }
    });
}














//ツイートの div を生成
function constructDivOld(tweetId, html) {
	// var parentDiv = document.getElementById("timeline");

	var targetDiv = document.getElementById(tweetId);
	// targetDiv.id = tweetId;

	var tweetElement = targetDiv.children[0].children[0];
	var alubmElement = targetDiv.children[0].children[1];

	// tweet データの作成
	tweetElement.innerHTML = html;
	// tweetElement.style.width = '100%';
	// tweetElement.style.marginRight = 'auto';
	// tweetElement.style.marginLeft = 'auto';

	// twitter の js を挿入しないと、HTMLからTwitterの埋め込みウィジェットにならない。
	var newScr = document.createElement("script");
	newScr.type = 'text/javascript';
	newScr.src = 'https://platform.twitter.com/widgets.js';
	newScr.charset = 'utf-8';

	tweetElement.appendChild(newScr);

	//console.log("☆targetDiv.class:\t"+targetDiv.className);

	//alubmElement.style.position = 'absolute';
	//alubmElement.style.right = '0';
	//alubmElement.style.bottom = '0';

	targetDiv.style.visibility = 'visible';

	//parentDiv.appendChild(targetDiv);
}