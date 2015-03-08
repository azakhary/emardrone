<?php

	ini_set('display_errors', '1');

	// show eisting collections
	$host = "mongodb://admin:1ePwQJx4KNaR@127.6.119.3:27017/";

    $m = new MongoClient($host);
    $db = $m->underwater;
    $collection = $db->checkpoints;

    $cursor = $collection->find(array("data_version" => "v1"));

	// iterate through the results
	foreach ($cursor as $document) {
		$date = date('Y-m-d H:i:s', $document["date"]->sec);
		if($document["description"] != null && $document["description"] > "") $document["description"] = "modification";
	    echo "Package: {$document["package"]}, Date: {$date}, Gplus: {$document["gplus"]}, Reviews: {$document["reviews"]}, D: {$document["description"]} </br>";
	}

?>