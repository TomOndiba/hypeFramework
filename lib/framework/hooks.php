<?php

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
			break;
	}
	$form = hj_framework_get_data_pattern($entity->getType(), $entity->getSubtype());
	if (elgg_instanceof($form, 'object', 'hjform') && $entity->icontime) {
		return elgg_get_config('url') . "hj/icon/$entity->guid/$size/$entity->icontime.jpg";
	}

	return $return;
}

function hj_framework_setup_segment_widgets($hook, $type, $return, $params) {

	$entity_guid = elgg_extract('guid', $params, 0);
	$entity = get_entity($entity_guid);
	$context = elgg_extract('context', $params, 'framework');
	$event = elgg_extract('event', $params, 'update');

	if ($entity->getSubtype() == 'hjsegment' && $event == 'create') {
		$sections = elgg_trigger_plugin_hook('hj:framework:widget:types', 'all', array('context' => $context), array());

		if (is_array($sections)) {
			foreach ($sections as $section => $name) {
				$entity->addWidget($section, null, $context);
			}
		}
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

function hj_framework_process_inputs($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params, false);
	$field = elgg_extract('field', $params, false);
	if (!$entity || !$field || !elgg_instanceof($entity)) {
		return true;
	}
	$type = $entity->getType();
	$subtype = $entity->getSubtype();

	switch ($field->input_type) {

		case 'file' :
			if (elgg_is_logged_in()) {
				global $_FILES;
				$field_name = $field->name;
				$file = $_FILES[$field_name];

				// Maybe someone doesn't want us to save the file in this particular way
				if (!empty($file['name']) && !elgg_trigger_plugin_hook('hj:framework:form:fileupload', 'all', array('entity' => $entity, 'file' => $file, 'field_name' => $field_name), false)) {
					hj_framework_process_file_upload($file, $entity, $field_name);
				}
			}
			break;

		case 'entity_icon' :
			$field_name = $field->name;

			if ((isset($_FILES['icon'])) && (substr_count($_FILES['icon']['type'], 'image/'))) {

				hj_framework_generate_entity_icons($entity);
				$entity->$field_name = null;
			}
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

			break;

		case 'multifile' :

			if (elgg_is_logged_in()) {
				$values = get_input($field->name);
				if (is_array($values)) {
					foreach ($values as $value) {
						create_metadata($entity->guid, $field->name, $value, '', $entity->owner_guid, $entity->access_id, true);
						if (!elgg_trigger_plugin_hook('hj:framework:form:multifile', 'all', array('entity' => $entity, 'file_guid' => $value, 'field_name' => $field_name), false)) {
							make_attachment($entity->guid, $value);
						}
					}
				}

			}

			break;
	}

	return true;
}

function hj_framework_ajax_pageshell($hook, $type, $return, $params) {

	if (elgg_is_xhr()) {
		$params['page_shell'] = 'ajax';
		return elgg_view('page/ajax', $params);
	}

	return $return;
}