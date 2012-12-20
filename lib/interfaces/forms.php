<?php

/**
 * Check if the forms configuration array has changed and rebuild forms
 */
function hj_framework_forms_check_updates() {
	$hash = elgg_get_plugin_setting('forms_hash', 'hypeFramework');
	$form_config = elgg_trigger_plugin_hook('forms:config', 'framework:forms', array(), null);

	$current_hash = sha1(serialize($form_config));

	if ($hash != $current_hash) {
		hj_framework_forms_regenerate_from_config($form_config);
		elgg_set_plugin_setting('forms_hash', $current_hash, 'hypeFramework');
	}
}

/**
 * Create/update forms from a configuration array
 * Use 'forms:config', 'framework:forms' plugin hook to modify the config
 * 
 * @param array $form_config
 */
function hj_framework_forms_regenerate_from_config($form_config = array()) {

	error_log('Renegerating forms ...');

	foreach ($form_config as $form_details) {

		$type = elgg_extract('subject_entity_type', $form_details, 'object');
		$subtype = elgg_extract('subject_entity_subtype', $form_details, 'hjformsubmission');
		$handler = elgg_extract('handler', $form_details, null);

		// Get an existing form, construct one if it doesn't exist
		$existing_form = hj_framework_get_data_pattern($type, $subtype, $handler);

		$form = new hjForm($existing_form->guid);

		foreach ($form_details as $key => $value) {
			if ($key == 'fields') {
				continue; // do not save fields as metadata
			}
			$form->$key = $value;
		}

		if ($form->save()) {
			$new_fields = elgg_extract('fields', $form_details);
			$new_fields_hash = sha1(serialize($new_fields));
			$old_fields_hash = $form->fields_hash;

			if ($new_fields_hash !== $old_fields_hash) { // fields config has changed
				// delete old fields
				$old_fields = $form->getFields();
				$old_fields_names = array();
				if ($old_fields) {
					foreach ($old_fields as $old_field) {

						if (!array_key_exists($old_field->name, $new_fields)) {
							error_log('To delete a field from a form set its value to false in the configuration array');
							error_log("Field $old_field->name will not be deleted");
						} else {
							$old_fields_names[$old_field->name] = $old_field;
						}
					}
					foreach ($new_fields as $new_field_name => $new_field_options) {
						if ($new_field_options === false) {
							error_log("Field $new_field_name was deleted from $form->title");
							$old_field->delete();
						} else {
							if (array_key_exists($new_field_name, $old_fields_names)) {
								error_log("Field $new_field_name was updated in $form->title");
								$form->updateField($new_field_options);
							} else {
								error_log("Field $new_field_name was added to $form->title");
								$form->addField($new_field_options);
							}
							if (isset($new_field_options['priority'])) {
								$form->getField($new_field_name)->priority = $new_field_options['priority'];
							}
						}
					}
				} else {
					foreach ($new_fields as $new_field_name => $new_field_options) {
						error_log("Field $new_field_name was added to $form->title");
						$form->addField($new_field_options);
						if (isset($new_field_options['priority'])) {
							$form->getField($new_field_name)->priority = $new_field_options['priority'];
						}
					}
				}
				$form->fields_hash = $new_fields_hash;
			}
		}
	}
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
	$forms = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'hjform',
		'limit' => 0
			));

	$options = array();
	//$options[] = 'select...';
	$options[] = elgg_echo('hj:formbuilder:formsdropdown:new');
	if (is_array($forms)) {
		foreach ($forms as $form) {
			//$form->delete();
			$options[$form->guid] = "$form->title";
		}
	}
	return $options;
}

function hj_formbuilder_get_forms_as_sections() {
	$forms = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'hjform',
		'limit' => 0
			));

	$core_subtypes = array('hjsegment', 'hjfile', 'hjfilefolder', 'hjcustommodule');

	$options = array();
	//$options[] = 'select...';
	if (is_array($forms)) {
		foreach ($forms as $form) {
			//$form->delete();
			if (!in_array($form->subject_entity_subtype, $core_subtypes)) {
				$options[$form->subject_entity_subtype] = "$form->subject_entity_subtype";
			}
		}
	}
	return $options;
}

/* * *********************************************
 *
 * 		FORMS 1.8.7
 *
 * ********************************************* */

/**
 * Registers a form for adding/editing an entity with a given type/subtype
 *
 * @param str $form_name
 * @param str $type
 * @param str $subtype
 * @return void
 */
