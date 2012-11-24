<?php
/**
 * Elgg Form Fieldset
 *
 * @uses $vars['legend'] Legend text
 * @uses $vars['inputs'] Input views
 * @uses $vars['class'] Additional CSS class
 * @uses $vars['values'] An array of values (sticky forms)
 */
$body = '<ul class="elgg-table elgg-table-fieldset">';

if (isset($vars['values'])) {
	$sticky_values = $vars['values'];
	unset($vars['values']);
}

if (isset($vars['inputs'])) {
	$inputs = elgg_extract('inputs', $vars);
	foreach ($inputs as $name => $params) {
		unset($params['fieldset']);
		unset($params['priority']);
		unset($params['output']);

		if (isset($sticky_values[$name])) {
			$params['value'] = $sticky_values[$name];
		}
		
		$body .= elgg_view('page/components/forms/layouts/inputs/default', $params);
	}
	unset($vars['inputs']);
}

$body .= '</ul>';

if (isset($vars['legend'])) {
	$legend = '<legend><span class="framework-ui-toggle elgg-state-active"></span>' . $vars['legend'] . '</legend>';
	$body = "{$legend}{$body}";
	unset($vars['legend']);
}

if (isset($vars['class'])) {
	$vars['class'] = "elgg-form-fieldset {$vars['class']}";
} else {
	$vars['class'] = "elgg-form-fieldset";
}

if (isset($vars['name'])) {
	$vars['class'] = "{$vars['class']} elgg-form-fieldset-{$vars['name']}";
	unset($vars['name']);
}

unset($vars['priority']);
?>

<fieldset <?php echo elgg_format_attributes($vars); ?>><?php echo $body ?></fieldset>