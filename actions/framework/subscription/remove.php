<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if ($entity instanceof hjObject) {
	if ($entity->removeSubscription()) {
		system_message(elgg_echo('hj:framework:subscription:remove:success'));
		forward(REFERER);
	}
}

register_error(elgg_echo('hj:framework:subscription:remove:error'));