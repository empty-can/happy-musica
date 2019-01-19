<?php
require_once ("../lib/init.php");
?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>二次絵絶対拡散するフォロワー</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>

<![endif]-->
<style>
</style>
</head>
<body>
<?php
$filename = pathinfo(__FILE__, PATHINFO_FILENAME);
include './2Dheader.php';

printErrorMessages("color:red;font-weight:bold;");
?>
<table border="1">
<?php

    $connection = getTwitterConnection();
    // 横島ボットのフォロワー一覧
    $oObj = $connection->get("followers/list", [
    "screen_name" => "orenoyome"
    , "count" => "50"
    ]);

    foreach ($oObj->users as $tmp) {
        //var_dump($tmp);
            ?>
            <tr>
            	<td>
            <img src="<?php echo $tmp->profile_image_url_https;?>" alt="<?php echo $tmp->name;?>" />
            <?php echo $tmp->name;?>　
            @<?php echo $tmp->screen_name;?>
            	</td>
            </tr>
            <tr>
            	<td>
            <?php echo $tmp->description;?>
            	</td>
            </tr>
            <?php
    }
    ?>
    </table>
	<p><a href="./" target="_self">トップページページ</a></p>
</body>
</html>