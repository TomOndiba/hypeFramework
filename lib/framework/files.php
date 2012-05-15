<?php

/**
 * hjFile helper functions
 */

/**
 * Get an array of hjFileFolder for a particular user in a given container
 *
 * @param ElggUser $user
 * @param ElggEntity $container_guid
 * @return type
 */
function hj_framework_get_user_file_folders($format = 'options_array', $owner_guid = NULL, $container_guid = NULL, $limit = 0) {
	if (!$owner_guid && elgg_is_logged_in()) {
		$owner_guid = elgg_get_logged_in_user_entity()->guid;
	} else {
		return true;
	}

	$filefolders = hj_framework_get_entities_by_priority('object', 'hjfilefolder', $owner_guid, $container_guid, $limit);
	switch ($format) {
		case 'options_array' :
			if (is_array($filefolders)) {
				$result[] = elgg_echo("hj:framework:newfolder");
				foreach ($filefolders as $filefolder) {
					$result[$filefolder->getGUID()] = $filefolder->title;
				}
			}
			break;

		case 'entities_array' :
			$result = $filefolders;
			break;
	}
	return $result;
}

function hj_framework_allow_file_download($file_guid) {
	return elgg_trigger_plugin_hook('hj:framework:allowdownload', 'all', array('file_guid' => $file_guid), true);
}

function hj_framework_process_file_upload($file, $entity, $field_name) {

	$filefolder = hj_framework_process_filefolder_input($entity);

	// Just in case we want to upload a newer version of the file in the future
	if ($file_guid = get_input("{$field_name}_guid")) {
		$existing_file = true;
		$file_guid = (int) $file_guid;
	} else {
		$existing_file = false;
		$file_guid = null;
	}

	if (!$file_title = get_input("{$field_name}_title")) {
		$file_title = get_input('title');
	}
	if (!$file_description = get_input("{$field_name}_description")) {
		$file_description = get_input('description');
	}
	if (!$file_tags = get_input("{$field_name}_tags")) {
		$file_tags = get_input('tags');
		$file_tags = explode(',', $file_tags);
	}

	$filehandler = new hjFile($file_guid);
	$filehandler->owner_guid = elgg_get_logged_in_user_guid();
	$filehandler->container_guid = $filefolder->getGUID();
	$filehandler->access_id = $filefolder->access_id;
	$filehandler->data_pattern = hj_framework_get_data_pattern('object', 'hjfile');
	$filehandler->title = $file_title;
	$filehandler->description = $file_description;
	$filehandler->tags = $file_tags;

	$prefix = "hjfile/";

	if ($existing_file) {
		$filename = $filehandler->getFilenameOnFilestore();
		if (file_exists($filename)) {
			unlink($filename);
		}
		$filestorename = $filehandler->getFilename();
		$filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
	} else {
		$filestorename = elgg_strtolower($file['name']);
	}

	$filehandler->setFilename($prefix . $filestorename);
	$filehandler->setMimeType($file['type']);
	$filehandler->originalfilename = $file['name'];
	$filehandler->simpletype = file_get_simple_type($file['type']);
	$filehandler->filesize = round($file['size'] / (1024 * 1024), 2) . "Mb";

	$filehandler->open("write");
	$filehandler->close();
	move_uploaded_file($file['tmp_name'], $filehandler->getFilenameOnFilestore());

	$file_guid = $filehandler->save();

	hj_framework_set_entity_priority($filehandler);
	elgg_trigger_plugin_hook('hj:framework:file:process', 'object', array('entity' => $filehandler));

	if ($file_guid) {
		$meta_value = $filehandler->getGUID();
	} else {
		$meta_value = $filehandler->getFilenameOnFilestore();
	}

	create_metadata($entity->guid, $field_name, $meta_value, '', $filehandler->owner_guid, $filehandler->access_id, true);

	if ($file_guid && $filehandler->simpletype == "image") {

		$thumb_sizes = hj_framework_get_thumb_sizes();
		$thumb_sizes = elgg_trigger_plugin_hook('hj:framework:form:iconsizes', 'file', array('entity' => $formSubmission, 'field' => $field), $thumb_sizes);

		foreach ($thumb_sizes as $thumb_type => $thumb_size) {
			$thumbnail = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(), $thumb_size['w'], $thumb_size['h'], $thumb_size['square'], 0, 0, 0, 0, true);
			if ($thumbnail) {
				$thumb = new ElggFile();
				$thumb->setMimeType($file['type']);

				$thumb->setFilename("{$prefix}{$filehandler->getGUID()}{$thumb_type}.jpg");
				$thumb->open("write");
				$thumb->write($thumbnail);
				$thumb->close();

				$thumb_meta = "{$thumb_type}thumb";
				$filehandler->$thumb_meta = $thumb->getFilename();
				unset($thumbnail);
			}
		}
	}
	return;
}

