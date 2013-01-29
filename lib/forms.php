<?php

/**
 * 	Rendering and validation of forms
 * 
 * @example mod/hypeFramework/documentation/examples/form.php An example of how to create / render form
 * @link mod/hypeFramework/views/default/framework/bootstrap/form.php
 */
function hj_framework_view_form($form_name, $params = array()) {

	$params = hj_framework_prepare_form($form_name, $params);

	$form = elgg_extract('form', $params, array());
	$attributes = elgg_extract('attributes', $form, array());

	$attributes['body'] = elgg_view('framework/bootstrap/form', $params);

	return elgg_view('input/form', $attributes);
	
}

function hj_framework_prepare_form($form_name, $params = array()) {

	$config = elgg_trigger_plugin_hook('init', "form:$form_name", $params, array());

	$fields = elgg_extract('fields', $config, array());

	$params['form'] = $config;
	$params['form_name'] = $form_name;

	// Get sticky values
	if (elgg_is_sticky_form($form_name)) {
		$sticky_values = elgg_get_sticky_values($form_name);
		elgg_clear_sticky_form($form_name);
	}

	// Get validation errors and messages
	$validation_status = hj_framework_get_form_validation_status($form_name);
	hj_framework_clear_form_validation_status($form_name);

	// Add missing entity attribute fields as hidden inputs
	$entity_attributes = array(
		'guid', 'type', 'subtype', 'owner_guid', 'container_guid', 'access_id', 'title', 'description'
	);

	foreach ($entity_attributes as $attr) {

		if (!array_key_exists($attr, $fields)) {

			$params['form']['fields'][$attr] = array(
				'name' => $attr,
				'input_type' => 'hidden',
				'value' => ($params['entity']) ? $params['entity']->$attr : get_input($attr, '')
			);
		}
	}

	// Prepare fields
	if ($fields && count($fields)) {

		foreach ($fields as $name => $options) {

			$field['name'] = $name;

			if (isset($sticky_values[$name])) {
				$field['sticky_value'] = $sticky_values[$name];
			}

			if (isset($validation_status[$name])) {
				$field['validation_status'] = $validation_status[$name];
			}

			if (is_string($options)) {  // field options are set as a callback function
				if (is_callable($options)) {
					$params['field'] = $field;
					$options = call_user_func($options, $params);
					$options['name'] = $name;
				} else {
					continue;
				}
			} else {
				$options = array_merge($options, $field);
			}

			$params['field'] = $options;

			// Walk through field options to see if value, options or options_values parameter was set as callback function
			array_walk($params['field'], 'hj_framework_map_callable_field_options', $params);

			if ($params['field']['input_type'] == 'file' || $params['field']['value_type'] == 'file') {
				$multipart = true;
			}

			$params['form']['fields'][$name] = $params['field'];

			unset($params['field']);
		}
	}

	if ($multipart) {
		$params['form']['attributes']['enctype'] = 'multipart/form-data';
	}

	if (!isset($params['form']['attributes']['action'])) {
		$params['form']['attributes']['action'] = 'action/framework/edit/object';
	}

	if (!isset($params['form']['attributes']['method'])) {
		$params['form']['attributes']['method'] = 'POST';
	}

	if (!isset($params['form']['attributes']['id'])) {
		$params['form']['attributes']['id'] = preg_replace('/[^a-z0-9\-]/i', '-', $form_name);
	}

	return $params;
}

/**
 * Validate the form before proceeding with the action
 * 
 * @param str $form_name
 * @return bool
 */
function hj_framework_validate_form($form_name = null) {

	if (!$form_name) {
		return false;
	}

	$form = hj_framework_prepare_form($form_name);
	$fields = $form['form']['fields'];

	if ($fields) {

		foreach ($fields as $name => $options) {

			$type = elgg_extract('input_type', $options, 'text');

			$valid['status'] = true;
			$valid['msg'] = elgg_echo('hj:framework:input:validation:success');


			if ($options['required'] === true || $options['required'] == 'required') {
				$value = get_input($name, '');

				if (empty($value)) {

					if (is_string($options['label'])) {
						$options['label'] = array('text' => $options['label']);
					}

					if (!isset($options['label']['text'])) {
						$options['label']['text'] = elgg_echo("$form_name:$name");
					}

					$valid['status'] = false;
					$valid['msg'] = elgg_echo('hj:framework:input:validation:error:requiredfieldisempty', array($options['label']['text']));
				}
			}

			$valid = elgg_trigger_plugin_hook('validate:input', "form:input:name:$name", array('form_name' => $form_name), $valid);
			$valid = elgg_trigger_plugin_hook('validate:input', "form:input:type:$type", array('form_name' => $form_name), $valid);

			hj_framework_set_form_validation_status($form_name, $name, $valid['status'], $valid['msg']);

			if ($valid['status'] === false) {
				$error = true;
			}
		}
	}

	if ($error) {
		register_error(elgg_echo('hj:framework:form:validation:error'));
		return false;
	}

	return true;
}

