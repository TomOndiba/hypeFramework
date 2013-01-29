<?php

$entity = elgg_extract('entity', $vars);

$author = get_user($entity->member);

$icon = '';
if ($author) {
	$icon = elgg_view_entity_icon($author, 'small', array(
		'width' => 27,
		'height' => 27
	));
	$body .= elgg_view('output/url', array(
		'href' => $author->getURL(),
		'text' => $author->name,
		'is_trusted' => true,
	));
	$body .= '<br />';
} else {
	$body .= "$entity->contributor_firstname $entity->contributor_lastname<br />";
}

if (empty($body)) {
	$body .= "<span class=\"placeholder-dash\">-</span>";
}

$body .= '<small>' . date("g:ia F d, Y", $entity->time_created) . '</small>';

echo elgg_view_image_block($icon, $body);