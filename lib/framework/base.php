<?php

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
 * 1.8.7
 */

function hj_framework_register_forms() {

	elgg_register_ajax_view('page/components/forms/form');

	hj_framework_register_entity_add_form('filefolder:edit', 'object', 'hjfilefolder');
	hj_framework_register_entity_add_form('file:edit', 'object', 'hjfile', 'default');

	// Configure forms
	elgg_register_plugin_hook_handler('init', 'form:filefolder:edit', 'hj_framework_forms_filefolder');
	elgg_register_plugin_hook_handler('init', 'form:file:edit', 'hj_framework_forms_file');

	$formconfig_id = elgg_get_plugin_setting('formconfig', 'hypeFramework');
	$formconfig = get_entity($formconfig_id);
	$forms = $formconfig->forms;

	global $CONFIG;

	foreach ($forms as $form) {
		$options = unserialize($form);
		if (!isset($CONFIG->framework['forms'][$options['name']])) {
			hj_framework_register_entity_add_form($options['name'], $options['subject_entity']['type'], $options['subject_entity']['subtype']);
			elgg_register_plugin_hook_handler('init', "form:{$options['name']}", 'hj_framework_init_forms');
		}

	}

}