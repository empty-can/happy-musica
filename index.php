<?php
require_once ("./lib/init.php");
use Abraham\TwitterOAuth\TwitterOAuth;
include './lib/parts/header.php';

function generateTR ($id, $img, $name, $screenName, $comic="") {
    echo '<tr>';
    echo '<td style="text-align:right;width:56px;"><img class="icon" src="https://pbs.twimg.com/profile_images/'.$id.'/'.$img.'" alt="" /></td>';
    echo '<td style="text-align:left;white-space:nowrap;">　<a href="/osaisen/timeline/?name='.$name.'&screen_name='.$screenName.'&count=50" target="_self">'.$name.'</a></td>';
    if(!empty($comic))
        echo '<td style="text-align:left;white-space:nowrap;"><span style="font-size:0.75em;">『'.$comic.'』</span></td>';
        echo '</tr>';
}

$loginCookieId = getCookieParam("login_cookie_id");

if(!empty($loginCookieId)) {
    $isLogined = isLoginedTamikusa($loginCookieId);

    if($isLogined===true) {
        $account = getLoginedTamikusaAccount($loginCookieId);
        setSessionParam('logined', true);

        if(empty(getSessionParam('user_info',''))) {
            $tamikusaInfo = getTamikusa($account);

            $access_token = $tamikusaInfo[2];
            $access_token_secret = $tamikusaInfo[3];
            $user_connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $access_token, $access_token_secret);
            $userInfo = $user_connection->get('account/verify_credentials');

            setSessionParam('user_info', $userInfo);
            setSessionParam('access_token', $access_token);
            setSessionParam('access_token_secret', $access_token_secret);
        }
    }
}

$userInfo = getSessionParam('user_info');
$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');
?>
<title>二次絵絶対拡散するサイト</title>
<style>
*{
    font-size: 3vh;
}

input {
    margin-bottom: 10px;
}

body {
    // border: solid 1px black;
	width: 100%;
	margin: 0px;
	padding: 0px;
}

h1 {
	width: 100%;
    font-size: 5vh;
	text-align: center;
	margin-left: auto;
	margin-right: auto;
}

#login {
    margin: 1em;
}

#main {
	text-align: center;
}

#login {
	text-align: center;
}

.margin {
	width: 80%;
	margin-right: auto;
	margin-left: auto;
	padding-right: auto;
	padding-left: auto;
}

.img {
	width: 60%;
	max-width: 400px;
	margin-right: auto;
	margin-left: auto;
	padding-right: auto;
	padding-left: auto;
}

img {
    width: 100%;
    height: auto;
}

.square_btn {
	display: inline-block;
	padding: 0.25em 1em;
	text-decoration: none;
	background: #668ad8; /*ボタン色*/
	color: #FFF;
	border-bottom: solid 4px #627295;
	border-radius: 3px;
}

.square_btn:active { /*ボタンを押したとき*/
	-ms-transform: translateY(4px);
	-webkit-transform: translateY(4px);
	transform: translateY(4px); /*下に動く*/
	border-bottom: none; /*線を消す*/
}

.home_btn {
	display: inline-block;
	padding: 0.25em 1em;
	text-decoration: none;
	background: forestgreen; /*ボタン色*/
	color: #FFF;
	border-bottom: solid 4px mediumseagreen;
	border-radius: 3px;
}

.home_btn:active { /*ボタンを押したとき*/
	-ms-transform: translateY(4px);
	-webkit-transform: translateY(4px);
	transform: translateY(4px); /*下に動く*/
	border-bottom: none; /*線を消す*/
}

.content {
    width: 80%;
    margin-top: 1em;
    margin-right: auto;
    margin-left: auto;
    font-size: .8em;
}

.menu {
    width: 25vw;
    margin-right: auto;
    margin-left: auto;
}

ul {
    text-align: left;
}

li * {
    font-size: 2vh;
    margin: 0px;
    padding: 0px;
}

.icon {
	border-radius: 50%;
	width: 48px;
	height: 48px;
}

table {
    width: 50%;
    margin: auto;
}

#main{
	overflow: auto;
}

