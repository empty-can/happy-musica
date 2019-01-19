<?php
$pageName = array(
    "2Droom" => "二次絵絶対拡散するルーム"
    ,"2Dtimeline" => "二次絵絶対拡散するタイムライン"
    ,"2DumekomiTimeline" => "二次絵絶対拡散するタイムライン(埋め込み型)"
    ,"2Dfollower" => "二次絵絶対拡散するフォローリスト"
    ,"2DpublicUmekomiTimeline" => "公式APIを使った埋め込みタイムライン"
    ,"CSSIF" => "CSSだけでI/F作ってみた"
);


?>
<p><?php echo $pageName[$filename];?></p>
<ul>
<li><a href="../" target="_self">トップページ</a></li>
<?php
    if($pageName["2Droom"]!==$pageName[$filename]) {
?>
<li><a href="./2Droom.php" target="_self"><?php echo $pageName["2Droom"];?></a></li>
<?php
    }
    if($pageName["2Dtimeline"]!==$pageName[$filename]) {
?>
<li><a href="./2Dtimeline.php" target="_self"><?php echo $pageName["2Dtimeline"];?></a></li>
<?php
    }
    if($pageName["2DumekomiTimeline"]!==$pageName[$filename]) {
?>
<li><a href="./2DumekomiTimeline.php" target="_self"><?php echo $pageName["2DumekomiTimeline"];?></a></li>
<?php
    }
    if($pageName["2DpublicUmekomiTimeline"]!==$pageName[$filename]) {
?>
<li><a href="./2DpublicUmekomiTimeline.php" target="_self"><?php echo $pageName["2DpublicUmekomiTimeline"];?></a></li>
<?php
    }
    if($pageName["CSSIF"]!==$pageName[$filename]) {
?>
<li><a href="./CSSIF.php" target="_self"><?php echo $pageName["CSSIF"];?></a></li>
<?php
    }
    if($pageName["2Dfollower"]!==$pageName[$filename]) {
?>
<li><a href="./2Dfollower.php" target="_self"><?php echo $pageName["2Dfollower"];?></a></li>
<?php } ?>
</ul>