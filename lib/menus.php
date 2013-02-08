<?php

elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_framework_entity_menu');
elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_framework_entity_menu_cleanup', 999);

function hj_framework_entity_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	if (!$entity instanceof hjObject) {
		return $return;
	}

	if (!$entity->canEdit()) {
		return $return;
	}

	global $CONFIG;

	$sort_by = 'priority';

	if (isset($CONFIG->menus['entity'])) {
		$menu = $CONFIG->menus['entity'];
	} else {
		$menu = array();
	}

	$vars['entity'] = $entity;
	$return = elgg_trigger_plugin_hook('register', "menu:entity", $vars, $menu);

	$full = elgg_extract('full_view', $params, false);

	$items = array(
		'options' => ($full) ? null : array(
			'text' => elgg_view_icon('hjtoggler-down'),
			'href' => "#elgg-entity-$entity->guid",
				),
		'access' => false,
		'edit' => array(
			'text' => elgg_echo('edit'),
			'href' => $entity->getEditURL(),
			'parent_name' => ($full) ? null : 'options',
			'class' => ($full) ? null : 'elgg-button-edit-entity',
			'data-toggle' => 'dialog',
			'data-callback' => ($full) ? null : 'refresh:lists::framework',
			'data-uid' => $entity->guid,
			'priority' => 995
		),
		'delete' => array(
			'text' => elgg_echo('delete'),
			'href' => $entity->getDeleteURL(),
			'parent_name' => ($full) ? null : 'options',
			'class' => ($full) ? 'elgg-requires-confirmation' : 'elgg-button-delete-entity',
			'data-uid' => $entity->guid,
			'priority' => 1000
		)
	);

	if ($items) {
		foreach ($items as $name => $item) {
			foreach ($return as $key => $val) {
				if (!$val instanceof ElggMenuItem) {
					unset($return[$key]);
				}
				if ($val instanceof ElggMenuItem && $val->getName() == $name) {
					unset($return[$key]);
				}
			}
			$item['name'] = $name;
			$return[$name] = ElggMenuItem::factory($item);
		}
	}
	return $return;
}

function hj_framework_entity_menu_cleanup($hook, $type, $return, $params) {

	foreach ($return as $key => $val) {
		if (!$val instanceof ElggMenuItem) {
			unset($return[$key]);
		}
	}

	foreach ($return as $key => $item) {

		if ($item->getName() == 'options') {
			continue;
		}
		if (!$params['full_view']) {
			$item->setParentName('options');
		} else {
			$item->addLinkClass('elgg-button elgg-button-action');
		}
		$item->setSection('default');
		$return[$key] = $item;
		
	}

	return $return;
}