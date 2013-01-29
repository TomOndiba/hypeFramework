<?php

/**
 * Elgg Form Body Wrapper
 *
 * @uses $vars['name'] Form name
 * @uses $vars['title'] Title of the form module
 * @uses $vars['description'] Description of the form module
 * @uses $vars['fieldsets'] An array of fieldsets with inputs
 */

if (isset($vars['name'])) {
	if (elgg_is_sticky_form($vars['name'])) {
		$sticky_values = elgg_get_sticky_values($vars['name']);
		elgg_clear_sticky_form($vars['name']);
	}
	unset($vars['name']);
}

$fieldsets = elgg_extract('fieldsets', $vars);

$title = elgg_extract('title', $vars);
if (!empty($vars['description'])) {
	$description = elgg_view('output/longtext', array(
		'value' => elgg_extract('description', $vars),
		'class' => 'elgg-form-description'
			));
}

foreach ($fieldsets as $fieldset) {
	$override_view = $fieldset['override_view'];
	unset($fieldset['override_view']);

	$fieldset['values'] = $sticky_values;

	if (elgg_view_exists($override_view)) {
		$fieldset_view = elgg_view($override_view, $fieldset);
	} elseif (elgg_view_exists("page/components/forms/layouts/fieldsets/{$fieldset['name']}")) {
		$fieldset_view = elgg_view("page/components/forms/layouts/fieldsets/{$fieldset['name']}", $fieldset);
	} else {
		$fieldset_view = elgg_view('page/components/forms/layouts/fieldsets/default', $fieldset);
	}

	if ($fieldset['name'] != 'footer') {
		$content .= $fieldset_view;
	} else {
		$footer .= $fieldset_view;
	}
}

echo elgg_view_module('form', $title, $description . $content, array('footer' => $footer));