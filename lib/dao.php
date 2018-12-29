<?php
require_once (dirname(__FILE__) . "./db/db.php");
// データアクセスライブラリをロード

/**
 * 民草情報を取得する
 */
function getTamikusa($account)
{
    $mydb = new MyDB();

    $account = $mydb->escape($account);

    $results = $mydb->select("SELECT * FROM tamikusa WHERE id = '$account'");

    $mydb->close();

    return $results[0];
}

/**
 * 民草情報を取得する
 */
function isTamikusa($account)
{
    $mydb = new MyDB();

    $account = $mydb->escape($account);

    $results = $mydb->select("SELECT count(id) FROM tamikusa WHERE id = '$account'");

    $mydb->close();

    return ($results[0][0]==1);
}

/**
 * 民草のパスワードを取得する
 */
function getTamikusaPassword($account)
{
    $mydb = new MyDB();

    $account = $mydb->escape($account);

    $results = $mydb->select("SELECT password FROM tamikusa WHERE id = '$account'");

    $mydb->close();

    return $results[0][0];
}

/**
 * 民草がログインしているかどうかを判定する
 */
function isLoginedTamikusa($loginCookieId)
{
    $mydb = new MyDB();

    $loginCookieId = $mydb->escape($loginCookieId);

    $results = $mydb->select("SELECT count(login_cookie_id) FROM tamikusa WHERE login_cookie_id = '$loginCookieId'");

    $mydb->close();

    return ($results[0][0]==1);
}

/**
 * 民草のログイン情報を取得する
 */
function getLoginedTamikusaAccount($loginCookieId)
{
    $mydb = new MyDB();

    $loginCookieId = $mydb->escape($loginCookieId);

    $results = $mydb->select("SELECT id FROM tamikusa WHERE login_cookie_id = '$loginCookieId'");

    $mydb->close();

    return $results[0][0];
}

/**
 * 民草のログイン情報を記録する
 */
function setTamikusaLoginInfo($account, $loginCookieId)
{
    $mydb = new MyDB();

    $account = $mydb->escape($account);
    $loginCookieId = $mydb->escape($loginCookieId);

    $results = $mydb->select("UPDATE tamikusa SET login_cookie_id = '$loginCookieId' WHERE id = '$account'");

    $mydb->close();

    return $results[0][0];
}

/**
 * 民草情報を挿入する
 */
function insertTamikusa($account, $password, $oauth_token, $oauth_token_secret)
{
    $mydb = new MyDB();

    $account = $mydb->escape($account);
    $password = $mydb->escape($password);
    $oauth_token = $mydb->escape($oauth_token);
    $oauth_token_secret = $mydb->escape($oauth_token_secret);

    $query = "INSERT INTO tamikusa (id, password, oauth_token, oauth_token_secret)"
        ." VALUES ('$account', '$password', '$oauth_token', '$oauth_token_secret');";

    $results = $mydb->insert($query);

    $mydb->close();

    return $results[0];
}

