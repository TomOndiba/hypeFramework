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

	$filefolders = hj_framework_get_entities_by_priority(array(
		'type' => 'object',
		'subtype' => 'hjfilefolder',
		'owner_guid' => $owner_guid,
		'container_guid' => $container_guid,
		'limit' => $limit
			));

	switch ($format) {
		case 'options_array' :
			if (is_array($filefolders)) {
				$result[] = elgg_echo("framework:newfolder");
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
	return elgg_trigger_plugin_hook('framework:allowdownload', 'all', array('file_guid' => $file_guid), true);
}

function hj_framework_process_file_upload($file, $entity, $name) {

	//$filefolder = hj_framework_process_filefolder_input($entity);
	// Is this file an attachment or a file entity?
	$file_subtypes = elgg_trigger_plugin_hook('file:subtypes', 'framework:config', null, array('file', 'hjfile'));

	$is_attachment = false;
	if (!in_array($entity->getSubtype(), $file_subtypes)) {
		$is_attachment = true;
	}

	if ($is_attachment) {
		$formfileoptions = get_input('fileoptions');
		$fileoptions = $formfileoptions[$name];
		if ($fileoptions) {
			$file_guid = elgg_extract('guid', $fileoptions, null);
			$file_title = elgg_extract('title', $fileoptions, $file['name']);
			$file_description = elgg_extract('description', $fileoptions, null);
			$file_tags = string_to_tag_array(elgg_extract('tags', $fileoptions, ''));
			$file_owner_guid = elgg_extract('owner_guid', $fileoptions, elgg_get_logged_in_user_guid());
			$file_container_guid = elgg_extract('container_guid', $fileoptions, elgg_get_logged_in_user_guid());
			$file_access_id = elgg_extract('access_id', $fileoptions, $entity->access_id);
		}

		if ($file_guid) {
			$existing_file = true;
		}
		$filehandler = new hjFile($file_guid);
	} else {
		if ($file_guid = get_input('subject_guid', false)) {
			$existing_file = true;
		}

		$filehandler = $entity;
	}

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

	$prefix = "hjfile/";

	$filehandler->setFilename($prefix . $filestorename);
	$filehandler->setMimeType($file['type']);
	$filehandler->originalfilename = $file['name'];
	$filehandler->simpletype = hj_framework_get_simple_type($file['type']);
	$filehandler->filesize = $file['size'];

	$filehandler->open("write");
	$filehandler->close();
	move_uploaded_file($file['tmp_name'], $filehandler->getFilenameOnFilestore());

	$guid = $filehandler->save();

	$guid = elgg_trigger_plugin_hook('filehandler', 'form:input:file', array('entity' => $filehandler), $guid);

	if ($is_attachment) {
		make_attachment($entity->guid, $guid);
	}

	if ($guid && $filehandler->simpletype == "image") {

		$thumb_sizes = hj_framework_get_thumb_sizes($entity->getSubtype());

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
		$filehandler->owner_guid = (int) $user_guid;
		$filehandler->container_guid = (int) $user_guid;
		$filehandler->access_id = ACCESS_DEFAULT;
		$filehandler->data_pattern = hj_framework_get_data_pattern('object', 'hjfile');
		$filehandler->title = $file['name'];
		$filehandler->description = '';

		$prefix = "hjfile/";

		$filestorename = elgg_strtolower($file['name']);

		$mime = hj_framework_get_mime_type($file['name']);

		$filehandler->setFilename($prefix . $filestorename);
		$filehandler->setMimeType($mime);
		$filehandler->originalfilename = $file['name'];
		$filehandler->simpletype = hj_framework_get_simple_type($mime);
		$filehandler->filesize = round($file['size'] / (1024 * 1024), 2) . "Mb";

		$filehandler->open("write");
		$filehandler->close();
		move_uploaded_file($file['tmp_name'], $filehandler->getFilenameOnFilestore());

		$file_guid = $filehandler->save();

		hj_framework_set_entity_priority($filehandler);
		elgg_trigger_plugin_hook('framework:file:process', 'object', array('entity' => $filehandler));

		if ($file_guid) {
			$meta_value = $filehandler->getGUID();
		} else {
			$meta_value = $filehandler->getFilenameOnFilestore();
		}

		if ($file_guid && $filehandler->simpletype == "image") {

			$thumb_sizes = hj_framework_get_thumb_sizes();

			foreach ($thumb_sizes as $thumb_type => $thumb_size) {
				$thumbnail = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(), $thumb_size['w'], $thumb_size['h'], $thumb_size['square'], 0, 0, 0, 0, true);
				if ($thumbnail) {
					$thumb = new ElggFile();
					$thumb->setMimeType($file['type']);
					$thumb->owner_guid = $user_guid;
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

function hj_framework_get_thumb_sizes($handler = null) {
	$thumb_sizes = elgg_get_config('icon_sizes');

	$thumb_sizes['large'] = array(
		'w' => 200,
		'h' => 200,
		'square' => true
	);
	$thumb_sizes['preview'] = array(
		'w' => 400,
		'h' => 400,
		'square' => true
	);
	$thumb_sizes['master'] = array(
		'w' => 600,
		'h' => 600,
		'square' => false
	);
	$thumb_sizes['full'] = array(
		'w' => 1024,
		'h' => 1024,
		'square' => false
	);
	$thumb_sizes['cover'] = array(
		'w' => 850,
		'h' => 315,
		'square' => false
	);

	$thumb_sizes = elgg_trigger_plugin_hook('framework:form:iconsizes', 'file', array('handler' => $handler), $thumb_sizes);
	return $thumb_sizes;
}

function hj_framework_get_simple_type($mimetype) {

	switch ($mimetype) {
		case "application/msword":
			return "document";
			break;
		case "application/pdf":
			return "document";
			break;
	}

	if (substr_count($mimetype, 'text/')) {
		return "document";
	}

	if (substr_count($mimetype, 'audio/')) {
		return "audio";
	}

	if (substr_count($mimetype, 'image/')) {
		return "image";
	}

	if (substr_count($mimetype, 'video/')) {
		return "video";
	}

	if (substr_count($mimetype, 'opendocument')) {
		return "document";
	}

	return "general";
}

function hj_framework_get_mime_type($filename) {

	// our list of mime types
	$mime_types = array(
		"pdf" => "application/pdf"
		, "exe" => "application/octet-stream"
		, "zip" => "application/zip"
		, "docx" => "application/msword"
		, "doc" => "application/msword"
		, "xls" => "application/vnd.ms-excel"
		, "ppt" => "application/vnd.ms-powerpoint"
		, "gif" => "image/gif"
		, "png" => "image/png"
		, "jpeg" => "image/jpg"
		, "jpg" => "image/jpg"
		, "mp3" => "audio/mpeg"
		, "wav" => "audio/x-wav"
		, "mpeg" => "video/mpeg"
		, "mpg" => "video/mpeg"
		, "mpe" => "video/mpeg"
		, "mov" => "video/quicktime"
		, "avi" => "video/x-msvideo"
		, "3gp" => "video/3gpp"
		, "css" => "text/css"
		, "jsc" => "application/javascript"
		, "js" => "application/javascript"
		, "php" => "text/html"
		, "htm" => "text/html"
		, "html" => "text/html"
	);

	$extension = strtolower(end(explode('.', $filename)));

	return $mime_types[$extension];
}