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

		case 'print' :
			include $path_pages . 'print/print.php';
			break;

		case 'pdf' :
			include $path_pages . 'print/pdf.php';
			break;

		case 'icon':
			set_input('guid', $page[1]);
			set_input('size', $page[2]);
			include $path_pages . "icon/icon.php";
			break;

		case 'sync':
			switch ($page[1]) {
				default :
					include $path_pages . "sync/sync.php";
					break;

				case 'priority' :
					include $path_pages . "sync/sync_priority.php";
					break;

				case 'metadata' :
					include $path_pages . "sync/sync_metadata.php";
					break;

				case 'relationship' :
					include $path_pages . "sync/sync_relationship.php";
					break;
			}
			break;

		case 'multifile' :
			session_id(get_input('Elgg'));
			global $_SESSION;
			$_SESSION = json_decode(get_input('SESSION'), true);
			hj_framework_handle_multifile_upload(get_input('userid'));
			break;

		default :
			return false;
			break;
	}
	return true;
}

