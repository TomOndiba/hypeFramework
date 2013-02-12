<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!elgg_instanceof($entity) || !$entity->canEdit()) {
	register_error(elgg_echo('hj:framework:error:cantsetaccess'));
	forward(REFERRER);
}

$access_id = get_input('access_id');

$entity->access_id = $access_id;

if ($entity->save()) {
	system_message(elgg_echo('hj:framework:success:accessidset'));
} else {
	register_error(elgg_echo('hj:framework:error:cantsetaccess'));
}

forward(REFERRER);

