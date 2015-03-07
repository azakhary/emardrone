<?php

ini_set('display_errors', '1');

include "cUrl.php";

function getPlusOne($package) {
    $main_url = "https://apis.google.com/u/0/se/3/_/+1/fastbutton?usegapi=1&annotation=inline&recommendations=false&size=medium&source=google%3Amarket&hl=en&origin=https%3A%2F%2Fplay.google.com&url=https%3A%2F%2Fmarket.android.com%2Fdetails%3Fid%3D".$package."&gsrc=3p&ic=1";

    $html =  file_get_contents($main_url);

    $count = cUrl::getBetweenString($html, '<td class="gP"><span class="A8 eja">+', ' ');
    
    return $count;
}

function getPlayStoreHTML($package) {
    $url = "https://play.google.com/store/apps/details?id=$package&hl=en";

    $html =  file_get_contents($url);

    return $html;
}

function getReviewCount($package, $html) {

    $count = cUrl::getBetweenString($html, 'stars-count"> (<span class="reviewers-small"></span>', ')');

    $count = str_replace(',', '', $count);
    
    return $count;    
}

function getDescription($package, $html) {
    $description = cUrl::getBetweenString($html, '<div class="id-app-orig-desc">', '</');
    $description = str_replace("<br>", "\n", $description);
    return $description; 
}

function save_data_point_mongo($package, $reviews, $plus, $description) {
    $dt = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('UTC'));
    $ts = $dt->getTimestamp();
    $now = new MongoDate($ts);

    $host = "mongodb://admin:1ePwQJx4KNaR@127.6.119.3:27017/";

    $m = new MongoClient($host);
    $db = $m->underwater;
    $collection = $db->checkpoints;

    $document = array( "package" => $package, "date" => $now, "reviews" => $reviews, "gplus" => $plus, "description" => $description );
    $collection->insert($document);
}

function read_targets() {
    $link = mysqli_connect('127.6.119.2', 'adminmW9DPtq', '42T3X5F_Zxc-', 'underwater');

    $targets = array();

    // Performing SQL query
    $query = "SELECT * FROM `targets`";
    $result = mysqli_query($link, $query);

    while($row = $result->fetch_array()) {
        $targets[] = $row['package'];
    }

    return $targets;
}

$targets = read_targets();

$package = $targets[0];

$html = getPlayStoreHTML($package);

$plus_one_count = getPlusOne($package);
$review_count = getReviewCount($package, $html);
$description = getDescription($package, $html);

save_data_point_mongo($package, $review_count, $plus_one_count, $description);

echo "done";
?>