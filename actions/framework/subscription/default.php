<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if ($entity instanceof hjObject) {
	if ($entity->isSubscribed()) {
		action('framework/subscription/remove');
	} else {
		action('framework/subscription/create');
	}
}

forward(REFERER);