/**
 * Let the views know the validation status so that appropriated messages can be shown to the user
 *
 * @param str $form_name
 * @return mixed
 */
function hj_framework_get_form_validation_status($form_name) {

	$validation_status = null;

	if (isset($_SESSION['form_validation_status'][$form_name])) {
		$validation_status = $_SESSION['form_validation_status'][$form_name];
	}

	return $validation_status;
}

/**
 * Store field validation status and message
 *
 * @param str $form_name
 * @param str $field_name
 * @param bool $status
 * @param str $msg
 * @return void
 */
function hj_framework_set_form_validation_status($form_name, $field_name, $status = true, $msg = '') {

	if (!isset($_SESSION['form_validation_status'][$form_name])) {
		$_SESSION['form_validation_status'][$form_name] = array();
	}

	$_SESSION['form_validation_status'][$form_name][$field_name] = array(
		'status' => $status,
		'msg' => $msg
	);

	return;
}

/**
 * Clear validation status after informing the user about errors
 *
 * @param str $form_name
 * @return void
 */
function hj_framework_clear_form_validation_status($form_name) {

	if (isset($_SESSION['form_validation_status'][$form_name])) {
		unset($_SESSION['form_validation_status'][$form_name]);
	}

	return;
}

/**
 * Obtain an array of input types
 *
 * @return array
 */
function hj_framework_forms_input_types() {
	$types = array(
		'text',
		'plaintext',
		'longtext',
		'url',
		'email',
		'date',
		'dropdown',
		'tags',
		'checkboxes',
		'file',
		'hidden',
		'radio',
		'access',
		'entity_icon',
		'relationship_tags',
		'multifile'
	);

	$types = elgg_trigger_plugin_hook('hj:formbuilder:fieldtypes', 'all', null, $types);
	return $types;
}

function hj_framework_forms_filefolder_types() {
	$types = array('default', 'audio', 'video', 'photo', 'design', 'docs', 'powerpoint');
	$types = elgg_trigger_plugin_hook('hj:formbuilder:foldertypes', 'all', null, $types);
	return $types;
}

/**
 * Create an options_values array for a dropdown of available hjForms
 *
 * @return array 
 */
function hj_formbuilder_get_forms_as_dropdown() {
	return false;
}

function hj_formbuilder_get_forms_as_sections() {
	return false;
}

// Custom input processing
elgg_register_plugin_hook_handler('process:input', 'form:input:type:tags', 'hj_framework_process_tags_input');
elgg_register_plugin_hook_handler('process:input', 'form:input:type:file', 'hj_framework_process_file_input');
elgg_register_plugin_hook_handler('process:input', 'form:input:type:entity_icon', 'hj_framework_process_entity_icon_input');
elgg_register_plugin_hook_handler('process:input', 'form:input:name:category', 'hj_framework_process_category_input');
elgg_register_plugin_hook_handler('process:input', 'form:input:name:category_guid', 'hj_framework_process_category_input');
elgg_register_plugin_hook_handler('process:input', 'form:input:name:category_guids', 'hj_framework_process_category_input');
elgg_register_plugin_hook_handler('process:input', 'form:input:name:add_to_river', 'hj_framework_process_add_to_river_input');
elgg_register_plugin_hook_handler('process:input', 'form:input:name:notify_admins', 'hj_framework_process_notify_admins_input');

function hj_framework_process_tags_input($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);

	if (!elgg_instanceof($entity)) {
		return false;
	}

	$name = elgg_extract('name', $params, 'tags');
	$access_id = elgg_extract('access_id', $params);

	$value = get_input($name, '');

	$value = string_to_tag_array($value);

	if ($value) {
		foreach ($value as $val) {
			create_metadata($entity->guid, $name, $val, '', $entity->owner_guid, $access_id, true);
		}
	}

	return true;
}

