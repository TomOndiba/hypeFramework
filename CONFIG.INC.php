<?php

define('HYPEFRAMEWORK_RELEASE', 1349960132);

// global page handler namespace
$pagehandler = elgg_get_plugin_setting('global_pagehandler', 'hypeFramework');
if (!$pagehandler) {
	$pagehandler = 'framework';
	elgg_set_plugin_setting('global_pagehandler', $pagehandler, 'hypeFramework');
}
define('HYPEFRAMEWORK_PAGEHANDLER', $pagehandler);

define('HYPEFRAMEWORK_PATH', 'mod/hypeFramework/');
define('HYPEFRAMEWORK_PATH_ACTIONS', elgg_get_root_path() . HYPEFRAMEWORK_PATH . 'actions/');
define('HYPEFRAMEWORK_PATH_CLASSES', elgg_get_root_path() . HYPEFRAMEWORK_PATH . 'classes/');
define('HYPEFRAMEWORK_PATH_GRAPHICS', elgg_get_site_url() .HYPEFRAMEWORK_PATH . 'graphics/');
define('HYPEFRAMEWORK_PATH_LANGUAGES', elgg_get_root_path() .HYPEFRAMEWORK_PATH . 'languages/');
define('HYPEFRAMEWORK_PATH_LIBRARIES', elgg_get_root_path() .HYPEFRAMEWORK_PATH . 'lib/');
define('HYPEFRAMEWORK_PATH_PAGES', elgg_get_root_path() .HYPEFRAMEWORK_PATH . 'pages/');
define('HYPEFRAMEWORK_PATH_VENDORS', elgg_get_root_path() .HYPEFRAMEWORK_PATH . 'vendors/');
define('HYPEFRAMEWORK_PATH_VIEWS', elgg_get_site_url() . HYPEFRAMEWORK_PATH . 'views/default/');