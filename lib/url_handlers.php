<?php

elgg_register_entity_url_handler('object', 'hjform', 'hj_framework_entity_url_handlers');
elgg_register_entity_url_handler('object', 'hjfield', 'hj_framework_entity_url_handlers');
elgg_register_entity_url_handler('object', 'hjfile', 'hj_framework_entity_url_handlers');
elgg_register_entity_url_handler('object', 'hjfilefolder', 'hj_framework_entity_url_handlers');
elgg_register_entity_url_handler('object', 'hjannotation', 'hj_framework_entity_url_handlers');
elgg_register_entity_url_handler('object', 'hjsegment', 'hj_framework_entity_url_handlers');


/**
 * Handler entity urls
 *
 * @param ElggEntity $entity
 * @return str
 */
function hj_framework_entity_url_handlers($entity) {

	switch ($entity->getSubtype()) {

		default :
			return '';
			break;

		case 'hjform' :
			return "$HJ_PAGEHANDLER/form/view/$entity->guid";
			break;

		case 'hjfield' :
			$form = $entity->getContainerEntity();
			if ($form) {
				return $form->getURL();
			}
			break;

		case 'hjfile' :
			return "$HJ_PAGEHANDLER/file/view/$entity->guid";
			break;

		case 'hjfilefolder' :
			return "$HJ_PAGEHANDLER/collection/view/$entity->guid";
			break;

		case 'hjsegment' :
			return "$HJ_PAGEHANDLER/segment/view/$entity->guid";
			break;

		case 'hjannotation' :
			$container = $entity->getContainerEntity();
			return $container->getURL() . "#annotation-$entity->guid";
			break;
	}
}
