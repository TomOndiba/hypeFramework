<?php

/**
 * Process uploaded files
 *
 * @param mixed $files		Uploaded files
 * @param mixed $entity		If an entity is set and it doesn't belong to one of the file subtypes, uploaded files will be converted into hjFile objects and attached to the entity
 * @return void
 */
function hj_framework_process_file_upload($name, $entity = null) {

	if (is_array($_FILES[$name]['name'])) {
		$files = hj_framework_prepare_files_global($_FILES);
		$files = $files[$name];
	} else {
		$files = $_FILES[$name];
		$files = array($files);
	}

	// Is this file an attachment or a file entity?
	$file_subtypes = elgg_trigger_plugin_hook('file:subtypes', 'framework:config', null, array('file', 'hjfile'));
	$is_attachment = false;

	if (elgg_instanceof($entity)) {
		if (!in_array($entity->getSubtype(), $file_subtypes)) {
			$is_attachment = true;
		}
	}

	foreach ($files as $file) {
		if ($is_attachment) {
			$filehandler = new hjFile();
		} else {
			$filehandler = new hjFile($entity->guid);
		}

		$prefix = 'hjfile/';

		if ($entity instanceof hjFile) {
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
		$filehandler->simpletype = hj_framework_get_simple_type($file['type']);
		$filehandler->filesize = $file['size'];

		$filehandler->open("write");
		$filehandler->close();
		move_uploaded_file($file['tmp_name'], $filehandler->getFilenameOnFilestore());

		if ($filehandler->save()) {

			if ($is_attachment && elgg_instanceof($entity)) {
				make_attachment($entity->guid, $filehandler->getGUID());
			}

			if ($filehandler->simpletype == "image") {
				hj_framework_generate_entity_icons($filehandler);
			}

			$return[$file['name']] = $filehandler->getGUID();
		} else {
			$return[$file['name']] = false;
		}
	}

	return $return;
}

function hj_framework_prepare_files_global(array $_files, $top = TRUE) {
	$files = array();
	foreach ($_files as $name => $file) {
		if ($top) {
			$sub_name = $file['name'];
		} else {
			$sub_name = $name;
		}
		if (is_array($sub_name)) {
			foreach (array_keys($sub_name) as $key) {
				$files[$name][$key] = array(
					'name' => $file['name'][$key],
					'type' => $file['type'][$key],
					'tmp_name' => $file['tmp_name'][$key],
					'error' => $file['error'][$key],
					'size' => $file['size'][$key],
				);
				$files[$name] = hj_framework_prepare_files_global($files[$name], FALSE);
			}
		} else {
			$files[$name] = $file;
		}
	}
	return $files;
}

function hj_framework_generate_entity_icons($entity, $file = array()) {

	$icon_sizes = hj_framework_get_thumb_sizes($entity->getSubtype());

	$prefix = "icons/" . $entity->guid;

	if ($entity instanceof hjFile) {
		$filehandler = $entity;
	} else {
		$filehandler = new ElggFile();
		$filehandler->owner_guid = elgg_get_logged_in_user_guid();
		$filehandler->setFilename($prefix . ".jpg");
		$filehandler->open("write");
		$filehandler->write(file_get_contents($file['tmp_name']));
		$filehandler->close();
	}

	foreach ($icon_sizes as $size => $values) {
		$thumb_resized = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(), $values['w'], $values['h'], $values['square']);

		if ($thumb_resized) {
			$thumb = new ElggFile();
			$thumb->owner_guid = elgg_get_logged_in_user_guid();
			$thumb->setMimeType('image/jpeg');

			$thumb->setFilename($prefix . "$size.jpg");
			$thumb->open("write");
			$thumb->write($thumb_resized);
			$thumb->close();
			$icontime = true;
		}
	}
	if ($icontime) {
		$entity->icontime = time();
	}
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

	$thumb_sizes = elgg_trigger_plugin_hook('icon_sizes', 'formwork:config', array('handler' => $handler), $thumb_sizes);
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