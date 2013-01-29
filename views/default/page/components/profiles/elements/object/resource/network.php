<?php

$entity = elgg_extract('entity', $vars);
$container = $entity->getContainerEntity();

if (elgg_instanceof($container, 'group', 'network')) {
	$network = elgg_view('output/url', array(
		'href' => $container->getURL(),
		'text' => $container->code
			));
}

echo $network;