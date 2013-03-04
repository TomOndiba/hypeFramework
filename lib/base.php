<?php

/**
 * Check the current plugin release and load $plugin_id/lib/upgrade.php script if the release is newer
 *
 * @param str		$plugin_id		Plugin Name
 * @param str		$release		Release timestamp
 */
function hj_framework_check_release($plugin_id, $release) {

	if (!elgg_is_admin_logged_in()) {
		return false;
	}

	$old_release = elgg_get_plugin_setting('release', $plugin_id);

	if ($release > $old_release) {

		$shortcuts = hj_framework_path_shortcuts($plugin_id);
		$lib = "{$shortcuts['lib']}upgrade.php";

		elgg_register_library("hj:{$plugin_id}:upgrade", $lib);
		elgg_load_library("hj:{$plugin_id}:upgrade");

		elgg_set_plugin_setting('release', $release, $plugin_id);
	}

	return true;
}

/**
 * Get plugin tree shortcut urls
 *
 * @param string  $plugin     Plugin name string
 * @return array
 */
function hj_framework_path_shortcuts($plugin) {
	$path = elgg_get_plugins_path();
	$plugin_path = $path . $plugin . '/';

	return $structure = array(
		"actions" => "{$plugin_path}actions/",
		"classes" => "{$plugin_path}classes/",
		"graphics" => "{$plugin_path}graphics/",
		"languages" => "{$plugin_path}languages/",
		"lib" => "{$plugin_path}lib/",
		"pages" => "{$plugin_path}pages/",
		"vendors" => "{$plugin_path}vendors/"
	);
}

function hj_framework_decode_options_array(&$item, $key) {
	$item = htmlspecialchars_decode($item, ENT_QUOTES);
	if ($item == 'null') {
		$item = null;
	}
	if ($item == 'false') {
		$item = false;
	}
	if ($item == 'true') {
		$item = true;
	}
}

/**
 * Helper functions to manipulate entities
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category Framework Entities Library
 */

/**
 * Set priority of an element in a list
 *
 * @see ElggEntity::$priority
 *
 * @param ElggEntity $entity
 * @return bool
 */
function hj_framework_set_entity_priority($entity, $priority = null) {

	if ($priority) {
		$entity->priority = $priority;
		return true;
	}

	$count = elgg_get_entities(array(
		'type' => $entity->getType(),
		'subtype' => $entity->getSubtype(),
		'owner_guid' => $entity->owner_guid,
		'container_guid' => $entity->container_guid,
		'count' => true
			));

	if (!$entity->priority)
		$entity->priority = $count + 1;

	return true;
}

/**
 * Get a list of entities sorted by priority
 *
 * @param string $type
 * @param string $subtype
 * @param int $owner_guid
 * @param int $container_guid
 * @param int $limit
 * @return array An array of ElggEntity
 */
function hj_framework_get_entities_by_priority($options = array()) {

	if (!is_array($options) || empty($options)) {
		return false;
	}
	
	$defaults = array(
		'order_by_metadata' => array(
			'name' => 'priority', 'value' => 'ASC'
		)
	);

	$options = array_merge($defaults, $options);

	return elgg_get_entities_from_relationship($options);
}

function hj_framework_get_entities_from_metadata_by_priority($type = 'object', $subtype = null, $owner_guid = NULL, $container_guid = null, $metadata_name_value_pairs = null, $limit = 0, $offset = 0, $count = false) {
	if (is_array($metadata_name_value_pairs)) {
		$db_prefix = elgg_get_config('dbprefix');
		$entities = elgg_get_entities_from_metadata(array(
			'type' => $type,
			'subtype' => $subtype,
			'owner_guid' => $owner_guid,
			'container_guid' => $container_guid,
			'metadata_name_value_pairs' => $metadata_name_value_pairs,
			'limit' => $limit,
			'offset' => $offset,
			'count' => $count,
			'joins' => array("JOIN {$db_prefix}metadata as mt on e.guid = mt.entity_guid
                      JOIN {$db_prefix}metastrings as msn on mt.name_id = msn.id
                      JOIN {$db_prefix}metastrings as msv on mt.value_id = msv.id"
			),
			'wheres' => array("((msn.string = 'priority'))"),
			'order_by' => "CAST(msv.string AS SIGNED) ASC"
				));
	} else {
		$entities = hj_framework_get_entities_by_priority(array(
			'type' => $type,
			'subtype' => $subtype,
			'owner_guid' => $owner_guid,
			'container_guid' => $container_guid,
			'limit' => $limit
				));
	}
	return $entities;
}

