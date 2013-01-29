<?php

$entity = elgg_extract('entity', $vars);
$owner = $entity->getOwnerEntity();

$icon = '';
if ($owner) {
	$icon = elgg_view_entity_icon($owner, 'small', array(
		'height' => 27,
		'width' => 27
			));
	$body .= elgg_view('output/url', array(
		'href' => $owner->getURL(),
		'text' => $owner->name,
		'is_trusted' => true,
		'target' => '_blank'
			));
	$body .= '<br />';
}
$body .= '<small>' . date("F j, Y - ga T", $entity->time_created) . '</small>';

echo elgg_view_image_block($icon, $body);