<?php

$system_messages = $vars['sysmessages'];
unset($vars['sysmessages']);

$output = elgg_clean_vars($vars);

function hj_framework_decode_xhr_view_outputs($val) {
	if (is_array($val)) {
		foreach ($val as $k => $v) {
			$output[$k] = hj_framework_decode_xhr_view_outputs($v);
		}
	} else if (is_string($val) && $json = json_decode($val, true)) {
		if (is_array($json)) {
			$output = hj_framework_decode_xhr_view_outputs($json);
		} else {
			$output = $json;
		}
	} else {
		$output = $val;
	}

	return $output;
}

$output = hj_framework_decode_xhr_view_outputs($output);

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
	'resources' => $resources,
	'href' => full_url()
);

if (isset($system_messages['success'])) {
	$params['system_messages']['success'] = $system_messages['success'];
}

if (isset($system_messages['error'])) {
	$params['system_messages']['error'] = $system_messages['error'];
	$params['status'] = -1;
}

if (stripos($_SERVER['HTTP_ACCEPT'], 'application/json') === FALSE) {
	header("Content-type: text/plain");
} else {
	header("Content-type: application/json");
}

echo json_encode($params);
exit();