/**
 * Get a an hjForm (data pattern) associated with this entity
 *
 * @param string $type
 * @param string $subtype
 * @param string $handler
 * @deprecated 1.8.6 Use @see hj_framework_get_form_by_name_by_entity_type()
 * @return hjForm
 */
function hj_framework_get_data_pattern($type, $subtype, $handler = null) {
	$forms = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'hjform',
		'metadata_name_value_pairs' => array(
			array(
				'name' => 'subject_entity_type',
				'value' => $type
			),
			array(
				'name' => 'subject_entity_subtype',
				'value' => $subtype
			),
			array(
				'name' => 'handler',
				'value' => $handler
			)
			)));

	if (sizeof($forms) > 1) {
		// Multiple instances of the same form
		// Need to update the entities to the latest form version
		hj_framework_update_data_pattern_references($forms);
	}

	return $forms[0];
}

/**
 * Several instances of the same form may lead to problems in rendering edit forms
 * We need to update the data_pattern metadata to the latest
 *
 * @param type $forms
 * @param type $type
 * @param type $subtype
 * @param type $handler
 */
function hj_framework_update_data_pattern_references($forms) {
	$ignore = elgg_get_ignore_access();
	elgg_set_ignore_access();

	$new_form = $forms[0]; // form to keep
	unset($forms[0]);

	foreach ($forms as $old_form) {

		$dbprefix = elgg_get_config('dbprefix');
		$entities = elgg_get_entities_from_metadata(array(
			'metadata_name' => 'data_pattern',
			'metadata_value' => $old_form->guid,
			'limit' => 0
				));

		foreach ($entities as $e) {
			$e->data_pattern = $new_form->guid;
		}

		$old_form->disable(); // not sure we want to delete it completely
	}

	elgg_set_ignore_access($ignore);

	return true;
}

function hj_framework_extract_params_from_entity($entity, $params = array(), $context = null) {
	$return = array();

	if ($context) {
		elgg_push_context($context);
	} else {
		$context = elgg_get_context();
	}

	if (elgg_instanceof($entity)) {

		$container = $entity->getContainerEntity();
		$owner = $entity->getOwnerEntity();
//        $form_guid = get_input('f', $entity->data_pattern);
		$form_guid = $entity->data_pattern;
		$form = get_entity($form_guid);
		if (elgg_instanceof($form)) {
			$fields = $form->getFields();
			$handler = $form->handler;
		}
		$widget = get_entity($entity->widget);

		$entity_params = array(
			'entity_guid' => $entity->guid,
			'subject_guid' => $entity->guid,
			'container_guid' => $container->guid,
			'owner_guid' => $owner->guid,
			'form_guid' => $form->guid,
			'widget_guid' => $widget->guid,
			'type' => $entity->getType(),
			'subtype' => $entity->getSubtype(),
			'context' => $context,
			'handler' => $handler,
			'event' => 'update'
		);

		$params = array_merge($entity_params, $params);
	}
	return $params;
}

function hj_framework_extract_params_from_url() {

	if ($params = get_input('params')) {
		return hj_framework_extract_params_from_params($params);
	}

	$context = get_input('context');
	if (!empty($context)) {
		elgg_push_context($context);
	} else {
		$context = elgg_get_context();
	}

	$section = get_input('subtype');
	if (empty($section)) {
		$section = "hj{$context}";
	}

	$handler = get_input('handler');
	if (empty($handler)) {
		$handler = '';
	}

	$subject_guid = get_input('subject_guid');
	$subject = get_entity($subject_guid);

	if ($entity_guid = get_input('entity_guid')) {
		$entity = get_entity($entity_guid);
		return hj_framework_extract_params_from_entity($entity, $params, $context);
	}

	$container_guid = get_input('container_guid');
	$container = get_entity($container_guid);
	if (!elgg_instanceof($container)) {
		$container = elgg_get_page_owner_entity();
	}

	$owner_guid = get_input('owner_guid');
	if (!empty($owner_guid)) {
		$owner = get_entity($owner_guid);
	} else if (elgg_instanceof($container)) {
		$owner = $container->getOwnerEntity();
	} else if (elgg_is_logged_in()) {
		$owner = elgg_get_logged_in_user_entity();
	} else {
		$owner = elgg_get_site_entity();
	}

	$form_guid = get_input('form_guid');
	$form = get_entity($form_guid);

	if (!elgg_instanceof($form)) {
		$form = hj_framework_get_data_pattern('object', $section, $handler);
	}
	if (elgg_instanceof($form)) {
		$fields = $form->getFields();
	}

	$widget_guid = get_input('widget_guid');
	$widget = get_entity($widget_guid);

	$url_params = array(
		'subject_guid' => $subject->guid,
		'container_guid' => $container->guid,
		'owner_guid' => $owner->guid,
		'form_guid' => $form->guid,
		'widget_guid' => $widget->guid,
		'subtype' => $section,
		'context' => $context,
		'handler' => $handler,
		'event' => 'create'
	);

	return $params;
}

