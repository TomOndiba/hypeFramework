<?php

$output = elgg_clean_vars($vars);

foreach ($output as $key => $value) {
	$json = json_decode($value);
	if (isset($json)) {
		$output[$key] = $json;
	} else {
		$output[$key] = $value;
	}
}

echo json_encode($output);