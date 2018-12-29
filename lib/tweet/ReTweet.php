<?php
namespace lib\tweet;

require_once(dirname(__FILE__)."/../util.php"); // ユーティリティライブラリをロード

/**
 *
 * @author iimay
 *
 */
class ReTweet
{
    private $tweet;
    private $uerURLs;


    /**********************************************************
     *
     * メディア情報を返す
     *
     **********************************************************/
    public function getAllMediaURL() {
        $result = array();

        if (isset($this->tweet->extended_entities)) {
            $entities = $this->tweet->extended_entities;

            if (isset($entities) && isset($entities->media)) {
                foreach ($entities->media as $media) {
                    $result[] = $media->media_url_https;
                }
            }
            return $result;
        } else {

            return ["error"=>"no extended entities"];
        }

        //myVarDump($result);

    }

    /**
     * このツイートがメディアを含むか否か。
     *
     * @return boolean
     */
    public function isMediaTweet() {
        return (isset($this->tweet->extended_entities) && isset($this->tweet->extended_entities->media));
    }

    /**********************************************************
     *
     * ユーザ情報を返す
     *
     **********************************************************/

    public function getProfileImgURL() {
        return webPrint($this->tweet->user->profile_image_url_https);
    }

    public function getName() {
        return webPrint($this->tweet->user->name);
    }

    public function getDescription() {
        return webPrint($this->tweet->user->description);

    }

    public function getLocation() {
        return webPrint($this->tweet->user->location);
    }

    /**
     * スクリーンネームを返す
     *
     * @return unknown
     */
    public function getScreenName() {
        return $this->tweet->user->screen_name;
    }

    /**
     * ユーザIDを返す
     *
     * @return unknown
     */
    public function getUserId() {
        return $this->tweet->user->id;
    }



    /**********************************************************
     *
     * ツイートそのものの情報を返す
     *
     ********************************************************** /

    /**
     * ツイートのURLを返す
     *
     * @return unknown
     */
    public function getTweetURL($urlFormat='https://twitter.com/%s/status/%s') {
        return sprintf($urlFormat, urlencode($this->tweet->user->screen_name), urlencode($this->getId()));
    }

    /**
     * ツイートの本文を返す
     *
     * @return string
     */
    public function getText() {
        $displayText = preg_replace('/ http[s]?:\/\/[a-zA-Z0-9\.\/]+$/', '', $this->tweet->text);
        return $this->linkURLs(webPrint($displayText));
    }

    /**
     * ツイート日付を返す
     *
     * @return unknown
     */
    public function getCreatedAt($format='H:i - Y年n月j日', $timezone='Asia/Tokyo') {
        date_default_timezone_set($timezone);
        return date($format, strtotime($this->tweet->created_at));
    }

    /**
     * id を返す
     *
     * @return unknown
     */
    public function getId() {
        return $this->tweet->id;
    }










    /**********************************************************
     *
     * その他ユーティリティ関数
     *
     ********************************************************** /

    /**
     * コンストラクタ
     *
     * @param unknown $tweet
     */
    public function __construct($tweet) {
        $this->tweet = $tweet;
        $this->URLs = array();
        $this->URLs += $this->getUrls($tweet->entities);
        $this->URLs += $this->getUserUrls($tweet->user->entities);

        //myVarDump($this->URLs);
    }

    /**
     * 埋込み型のHTMLを取得する
     *
     * @return unknown
     */
    public function getOembedHTML() {
        return json_decode(
            file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($this->getTweetUrl()))
            )->html;
    }

    /**
     *
     * @return string
     */
    public function getEncodedTweetURL() {
        return urlencode('https://twitter.com/'.$this->getScreenName().'/status/'.$this->getId());
    }

    /**
     * 埋込み型のURLを取得する
     *
     * @return unknown
     */
    public function getOembedURL() {
        return 'https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($this->getTweetUrl());
    }

    /**
     * ユーザエンティティの中から短縮URLと展開URLの組を連想配列で取得
     *
     * @param unknown $userEntities
     * @return array|NULL[]
     */
    private function getUrls($entities) {
        $result = array();

        if(isset($entities)) {

            if(isset($entities->urls)) {
                foreach($entities->urls as $url) {
                    $result += array($url->url => array("expanded_url" => $url->expanded_url, "display_url" => $url->display_url));
                }
            }

            if(isset($entities->media)) {
                foreach($entities->media as $media) {
                    $result += array($media->url => array("expanded_url" => $media->media_url_https, "display_url" => $media->display_url));
                }
            }
        }

        return $result;
    }

    /**
     * ユーザエンティティの中から短縮URLと展開URLの組を連想配列で取得
     *
     * @param unknown $userEntities
     * @return array|NULL[]
     */
    private function getUserUrls($userEntities) {
        $result = array();

        if(isset($userEntities)) {

            if(isset($userEntities->urls)) {
                foreach($userEntities->urls as $url) {
                    $result += array($url->url => array("expanded_url" => $url->expanded_url, "display_url" => $url->display_url));
                }
            }

            if(isset($userEntities->description) && isset($userEntities->description->urls)) {
                foreach($userEntities->description->urls as $url)
                    $result += array($url->url => array("expanded_url" => $url->expanded_url, "display_url" => $url->display_url));
            }
        }

        return $result;
    }

    /**
     * 文中のURLを<a>で囲む。
     *
     * @param string $targetStr
     * @return string
     */
    private function linkURLs($targetStr="") {

        foreach($this->URLs as $key => $value) {
            $targetStr = str_replace($key, '<a href="'.$value["expanded_url"].'" target="_blank">'.$value["display_url"].'</a>', $targetStr);
        }

        return $targetStr;
    }
}

