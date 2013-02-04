<?php

$form_name = get_input('form_name', false);

elgg_make_sticky_form($form_name);

if (!hj_framework_validate_form($form_name)) {
	forward(REFERER);
}

$guid = get_input('guid', null);

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
			forward('', '404');
			break;
	}
}

if ($guid) { // Entity already exists
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
	register_error(elgg_echo('hj:framework:error:cannotcreateentity'));
	forward(REFERER);
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

	elgg_trigger_plugin_hook('process:form', "form:$form_name", array('form_name' => $form_name, 'entity' => $entity), null);
}

$forward_url = elgg_trigger_plugin_hook('forward', 'form', array('entity' => $entity, 'form_name' => $form_name), $entity->getURL());

system_message(elgg_echo('hj:framework:submit:success'));

elgg_clear_sticky_form($form_name);
hj_framework_clear_form_validation_status($form_name);

forward($forward_url);
