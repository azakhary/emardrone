<?php

    include "cUrl.php";

    $curl = new cUrl();

    $url = "https://play.google.com/store/apps/category/GAME_ROLE_PLAYING/collection/topselling_free?hl=en";

    // set url 
    $curl->loadUrl($url);

    $content = $curl->getData();

    $appName = cUrl::getBetweenString($content, '<div class="cover-inner-align"> <img alt="', '"');

    var_dump($appName);  

?>