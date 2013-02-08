<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity instanceof hjObject) {
	return true;
}

if (!$entity instanceof hjObject) {
	return false;
}

$ancestry = hj_framework_get_ancestry($entity->guid);
foreach ($ancestry as $ancestor) {
	if (elgg_instanceof($ancestor, 'site')) {
		// do nothing
	} else if (elgg_instanceof($ancestor, 'group')) {
		elgg_set_page_owner_guid($ancestor->guid);
		elgg_push_breadcrumb($ancestor->name, $ancestor->getURL());
	} else if (elgg_instanceof($ancestor, 'object')) {
		elgg_push_breadcrumb($ancestor->title, $ancestor->getURL());
	}
}

$title = $entity->getTitle();
elgg_push_breadcrumb($title);

$menu = elgg_view_menu('hjentityhead', array(
	'entity' => $entity,
	'full_view' => true,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-title elgg-menu-hz'
		));

$entity->logView();

$params = array(
	'title' => $entity->getTitle() . $menu,
	'content' => elgg_view_entity($entity, array(
		'full_view' => true
	)),
	'filter' => false
);

echo elgg_view('page/layouts/content', $params);