<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");

$screen_name = getSessionParam('screen_name', "orenoyome");
$screen_name = getGetParam('screen_name', $screen_name);

setSessionParam('screen_name', $screen_name);

$param = array(
    "screen_name" => $screen_name
);

$list = getTweetObjects(PublicUserToken, PublicUserTokenSecret, "followers/list", $param)->users;