function hj_framework_register_entity_add_form($form_name, $type, $subtype) {

	global $CONFIG;

	$CONFIG->framework['forms'][$form_name] = array(
		'type' => $type,
		'subtype' => $subtype
	);
}

/**
 * Does this entity have a registered form?
 *
 * @param ElggEntity $entity
 * @return bool
 */
function hj_framework_has_form($entity) {
	global $CONFIG;

	if (!isset($CONFIG->framework['forms'])) {
		return false;
	}

	foreach ($CONFIG->framework['forms'] as $form_name => $options) {
		if ($options['type'] == $entity->getType()
				&& $options['subtype'] == $entity->getSubtype()) {
			return true;
		}
	}

	return false;
}

/**
 * Initialize a form for adding a new entity with given type/subtype
 *
 * @param str $type
 * @param str $subtype
 * @param array $params Additional params
 * @return hjDynamicForm|false
 */
function hj_framework_get_entity_add_form($type, $subtype, $params = array()) {

	global $CONFIG;

	if (isset($CONFIG->framework['forms'])) {
		foreach ($CONFIG->framework['forms'] as $form_name => $options) {
			if ($options['type'] == $type
					&& $options['subtype'] == $subtype) {
				return hj_framework_init_form($form_name, null, $params);
			}
		}
	}
	
	$handler = elgg_extract('handler', $params, null);
	$form = hj_framework_get_data_pattern($type, $subtype, $handler);

	return $form;
}

/**
 * Render an add form for entity type/subtype
 *
 * @param str $type
 * @param str $subtype
 * @param array $params
 * @return str HTML of the form
 */
function hj_framework_view_entity_add_form($type, $subtype, $params = array()) {

	$form = hj_framework_get_entity_add_form($type, $subtype, $params);
	return hj_framework_view_form($form, $params);
}

/**
 * Initialize a form for editing an entity
 *
 * @param ElggEntity $entity
 * @param array $params
 * @return hjDynamicForm|false
 */
function hj_framework_get_entity_edit_form($entity, $params = array()) {
	global $CONFIG;

	if (!isset($CONFIG->framework['forms'])) {
		return false;
	}

	foreach ($CONFIG->framework['forms'] as $form_name => $options) {
		if ($options['type'] == $entity->getType()
				&& $options['subtype'] == $entity->getSubtype()) {
			return hj_framework_init_form($form_name, $entity, $params);
		}
	}

	return false;
}

/**
 * Render an edit entity form
 *
 * @param ElggEntity $entity
 * @param array $params
 * @return str HTML of the form
 */
function hj_framework_view_entity_edit_form($entity, $params = array()) {

	$form = hj_framework_get_entity_edit_form($entity, $params);
	return hj_framework_view_form($form, $params);
}

/**
 * Return an array of config options (type, subtype) associated with this form
 *
 * @param str $form_name
 * @return array
 */
function hj_framework_get_form_assoc_options($form_name) {
	global $CONFIG;
	return $CONFIG->framework['forms'][$form_name];
}

/**
 * Initialize a form object
 *
 * @param str $form_name
 * @param ElggEntity $entity
 * @param array $params additional params to pass to the 'init', 'form:$form_name' hook
 * @return hjDynamicForm
 */
function hj_framework_init_form($form_name, $entity = null, $params = array()) {
	$form = new hjDynamicForm($form_name, $entity, $params);
	return $form;
}

/**
 * Render a form
 *
 * @param hjDynamicForm $form
 * @return type
 */
function hj_framework_view_form($form, $params = array()) {

	if ($form instanceof hjDynamicForm) {
		$form_vars = $form->getFormVars();
		return elgg_view('input/form', $form_vars);
	} else if ($form instanceof hjForm) {
		$params['form_guid'] = $form->guid;
		$params = hj_framework_extract_params_from_params($params);
		return elgg_view_entity($form, $params);
	}
}

/**
 * Process a form and create/edit an entity
 *
 * @param str $form_name
 * @return ElggEntity
 */
