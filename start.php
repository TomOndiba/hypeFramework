<?php

/* hypeFramework
 * Provides classes, libraries and views for hypeJunction plugins
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyrigh (c) 2011-2012, Ismayil Khayredinov
 */

require 'CONFIG.INC.php'; // global path and var definitions

elgg_register_event_handler('init', 'system', 'hj_framework_init');
elgg_register_event_handler('init', 'system', 'hj_framework_forms_init');

function hj_framework_init() {

	elgg_register_library('framework:base', HYPEFRAMEWORK_PATH_LIBRARIES . 'base.php');
	elgg_load_library('framework:base');

	hj_framework_check_release(HYPEFRAMEWORK_PLUGINNAME, HYPEFRAMEWORK_RELEASE);

	elgg_register_classes(HYPEFRAMEWORK_PATH_CLASSES);

	/**
	 * CORE LIBRARIES
	 */
	// Register Actions
	elgg_register_library('framework:actions', HYPEFRAMEWORK_PATH_LIBRARIES . 'actions.php');
	elgg_load_library('framework:actions');

	// Register view extentions, JS and CSS simplecache views
	elgg_register_library('framework:views', HYPEFRAMEWORK_PATH_LIBRARIES . 'views.php');
	elgg_load_library('framework:views');

	// Hook handler definitions and functions
	elgg_register_library('framework:hook_handlers', HYPEFRAMEWORK_PATH_LIBRARIES . 'hook_handlers.php');
	elgg_load_library('framework:hook_handlers');

	// Page handler definitions and functions
	elgg_register_library('framework:page_handlers', HYPEFRAMEWORK_PATH_LIBRARIES . 'page_handlers.php');
	elgg_load_library('framework:page_handlers');

	// URL handler definitions and functions
	elgg_register_library('framework:url_handlers', HYPEFRAMEWORK_PATH_LIBRARIES . 'url_handlers.php');
	elgg_load_library('framework:url_handlers');

	/**
	 * INTERFACE LIBRARIES
	 */
	// Form Object configuration arrays and upgrade logic
	elgg_register_library('hj:interface:forms', HYPEFRAMEWORK_PATH_LIBRARIES . 'interfaces/forms.php');
	elgg_load_library('hj:interface:forms');

	// File management
	elgg_register_library('hj:interface:files', HYPEFRAMEWORK_PATH_LIBRARIES . 'interfaces/files.php');
	elgg_load_library('hj:interface:files');
	
	// List management
	elgg_register_library('hj:interface:lists', HYPEFRAMEWORK_PATH_LIBRARIES . 'interfaces/lists.php');
	elgg_load_library('hj:interface:lists');
	
	// Hierarchy management
	elgg_register_library('hj:interface:hierarchies', HYPEFRAMEWORK_PATH_LIBRARIES . 'interfaces/hierarchies.php');
	elgg_load_library('hj:interface:hierarchies');
	
	// Geolocation management
	elgg_register_library('hj:interface:location', HYPEFRAMEWORK_PATH_LIBRARIES . 'interfaces/location.php');
	elgg_load_library('hj:interface:location');

	// Date and Time management
	elgg_register_library('hj:interface:calendar', HYPEFRAMEWORK_PATH_LIBRARIES . 'interfaces/calendar.php');
	elgg_load_library('hj:interface:calendar');

	// Media management
	elgg_register_library('hj:interface:media', HYPEFRAMEWORK_PATH_LIBRARIES . 'interfaces/media.php');
	elgg_load_library('hj:interface:media');

	// File management
	elgg_register_library('hj:interface:relationships', HYPEFRAMEWORK_PATH_LIBRARIES . 'interfaces/relationships.php');
	elgg_load_library('hj:interface:relationships');

	// Database of useful knowledge
	elgg_register_library('framework:knowledge', HYPEFRAMEWORK_PATH_LIBRARIES . 'knowledge.php');
	elgg_load_library('framework:knowledge');

	/**
	 * VENDOR LIBRARIES
	 */
	// DomPDF
	// DomPDF library is not included by default
	// Download and unzip to vendors/dompdf
	$dompdf = HYPEFRAMEWORK_PATH_VENDORS . 'dompdf/dompdf_config.inc.php';
	if (file_exists($dompdf)) {
		elgg_register_library('framework:dompdf', $dompdf);
	}
}

/**
 * Initialize forms on system init
 */
function hj_framework_forms_init() {
	// Check for form configuration updates
	// Ignore configration arrays if hypeFormBuilder is running
	if (elgg_is_admin_logged_in() && !elgg_is_active_plugin('hypeFormBuilder')) {
		elgg_register_event_handler('ready', 'system', 'hj_framework_forms_check_updates');
	}
}