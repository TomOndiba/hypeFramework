<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity)
	return true;

echo elgg_view('output/longtext', array(
	'value' => $entity->description,
	'class' => 'elgg-entity-description',
));