#cont{
	overflow: auto;
}
</style>
</head>
<body>
	<div id="main">
		<h1>二次絵絶対拡散するサイト</h1>
		<p><a href="./auth/logout.php" target="_self">ログアウト</a></p>
		<br>
		<div class="img">
			<img alt="アイコン" src="./imgs/1Ml7URFU_400x400.jpg">
		</div>
		<br>
<?php
printErrorMessages("color:red;font-weight:bold;");

    if (isLogin()) {
?>
		<hr/>
		<br/>
    	<div class="content">
            ようこそ「<?php echo $userInfo->{'name'}."（".$userInfo->{'screen_name'}."）"; ?>」=サン。<br>
			<br>
		<p>
			<a href="/osaisen/home/?count=50" target="_self" class="home_btn">ホームタイムライン</a>
		<p>
			<br/>
		</p>
			<a href="/osaisen/favolist/?screen_name=<?php echo $userInfo->{'screen_name'}; ?>&count=50" target="_self" class="home_btn">あなたの　いいね♥</a>
		</p>
			<br/>
			<h3 id="your_list">■Twitter検索</h3>
		<div style="margin:1em;display:flex; justify-content:center; align-items:center;">
			<form id="search" action="/osaisen/search/" method="get"
				style="display: block;">
				<input id="searchText" type="text" name="search" size="40"
					maxlength="726" style="width: 25vw;" value="" />
				<button type="submit" onclick="if(document.getElementById('searchText').value!=''){document.getElementById('search').submit()";>&#x1f50d;</button>
				<button type="submit" onclick="if(document.getElementById('searchText').value!=''){document.getElementById('searchText').value='#'+document.getElementById('searchText').value;document.getElementById('search').submit()";>＃</button>
			</form>
		</div>
			<h3 id="your_list">■あなたのリスト</h3>
			<?php
			$param = array(
				"screen_name" => $userInfo->{'screen_name'}
			);

			$lists = getTweetObjects($accessToken, $accessTokenSecret, "lists/list", $param);

			if(isset($lists->{'errors'})) {
				echo 'APIの上限を超えたようです。少々お待ちください。';
			} else {
				$sort = array();
				$sortedList = json_decode(json_encode($lists), true);
			   //  var_dump($sortedList);
			    foreach ($sortedList as $key => $value) {
			    	//print_r($key);
			    	//var_dump($value);
			        $sort[$key] = $value['name'];
			    }

			    array_multisort($sort, SORT_ASC, $sortedList);

			    //print_r($lists);

				$myList = array();

				echo '<table style="margin:auto;text-align:left;white-space:nowrap;width:100px;">';

				foreach ($sortedList as $list) {
				    $myList[] = [$list['id'] => $list['name']];
					echo '<tr><td><a href="/osaisen/list/?list_id='.$list['id'].'&name='.$list['name'].'&count=50" target="_self">↗️'.$list['name'].'</a></td>'.
									'<td><a href="/osaisen/list/edit.php?id='.$list['id'].'&title='.$list['name'].'" target="_self">&#x1F4DD;</a></td>'.
					               '<td>（&#x1F465;：'.$list['member_count'].'）</td></tr>';
				}
				//print_r($array);

				echo '</table>';

				setSessionParam('myList', $myList);

				if(strcmp($userInfo->{'screen_name'}, 'osaisen_info')==0)
				    file_put_contents ('/xampp/htdocs/osaisen/tmp/publicList.json', json_encode($myList));
			}
			

			$collections = getTweetObjects($accessToken, $accessTokenSecret, 'collections/list', $param);
			$myCollections[] = array();

			//var_dump($collections->objects->timelines);
			//var_dump($collections->response->results);
			//var_dump($collections);

			if (isset($collections->{'errors'})) {
			    echo 'APIの上限を超えたようです。少々お待ちください。';
			} else if(isset($collections->response->results) && empty($collections->response->results)) {
			    echo '';
			} else if(!is_string($collections)) {
			    foreach ($collections->objects->timelines as $key => $value) {
			        $tmp = [$key => $value];

			        $myCollections[] = $tmp;
			    }
			}
?>
		<br/>
		<a href="/osaisen/list/create.php">&#x2795;リストを追加</a>
		<hr/>
		
	<h3>現在のコレクション</h3>
	<table style="margin:auto;text-align:left;white-space:nowrap;width:100px;">
	<?php
		foreach($myCollections as $collections) {
			foreach(array_values($collections) as $collection) {
				$tkn = explode('/', $collection->custom_timeline_url);
				
				$collectionId = $tkn[count($tkn)-1];
				?>
				<tr><td>↗️<a href="/osaisen/collections/?collection_id=<?php echo	 $collectionId;?>&collection_name=<?php echo $collection->name;?>"><?php echo $collection->name?></a>
				　<a href="<?php echo $collection->custom_timeline_url; ?>" target="_blank">↗️Twitterページ</a>
				　<a href="/osaisen/collections/add.php?collection_id=<?php echo $collectionId;?>&collection_name=<?php echo $collection->name;?>">&#x2795;ツイートを追加</a></td></tr>
				<!-- tr><td>↗️<a href="<?php echo $collection->custom_timeline_url; ?>" target="_blank"><?php echo $collection->name?></a>
				　<a href="/osaisen/collections/add.php?collection_id=<?php echo $collectionId;?>&collection_name=<?php echo $collection->name;?>">&#x2795;ツイートを追加</a></td></tr -->
				<?php
			}
		}
	?>
	</table>
		<br/>
		<a href="/osaisen/collections/create.php">&#x2795;コレクションを追加</a>
		<hr/>
		<br/>
<?php
    } else {
    ?>
		<div id="login">
    		<form action="./auth/login.php" method="POST">
    			<input type="text" name="account" placeholder="アカウント名"><br>
    			<input type="password" name="password" placeholder="パスワード"><br>
    			<input type="submit" name="submit" value="ログイン">
    			<input type="submit" name="submit" value="アカウント登録">
    		</form>
    		<!-- p><a href="./auth/auth.php" target="_self">アプリケーション認証ページ</a></p -->
		</div>
<?php
    }
    ?>
    	<div id="cont">
			<b id="timeline">公式おススメリスト</b><br/>
			<?php
			$pulicList = json_decode(file_get_contents('/xampp/htdocs/osaisen/tmp/publicList.json'));

			if(!empty($pulicList)) {

				echo '<table style="margin:auto;text-align:left;white-space:nowrap;width:100px;">';

				foreach ($pulicList as $tmp) {
					foreach ($tmp as $key => $value) {
					    echo '<tr><td><a href="/osaisen/list/?list_id='.$key.'&name='.$value.'&count=50" target="_self">'.$value.'</a>系</td></tr>';
					}
				}

				echo '</table>';
			}
