<?php

$entity = elgg_extract('entity', $vars);

if (!elgg_in_context('forum')) {
	$forum = $entity->getContainerEntity();
	$network = $forum->getContainerEntity();

	$forum_name = $forum->title;

	if (!elgg_instanceof($network, 'site')) {
		$forum_name = "$network->code: $forum->title";
	}

	echo '<b>' . $forum_name . '</b><br />';
}