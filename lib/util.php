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


