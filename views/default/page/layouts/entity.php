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

$title = elgg_view_title($title, array('class' => 'elgg-heading-main'));

$buttons = elgg_view_menu('title', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
	'entity' => $entity
		));

$header = <<<HTML
<div class="elgg-head clearfix">
	$title$buttons
</div>
HTML;

$entity->logView();

$params = array(
	'title' => $entity->getTitle() . $menu,
	'content' => elgg_view_entity($entity, array(
		'full_view' => true
	)),
	'filter' => false,
	'header_override' => $header
);

$params = array_merge($vars, $params);

echo elgg_view('page/layouts/content', $params);