<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if ($entity instanceof hjObject) {
	if ($entity->isBookmarked()) {
		action('framework/bookmark/remove');
	} else {
		action('framework/bookmark/create');
	}
}

forward(REFERER);
