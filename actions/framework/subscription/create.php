<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if ($entity instanceof hjObject) {
	if ($entity->createSubscription()) {
		system_message(elgg_echo('hj:framework:subscription:create:success'));
		forward(REFERER);
	}
}

register_error(elgg_echo('hj:framework:subscription:create:error'));