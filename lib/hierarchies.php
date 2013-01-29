<?php

function hj_framework_create_hierarchy_breadcrumbs($guid) {

	$entity = get_entity($guid);
	if (!$entity) {
		return false;
	}

	$breadcrumbs = array("$guid");

	$container = $entity->getContainerEntity();

	while(elgg_instanceof($container)) {
		array_unshift($breadcrumbs, $container->guid);
		$container = $container->getContainerEntity();
	}

	create_metadata($entity->guid, 'breadcrumbs', json_encode($breadcrumbs), '', $entity->owner_guid, $entity->access_id);

	return $breadcrumbs;
	
}