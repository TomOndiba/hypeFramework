<?php

function hj_framework_init_forms($hook, $type, $return, $params) {

	$form = elgg_extract('form', $params);
	$entity = elgg_extract('entity', $params);

	$options = hj_framework_get_form_config_options($form->getName());

	$form->setAction($options['attributes']['action']);
	$form->setTitle($options['attributes']['title']);
	$form->setDescription($options['attributes']['description']);
	
	foreach ($options['inputs'] as $name => $input) {
		$type = $input['type'];
		$required = $input['required'];
		unset($input['type']);
		unset($input['required']);

		$form->registerInput($name, $type, $required, $input);
	}
}

/**
 * Initialize a filefolder form
 *
 * @param str $hook 'init'
 * @param str $type 'form:hjfilefolder:edit'
 * @param mixed $return null
 * @param hjDynamicForm $form
 * @return void
 */
function hj_framework_forms_filefolder($hook, $type, $return, $params) {

	$form = elgg_extract('form', $params);
	
	$form->setData('title', false);
	$form->setData('description', false);

//$form->setData('title', 'forms:filefolder');
//$form->setData('description', 'forms:filefolder:description');
//$form->setAction();
//$form->setMethod();
// Name of the Input Must be Unique
	$form->registerInput('title', array(
// Label that defines this field
		'label' => array(
			'for' => 'title',
			'text' => 'filefolder:title:label',
			'class' => null, // additional class to attach to the label
// other key=>value attributes that will be parsed into the <label> tag
		),
		// Tips to display with the input
		'hint' => array(
			'type' => 'text', // text|tooltip
			'text' => 'filefolder:title:hint',
			'class' => null,
		// other key=>value attributes that will be parsed into the <div> tag
		),
		// Input view parameters
		'input' => array(
// other key=>value attributes that will be parsed into the <input>, <textarea> or <select> tag
		),
		'type' => 'text', // Elgg input type
		'required' => true,
		'disabled' => false,
		'class' => null,
		'value' => '', // string or callback function
		'options_values' => false, /// string or callback function
		'overwrite_view' => false, // by default 'input/$type' is used
		'placeholder' => 'filefolder:title:placeholder',
		'output' => array(
			'type' => 'text',
			'overwrite_view' => false
		),
		'fieldset' => 'default', // fieldset to add this input to
		'overwrite_views' => array(
			'wrapper' => false,
			'label' => false,
			'hint' => false,
			'input' => false,
			'output' => false
		),
		'priority' => 100
	));

	$form->registerInput('description', array(
		'label' => array(
			'text' => 'filefolder:description',
		),
		'input' => array(
			'type' => 'longtext',
			'name' => 'description',
			'required' => true,
			'class' => 'elgg-input-longtext',
		),
		'priority' => 150
	));

	$form->registerInput('tags', array(
		'label' => array(
			'text' => 'filefolder:tags',
		),
		'input' => array(
			'type' => 'tags',
			'name' => 'tags',
		),
		'priority' => 200
	));

	$form->registerInput('datatype', array(
		'label' => array(
			'text' => 'filefolder:datatype',
		),
		'input' => array(
			'type' => 'dropdown',
			'name' => 'datatype',
			'options_values' => 'hj_formbuilder_get_filefolder_types', // callback function or array
		),
		'priority' => 250
	));

	$form->registerInput('access_id', array(
		'label' => array(
			'text' => 'filefolder:access',
		),
		'input' => array(
			'type' => 'access',
			'name' => 'access_id',
		),
		'priority' => 300
	));

	$form->setValidationAttr('comments_on', false);

//	$form->registerFieldset('default', array(
//				'default' => array(
//					'legend' => false,
//					'class' => null,
//					'title' => null,
//					'overwrite_view' => false,
//					'priority' => 100
//				)
//			));

	return $return;
}

/**
 *  Generic handler of entity icons
 */
