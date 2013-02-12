<?php

elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_framework_entity_menu');
elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_framework_entity_dropdown_menu', 999);

elgg_register_plugin_hook_handler('register', 'menu:title', 'hj_framework_entity_title_menu');

function hj_framework_entity_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	if (!$entity instanceof hjObject) {
		return $return;
	}

	if (!$entity->canEdit()) {
		return $return;
	}

	global $CONFIG;
	if (isset($CONFIG->menus['entity'])) {
		$menu = $CONFIG->menus['entity'];
	} else {
		$menu = array();
	}

	$vars['entity'] = $entity;
	$return = elgg_trigger_plugin_hook('register', "menu:entity", $vars, $menu);

	$items = array(
		'options' => array(
			'text' => elgg_view_icon('settings'),
			'href' => "#elgg-entity-$entity->guid",
			'priority' => 500
		),
		'edit' => array(
			'text' => elgg_echo('edit'),
			'href' => $entity->getEditURL(),
			'parent_name' => 'options',
			'class' => 'elgg-button-edit-entity',
			'data-toggle' => 'dialog',
			'data-callback' => 'refresh:lists::framework',
			'data-uid' => $entity->guid,
			'priority' => 995
		),
		'delete' => array(
			'text' => elgg_echo('delete'),
			'href' => $entity->getDeleteURL(),
			'parent_name' => 'options',
			'class' => 'elgg-button-delete-entity',
			'data-uid' => $entity->guid,
			'priority' => 1000
		)
	);

//	if ($entity->canEdit()) {
//		$items['access'] = array(
//			'text' => elgg_view_icon('eye'),
//			'href' => "#elgg-entity-$entity->guid",
//			'priority' => 100
//		);
//		$access = get_write_access_array();
//
//		foreach ($access as $val => $str) {
//			$items["access:$val"] = array(
//				'text' => $str,
//				'href' => "action/framework/access/set?guid=$entity->guid&access_id=$val",
//				'is_action' => true,
//				'parent_name' => 'access',
//				'class' => 'elgg-button-edit-access',
//				'selected' => ($entity->access_id == $val)
//			);
//		}
//	} else {
//		$items['access'] = false;
//	}

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

function hj_framework_entity_dropdown_menu($hook, $type, $return, $params) {

	$dropdown = elgg_extract('dropdown', $params, false);

	if (!$dropdown) {
		foreach ($return as $name => $item) {
			if (!$item instanceof ElggMenuItem) {
				unset($return[$name]);
			} else {
				$parent = $item->getParentName();
				if ($parent) {
					$item->setParentName('');
					$parents[] = $parent;
				}
				$return[$name] = $item;
			}
		}
		foreach ($return as $name => $item) {
			if (in_array($item->getName(), $parents)) {
				unset($return[$name]);
			}
		}
	}

	return $return;
}

function hj_framework_entity_title_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	if (!$entity instanceof hjObject) {
		return $return;
	}

	if (!$entity->canEdit()) {
		return $return;
	}

	global $CONFIG;
	if (isset($CONFIG->menus['entity'])) {
		$menu = $CONFIG->menus['entity'];
	} else {
		$menu = array();
	}

	$vars['entity'] = $entity;
	$return = elgg_trigger_plugin_hook('register', "menu:entity", $vars, $menu);

	$items = array(
		'edit' => array(
			'text' => elgg_echo('edit'),
			'href' => $entity->getEditURL(),
			'class' => 'elgg-button elgg-button-action elgg-button-edit-entity',
			'data-toggle' => 'dialog',
			'data-uid' => $entity->guid,
			'priority' => 995
		),
		'delete' => array(
			'text' => elgg_echo('delete'),
			'href' => $entity->getDeleteURL(),
			'class' => 'elgg-button elgg-button-delete elgg-requires-confirmation',
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
