<?php

include "cUrl.php";

function getPlusOne($package) {
    $main_url = "https://apis.google.com/u/0/se/3/_/+1/fastbutton?usegapi=1&annotation=inline&recommendations=false&size=medium&source=google%3Amarket&hl=en&origin=https%3A%2F%2Fplay.google.com&url=https%3A%2F%2Fmarket.android.com%2Fdetails%3Fid%3D".$package."&gsrc=3p&ic=1";

    $html =  file_get_contents($main_url);

    $count = cUrl::getBetweenString($html, '<td class="gP"><span class="A8 eja">+', ' ');
    
    return $count;
}

function getReviewCount($package) {
    $url = "https://play.google.com/store/apps/details?id=$package&hl=en";

    $html =  file_get_contents($url);

    $count = cUrl::getBetweenString($html, 'stars-count"> (<span class="reviewers-small"></span>', ')');

    $count = str_replace(',', '', $count);
    
    return $count;    
}

function save_data_point_sql($reviews, $plus) {
    $link = mysql_connect('127.6.119.2', 'adminmW9DPtq', '42T3X5F_Zxc-');
    mysql_select_db('underwater');

    // Performing SQL query
    $query = "INSERT INTO checkpoints (`date`, `reviews`, `checkpoints`) VALUES(NOW(), '$reviews' , '$plus')";
    $result = mysql_query($query);
}

$package = "com.gamehivecorp.taptitans";

$plus_one_count = getPlusOne($package);
$review_count = getReviewCount($package);

save_data_point_sql($review_count, $plus_one_count);


?>