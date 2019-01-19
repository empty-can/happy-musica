<?php
require_once (dirname(__FILE__) . "/../../lib/init.php");
require_once 'C:\xampp7\google_api\test.php';

/**
 * リツイートの json を取得する
 *
 * @param Object $retweet
 */
function saveTweetJson($tweet = null)
{
    global $service;

    if (empty($tweet))
        return;

        $jsonPath = $tweet->id . '.json';

        if (searchFiles($service, TEST_JSON_DIR_ID, $tweet->id, 'json') == 0) {
            echo "★upload json:" . $tweet->id . "\r\n";
            uploadFiles($service, TEST_JSON_DIR_ID, json_encode($tweet), $tweet->id, 'json');
            return 1;
            //     }

            //     if (! file_exists($jsonPath)) {
            //         // if (! file_exists($jsonPath . '.zip')) {
            //         echo "★DL " . $jsonPath . "\r\n";
            //         file_put_contents($jsonPath, json_encode($tweet));
            //         return 1;
            // } else if (file_exists($jsonPath)) {
            // echo "Retweet info is already exists.\r\n";
            // file2Zip($jsonPath);
            // unlink($jsonPath);
        } else {
            return 0;
        }
}

/**
 * リツイートの 画像 を取得する
 *
 * @param Object $retweet
 */
function saveImg($media = null)
{
    global $service;

    if (empty($media))
        return;

        $imgPathTkn = explode("/", $media->media_url_https);
        $imgName = end($imgPathTkn);

        $tkn = explode('.', $imgName);

        if (searchFiles($service, TEST_IMG_DIR_ID, $tkn[0], $tkn[1]) == 0) {
            echo "★upload image:" . $media->media_url_https . "\r\n";
            uploadFiles($service, TEST_IMG_DIR_ID, file_get_contents($media->media_url_https), $tkn[0], $tkn[1]);
            return 1;
            //     }

            //     $imgPath = images . $imgName;

            //     if (! file_exists($imgPath)) {
            //         echo "★DL " . $imgPath . "\r\n";
            //         file_put_contents($imgPath, file_get_contents($media->media_url_https));
            //         return 1;
        } else {
            // echo $imgName . " is already exists.\r\n";
            return 0;
        }
}

function saveTweet($tweet)
{
    global $retweetCounter, $imgCounter, $totalCounter;

    $totalCounter ++;

    if (! isset($tweet->entities) && (! isset($tweet->entities->urls) || empty($tweet->entities->urls))) {
        echo "★★★no media tweet!★★★ " . getTweetUrlByTweet($tweet) . "\r\n";
    }

    $retweetCounter += saveTweetJson($tweet);

    if (isset($tweet->extended_entities)) {
        $extended_entities = $tweet->extended_entities;

        foreach ($extended_entities->media as $extended_media) {
            $imgCounter += saveImg($extended_media);
        }
    }

    if (isset($tweet->in_reply_to_status_id)) {
        // echo getTweetUrl($retweet->in_reply_to_screen_name, $retweet->in_reply_to_status_id);
        // echo getTweetUrl($tweet->in_reply_to_screen_name, $tweet->in_reply_to_status_id)."\r\n";
        return $tweet->in_reply_to_status_id;
    } else {
        return null;
    }
}