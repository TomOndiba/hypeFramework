<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if ($entity instanceof hjObject) {
	if ($entity->createBookmark()) {
		system_message(elgg_echo('hj:framework:bookmark:create:success'));
		forward(REFERER);
	}
}

register_error(elgg_echo('hj:framework:bookmark:create:error'));