<?php

$entity = elgg_extract('entity', $vars);
$options = array(
	'relationship' => 'attachment',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => false,
);

$attached_to_entities = elgg_get_entities_from_relationship($options);

if ($attached_to_entities) {
	foreach ($attached_to_entities as $ent) {

		$attached_to_list[] = elgg_echo('item:object:' . $ent->getSubtype()) . ': ' . elgg_view('output/url', array(
					'text' => $ent->title,
					'href' => $ent->getURL()
				)) . ' by ' . elgg_view('output/url', array(
					'text' => $ent->getOwnerEntity()->name,
					'href' => $ent->getOwnerEntity()->getURL()
				));
	}
	$attached_to_str .= implode('<br />', $attached_to_list);
}

echo $attached_to_str;