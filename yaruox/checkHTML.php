<?php
define("page", "http://www.yaruox.jp");
define("message1", "nclosed element");
define("message2", "tray end tag");


/**
 *
 * @param string $target
 * @param string $pattern
 * @return boolean
 */
function contains($target, $pattern)
{
    if (strpos($target, $pattern) === false)
        return false;
        else
            return true;
}

function getCheck($url) {
    $opts = array(
        'http'=>array(
            'method'=> "GET",
            'header'=> "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36\r\n"
        )
    );
    $context = stream_context_create($opts);

//     $url = "https://validator.w3.org/nu/?doc=".urlencode($url);

    mb_regex_encoding('UTF-8');
    $data = file_get_contents("https://validator.w3.org/nu/?doc=".urlencode($url), false, $context);

    $result = ((contains($data,message1))||(contains($data,message2)));

    if($result) {
        $tkns = explode("/", $url);
        $length = (int)count($tkns);
        $sakusha = (int)$length-4;
        $sakuhin = (int)$length-3;
        $page = (int)$length-2;
        file_put_contents('C:/xampp/htdocs/matome/pages/article/0/0/checked/'.$tkns[$sakusha].'_'.$tkns[$sakuhin].'_'.$tkns[$page].'.html', $data);
    }

    return $result;
}

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

//         var_dump(getCheck($url));

        if(getCheck($url)) {
            echo "Hit!\t$url\t\r\n";
        }

    }

    sleep(30);
}