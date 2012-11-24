<?php

$entity_guid = get_input('entity_guid');
$entity = get_entity($entity_guid);

$type = get_input('type', 'object');
$subtype = get_input('subtype', 'hjformsubmission');
$handler = get_input('handler', 'default');

if ($entity) {
	$container = $entity->getContainerEntity();
	elgg_push_breadcrumb($entity->title, $entity->getURL());
	elgg_push_breadcrumb(elgg_echo('edit'));
	$title = elgg_echo('entity:edit', array($entity->title));
} else {
	$title = elgg_echo("$type:$subtype:$handler:add");
}

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, $container->getURL());
	$page_owner_guid = $container->guid;
} else {
	$user = elgg_get_logged_in_user_entity();
	elgg_push_breadcrumb($user->name, $user->getURL());
	$page_owner_guid = $user->guid;
}

$content = hj_framework_view_form("$type/$subtype/$handler/edit", array(), array(
	'entity' => $entity
		));

if (!elgg_is_xhr()) {
	$layout = elgg_view_layout('content', array(
		'filter' => false,
		'title' => $title,
		'content' => $content
			));

	echo elgg_view_page($title, $layout);
} else {
	$output['data'] = $content;
	print(json_encode($output));
	forward();
}