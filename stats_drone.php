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

$package = "com.gamehivecorp.taptitans";

$plus_one_count = getPlusOne($package);
$review_count = getReviewCount($package);

echo "Plus Ones: $plus_one_count \n";
echo "Reviews: $review_count";

?>