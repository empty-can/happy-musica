<?php
define("page", "http://www.yaruox.jp");
define("index", page . "/matome/article/");


mb_regex_encoding('UTF-8');
preg_match_all('/\/matome\/article\/[0-9]+\/[0-9]+\/0\//', file_get_contents(page."/matome/top/"), $pages);

foreach ($pages[0] as $page) {
    $url = page . $page;
    $content = file_get_contents($url);
    preg_match_all('/<p><a href="\/matome\/article\/[0-9]+\/[0-9]+\/[0-9]+\/">/', $content, $articles);

    $beforeMaxID = 0;
    $beforeMinID = 0;

    foreach ($articles[0] as $article) {
        $tkn = explode('"', $article);

        $url = page . $tkn[1];

        $ids = getIds(file_get_contents($url));

        if ((($ids[1] - $beforeMaxID) < 3000) && (($ids[1] - $beforeMaxID) > ($ids[0] * 1.5))) {
            // echo "$url:\r\n";
            // echo "\t".($ids[0]-$ids[1])."\r\n";
            // echo "\t$beforeMaxID->$ids[0]:\t".($ids[0]-$beforeMaxID)."\r\n";

            file_put_contents('C:\xampp\htdocs\matome\pages\article\0\0\diff.csv', "$url:\t$ids[0]\t" . ($ids[1] - $beforeMaxID) . "\r\n", FILE_APPEND);
            echo "$url:\t$ids[0]\t" . ($ids[1] - $beforeMaxID) . "\r\n";
        }

        $beforeMaxID = $ids[1];
        $beforeMinID = $ids[2];
    }
}

function getIds($text)
{
    mb_regex_encoding('UTF-8');

    preg_match_all('/\n<div id\=\"[0-9]{1,4}\"/', $text, $ids);

    $max_id = (int) 0;
    $min_id = (int) 100000;
    $id_count = (int) 0;

    foreach ($ids[0] as $id) {
        $tkn = explode('"', $id);
        $tmp = (int) $tkn[1];

        if ($max_id < $tmp)
            $max_id = $tmp;

        if ($min_id > $tmp)
            $min_id = $tmp;

        $id_count += (int) 1;
    }

    return array(
        $id_count,
        $max_id,
        $min_id
    );
}