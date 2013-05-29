<?php
/**
 * Set access_id on an entity
 *
 * @uses $guid			guid of an entity to be updated
 * @uses $access_id		new access_id
 */
$guid = get_input('guid');
$entity = get_entity($guid);

if (!elgg_instanceof($entity) || !$entity->canEdit()) {
	register_error(elgg_echo('hj:framework:error:cantsetaccess'));
	forward(REFERRER, 'action');
}

$access_id = get_input('access_id');

$entity->access_id = $access_id;

if ($entity->save()) {
	system_message(elgg_echo('hj:framework:success:accessidset'));
} else {
	register_error(elgg_echo('hj:framework:error:cantsetaccess'));
}

forward(REFERRER, 'action');

