<?php

$input_type = elgg_extract('input_type', $vars, 'text');
$value = elgg_extract('value', $vars, array());
$name = elgg_extract('name', $vars, array());

echo elgg_view("input/$input_type", array(
	'name' => "{$name}[from]",
	'value' => $value['from'],
	'placeholder' => elgg_echo('range:from')
));

echo elgg_view("input/$input_type", array(
	'name' => "{$name}[to]",
	'value' => $value['to'],
	'placeholder' => elgg_echo('range:to')
));

