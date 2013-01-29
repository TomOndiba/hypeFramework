<?php

$form_name = elgg_extract('form_name', $vars);

$form = elgg_extract('form', $vars);
$fields = elgg_extract('fields', $form, array());

$entity = elgg_extract('entity', $vars, false);

$event = ($entity) ? 'edit' : 'create';

// Get form title
if (isset($form['title'][$event])) {
	$title = $form['title'][$event];
}

// Get form description
if (isset($form['description'][$event])) {
	$content = elgg_view('output/longtext', array(
		'value' => $form['description'][$event],
		'class' => 'elgg-form-description'
			));
}

if ($fields && count($fields)) {
	foreach ($fields as $name => $options) {
		$options['form_name'] = $form_name;
		$params['field'] = $options;
		$content .= elgg_view('framework/bootstrap/form_elements/field', $params);
	}
}

$footer .= elgg_view('input/hidden', array(
	'name' => 'form_name',
	'value' => $form_name
		));

$footer .= elgg_view('input/submit');
$footer .= elgg_view('input/button', array(
	'value' => elgg_echo('cancel'),
	'class' => 'elgg-button-cancel'
		));

echo elgg_view_module('form', $title, $content, array(
	'footer' => $footer
));
