<?php

$endpoint = get_input('endpoint', 'pageshell');

if ($endpoint == 'pageshell') {
	return false;
} else if ($endpoint == 'global_xhr_output') {
	global $XHR_OUTPUT;
	echo json_encode($XHR_OUTPUT);
	return true;
}

$vars['nav'] = elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));
$vars['sidebar'] = elgg_view('page/elements/sidebar', $vars);
$vars['sidebar_alt'] = elgg_view('page/elements/sidebar_alt', $vars);

echo json_encode(elgg_clean_vars($vars));