<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
	return true;
}

$type = $entity->getType();
$subtype = $entity->getSubtype();
if (!$subtype) $subtype = 'default';

$view = "page/components/profiles/tiles/$type/$subtype";

if (elgg_view_exists($view)) {
	echo elgg_view($view, $vars);
} else {
	echo elgg_view_entity($entity, array(
		'full_view' => false,
		'class' => 'tile-view'
	));
}