?>
			<!--b id="timeline">おススメ タイムライン</b>

			<?php
			/**
			$param = array(
				"screen_name" => 'osaisen_info'
			);

			$lists = getTweetObjects(PublicUserToken, PublicUserTokenSecret, "lists/list", $param);

			if(isset($lists->{'errors'})) {
				echo 'APIの上限を超えたようです。少々お待ちください。';
			} else {
				$sort = array();
				$sortedList = json_decode(json_encode($lists), true);
			   //  var_dump($sortedList);
			    foreach ($sortedList as $key => $value) {
			    	//print_r($key);
			    	//var_dump($value);
			        $sort[$key] = $value['name'];
			    }

			    array_multisort($sort, SORT_ASC, $sortedList);

			    $publicListMembers = array();

			    foreach ($sortedList as $list) {

			        $param = array(
			            "list_id" => $list['id']
			        );

			        $tmp = getTweetObjects(PublicUserToken, PublicUserTokenSecret, "lists/members", $param);

			        echo $list['name'];
			        var_dump($tmp);
			    }

			    print_r($publicListMembers);

// 				$myList = array();

// 				echo '<table style="margin:auto;text-align:left;white-space:nowrap;width:100px;">';

// 				foreach ($sortedList as $list) {
// 				    $myList[] = [$list['id'] => $list['name']];
// 					echo '<tr><td><a href="/osaisen/list/?list_id='.$list['id'].'&name='.$list['name'].'&count=50" target="_self">'.$list['name'].'</a></td>'.
// 									'<td><a href="/osaisen/list/edit.php?id='.$list['id'].'&title='.$list['name'].'" target="_self">&#x1F4DD;</a></td>'.
// 					               '<td>（&#x1F465;：'.$list['member_count'].'）</td></tr>';
// 				}
				//print_r($array);

				echo '</table>';
			}
			**/
