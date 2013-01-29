<?php

/**
 * Elgg Input Wrapper
 *
 * @uses $vars['type'] Input type to parse the wrapper class
 * @uses $vars['label'] Label
 * @uses $vars['hint'] Tooltip or hint text
 * @uses $vars['override_views'] Views to use for 'label', 'hint', 'input', 'output'
 */
$override_views = elgg_extract('override_views', $vars, array());
unset($vars['override_views']);

$input_type = $vars['type'];
$input_name = $vars['name'];

if ($vars['label'] !== false && $input_type !== 'hidden') {
	if (!isset($vars['label']['text'])) {
		$vars['label']['text'] = elgg_echo("label:$input_name");
	}
	if (!isset($vars['label']['for'])) {
		$vars['label']['for'] = $input_name;
	}
	$label_view = $override_views['label'];
	if (elgg_view_exists($label_view)) {
		$label = elgg_view($label_view, $vars);
	} else {
		$label = elgg_view('page/components/forms/elements/label', $vars);
	}
	unset($vars['label']);
}

if (isset($vars['hint'])) {
	$hint_view = $override_views['hint'];
	if (elgg_view_exists($hint_view)) {
		$hint = elgg_view($hint_view, $vars);
	} else {
		$hint = elgg_view('page/components/forms/elements/hint', $vars);
	}
	unset($vars['hint']);
}

$input_view = $override_views['input'];
if (elgg_view_exists($input_view)) {
	$input = elgg_view($input_view, $vars);
} elseif (elgg_view_exists("input/$input_type")) {
	$input = elgg_view("input/$input_type", $vars);
} else {
	$input = elgg_view('input/text', $vars);
}

$wrapper_view = $override_views['wrapper'];
$wrapper_params = array(
	'label' => $label,
	'hint' => $hint,
	'input' => $input,
	'type' => $input_type
);

if (elgg_view_exists($wrapper_view)) {
	echo elgg_view($wrapper_view, $wrapper_params);
} else {
	echo elgg_view('page/components/forms/elements/footer', $wrapper_params);
}