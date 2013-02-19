<?php

$guids = hj_framework_process_file_upload('file_temp');

if ($guids) {
	foreach ($guids as $name => $guid) {
		$response[$name] = $guid;
		$entity = get_entity($guid);
		if ($entity) {
			$entity->disable('temp_file_upload');
		}
	}
}

print json_encode($response);

forward();