function hj_framework_extract_params_from_params($params) {

	$context = $params['context'];
	if (!empty($context)) {
		elgg_push_context($context);
	} else {
		$context = elgg_get_context();
	}

	$section = $params['subtype'];
	if (empty($section)) {
		$section = "hj{$context}";
	}

	$handler = $params['handler'];
	if (empty($handler)) {
		$handler = '';
	}

	if (!$subject_guid = $params['subject_guid']) {
		$subject_guid = $params['entity_guid'];
	}
	$subject = get_entity($subject_guid);

	$container_guid = $params['container_guid'];
	$container = get_entity($container_guid);
	if (!elgg_instanceof($container)) {
		$container = elgg_get_page_owner_entity();
	}

	$owner_guid = $params['owner_guid'];
	if (!empty($owner_guid)) {
		$owner = get_entity($owner_guid);
	} else if (elgg_instanceof($container)) {
		$owner = $container->getOwnerEntity();
	} else if (elgg_is_logged_in()) {
		$owner = elgg_get_logged_in_user_entity();
	} else {
		$owner = elgg_get_site_entity();
	}

	$form_guid = $params['form_guid'];
	$form = get_entity($form_guid);
	if (!elgg_instanceof($form)) {
		$form = hj_framework_get_data_pattern('object', $section, $handler);
	}
	if (elgg_instanceof($form, 'object', 'hjforum')) {
		$fields = $form->getFields();
	}

	$widget_guid = $params['widget_type'];
	$widget = get_entity($widget_guid);

	$new_params = array(
		'subject_guid' => $subject->guid,
		'container_guid' => $container->guid,
		'owner_guid' => $owner->guid,
		'form_guid' => $form->guid,
		'widget_guid' => $widget->guid,
		'subtype' => $section,
		'context' => $context,
		'handler' => $handler,
		'event' => 'create'
	);

	$params = array_merge($new_params, $params);
	return $params;
}

function hj_framework_http_build_query($params) {
	if (isset($params['params'])) {
		$params = $params['params'];
	}
	foreach ($params as $key => $param) {
		if (isset($params[$key]) && !elgg_instanceof($param)) {
			$url_params['params'][$key] = $param;
		}
	}
	return http_build_query($url_params);
}

function hj_framework_json_query($params) {
	if (isset($params['params'])) {
		$params = $params['params'];
	}
	foreach ($params as $key => $param) {
		if (isset($params[$key]) && !elgg_instanceof($param)) {
			$url_params['params'][$key] = $param;
		}
	}
	return json_encode($url_params);
}

function hj_framework_decode_params_array($params) {
	foreach ($params as $key => $param) {
		if ($param == 'false') {
			$params[$key] = false;
		} else if ($params == 'true') {
			$params[$key] = true;
		}
	}
	return $params;
}

function hj_framework_get_email_url() {
	$extract = hj_framework_extract_params_from_url();
	$subject = elgg_extract('subject', $extract);

	if (elgg_instanceof($subject)) {
		return $subject->getURL();
	} else {
		return elgg_get_site_url();
	}
}

/**
 * Unset values from vars
 * @param array $vars
 * @return array
 */
function hj_framework_clean_vars($vars) {
	if (!is_array($vars)) {
		return $vars;
	}
	$vars = elgg_clean_vars($vars);
	$clean = array(
		'entity_guid',
		'subject_guid',
		'container_guid',
		'owner_guid',
		'form_guid',
		'widget_guid',
		'subtype',
		'context',
		'handler',
		'event',
		'form_name'
	);
	foreach ($clean as $key) {
		unset($vars[$key]);
	}
	return $vars;
}

/**
 * Replacement for Elgg'd native function which returns a malformatted url
 */
function hj_framework_http_remove_url_query_element($url, $element) {
	$url_array = parse_url($url);

	if (isset($url_array['query'])) {
		$query = elgg_parse_str($url_array['query']);
	} else {
		// nothing to remove. Return original URL.
		return $url;
	}

	if (array_key_exists($element, $query)) {
		unset($query[$element]);
	}

	$url_array['query'] = http_build_query($query);
	$string = elgg_http_build_url($url_array, false);
	return $string;
}