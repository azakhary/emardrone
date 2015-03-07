<?php

ini_set('display_errors', '1');

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

function save_data_point_mongo($package, $reviews, $plus) {
    $host = "mongodb://admin:1ePwQJx4KNaR@127.6.119.3:27017/";

    $m = new MongoClient($host);
    $db = $m->underwater;
    $collection = $db->checkpoints;

    $document = array( "package" => $package, "date" => date("Y-m-d H:i:s"), "reviews" => $reviews, "gplus" => $plus );
    $collection->insert($document);
}

function read_targets() {
    $link = mysql_connect('127.6.119.2', 'adminmW9DPtq', '42T3X5F_Zxc-');
    mysql_select_db('underwater');

    $targets = array();

    // Performing SQL query
    $query = "SELECT * FROM `targets`";
    $result = mysql_query($query);
    var_dump(mysql_error());

    while($row = $result->fetch_assoc()) {
        $targets[] = $row['package'];
    }

    return $targets;
}

$targets = read_targets();

$package = $targets[0];

$plus_one_count = getPlusOne($package);
$review_count = getReviewCount($package);

save_data_point_mongo($package, $review_count, $plus_one_count);

echo "done";
?>