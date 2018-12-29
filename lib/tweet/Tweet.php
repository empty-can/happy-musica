<?php
namespace lib\tweet;

require_once (dirname(__FILE__) . "/../util.php");
 // ユーティリティライブラリをロード

/**
 *
 * @author iimay
 *
 */
class Tweet
{

    private $tweet;

    private $uerURLs;

    private $videoUrl;
    
    public $isRetweet;
    
    public $retweetBy;

    /**
     *
     * @return unknown
     */
    public function isRetweet()
    {
        return $this->isRetweet;
    }

    public function getRetweet()
    {
        return $this->retweetBy;
    }

    public function isSameScreenName($screenName)
    {
        if ($screenName == $this->getScreenName())
            return true;
        else
            return false;
    }

    /**
     * リプライ元ツイートを返す
     *
     * @return boolean
     */
    public function getReplyTweet() {
        $param = array(
            "id" => $this->tweet->in_reply_to_status_id_str
        );

        $replyTweet = obj2tweet(getTweetObjects(PublicUserToken, PublicUserTokenSecret, "statuses/show", $param));
        return new Tweet($replyTweet[0]);
    }

    /**
     * 自分のツイートに対する返信かどうか
     *
     * @return boolean
     */
    public function isReplyToSelf() {
        return $this->getScreenName() == $this->tweet->in_reply_to_screen_name;
    }

    /**
     * ********************************************************
     *
     * メディア情報を返す
     *
     * ********************************************************
     */
    public function getAllMediaURL()
    {
        $targetTweet = (isset($this->tweet->retweeted_status)) ? $this->tweet->retweeted_status : $this->tweet;

        if (isset($targetTweet->extended_entities)) {
            $entities = $targetTweet->extended_entities;

            if (isset($entities) && isset($entities->media)) {
                foreach ($entities->media as $media) {
                    $result[] = $media->media_url_https;
                }
            }
            return $result;
        } else {
            return [
                "error" => "no extended entities"
            ];
        }

        // myVarDump($result);
    }

    /**
     * このツイートがメディアを含むか否か。
     *
     * @return boolean
     */
    public function isMediaTweet()
    {
        return (isset($this->tweet->extended_entities) && isset($this->tweet->extended_entities->media));
    }

    /**
     * このツイートがビデオメディアを含むか否か。
     *
     * @return boolean
     */
    public function isVideoTweet()
    {
        if($this->isMediaTweet()) {
            foreach ($this->tweet->extended_entities->media as $media) {
                if(isset($media->video_info)) {
                    foreach ($media->video_info->variants as $variants) {
//                         var_dump($variants->url);
                        $this->videoUrl = explode('?', $variants->url)[0];
                        return true;
                    }
                }
            }
        }

        return false;
//         myVarDump($this);
//         var_dump($this->tweet->extended_entities->media);
//         return (isset($this->tweet->extended_entities)
//             && isset($this->tweet->extended_entities->media)
//             && isset($this->tweet->extended_entities->media->video_info)
//             && isset($this->tweet->extended_entities->media->video_info->variants)
//             );
    }

    /**
     * ********************************************************
     *
     * ビデオ情報を返す
     *
     * ********************************************************
     */
    public function getVideoURL()
    {
        if($this->isMediaTweet()) {
            $result = $this->videoUrl;
        } else {
            $result["error"] = "no extended entities";
        }

        return $result;
    }

    /**
     * このツイートが外部URLを含むか否か。
     *
     * @return boolean
     */
    public function hasExpandedLink()
    {
        return (isset($this->tweet->entities) && isset($this->tweet->entities->urls));
    }

    /**
     * ********************************************************
     *
     * ユーザ情報を返す
     *
     * ********************************************************
     */
    public function getProfileImgURL()
    {
        return webPrint($this->tweet->user->profile_image_url_https);
    }

    public function getName()
    {
        return webPrint($this->tweet->user->name);
    }

    public function getDescription()
    {
        return webPrint($this->tweet->user->description);
    }

    public function getLocation()
    {
        return webPrint($this->tweet->user->location);
    }

    /**
     * スクリーンネームを返す
     *
     * @return unknown
     */
    public function getScreenName()
    {
        return $this->tweet->user->screen_name;
    }

    /**
     * ユーザIDを返す
     *
     * @return unknown
     */
    public function getUserId()
    {
        return $this->tweet->user->id;
    }

    /**
     * ********************************************************
     *
     * ツイートそのものの情報を返す
     *
     * ********************************************************
     */
    public function getOriginalTweet()
    {
    	$result = null;
    	
        if($this->isRetweet()) {
           $result = new Tweet($this->tweet->retweeted_status);
           $result->isRetweet = true;
           $result->retweetBy = $this->retweetBy;
        } else {
        	$result = $this;
        }
        
        return $result;
    }

