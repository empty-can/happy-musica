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
	// console.log(tweetsAndMedia[tweetId]);

	var mediaElement = document.getElementById('media');
	mediaElement.style.visibility = 'visible';
	mediaElement.style.zIndex = '20';
	//	console.log("×"+JSON.stringify(tweetsAndMedia));

	// mediaElement.innerHTML = tweetsAndMedia[tweetId].length;
	// console.log('♥Length：'+JSON.stringify(tweetsAndMedia[tweetId]));
	// console.log('▲：'+tweetId);
	// console.log('♥MediaNum：'+JSON.stringify(tweetsAndMedia[tweetId]));

	for(var i=0; i<tweetsAndMedia[tweetId].length; i++) {
		var img = document.createElement('img');

		console.log('★Media：'+tweetsAndMedia[tweetId][i]);
		img.src = tweetsAndMedia[tweetId][i];

		mediaElement.appendChild(img);
	}

	var button = document.getElementById('closeMedia');
	button.classList.remove('disabled_button');
	button.classList.add('button');
}

/**
 *
 * @param target
 * @returns
 */
function closeMedia() {

	var mediaElement = document.getElementById('media');

	while (mediaElement.firstChild) {
		mediaElement.removeChild(mediaElement.firstChild);
	}

	mediaElement.style.visibility = 'hidden';
	mediaElement.style.zIndex = '0';

	var button = document.getElementById('closeMedia');
	button.classList.remove('button');
	button.classList.add('disabled_button');
}

/**
 * 指定の id のツイートを取得
 *
 * @param tweetId
 * @returns
 */
function getRequest(tweetId, screen_name) {
	var request = new XMLHttpRequest();
	var url = "/osaisen/parts/getTweetHTML.php?tweetId="+tweetId+'&screen_name='+screen_name;
	// console.log("URL: "+url);


	request.onload = function(e) {
		reflectTweet(tweetId, JSON.parse(request.responseText));
		//console.log("★responseText: "+JSON.parse(request.responseText));
	}


	request.open("GET", url, true);

	request.send();
}

function toTop(id) {
	document.getElementById(id).scrollTop = 0;
}