function hj_framework_process_file_input($hook, $type, $return, $params) {

	if (!elgg_is_logged_in())
		return false;

	$entity = elgg_extract('entity', $params, false);

	if (!elgg_instanceof($entity))
		return false;

	global $_FILES;
	$name = elgg_extract('name', $params, 'file');

	$file = $_FILES[$name];

	// Maybe someone doesn't want us to save the file in this particular way
	if (!empty($file['name'])) {
		if (!elgg_trigger_plugin_hook('fileupload', 'form:input:file', array(
					'entity' => $entity,
					'file' => $file,
					'name' => $name), false
		)) {
			hj_framework_process_file_upload($file, $entity, $name);
		}
	}
	return true;
}

function hj_framework_process_entity_icon_input($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	$name = elgg_extract('name', $params);

	global $_FILES;
	if ((isset($_FILES[$name])) && (substr_count($_FILES[$name]['type'], 'image/'))) {
		hj_framework_generate_entity_icons($entity, $name);
	}
	return true;
}

function hj_framework_process_notify_admins_input($hook, $type, $return, $params) {

	$admins = elgg_get_admins();
	foreach ($admins as $admin) {
		$to[] = $admin->guid;
	}

	$form = elgg_extract('form', $params);
	$entity = elgg_extract('entity', $params);

	$from = elgg_get_config('site')->guid;
	$subject = elgg_echo('hj:framework:form:submitted:subject', elgg_echo($form->title));

	$submissions_url = $form->getURL();
	$message = elgg_echo('hj:framework:form:submitted:message', array($entity->getURL()));
	notify_user($to, $from, $subject, $message);

	return true;
}

/**
 * Helper function used to dynamically construct options arrays for form fields
 *
 * @see hj_framework_forms_init()
 *
 * @param mixed $value		Callback function or array
 * @param str	$key		Field name
 * @param mixed $params		An array of parameters recieved from the form view, including $entity (if any)
 */
function hj_framework_map_callable_field_options_array(&$value, $key, $params = array()) {
	if (is_string($value) && is_callable($value)) {
		$value = call_user_func($value, $params);
	}
}

/**
 * Helper function used to dynamically determine 'value', 'options', 'options_values' keys for the 'input/$input_type' view
 *
 * @param mixed $value		Callback function
 * @param str	$key		Field option name
 * @param mixed $params		An array of parameters received from the form view, include $entity (if any)
 */
function hj_framework_map_callable_field_options(&$value, $key, $params = array()) {
	$mappable_values = array('value', 'options', 'options_values');
	if (in_array($key, $mappable_values) && is_string($value) && is_callable($value)) {
		$value = call_user_func($value, $params);
	}
}

function hj_framework_process_add_to_river_input($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);
	$event = elgg_extract('event', $params, 'create');
	$name = elgg_extract('name', $params);

	$add_to_river = get_input($name, false);

	if (!$entity || !$add_to_river)
		return false;

	$view = "river/{$entity->getType()}/{$entity->getSubtype()}/$event";
	if (!elgg_view_exists($view)) {
		$view = "river/object/hjformsubmission/create";
	}
	add_to_river($view, "$event", elgg_get_logged_in_user_guid(), $entity->guid);

	return true;

}

function hj_framework_process_category_input($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);
	$name = elgg_extract('name', $params);

	$category_guids = get_input($name, 0);

	if ($category_guids && !is_array($category_guids)) {
		$category_guids = array($category_guids);
	}

	foreach ($category_guids as $key => $value) {
		$category_guids[$key] = (int)$value;
	}

	$current_categories = elgg_get_entities_from_relationship(array(
		'relationship' => 'filed_in',
		'relationship_guid' => $entity->guid,
			));

	if (is_array($current_categories)) {
		foreach ($current_categories as $current_category) {
			if (!in_array($current_category->guid, $category_guids)) {
				remove_entity_relationship($entity->guid, 'filed_in', $current_category->guid);
			}
		}
	}

	if ($category_guids) {
		foreach ($category_guids as $category_guid) {
			$category = get_entity($category_guid);
			while (elgg_instanceof($category)) {
				add_entity_relationship($entity->guid, 'filed_in', $category->guid);
				$category = $category->getContainerEntity();
			}
		}
	}

	return true;

}
