<?php

$type = get_input('entity_type', 'default');
$subtype = get_input('entity_subtype', 'default');

$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());
$container = get_entity($container_guid);

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, $container->getURL());
	$page_owner_guid = $container->guid;
} else {
	$user = elgg_get_logged_in_user_entity();
	elgg_push_breadcrumb($user->name, $user->getURL());
	$page_owner_guid = $user->guid;
}

elgg_set_page_owner_guid($page_owner_guid);

$title = elgg_echo("$type:$subtype:add");
elgg_push_breadcrumb($title);

$content = hj_framework_view_entity_add_form($type, $subtype, array(
	'container' => $container
		));

$layout = elgg_view_layout('content', array(
	'filter' => false,
	'title' => $title,
	'content' => $content
		));

echo elgg_view_page($title, $layout);
