<?php

$entity = elgg_extract('entity', $vars);

if ($entity->getSubtype() == 'file') {
	$actions .= elgg_view('output/url', array(
		'text' => 'Download',
		'href' => "file/download/$entity->guid",
		'class' => 'elgg-button elgg-button-action dashboard-action'
			));
} else if ($entity->getSubtype() == 'bookmarks') {
	$actions .= elgg_view('output/url', array(
		'text' => 'Visit Resource',
		'href' => $entity->address,
		'target' => '_blank',
		'class' => 'elgg-button elgg-button-action dashboard-action'
			));
}

echo $actions;
