<?php
require_once ("../../lib/init.php");
require_once ("../auth.php");

echo json_decode(file_get_contents('https://publish.twitter.com/oembed?lang=ja&url=https%3A%2F%2Ftwitter.com%2Forenoyome%2Fstatus%2F' . getGetParam('tweetId')))->html;