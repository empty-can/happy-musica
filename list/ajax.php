<?php
require_once ("/xampp/htdocs/osaisen/lib/init.php");

$api = 'lists/members/create';
$screenName = getPostParam('screen_name', '');
$listId = getPostParam('listId', array());

$accessToken = getSessionParam('access_token');
$accessTokenSecret = getSessionParam('access_token_secret');

$result = array();

foreach ($listId as $id) {
    $param = array(
        "screen_name" => $screenName,
        "list_id" => $id
    );

    $result[] = $param;

    $lists = postData($accessToken, $accessTokenSecret, $api, $param);

//     $result[] = $lists;
}

echo json_encode( $screenName );