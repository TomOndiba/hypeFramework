<?php

$shortcuts = hj_framework_path_shortcuts('hypeFramework');
$path_actions = $shortcuts['actions'];

elgg_register_action('framework/edit/object', $path_actions . 'framework/edit/object.php');
elgg_register_action('framework/delete/object', $path_actions . 'framework/delete/object.php');
elgg_register_action('framework/access/set', $path_actions . 'framework/access/set.php');

function hj_framework_edit_object_action() {
	$form_name = get_input('form_name', false);

	elgg_make_sticky_form($form_name);

	if (!hj_framework_validate_form($form_name)) {
		return false;
	}

	$guid = get_input('guid', ELGG_ENTITIES_ANY_VALUE);

	$event = ($guid) ? 'edit' : 'create';

	$type = get_input('type');
	$subtype = get_input('subtype');

	$class = get_subtype_class($type, $subtype);

	if ($class) {
		$entity = new $class($guid);
	} else {
		switch (get_input('type', 'object')) {
			case 'object' :
				$entity = new ElggObject($guid);
				break;

			case 'user' :
				$entity = new ElggUser($guid);
				break;

			case 'group' :
				$entity = new ElggGroup($guid);
				break;

			default:
				return false;
				break;
		}
	}

	if ($guid) { // Entity already exists
		if ((int)get_input('container_guid', 0) > 0) {
			$entity->container_guid = get_input('container_guid', ELGG_ENTITIES_ANY_VALUE);
		}

		if ($title = get_input('title', '')) {
			$entity->title = $title;
		}

		if ($description = get_input('description', '')) {
			$entity->description = $description;
		}

		if ($access_id = get_input('access_id', ACCESS_DEFAULT)) {
			$entity->access_id = $access_id;
		}
	} else { // Creating new entity
		$entity->subtype = get_input('subtype', 'hjformsubmission');

		if ($owner_guid = get_input('owner_guid', ELGG_ENTITIES_ANY_VALUE)) {
			$entity->owner_guid = $owner_guid;
		}

		if ($container_guid = get_input('container_guid', ELGG_ENTITIES_ANY_VALUE)) {
			$entity->container_guid = $container_guid;
		}

		$entity->title = get_input('title', '');
		$entity->description = get_input('description', '');

		$entity->access_id = get_input('access_id', ACCESS_DEFAULT);
	}

	$guid = $entity->save();
	if (!$guid) {
		register_error(elgg_echo('hj:framework:error:cannotcreateentity'));
		return false;
	} else {
		$entity = get_entity($guid);

		$accesslevel = get_input('accesslevel', false);

		$params = array(
			'entity' => $entity
		);

		$form = hj_framework_prepare_form($form_name, $params);
		$fields = $form['form']['fields'];

		$ignore_fields = array('guid', 'type', 'subtype', 'owner_guid', 'container_guid', 'access_id', 'title', 'description');

		foreach ($fields as $name => $options) {

			if (in_array($name, $ignore_fields))
				continue;

			$type = elgg_extract('input_type', $options, 'text');

			$accesslevel_id = (isset($accesslevel[$name])) ? $accesslevel[$name] : $entity->access_id;

			$params = array(
				'name' => $name,
				'form_name' => $form_name,
				'field' => $options,
				'access_id' => $accesslevel_id,
				'entity' => $entity,
				'event' => $event
			);

			if (!elgg_trigger_plugin_hook('process:input', "form:input:name:$name", $params, false)
					&& !elgg_trigger_plugin_hook('process:input', "form:input:type:$type", $params, false)) {

				$value = get_input($name);
				set_input($name, null);

				if (!$value)
					continue;

				elgg_delete_metadata(array(
					'guid' => $entity->guid,
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
		$entity->save();
		elgg_trigger_plugin_hook('process:form', "form:$form_name", array('form_name' => $form_name, 'entity' => $entity), null);
	}

	$forward_url = elgg_trigger_plugin_hook('action:forward', 'form', array('entity' => $entity, 'form_name' => $form_name), $entity->getURL());

	system_message(elgg_echo('hj:framework:submit:success'));

	elgg_clear_sticky_form($form_name);
	hj_framework_clear_form_validation_status($form_name);

	return array(
		'entity' => $entity,
		'forward' => $forward_url
	);
}

// View an entity (by its GUID) or render a view
elgg_register_action('framework/entities/view', $path_actions . 'framework/view.php', 'public');

// Edit an entity
elgg_register_action('framework/entities/edit', $path_actions . 'framework/edit.php');

// Process an hjForm on submit
elgg_register_action('framework/entities/save', $path_actions . 'framework/submit.php', 'public');

// Delete an entity by guid
elgg_register_action('framework/entities/delete', $path_actions . 'framework/delete.php', 'public');

// Reset priority attribute of an object
elgg_register_action('framework/entities/move', $path_actions . 'framework/move.php');

// E-mail form details
elgg_register_action('framework/form/email', $path_actions . 'framework/email.php');

// Add widget
elgg_register_action('framework/widget/add', $path_actions . 'framework/addwidget.php');

// Load widget
elgg_register_action('framework/widget/load', $path_actions . 'framework/loadwidget.php');

// Download file
elgg_register_action('framework/file/download', $path_actions . 'framework/download.php', 'public');