?>
    		<ul class="menu">
    			<li><a href="/osaisen/search/?search=おもしろ動画" target="_self">おもしろ動画</a></li>
    			<li><a href="http://www.yaruox.jp/osaisen/search/?search=世界の絶景" target="_self">世界の絶景</a></li>
    		</ul>
			<br>
			<b id="mangaka">おススメ 漫画家アカウント</b>
			<table style="margin:auto;">
				<?php generateTR("1052217769305886723", "_9re0cIk_normal.jpg", "しろまんた", "mashiron1020", "先輩がうざい後輩の話"); ?>
				<?php generateTR("953255123144159237", "14vgFkOg_normal.jpg", "藤井おでこ", "fuxxxxxroxxka", "幼女社長"); ?>
				<?php generateTR("980744545196363776", "s2d91lfe_normal.jpg", "赤塚大将", "AKAmagenta", "あの子はいつも揺戸瑠菜"); ?>
				<?php generateTR("852564019", "twitter_normal.jpg", "すがぬまたつや", "sugaaanuma"); ?>
				<?php generateTR("978329031174062082", "5-uGEEsu_normal.jpg", "おおのこうすけ", "kousuke_oono", "極主夫道"); ?>
				<?php generateTR("467683681864138752", "Z3EPydl__normal.png", "チョモラン", "huusen_uri", "あの人の胃には僕が足りない"); ?>
				<?php generateTR("1051306309566353408", "I11zcCli_normal.jpg", "阿東 里枝", "tanimikitakane"); ?>
    			<?php generateTR("980377801218863104", "E3eOMtQS_normal.jpg", "も", "kireina_mochi", "魔法使いの印刷所"); ?>
				<?php generateTR("888963040489558016", "oDuJHHGz_normal.jpg", "火鳥", "minatohitori‏", "快楽ヒストリエ"); ?>
				<?php generateTR("1007585726215540737", "hawdTwrH_normal.jpg", "社畜漫画家ベニガシラ", "poppoyakiya", "美少女同人作家と若頭"); ?>
				<?php generateTR("2266027451", "ihzpz07j299cbsu2a9pp_bigger.gif", "山本アットホーム", "yamapon_bot"); ?>
				<?php generateTR("1044176517670961153", "MWnBKcMh_normal.jpg", "双龍", "Souryu_STD", "間違った子を魔法少女にしてしまった"); ?>
			</table>
			<br>
			<b id="kankore">おススメ 艦これ絵師アカウント</b>
			<table style="margin:auto;">
				<?php generateTR("1078417956260831232", "hCReHMUF_normal.jpg", "ぱこ", "pakkopako"); ?>
				<?php generateTR("1072349044192759809", "LqfmlqjD_normal.jpg", "ゆーき", "yuuki999"); ?>
    			<?php generateTR("980377801218863104", "E3eOMtQS_normal.jpg", "も", "kireina_mochi"); ?>
				<?php generateTR("963379125460156417", "limbT-mb_normal.jpg", "大和なでしこ", "nadeshiko0328", "ほっぽちゃんの日常"); ?>
				<?php generateTR("693461130258227200", "JXTy5efr_normal.jpg", "めがひよ", "hiyokoSpace"); ?>
				<?php generateTR("1063267000896507904", "8aFzvGIy_normal.jpg", "ひじから", "hijikara"); ?>
				<?php generateTR("1016662696912039937", "pdNkIu_I_normal.jpg", "Mr.A（閲覧注意）", "askh559"); ?>
			</table>
			<br>
			<b id="fgo">おススメ FGO絵師アカウント</b>
			<table style="margin:auto;">
				<?php generateTR("797866647126097920", "3Nvp1X_5_normal.jpg", "風間雷太", "kazamaraita"); ?>
				<?php generateTR("1075202001892298752", "AXZ9CIWI_normal.jpg", "らんふ", "ranfptn"); ?>
    			<?php generateTR("980377801218863104", "E3eOMtQS_normal.jpg", "も", "kireina_mochi"); ?>
				<?php generateTR("429298136289263616", "B0fW8pp9_normal.png", "EIKI_F7", "EIKI_F7"); ?>
				<?php generateTR("1026479805527613440", "YSfjcIMs_normal.jpg", "ポロロッカ", "po_ro_ro_ka"); ?>
			</table>
			<br>
			<b id="toho">おススメ 東方絵師アカウント</b>
			<table style="margin:auto;">
    			<?php generateTR("2762694544", "8f109981dccd9e6c49653e742d492f2b_normal.gif", "生足", "inax_mrn"); ?>
    			<?php generateTR("970959770738814976", "dDDWJYKJ_normal.jpg", "相沢", "tinazum"); ?>
    			<?php generateTR("865889759213756416", "ZQh7ee9g_normal.jpg", "ジェット虚無僧", "LEXUS_6737"); ?>
    			<?php generateTR("1076397325499559936", "1b0rKhAR_normal.jpg", "きこか水産", "mizuumi007"); ?>
				<?php generateTR("888963040489558016", "oDuJHHGz_normal.jpg", "火鳥", "minatohitori‏"); ?>
			</table>
			<br>
			<b id="GuP">おススメ ガルパン絵師アカウント</b>
			<table style="margin:auto;">
    			<?php generateTR("953255123144159237", "14vgFkOg_normal.jpg", "藤井おでこ", "fuxxxxxroxxka"); ?>
    			<?php generateTR("1072750219316457472", "DL966OQi_normal.png", "こひのれ", "kohinore"); ?>
    			<?php generateTR("1066838396755042305", "wNVoBa3k_normal.jpg", "ゲンキダウン", "genkidown"); ?>
    			<?php generateTR("649569095529041920", "oLE16SOJ_normal.jpg", "ケム木村", "kemu_kimura"); ?>
    			<?php generateTR("1019275903791058944", "UhQL9Xj-_normal.jpg", "阿修羅クモ", "kumo___atm "); ?>
    			<?php generateTR("1051323371957366785", "DbdNpSOW_normal.jpg", "ふなこ", "DOKKA_no_FUNAKO"); ?>
    			<?php generateTR("1062379946541146112", "p64OldU4_normal.jpg", "身体能力", "3ph3ab3"); ?>
    			<?php generateTR("831151141954019329", "sdt-zOrn_normal.jpg", "猫の人", "nekoHit"); ?>
    			<?php generateTR("1075671575486029824", "9rClOLI9_normal.jpg", "穴山", "ana3RDO"); ?>
    			<?php generateTR("886682093236797441", "Bcf57KU__normal.jpg", "リスくん", "murariss"); ?>
    			<?php generateTR("1052116672021057536", "1UfFnWfg_normal.jpg", "山吉ファン太", "hs_fanta"); ?>
    			<?php generateTR("1071695072356225024", "TJcQaXCS_normal.png", "のろり", "nyororiso"); ?>
    			<?php generateTR("1025794163739000832", "RIIiIEfi_normal.jpg", "あすてる博士", "aster90"); ?>
    			<?php generateTR("633814118462439425", "eqr8Pwbe_normal.png", "伊藤黒介", "itokurosuke"); ?>
			</table>
			<br>
			<b id="imas">おススメ アイマス絵師アカウント</b>
			<table style="margin:auto;">
				<?php generateTR("1054103850238398464", "M_ZG8d84_normal.jpg", "天翔幻獣", "GotokuR"); ?>
			</table>
			<br>
			<b id="neta">ネタ アカウント</b>
			<table style="margin:auto;">
    			<?php generateTR("1009077048172732416", "jkTzwz4y_normal.jpg", "生ガキちゃん", "namagakichan"); ?>
			</table>
			<br>
			<b id="uouo">ウオウオフィッシュライフ</b>
			<table style="margin:auto;">
    			<?php generateTR("915552706369961985", "7fJHwtRz_normal.jpg", "Tuno", "TunoTarosan"); ?>
    			<?php generateTR("", "_normal.jpg", "", ""); ?>
			</table>
			-->
    			<!-- li><a href="./mock.php" target="_self"><span style="color: red;">NEW！</span>本番ページに向けてのモック画面！</a></li -->
    			<!-- li><a href="./hometimeline.php" target="_self">マイタイムライン</a></li -->
    			<!-- li><a href="./2d/2Droom.php" target="_self">二次絵絶対拡散するルーム</a></li -->
    	</div>
		</div>
	</div>
</body>
</html>