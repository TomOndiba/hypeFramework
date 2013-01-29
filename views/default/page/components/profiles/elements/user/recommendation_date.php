<?php

$entity = elgg_extract('entity', $vars);
$date = elgg_extract('recommendation_date', $vars, false);

if (!$date) {
	$connection = elgg_extract('connection', $vars);
	$date = $connection->time_created;
}

if (!$date) {
	return true;
}

echo '<div class="user-detail-recommendation-date"><strong class="en-theme-sprite sprite-calendar"></strong>' . date("F j, Y", $date) . '</div>';