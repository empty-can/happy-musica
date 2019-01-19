<?php
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * tweetオブジェクトからツイートのURLを取得する
 *
 * @param unknown $tweet
 * @return string
 */
function getTweetUrlByTweet($tweet) {
    var_dump($tweet);
//     return 'https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id;
    return '';
}

/**
 * var_dumpを見やすく出力
 *
 * @param unknown $object
 */
function myVarDump($object) {
    ?><pre><?php
    var_dump($object);
    ?></pre><?php
    exit();
}

/**
 *
 *
 * @param unknown $str
 * @return string
 */
function webPrint($str) {
    return nl2br(preg_replace('/＆lt/', '&lt;', preg_replace('/＆gt;/', '&gt;', htmlspecialchars(preg_replace('/&/', '＆', preg_replace('/&amp;/', '＆', $str))))));
}

function linkHash($target) {
    preg_match_all("/#[^< 　]+/u",$target, $tmp);

//     echo print_r($tmp);
    $hashes = $tmp[0];

    if(isset($hashes)) {
//         usort($hashes, create_function('$a,$b', 'return mb_strlen($b, "UTF-8") - mb_strlen($a, "UTF-8");'));

//         echo print_r($hashes);
        foreach ($hashes as $hash) {
            $target = str_replace(
                $hash.' ',
                '<a href="/osaisen/search/?search='.str_replace("#", "%23", $hash).'" target="_blank" style="text-decoration-line: underline;text-decoration-color: white;">'.$hash.'</a> ',
                $target);
            $target = str_replace(
                $hash.'　',
                '<a href="/osaisen/search/?search='.str_replace("#", "%23", $hash).'" target="_blank" style="text-decoration-line: underline;text-decoration-color: white;">'.$hash.'</a>　',
                $target);
            $target = str_replace(
                $hash.'<br',
                '<a href="/osaisen/search/?search='.str_replace("#", "%23", $hash).'" target="_blank" style="text-decoration-line: underline;text-decoration-color: white;">'.$hash.'</a><br',
                $target);
        }
    }

    return $target;
}

/**
 *
 * @param unknown $screen_name
 * @param unknown $tweet_id
 * @return string
 */
function getTweetUrl($screen_name, $tweet_id) {
    return 'https://twitter.com/'.$screen_name.'/status/'.$tweet_id;
}

/**
 * 文字列の最後の URLをその直前の空白から削除
 *
 * @param unknown $targetText
 * @return string
 */
function cutURL($targetText) {
    $result = explode("http", $targetText);

    return rtrim($result[0]);
}

/**
 * ツイッターのコネクションを取得する
 *
 * @return \Abraham\TwitterOAuth\TwitterOAuth
 */
function getTwitterConnection()
{
    if(empty(getSessionParam(UserToken,'')))    {
        return new TwitterOAuth(Consumer_Key, Consumer_Secret, getSessionParam("PublicUserToken",''), getSessionParam("PublicUserTokenSecret",''));
    } else if(isPublic()) {
        return new TwitterOAuth(Consumer_Key, Consumer_Secret, getSessionParam(UserToken,''), getSessionParam(UserTokenSecret,''));
    }
}

/**
 * エラーメッセージを表示する
 *
 * @param string $style
 */
function printErrorMessages($style = "color:red;font-weight:bold;")
{
    if (! empty(getSessionParam(ErrorMessage)))?>
<span style="<?php echo $style;?>">
        <?php
        echo getSessionParam(ErrorMessage);
    ?>
        </span>
<?php
    setSessionParam(ErrorMessage, "");
}

/**
 * トップページかどうかを判定する
 *
 * @return boolean
 */
function isTopPage()
{
    return ((PageContext . "/" === getServerParam("REQUEST_URI",'')) || (PageContext . "/index.php" === getServerParam("REQUEST_URI",'')));
}

/**
 * 認証関係のページかどうか
 *
 * @return boolean
 */