function hj_framework_entity_icons($hook, $type, $return, $params) {
	$entity = $params['entity'];
	$size = $params['size'];

	if (!elgg_instanceof($entity)) {
		return $return;
	}

	switch ($entity->getSubtype()) {
		case 'hjfile' :
			if ($entity->simpletype == 'image') {
				return "mod/hypeFramework/pages/file/icon.php?guid={$entity->guid}&size={$size}";
			}

			$mapping = hj_framework_mime_mapping();

			$mime = $entity->mimetype;
			if ($mime) {
				$base_type = substr($mime, 0, strpos($mime, '/'));
			} else {
				$mime = 'none';
				$base_type = 'none';
			}

			if (isset($mapping[$mime])) {
				$type = $mapping[$mime];
			} elseif (isset($mapping[$base_type])) {
				$type = $mapping[$base_type];
			} else {
				$type = 'general';
			}

			$url = "mod/hypeFramework/graphics/mime/{$size}/{$type}.png";
			return $url;

			break;

		case 'hjfilefolder' :

			$type = $folder->datatype;
			if (!$type)
				$type = "default";

			$url = "mod/hypeFramework/graphics/folder/{$size}/{$type}.png";
			return $url;

			break;

		default :
			if ($entity->hjicontime) {
				return elgg_get_config('url') . "framework/icon/$entity->guid/$size/$entity->hjicontime.jpg";
			}
			break;
	}

	return $return;
}

function hj_framework_container_permissions_check($hook, $type, $return, $params) {
	$container = elgg_extract('container', $params, false);
	$subtype = elgg_extract('subtype', $params, false);

	if ($subtype == 'hjforumsubmission') {
		return true;
	}
	if (elgg_instanceof($container, 'object', 'hjsegment') || $subtype == 'hjsegment') {
		return true;
	}
	if (elgg_instanceof($container, 'user') && $subtype == 'widget') {
		return true;
	}
	if (elgg_instanceof($container, 'object', 'hjannotation')) {
		return true;
	}
	if (elgg_instanceof($container) && $subtype == 'hjannotation') {
		return $container->canAnnotate();
	}

	if (elgg_instanceof($container, 'object', 'hjfilefolder')) {
		return true;
	}
	$container = get_entity($container->container_guid);
	if (elgg_instanceof($container, 'group') && $subtype == 'hjannotation') {
		return $container->canWriteToContainer();
	}

	return $return;
}

function hj_framework_canannotate_check($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params, false);

	if (elgg_instanceof($entity, 'object', 'hjannotation')) {
		$container = $entity->findOriginalContainer();
		if ($container->getType() == 'river') {
			return true;
		}
		if (elgg_instanceof($container_top, 'group')) {
			return $container_top->canWriteToContainer();
		} else {
			return $container->canAnnotate();
		}
	}
	return $return;
}

function hj_framework_inputs($hook, $type, $return, $params) {
	$return[] = 'entity_icon';
	$return[] = 'relationship_tags';
	$return[] = 'multifile';
	return $return;
}

/**
 * Input processing logic
 *
 * @param str $hook 'process'
 * @param str $type 'forms'
 * @param bool $return Default true
 * @param array $params
 * @return bool		Return false to stop the default processing logic
 */
