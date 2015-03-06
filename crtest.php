<?php

   // create curl resource 
    $ch = curl_init(); 

    $url = "https://play.google.com/store/apps/category/GAME_ROLE_PLAYING/collection/topselling_free?hl=en";

    // set url 
    curl_setopt($ch, CURLOPT_URL, $url); 

    //return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // close curl resource to free up system resources 
    curl_close($ch);  

    echo $output;    

?>