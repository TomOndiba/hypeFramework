<?php

$entity = elgg_extract('entity', $vars);

$icon = elgg_view_entity_icon($entity, 'small');
$title = '<h3>' . $entity->title . '</h3>';
$filename = '<div class="elgg-subtitle">' . $entity->filename . '</div>';
$description = elgg_view('output/longtext', array(
	'value' => $description
));

echo elgg_view_image_block($icon, $title . $description);