function hj_framework_process_inputs($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params, false);
	$field = elgg_extract('input', $params, false);
	$value = elgg_extract('value', $params, false);
	$metadata_name = elgg_extract('metadata_name', $params, false);
	$access_id = elgg_extract('access_id', $params, ACCESS_DEFAULT);

	if (!$entity || !$field || !elgg_instanceof($entity)) {
		return true;
	}

	$type = $entity->getType();
	$subtype = $entity->getSubtype();

	$inputAttr = $field->getData('input');
	switch ($inputAttr['type']) {

		case 'tags' :
			$entity->$metadata_name = array_walk(explode(',', $value), 'trim');
			return false;
			break;

		case 'file' :
			if (elgg_is_logged_in()) {
				global $_FILES;
				$field_name = $field->name;
				$file = $_FILES[$field_name];

// Maybe someone doesn't want us to save the file in this particular way
				if (!empty($file['name'])) {
					if (!elgg_trigger_plugin_hook('fileupload', 'forms', array(
								'entity' => $entity,
								'file' => $file,
								'field_name' => $field_name), false)) {

						/** @todo convert metadata to 'attachment' relationship */
						hj_framework_process_file_upload($file, $entity, $metadata_name);
					}
				}
			}
			return false;
			break;

		case 'entity_icon' :
			$field_name = $input->name;

			global $_FILES;
			if ((isset($_FILES[$field_name])) && (substr_count($_FILES[$field_name]['type'], 'image/'))) {
				hj_framework_generate_entity_icons($entity, $field_name);
				$entity->$field_name = null;
			}
			return false;
			break;

		case 'relationship_tags' :
			$field_name = $field->name;
			$tags = get_input('relationship_tag_guids');
			$relationship_name = get_input('relationship_tags_name', 'tagged_in');

			$current_tags = elgg_get_entities_from_relationship(array(
				'relationship' => $relationship_name,
				'relationship_guid' => $entity->guid,
				'inverse_relationship' => true
					));
			if (is_array($current_tags)) {
				foreach ($current_tags as $current_tag) {
					if (!in_array($current_tag->guid, $tags)) {
						remove_entity_relationship($current_tag->guid, $relationship_name, $entity->guid);
					}
				}
			}
			if (is_array($tags)) {
				foreach ($tags as $tag_guid) {
					add_entity_relationship($tag_guid, $relationship_name, $entity->guid);
				}
				$tags = implode(',', $tags);
			}

			$entity->$field_name = $tags;

			return false;
			break;

		case 'multifile' :

			if (elgg_is_logged_in()) {
				$values = get_input($field->name);
				if (is_array($values)) {
					foreach ($values as $value) {
						create_metadata($entity->guid, $field->name, $value, '', $entity->owner_guid, $entity->access_id, true);
						if (!elgg_trigger_plugin_hook('framework:form:multifile', 'all', array('entity' => $entity, 'file_guid' => $value, 'field_name' => $field_name), false)) {
							make_attachment($entity->guid, $value);
						}
					}
				}
			}
			return false;
			break;
	}

	return true;
}

function hj_framework_ajax_pageshell($hook, $type, $return, $params) {

	if (elgg_is_xhr()) {
		$output = $params;
		foreach ($output as $key => $val) {
			if (is_string($val) && $json = json_decode($val)) {
				$output[$key] = $json;
			}
		}

		$js = elgg_get_loaded_js();
		$js_foot = elgg_get_loaded_js('footer');

		$js = array_merge($js, $js_foot);

		$css = elgg_get_loaded_css();

		$resources = array(
			'js' => array(),
			'css' => array()
		);

		foreach ($js as $script) {
			$resources['js'][] = $script;
		}

		foreach ($css as $link) {
			$resources['css'][] = $link;
		}


		$params = array(
			'output' => $output,
			'status' => 0,
			'system_messages' => array(
				'error' => array(),
				'success' => array()
			),
			'resources' => $resources
		);

		//Grab any system messages so we can inject them via ajax too
		$system_messages = system_messages(NULL, "");

		if (isset($system_messages['success'])) {
			$params['system_messages']['success'] = $system_messages['success'];
		}

		if (isset($system_messages['error'])) {
			$params['system_messages']['error'] = $system_messages['error'];
			$params['status'] = -1;
		}

		// Check the requester can accept JSON responses, if not fall back to
		// returning JSON in a plain-text response.  Some libraries request
		// JSON in an invisible iframe which they then read from the iframe,
		// however some browsers will not accept the JSON MIME type.
		if (stripos($_SERVER['HTTP_ACCEPT'], 'application/json') === FALSE) {
			header("Content-type: text/plain");
		} else {
			header("Content-type: application/json");
		}

		return json_encode($params);
	}

	return $return;
}

function hj_framework_ajax_pagelayout($hook, $type, $return, $params) {

	if (elgg_is_xhr() && $params['xhr_json_encode']) {
		$params['breadcrumbs'] = elgg_view('navigation/breadcrumbs', array('breadcrumbs' => elgg_get_breadcrumbs()));
		return json_encode($params);
	}

	return $return;
}