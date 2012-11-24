<?php

$guid = get_input('entity_guid');
$params = hj_framework_decode_params_array(get_input('params'));

$context = elgg_extract('push_context', $params, 'framework');
unset($params['push_context']);
elgg_push_context($context);

$entity = get_entity($guid);
if (!elgg_instanceof($entity)) {
	forward('', '404');
}

$container = $entity->getContainerEntity();
if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, $container->getURL());
	$page_owner_guid = $container->guid;
} else {
	$user = $entity->getOwnerEntity();
	elgg_push_breadcrumb($user->name, $user->getURL());
	$page_owner_guid = $user->guid;
}

$title = $entity->title;
elgg_push_breadcrumb($title);

$content = elgg_view_entity($entity, $params);

if (!elgg_is_xhr()) {
	$layout = elgg_view_layout('content', array(
		'filter' => false,
		'title' => $entity->title,
		'content' => $content
			));

	echo elgg_view_page($title, $layout);
} else {
	$output['data'] = elgg_view_page('', $content);
	print(json_encode($output));
	forward();
}
