<?php

// Default framework page handler is 'framework'
elgg_register_page_handler('framework', 'hj_framework_page_handlers');

/**
 * Page handlers for hypeFramework
 *
 *
 * @param type $page
 * @return type
 */
function hj_framework_page_handlers($page) {

	if (!isset($page[0])) {
		forward();
	}

	$path_pages = elgg_get_root_path() . 'mod/hypeFramework/pages/';

	switch ($page[0]) {

		case 'edit' :
			set_input('guid', $page[1]);
			include $path_pages . 'edit/object.php';
			break;

		case 'icon':
			set_input('guid', $page[1]);
			set_input('size', $page[2]);
			include $path_pages . "icon/icon.php";
			break;

		case 'download':
			set_input('guid', $page[1]);
			include $path_pages . "file/download.php";
			break;

		default :
			return false;
			break;
	}
	return true;
}

