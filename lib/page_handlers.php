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

		case 'add' : // framework/add/$container_guid/$type/$subtype/$handler/$widget_guid/$segment_guid
			if (isset($page[1])) {
				set_input('container_guid', $page[1]);
				if (isset($page[2])) {
					set_input('entity_type', $page[2]);
					if (isset($page[3])) {
						set_input('entity_subtype', $page[3]);
						if (isset($page[4])) {
							set_input('entity_handler', $page[4]);
							if (isset($page[5])) {
								set_input('widget_guid', $page[5]);
								if (isset($page[6])) {
									set_input('segment_guid', $page[6]);
								}
							}
						}
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}

			include $path_pages . 'entities/add.php';
			break;

		case 'edit' : // framework/edit/$entity_guid
			set_input('entity_guid', $page[1]);
			include $path_pages . 'entities/edit.php';
			break;

		case 'view' :
			set_input('entity_guid', $page[1]);
			include $path_pages . 'entities/view.php';
			break;

		case 'form' : // framework/form/$type/$subtype/$handler/$entity_guid
			set_input('type', $page[1]);
			set_input('subtype', $page[2]);
			set_input('handler', $page[3]);
			set_input('entity_guid', $page[4]);
			include $path_pages . 'forms/view.php';
			break;
		
		case 'file' :
			if (!isset($page[1]))
				forward();


			switch ($page[1]) {
				case 'view' :

					break;

				case 'download':
					set_input('guid', $page[2]);
					include $path_pages . 'file/download.php';
					break;

				default :
					forward();
					break;
			}

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