function isAuthPages()
{
    return contains(getServerParam("REQUEST_URI",''), PageContext . 'AuthPath');
}

/**
 * 公開ページかどうか
 *
 * @return boolean
 */
function isPublic()
{
    return contains(getServerParam("REQUEST_URI",''), PageContext . 'PublicPath');
}

/**
 * 例外（認証不要）ページかどうか
 *
 * @return boolean
 */
function isExceptionPage()
{
    $result = false;

    foreach (getServerParam("PrivilegedPath") as $path) {
        $result |= contains(getServerParam("REQUEST_URI",''), PageContext . $path);
    }

    return $result;
}

/**
 * ログイン中かどうか
 *
 * @return boolean
 */
function isLogin()
{
    return (getSessionParam('logined') === true);
}

/**
 * GETパラメータを取得する
 */
function getGetParam($key, $default = "")
{
    if (isset($_GET[$key]))
        return $_GET[$key];
        else
            return $default;
}

/**
 * GETパラメータを設定する
 */
function setGetParam($key, $value)
{
    $_GET[$key] = $value;
}
/**
 * POSTパラメータを取得する
 */
function getPostParam($key, $default = "")
{
    if (isset($_POST[$key]))
        return $_POST[$key];
        else
            return $default;
}

/**
 * SESSIONパラメータを取得する
 */
function getSessionParam($key, $default = "")
{
    if (isset($_SESSION[$key]))
        return $_SESSION[$key];
        else
            return $default;
}

/**
 * SERVERパラメータを設定する
 *
 * @param string $key
 * @param string $value
 */
function setServerParam($key, $value)
{
    $_SERVER[$key] = $value;
}

/**
 * REQUESTパラメータを取得する
 */
function getRequestParam($key, $default = "")
{
    if (isset($_REQUEST[$key]))
        return $_REQUEST[$key];
        else
            return $default;
}

/**
 * REQUESTパラメータを設定する
 *
 * @param string $key
 * @param string $value
 */
function setRequestParam($key, $value)
{
    $_REQUEST[$key] = $value;
}

/**
 * SERVERパラメータを取得する
 */
function getServerParam($key, $default = "")
{
    if (isset($_SERVER[$key]))
        return $_SERVER[$key];
        else
            return $default;
}

/**
 * SESSIONパラメータを設定する
 *
 * @param string $key
 * @param string $value
 */
function setSessionParam($key, $value)
{
    $_SESSION[$key] = $value;
}

/**
 * SERVERパラメータを取得する
 */
function getCookieParam($key, $default = "")
{
    if (isset($_COOKIE[$key]))
        return $_COOKIE[$key];
        else
            return $default;
}

/**
 * SESSIONパラメータを設定する
 *
 * @param string $key
 * @param string $value
 */
function setCookieParam($key, $value)
{
    $_COOKIE[$key] = $value;
}

/**
 *
 * @param string $target
 * @param string $pattern
 * @return boolean
 */
function contains($target, $pattern)
{
    if (strpos($target, $pattern) === false)
        return false;
    else
        return true;
}


/**
 * グローバルパラメータを設定する
 *
 * @param string $key
 * @param string $value
 */
function getGlobalParam($key, $default = "")
{
    if (isset($GLOBALS[$key]))
        return $GLOBALS[$key];
        else
            return $default;
}


/**
 * グローバルパラメータを設定する
 *
 * @param string $key
 * @param string $value
 */
function setGlobalParam($key, $value)
{
    $GLOBALS[$key] = $value;
}

