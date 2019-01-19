<?php
use lib\tweet\Tweet;

class CollectionsTweetList
{

    private $startTweetId;

    private $endTweetId;

    private $tweets4view;

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

    /**
     * コンストラクタ
     *
     * @param unknown $tweets
     */
    public function __construct($resultObject)
    {
        $tweetObject = $resultObject->tweets;
        $users = $resultObject->users;
        $this->tweets4view = array();
        $this->startTweetId = 0;
        $this->endTweetId = 0;

        if (isset($tweetObject->error)) {
            setRequestParam('errorMessages', $tweetObject->error);
        } else {

            foreach ($tweetObject as $key => $value) {
        
                $tweet = new Tweet($value);
                
                // var_dump($value->user->id);
                // myVarDump($users->{$value->user->id});
                
                $tweet->setUser($users->{$value->user->id});
                
                $id = $tweet->getId();

                if ($this->startTweetId == 0)
                    $this->startTweetId = $id;

                $this->endTweetId = $tweet->getId();

                $originalTweet = $tweet->getOriginalTweet();

                if(!$originalTweet->hasMediaEntity())
                  continue;
                
                array_push($this->tweets4view, $originalTweet);
            }

            $twitterAPIParam['max_id'] = $this->endTweetId;
        }
    }
}