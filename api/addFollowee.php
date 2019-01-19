<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");
use Abraham\TwitterOAuth\TwitterOAuth;

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');

$osaisenListId = getGetParam('listId', '');
$targetScreenName = getGetParam('targetScreenName', '');

$param = array(
    'list_id' => $osaisenListId,
    'screen_name' => $targetScreenName
);

$user_connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $accessToken, $accessTokenSecret);
$result = $user_connection->post('lists/members/create', $param);


echo json_encode( $result );