function cureateCollections($access_token, $access_token_secret, $request_json) {
    $api_key = Consumer_Key;
    $api_secret = Consumer_Secret;
    $request_url = 'https://api.twitter.com/1.1/collections/entries/curate.json' ;	// エンドポイント
    $request_method = 'POST' ;

    // キーを作成する (URLエンコードする)
    $signature_key = rawurlencode( $api_secret ) . '&' . rawurlencode( $access_token_secret ) ;

    // パラメータB (署名の材料用)
    $params_b = array(
    'oauth_token' => $access_token ,
    'oauth_consumer_key' => $api_key ,
    'oauth_signature_method' => 'HMAC-SHA1' ,
    'oauth_timestamp' => time() ,
    'oauth_nonce' => microtime() ,
    'oauth_version' => '1.0' ,
    ) ;

    // 処理用のパラメータCを作る (JSONは署名の材料に含めない)
    $params_c = $params_b ;

    // 連想配列をアルファベット順に並び替える
    ksort( $params_c ) ;

    // パラメータの連想配列を[キー=値&キー=値...]の文字列に変換する
    $request_params = http_build_query( $params_c , '' , '&' ) ;

    // 一部の文字列をフォロー
    $request_params = str_replace( array( '+' , '%7E' ) , array( '%20' , '~' ) , $request_params ) ;

    // 変換した文字列をURLエンコードする
    $request_params = rawurlencode( $request_params ) ;

    // リクエストメソッドをURLエンコードする
    // ここでは、URL末尾の[?]以下は付けないこと
    $encoded_request_method = rawurlencode( $request_method ) ;

    // リクエストURLをURLエンコードする
    $encoded_request_url = rawurlencode( $request_url ) ;

    // リクエストメソッド、リクエストURL、パラメータを[&]で繋ぐ
    $signature_data = $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params ;

    // キー[$signature_key]とデータ[$signature_data]を利用して、HMAC-SHA1方式のハッシュ値に変換する
    $hash = hash_hmac( 'sha1' , $signature_data , $signature_key , TRUE ) ;

    // base64エンコードして、署名[$signature]が完成する
    $signature = base64_encode( $hash ) ;

    // パラメータの連想配列、[$params]に、作成した署名を加える
    $params_c['oauth_signature'] = $signature ;

    // パラメータの連想配列を[キー=値,キー=値,...]の文字列に変換する
    $header_params = http_build_query( $params_c , '' , ',' ) ;

    // リクエスト用のコンテキスト
    $context = array(
    'http' => array(
    'method' => $request_method , // リクエストメソッド
    'header' => array(	// ヘッダー
    'Authorization: OAuth ' . $header_params ,
    "Content-Type: application/json" ,
    ) ,
    ) ,
    ) ;

    // オプションがある場合、コンテキストにPOSTフィールドを作成する
    if ( $request_json ) {
        $context['http']['content'] = $request_json ;
    }

    // cURLを使ってリクエスト
    $curl = curl_init() ;
    curl_setopt( $curl, CURLOPT_URL , $request_url ) ;
    curl_setopt( $curl, CURLOPT_HEADER, 1 ) ;
    curl_setopt( $curl, CURLOPT_CUSTOMREQUEST , $context['http']['method'] ) ;	// メソッド
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER , false ) ;	// 証明書の検証を行わない
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER , true ) ;	// curl_execの結果を文字列で返す
    curl_setopt( $curl, CURLOPT_HTTPHEADER , $context['http']['header'] ) ;	// ヘッダー
    if( isset( $context['http']['content'] ) && !empty( $context['http']['content'] ) ) {
        curl_setopt( $curl , CURLOPT_POSTFIELDS , $context['http']['content'] ) ;	// リクエストボディ
    }
    curl_setopt( $curl , CURLOPT_TIMEOUT , 5 ) ;	// タイムアウトの秒数
    $res1 = curl_exec( $curl ) ;
    $res2 = curl_getinfo( $curl ) ;
    curl_close( $curl ) ;

    // 取得したデータ
    $json = substr( $res1, $res2['header_size'] ) ;	// 取得したデータ(JSONなど)
    // $header = substr( $res1, 0, $res2['header_size'] ) ;	// レスポンスヘッダー (検証に利用したい場合にどうぞ)

    return $json;
}