function hj_framework_process_filefolder_input($entity) {
	$newfilefolder = get_input('newfilefolder');
	$filefolder = get_input('filefolder');

	$form = get_entity($entity->data_pattern);
	if ($form->subject_entity_subtype != 'hjfile') {
		$entity->filefolder = null;
		$entity->newfilefolder = null;
	}
	if ((int) $filefolder > 0) {
		$filefolder = get_entity(get_input('filefolder'));
	} else if ($newfilefolder) {
		$filefolder = new ElggObject();
		$filefolder->title = $newfilefolder;
		$filefolder->subtype = 'hjfilefolder';
		$filefolder->datatype = 'default';
		$filefolder->data_pattern = hj_framework_get_data_pattern('object', 'hjfilefolder');
		$filefolder->owner_guid = $entity->owner_guid;
		$filefolder->container_guid = $entity->getGUID();
		$filefolder->access_id = $entity->access_id;
		$filefolder->save();

		hj_framework_set_entity_priority($filefolder);
	} else {
		$filefolder = $entity;
	}
	return $filefolder;
}

function hj_framework_handle_multifile_upload($user_guid) {

	if (!empty($_FILES)) {
		$access = elgg_get_ignore_access();
			elgg_set_ignore_access(true);
		$file = $_FILES['Filedata'];

		$filehandler = new hjFile();
		$filehandler->owner_guid = (int)$user_guid;
		$filehandler->container_guid = (int)$user_guid;
		$filehandler->access_id = ACCESS_DEFAULT;
		$filehandler->data_pattern = hj_framework_get_data_pattern('object', 'hjfile');
		$filehandler->title = $file['name'];
		$filehandler->description = '';

		$prefix = "hjfile/";

		$filestorename = elgg_strtolower($file['name']);

		$filehandler->setFilename($prefix . $filestorename);
		$filehandler->setMimeType($file['type']);
		$filehandler->originalfilename = $file['name'];
		$filehandler->simpletype = file_get_simple_type($file['type']);
		$filehandler->filesize = round($file['size'] / (1024 * 1024), 2) . "Mb";

		$filehandler->open("write");
		$filehandler->close();
		move_uploaded_file($file['tmp_name'], $filehandler->getFilenameOnFilestore());

		$file_guid = $filehandler->save();

		hj_framework_set_entity_priority($filehandler);
		elgg_trigger_plugin_hook('hj:framework:file:process', 'object', array('entity' => $filehandler));

		if ($file_guid) {
			$meta_value = $filehandler->getGUID();
		} else {
			$meta_value = $filehandler->getFilenameOnFilestore();
		}

		if ($file_guid && $filehandler->simpletype == "image") {

			$thumb_sizes = hj_framework_get_thumb_sizes();
			$thumb_sizes = elgg_trigger_plugin_hook('hj:framework:form:iconsizes', 'file', array('entity' => $formSubmission, 'field' => $field), $thumb_sizes);

			foreach ($thumb_sizes as $thumb_type => $thumb_size) {
				$thumbnail = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(), $thumb_size['w'], $thumb_size['h'], $thumb_size['square'], 0, 0, 0, 0, true);
				if ($thumbnail) {
					$thumb = new ElggFile();
					$thumb->setMimeType($file['type']);

					$thumb->setFilename("{$prefix}{$filehandler->getGUID()}{$thumb_type}.jpg");
					$thumb->open("write");
					$thumb->write($thumbnail);
					$thumb->close();

					$thumb_meta = "{$thumb_type}thumb";
					$filehandler->$thumb_meta = $thumb->getFilename();
					unset($thumbnail);
				}
			}
		}
		$response = array(
			'status' => 'OK',
			'value' => $meta_value
		);
	} else {
		$response = array(
			'status' => 'FAIL'
		);
	}

	echo json_encode($response);
	elgg_set_ignore_access($access);
	return;
}