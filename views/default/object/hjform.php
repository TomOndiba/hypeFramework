<?php

$form = elgg_extract('entity', $vars);

if (!elgg_instanceof($form, 'object', 'hjform')) {
	return true;
}

$fields = $form->getFields();

if (elgg_is_sticky_form($form->title)) {
	extract(elgg_get_sticky_values($form->title));
	elgg_clear_sticky_form($form->title);
}

if (is_array($fields)) {
	foreach ($fields as $field) {
		if ($field->input_type == 'file' || $field->input_type == 'entity_icon') {
			$multipart = true;
			if (elgg_is_logged_in()) {
				$form_fields .= elgg_view_entity($field, $vars);
			}
		} else {
			$form_fields .= elgg_view_entity($field, $vars);
		}
	}
}

$params = elgg_clean_vars($vars);
unset($params['entity']);

//$params = hj_framework_json_query($params);

if (isset($params['subject_guid']) && $params['subject_guid'] > 0) {
	$params['event'] = 'update';
}

$form_fields .= elgg_view('input/hidden', array('value' => json_encode($params), 'name' => 'params'));

if (!isset($vars['ajaxify']) || $vars['ajaxify'] === true) {
	$ajaxify = true;
}
if ($multipart && $ajaxify) {
	// a hack to return json values on form submit when sending files via get/post in ajaxsubmit
	$form_fields .= elgg_view('input/hidden', array('value' => true, 'name' => 'xhr_mode'));
}

$form_fields .= elgg_view('input/submit', array(
	'value' => elgg_echo('submit')
		));

$form_fields .= elgg_view('input/button', array(
	'value' => elgg_echo('cancel'),
	'class' => 'hj-ajaxed-cancel-form'
		));

if ($ajaxify) {
	$class = "hj-ajaxed-save";
	if ($multipart) {
		$class = "$class hj-ajaxed-file-save";
	}
}

if (!isset($vars['fields_only']) || $vars['fields_only'] === false) {
	$form = elgg_view('input/form', array(
		'body' => $form_fields,
		'id' => "hj-form-entity-{$form->guid}",
		'action' => $form->action,
		'method' => $form->method,
		'enctype' => $form->enctype,
		'class' => "$form->class $class"
			));
	$body = elgg_view_module('aside', $form_title, $form_description . $form);
	echo $body;
} else {
	echo $form_fields;
}