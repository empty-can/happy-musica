<?php
require_once ("./lib/init.php");

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>WEPICKS! Twitter API V1.1 ホームタイムライン[ GET statuses/home_timeline ]
	サンプルコード</title>
</head>
<body>
	<a href="/osaisen/" target="_self">トップページ</a>
<?php
// Twitterからjson形式でデータが返ってくる
// $content = $connection->get("account/verify_credentials");
$connection = getTwitterConnection();

// Twitterからjson形式でデータが返ってくる
$oObj = $connection->get("statuses/home_timeline", [
    "count" => 50
    , "exclude_replies" => true
]);

// オブジェクトを展開
if (isset($oObj->{'errors'}) && $oObj->{'errors'} != '') {
    ?>
    取得に失敗しました。<br /> エラー内容：
	<br />
	<pre>
    <?php var_dump($oObj); ?>
    </pre>
<?php
} else {
    // オブジェクトを展開
    $iCount = sizeof($oObj);
    for ($iTweet = 0; $iTweet < $iCount; $iTweet ++) {
        $display = false;
        $errorMessage = "";
        ?>
        <!-- <?php
        // echo "ツイートオブジェクト\n";
        // print_r($oObj[$iTweet]);
        ?>-->
        <?php
        $iTweetId = $oObj[$iTweet]->{'id'};
        $sIdStr = (string) $oObj[$iTweet]->{'id_str'};
        $sText = $oObj[$iTweet]->{'text'};
        $sName = $oObj[$iTweet]->{'user'}->{'name'};
        $sScreenName = $oObj[$iTweet]->{'user'}->{'screen_name'};
        $sProfileImageUrl = $oObj[$iTweet]->{'user'}->{'profile_image_url'};
        $sCreatedAt = $oObj[$iTweet]->{'created_at'};
        $sStrtotime = strtotime($sCreatedAt);
        $sCreatedAt = date('Y-m-d H:i:s', $sStrtotime);
        $favo = $oObj[$iTweet]->{'favorite_count'};
        ?><hr />
	<h4>
		<img src="<?php echo $sProfileImageUrl; ?>" />
		<?php echo $sName; ?>(@<?php echo $sScreenName; ?>)さんのつぶやき(<?php echo $sCreatedAt; ?>)</h4>
	<ul>
		<!--
        <li>IDNO[id] : <?php //echo $iTweetId; ?></li>
        <li>名前[name] : <?php //echo $sIdStr; ?></li>
        <li>つぶやき[text] : <?php //echo $sText; ?></li>
         -->
		<li> <?php echo $sText; ?><br /><?php

        if (isset($oObj[$iTweet]->{'retweeted_status'})) {
            echo "[info:このツイートはリツイートです。]<br>\n";
            $retweeted_entities = $oObj[$iTweet]->{'retweeted_status'}->{'entities'};
            ?>
			<!-- <?php
            echo "retweeted_status\n";
            print_r($oObj[$iTweet]->{'retweeted_status'});
            ?>-->
			<ul>
			<?php
            foreach ($retweeted_entities->{'urls'} as $url) {
                if (preg_match('/.+(\.(jpeg)|(jpg)|(png)|(gif))$/', $url->{'expanded_url'})) {
                    print '[info:リツイートステータスから。]<br><a href="' . $url->{'expanded_url'} . '" target="_blank"><img src="' . $url->{'expanded_url'} . '" height="250"></a><br>' . "\n";
                } else {
                    echo "[info:リツイート本文のURLが画像ではありません。\n(";
                    print_r($url->{'expanded_url'});
                    echo ")]<br>\n";
                }
            }
            echo "<li>リツイート数：" . $oObj[$iTweet]->{'retweeted_status'}->{'retweet_count'} . "</li>\n";
            echo "<li>ファボ数：" . $oObj[$iTweet]->{'retweeted_status'}->{'favorite_count'} . "</li><br>\n";
            ?></ul><?php
        } else {
            // echo "このツイートはリツイートではありません。<br>\n";
        }
        ?></li><?php

        if (isset($oObj[$iTweet]->{'entities'})) {
            $urls = $oObj[$iTweet]->{'entities'}->{'urls'};
            ?>
			<!-- <?php //print_r($oObj[$iTweet]->{'entities'});?>-->
			<?php
            foreach ($urls as $url) {
                if (preg_match('/.+(\.(jpeg)|(jpg)|(png)|(gif))$/', $url->{'expanded_url'})) {
                    print '[info:ツイートエンティティから。]<br><a href="' . $url->{'expanded_url'} . '" target="_blank"><img src="' . $url->{'expanded_url'} . '" height="250"></a><br>' . "\n";
                } else {
                    echo "[info:本文のURLが画像ではありません。\n(";
                    print_r($url->{'expanded_url'});
                    echo ")]<br>\n";
                }
            }
        } else {
            echo "[info:本文にURLはありません。]<br>\n";
        }

        if (isset($oObj[$iTweet]->{'extended_entities'})) {
            $extended_media = $oObj[$iTweet]->{'extended_entities'}->{'media'};
            ?>
			<!-- <?php //print_r($oObj[$iTweet]->{'extended_entities'});?>-->
			<?php
            foreach ($extended_media as $media) {
                ?>
			<!-- <?php //print_r($media);?>-->
			<?php
                if (isset($media->{'media_url'})) {
                    $display = $display || true;
                    if (preg_match('/.+(\.(jpeg)|(jpg)|(png)|(gif))$/', $media->{'media_url'})) {
                        print '[info:ツイートの展開エンティティから。]<br><a href="' . $media->{'media_url'} . '" target="_blank"><img src="' . $media->{'media_url'} . '" height="250"></a><br>' . "\n";
                    } else {
                        echo "[info:埋め込みメディアが画像ではありません。 \n(";
                        print_r($media->{'media_url'});
                        echo ")]<br>\n";
                    }
                } else {
                    echo "[info:埋め込みメディアにURLはありません。]<br>\n";
                }
            }
        } else {
            echo "[info:埋め込みメディアはありません。]<br>\n";
        }
        ?>
		<li>いいね : <?php echo $favo; ?></li>
	</ul><?php

        /*
         * if (preg_match_all('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)', $sText, $result) !== false) {
         * foreach ($result[0] as $value) {
         * $remote_content = file_get_contents($value);
         * if (preg_match_all('(http[s]?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+/media/[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+((jpg)|(jpeg)|(png)|(gif)))', $remote_content, $result) !== false) {
         * $imgs = array();
         * foreach ($result[0] as $img) {
         * $imgs[$img] = $img;
         * }
         *
         * foreach ($imgs as $img) {
         * // URL表示
         * print '<img src="' . $img . '" height="250"><br>';
         * }
         * }
         * }
         * }
         */
    } // end for
}
?>



</body>
</html>