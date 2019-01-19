<?php

class TweetList
{

    private $startTweetId;

    private $endTweetId;

    private $tweets4view;

    private $calledNum;

    public function getTweet4View()
    {
        return $this->tweets4view;
    }

    public function getStartTweetId()
    {
        return $this->startTweetId;
    }

    public function getEndTweetId()
    {
        return $this->endTweetId;
    }

    public function getCalledNum()
    {
        return $this->calledNum;
    }

    /**
     * コンストラクタ
     *
     * @param unknown $tweet
     */
    public function __construct($userToken, $userTokenSecret, $twitterAPI, $twitterAPIParam, $maxId, $count, $maxCall)
    {
        $this->tweets4view = array();
        $this->startTweetId = 0;
        $this->endTweetId = 0;
        $this->calledNum = ((int) 0);

        $tweets = array();
        $index = array();

        $counter = ((int) 0);

        if ($maxId > 0) {
            $twitterAPIParam['max_id'] = $maxId;
            $this->endTweetId = $maxId;
        }

        while (true) {
            // myVarDump($twitterAPIParam);
            $tmp = obj2tweet(getTweetObjects($userToken, $userTokenSecret, $twitterAPI, $twitterAPIParam));

            if (isset($tmp['error'])) {
                setRequestParam('errorMessages', $tmp['error']);
                break;
            } else if (! is_array($tmp)) {
                break;
            }

            $tweets = array_merge($tweets, $tmp);
            $this->calledNum = ((int) $this->calledNum) + 1;

            // myVarDump($tweets);

            if (count($tweets) <= 0) {
                break;
            }

            foreach ($tweets as $tweet) {
                // print_r($tweet);
                // if($tweet->isReplyToSelf())
                // $tweets = array_merge($tweets, $tmp->getReplyTweet());

                $id = $tweet->getId();

                if ($this->startTweetId == 0)
                    $this->startTweetId = $id;

                $this->endTweetId = $tweet->getId();

                $originalTweet = $tweet->getOriginalTweet();

                // $this->endTweetId = $originalTweet->getId();

                // 同じツイートがReTweetされていた場合はスルー
                if (isset($index['ID:' . $originalTweet->getId()]))
                    continue;
                else
                    $index['ID:' . $originalTweet->getId()] = 'true';

                // メディアツイートでない場合はスルー
                if (! $originalTweet->isMediaTweet())
                    continue;
                else if (! empty($tweet->mediaURLs) && empty($tweet->mediaURLs['error']))
                    continue;

                $counter = ((int) $counter) + 1;

                if(!$originalTweet->hasMediaEntity())
                  continue;
                
                array_push($this->tweets4view, $originalTweet);

                // 重複ツイートを排除するための記録
                $index['ID:' . $id] = 'true';
                $index['ID:' . $tweet->getId()] = 'true';
            }

            if ($counter >= $count)
                break;
            else if (($this->calledNum >= $maxCall) && ($counter > 0))
                break;
            else if ($this->calledNum >= 10)
                break;

            $twitterAPIParam['max_id'] = $this->endTweetId;
        }
        // exit();

        if (count($this->tweets4view) > 0) {
            if ($index['ID:' . end($this->tweets4view)->getId()] == 'true')
                array_pop($this->tweets4view);
        } else {
            // $this->endTweetId = 0;
        }
    }
}