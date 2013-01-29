<?php
$entity = elgg_extract('entity', $vars);

$networks = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'subtype' => 'network',
	'limit' => 0,
	'relationship' => 'network_context',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true
		));

$networks_str = array();
foreach ($networks as $network) {
	$networks_str[] = elgg_view('output/url', array(
		'text' => $network->code,
		'href' => $network->getURL(),
		'class' => 'thread_network',
		'is_trusted' => true,
		'target' => '_blank'
			));
}
$network_str = implode(', ', $networks_str);

echo $network_str;