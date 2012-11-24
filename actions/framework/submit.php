<?php

/**
 * Action to perform on hjForm submit
 * Saves an entity of a given type and subtype (@see hjForm::$subject_entity_type, hjForm::$subject_entity_subtype)
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category AJAX
 * @category User Interface
 * @category Forms
 */

// Hack to allow non-logged in users to submit their forms
$access = elgg_get_ignore_access();
if (!elgg_is_logged_in()) {
	elgg_set_ignore_access(true);
}

// Get params array
// Make sure strings are converted to bool
$params = json_decode(get_input('params'), true);
$params = hj_framework_decode_params_array($params);

$subject = get_entity($params['subject_guid']);
$container = get_entity($params['container_guid']);
$owner = get_entity($params['owner_guid']);
$form = get_entity($params['form_guid']);
if (elgg_instanceof($form, 'object', 'hjform')) {
	$fields = $form->getFields();
}
$widget = get_entity($params['widget_guid']);
$segment = get_entity($params['segment_guid']);
$context = $params['context'];

if ($subject->guid) {
	$event = 'update';
} else {
	$event = 'create';
}

elgg_make_sticky_form($form->title);

if (is_array($fields)) {
	switch ($form->subject_entity_type) {
		case 'object' :
		default :
			$formSubmission = new ElggObject($subject->guid);
			break;
		case 'user' :
			$formSubmission = new ElggUser($subject->guid);
			break;
		case 'group' :
			$formSubmission = new ElggGroup($subject->guid);
			break;
	}

	$formSubmission->subtype = $form->subject_entity_subtype;
	$formSubmission->title = get_input('title');
	$formSubmission->description = get_input('description');
	$formSubmission->owner_guid = $owner->guid;
	$formSubmission->container_guid = $container->guid;
	$formSubmission->access_id = get_input('access_id', elgg_extract('access_id', $params, $container->access_id));
	$saved = $formSubmission->save();

	$formSubmission->data_pattern = $form->guid;
	$formSubmission->widget = $widget->guid;
	$formSubmission->segment = $segment->guid;
	$formSubmission->handler = $form->handler;
	$formSubmission->notify_admins = $form->notify_admins;
	$formSubmission->add_to_river = $form->add_to_river;
	$formSubmission->comments_on = $form->comments_on;

	$params['guid'] = $saved;
}

if ($saved && is_array($fields)) {
	hj_framework_set_entity_priority($formSubmission);

	foreach ($fields as $field) {
		if ((!elgg_is_logged_in() && $field->access_id == ACCESS_PUBLIC) || (elgg_is_logged_in())) {
			$field_name = $field->name;
			$field_value = get_input($field_name);

			switch ($field->input_type) {
				default :
					$formSubmission->$field_name = $field_value;

					// Do we need to treat the field in a special way?
					elgg_trigger_plugin_hook('framework:field:process', 'all', array('entity' => $formSubmission, 'field' => $field), true);
					break;

				case 'tags' :
					$tags = explode(",", $field_value);
					$formSubmission->$field_name = $tags;
					break;
			}
		}
	}
}
if ($saved) {
	// In case we want to manipulate received data
	if (elgg_trigger_plugin_hook('framework:form:process', 'all', $params, true)) {

		if ($formSubmission->notify_admins) {
			$admins = elgg_get_admins();
			foreach ($admins as $admin) {
				$to[] = $admin->guid;
			}
			$from = elgg_get_config('site')->guid;
			$subject = sprintf(elgg_echo('hj:formbuilder:formsubmission:subject'), $form->title);
			elgg_push_context('admin');
			$submissions_url = elgg_normalize_url('hjform/submissions/' . $form->guid);
			$message = sprintf(elgg_echo('hj:formbuilder:formsubmission:body'), elgg_view_entity($formSubmission, $params), $submissions_url);
			notify_user($to, $from, $subject, $message);
			elgg_pop_context();
		}
		if ($formSubmission->add_to_river) {
			$view = "river/{$formSubmission->getType()}/{$formSubmission->getSubtype()}/$event";
			if (!elgg_view_exists($view)) {
				$view = "river/object/hjformsubmission/create";
			}
			add_to_river($view, "$event", elgg_get_logged_in_user_guid(), $formSubmission->guid);
		}
		system_message(elgg_echo('hj:formbuilder:submit:success'));

		$xhr_mode = get_input('xhr_mode', false);
		if (elgg_is_xhr() || $xhr_mode) {
			$newFormSubmission = get_entity($formSubmission->guid);
			if ($widget && elgg_instanceof($widget, 'object', 'widget')) {
				elgg_push_context('widgets');
			}
			$output['data'] = "<li id=\"elgg-{$newFormSubmission->getType()}-$newFormSubmission->guid\" class=\"elgg-item hj-view-entity elgg-state-draggable\">" . elgg_view_entity($newFormSubmission, $params) . "</li>";
			$output = elgg_trigger_plugin_hook('framework:submit:output', 'all', $params, $output);
			if (elgg_in_context('widgets')) {
				elgg_pop_context();
			}
			elgg_set_ignore_access($access);
			if ($xhr_mode) {
				if (elgg_get_context() == 'fancybox') {
					elgg_pop_context();
				}
				header('Content-Type: application/json');
				$output['output']['data'] = $output['data'];
				unset($output['data']);
				print(json_encode($output));
				exit;
			} else {
				print(json_encode($output));
				return true;
			}
		}
		if ($form->subject_entity_subtype == 'hjsegment') {
			$url = "{$container->getURL()}&sg={$formSubmission->guid}";
		} else {
			$url = $formSubmission->getURL();
		}
		if (!empty($form->forward_url)) {
			$url = $form->forward_url;
		}
		if (!elgg_is_logged_in() && $formSubmission->access_id !== ACCESS_PUBLIC) {
			$url = '';
		}
		elgg_clear_sticky_form($form->title);
		forward($url);
	}
}

elgg_set_ignore_access($access);
register_error(elgg_echo('hj:formbuilder:submit:error'));
forward(REFERER);
