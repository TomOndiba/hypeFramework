<?php

$values = elgg_extract('value', $vars);

if (is_array($values)) {
	foreach ($values as $value) {
		echo elgg_view('output/file', array(
			'value' => (int)$value
		));
	}
}