function hj_framework_process_form($form_name) {

	$form = hj_framework_init_form($form_name);

// Skip these inputs when processing inputs
	$base_entity_inputs = array(
		//'form_name',
		'subject_guid', // Guid of an entity we are editing, no need to save as metadata
		'type', 'subtype', 'owner_guid', 'container_guid', 'access_id', 'title', 'description', // Base entity data
		'accesslevel', // In case each metadata needs to be stored with different access levels
		'fileoptions', // Additional options describing files in 'file' inputs
	);

	$subject_guid = get_input('subject_guid', null);

	switch (get_input('type', 'object')) {
		case 'object' :
			$entity = new ElggObject($subject_guid);
			break;

		case 'user' :
			$entity = new ElggUser($subject_guid);
			break;

		case 'group' :
			$entity = new ElggGroup($subject_guid);
			break;

		case 'site' :
			$entity = new ElggSite($subject_guid);
			break;

		default:
			return false;
			break;
	}
	if ($subject_guid) { // Entity already exists
		if (get_input('container_guid', 0) > 0) {
			$entity->container_guid = get_input('container_guid', null);
		}
		if ($title = get_input('title', false)) {
			$entity->title = $title;
		}
		if ($description = get_input('description', false)) {
			$entity->description = $description;
		}

		if ($access_id = get_input('access_id', false)) {
			$entity->access_id = $access_id;
		}
	} else { // Creating new entity
		$entity->subtype = get_input('subtype', 'hjformsubmission');

		if ($owner_guid = get_input('owner_guid', false)) {
			$entity->owner_guid = $owner_guid;
		}
		if ($container_guid = get_input('container_guid', false)) {
			$entity->container_guid = $container_guid;
		}

		$entity->title = get_input('title', '');
		$entity->description = get_input('description', '');

		$entity->access_id = get_input('access_id', ACCESS_DEFAULT);
	}
	$guid = $entity->save();

	if (!$guid) {
		return false;
	} else {
		$entity = get_entity($guid);

		hj_framework_set_entity_priority($entity);

		$accesslevel = get_input('accesslevel', false);

		$fieldsets = $form->getFieldsets();

		foreach ($fieldsets as $fieldset) {

			$inputs = elgg_extract('inputs', $fieldset, array());

			foreach ($inputs as $input) {

				$name = elgg_extract('name', $input, false);
				if (!$name)
					continue;

				if (in_array($name, $base_entity_inputs))
					continue;

				$type = elgg_extract('type', $input, 'text');

				$accesslevel_id = (isset($accesslevel[$name])) ? $accesslevel[$name] : $entity->access_id;

				$params = array(
					'name' => $name,
					'form' => $form,
					'input' => $input,
					'access_id' => $accesslevel_id,
					'entity' => $entity
				);

				if (!elgg_trigger_plugin_hook('process:input', "form:input:name:$name", $params, false)
						&& !elgg_trigger_plugin_hook('process:input', "form:input:type:$type", $params, false)) {

					$value = get_input($name);

					if (!$value)
						continue;

					elgg_delete_metadata(array(
						'entity_guid' => $entity->guid,
						'metadata_name' => $name
					));

					if (is_array($value)) {
						foreach ($value as $val) {
							if (!empty($val)) {
								create_metadata($entity->guid, $name, $val, '', $entity->owner_guid, $accesslevel_id, true);
							}
						}
					} else {
						create_metadata($entity->guid, $name, $value, '', $entity->owner_guid, $accesslevel_id);
					}
				}
			}
		}
		elgg_trigger_plugin_hook('process:form', "form:$form", array('form' => $form, 'entity' => $entity), null);
	}
	return $entity;
}

function hj_framework_get_registered_forms() {
	global $CONFIG;

	$hooks = $CONFIG->hooks['init'];

	$forms = array();
	foreach ($hooks as $hook => $handler) {

		$segments = explode(':', $hook);

		if ($segments[0] == 'form') {
			array_shift($segments);
			$forms[] = implode(':', $segments);
		}
	}
	return $forms;
}

// Custom input processing
elgg_register_plugin_hook_handler('process:input', 'form:input:type:tags', 'hj_framework_process_tags_input');
elgg_register_plugin_hook_handler('process:input', 'form:input:type:file', 'hj_framework_process_file_input');
elgg_register_plugin_hook_handler('process:input', 'form:input:name:entity_icon', 'hj_framework_process_entity_icon_input');
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
	$subject = elgg_echo('framework:form:submitted:subject', elgg_echo($form->title));

	$submissions_url = $form->getURL();
	$message = elgg_echo('framework:form:submitted:message', array($entity->getURL()));
	notify_user($to, $from, $subject, $message);

	return true;
}

function hj_framework_get_form_config_options($form_name) {
	$formconfig_id = elgg_get_plugin_setting('formconfig', 'hypeFramework');
	$formconfig = get_entity($formconfig_id);
	$forms = $formconfig->forms;

	foreach ($forms as $form) {
		$options = unserialize($form);
		if ($options['name'] == $form_name)
			return $options;
	}

	return false;
}