    /**
     * ツイートのURLを返す
     *
     * @return unknown
     */
    public function getTweetURL($urlFormat = 'https://twitter.com/%s/status/%s')
    {
        return sprintf($urlFormat, urlencode($this->tweet->user->screen_name), urlencode($this->getId()));
    }

    public function getFavCount() {
        return $this->tweet->favorite_count;
    }

    public function getRetCount() {
        return $this->tweet->retweet_count;
    }

    /**
     * ツイートの本文を返す
     *
     * @return string
     */
    public function getText()
    {
        $displayText = preg_replace('/http[s]?:\/\/[a-zA-Z0-9\.\/]+$/', '', $this->tweet->text);
        return $this->linkURLs(webPrint($displayText));
    }

    public function getTextWizLink()
    {

    }

    /**
     * ツイート日付を返す
     *
     * @return unknown
     */
    public function getCreatedAt($format = 'H:i - Y年n月j日', $timezone = 'Asia/Tokyo')
    {
        date_default_timezone_set($timezone);
        return date($format, strtotime($this->tweet->created_at));
    }

    /**
     * id を返す
     *
     * @return unknown
     */
    public function getId()
    {
        return $this->tweet->id;
    }

    /**
     * ********************************************************
     *
     * その他ユーティリティ関数
     *
     * ********************************************************* /
     *
     * /**
     * コンストラクタ
     *
     * @param unknown $tweet
     */
    public function __construct($tweet)
    {
        $this->tweet = $tweet;

        if (is_array($tweet)) {
            if (count($tweet) > 0 && isset($tweet[0]->message)) {
                echo "API error<br/>\r\n";
                echo $tweet[0]->message;
                exit();
            } else {
                myVarDump($tweet);
                exit();
            }
        }


        $this->URLs = array();
        $this->URLs += $this->getUrls($tweet->entities);
        $this->URLs += $this->getUserUrls($tweet->user->entities);
        $this->isRetweet = isset($tweet->retweeted_status);
        
        if($this->isRetweet) {
          $this->retweetBy = $tweet->user->name;
        //myVarDump($this->retweetBy);
        //myVarDump($tweet->user);
        //myVarDump($tweet);
        //myVarDump(isset($tweet->retweeted_status));
        //myVarDump($tweet->retweeted_status);
        //myVarDump($tweet->retweeted_status->user);
        } else {
          $this->retweetBy = "本人";
        }
    }

    /**
     * 埋込み型のHTMLを取得する
     *
     * @return unknown
     */
    public function getOembedHTML()
    {
        return json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($this->getTweetUrl())))->html;
    }

    /**
     *
     * @return string
     */
    public function getEncodedTweetURL()
    {
        return urlencode('https://twitter.com/' . $this->getScreenName() . '/status/' . $this->getId());
    }

    /**
     * 埋込み型のURLを取得する
     *
     * @return unknown
     */
    public function getOembedURL()
    {
        return 'https://publish.twitter.com/oembed?lang=ja&url=' . urlencode($this->getTweetUrl());
    }

    /**
     * ユーザエンティティの中から短縮URLと展開URLの組を連想配列で取得
     *
     * @param unknown $userEntities
     * @return array|NULL[]
     */
    private function getUrls($entities)
    {
        $result = array();

        if (isset($entities)) {

            if (isset($entities->urls)) {
                foreach ($entities->urls as $url) {
                    $result += array(
                        $url->url => array(
                            "expanded_url" => $url->expanded_url,
                            "display_url" => $url->display_url
                        )
                    );
                }
            }

            if (isset($entities->media)) {
                foreach ($entities->media as $media) {
                    $result += array(
                        $media->url => array(
                            "expanded_url" => $media->media_url_https,
                            "display_url" => $media->display_url
                        )
                    );
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
    private function getUserUrls($userEntities)
    {
        $result = array();

        if (isset($userEntities)) {

            if (isset($userEntities->urls)) {
                foreach ($userEntities->urls as $url) {
                    $result += array(
                        $url->url => array(
                            "expanded_url" => $url->expanded_url,
                            "display_url" => $url->display_url
                        )
                    );
                }
            }

            if (isset($userEntities->description) && isset($userEntities->description->urls)) {
                foreach ($userEntities->description->urls as $url)
                    $result += array(
                        $url->url => array(
                            "expanded_url" => $url->expanded_url,
                            "display_url" => $url->display_url
                        )
                    );
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
    private function linkURLs($targetStr = "")
    {
        foreach ($this->URLs as $key => $value) {
//             echo $key."<br/>";
//             myVarDump($value);
            //             $targetStr = str_replace($key, '<a href="' . $value["expanded_url"] . '" target="_blank">' . $value["display_url"] . '</a>', $targetStr);
            $targetStr = str_replace($value["display_url"], '<a href="' . $value["expanded_url"] . '" target="_blank">' . $value["display_url"] . '</a>', $targetStr);
        }

        return $targetStr;
    }
}

