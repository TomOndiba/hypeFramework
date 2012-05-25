<?php

/**
 * hypeFramework helper functions
 */

/**
 * Register hypeJunction Framework Libraries
 *
 * @return void
 */
function hj_framework_register_libraries() {
	$shortcuts = hj_framework_path_shortcuts('hypeFramework');

	/**
	 * Core Libraries
	 */
	// Setup functions
	elgg_register_library('hj:framework:setup', $shortcuts['lib'] . 'framework/setup.php');
	// A pool of useful recyclable information (dropdowns, arrays etc)
	elgg_register_library('hj:framework:knowledge', $shortcuts['lib'] . 'framework/knowledge.php');
	// Menu Builder
	elgg_register_library('hj:framework:menu', $shortcuts['lib'] . 'framework/menu.php');
	// URL and Page Handler Functions
	elgg_register_library('hj:framework:pagehandler', $shortcuts['lib'] . 'framework/pagehandler.php');
	// Plugin and event hooks Functions
	elgg_register_library('hj:framework:hooks', $shortcuts['lib'] . 'framework/hooks.php');
	// Entity related helpers
	elgg_register_library('hj:framework:entities', $shortcuts['lib'] . 'framework/entities.php');
	// File Management
	elgg_register_library('hj:framework:files', $shortcuts['lib'] . 'framework/files.php');
	// Form Management
	elgg_register_library('hj:framework:forms', $shortcuts['lib'] . 'framework/forms.php');

	// DomPDF
	// DomPDF library is not included by default
	// Download and unzip to lib/dompdf
	$dompdf = $shortcuts['lib'] . 'dompdf/dompdf_config.inc.php';
	if (file_exists($dompdf)) {
		elgg_register_library('hj:framework:dompdf', $dompdf);
	}

	/**
	 * Load Libraries
	 */
	elgg_load_library('hj:framework:knowledge');
	elgg_load_library('hj:framework:menu');
	elgg_load_library('hj:framework:pagehandler');
	elgg_load_library('hj:framework:hooks');
	elgg_load_library('hj:framework:entities');
	elgg_load_library('hj:framework:files');

	return true;
}

/**
 * Register hypeJunction Javascript Libraries
 *
 * @return void
 */
function hj_framework_register_js() {

	elgg_extend_view('js/hj/framework/ajax', 'js/lightbox');
	elgg_extend_view('js/hj/framework/ajax', 'js/hj/framework/fieldcheck');
	elgg_extend_view('js/hj/framework/ajax', 'js/vendors/slider/bxslider');

	$hj_js_ajax = elgg_get_simplecache_url('js', 'hj/framework/ajax');
	elgg_register_js('hj.framework.ajax', $hj_js_ajax);
	elgg_load_js('hj.framework.ajax');

	$hj_js_tabs = elgg_get_simplecache_url('js', 'hj/framework/tabs');
	elgg_register_js('hj.framework.tabs', $hj_js_tabs);

	$hj_js_sortable_tabs = elgg_get_simplecache_url('js', 'hj/framework/tabs.sortable');
	elgg_register_js('hj.framework.tabs.sortable', $hj_js_sortable_tabs);

	$hj_js_sortable_list = elgg_get_simplecache_url('js', 'hj/framework/list.sortable');
	elgg_register_js('hj.framework.list.sortable', $hj_js_sortable_list);

	// JS to check mandatory fields
	$hj_js_relationshiptags = elgg_get_simplecache_url('js', 'hj/framework/relationshiptags');
	elgg_register_js('hj.framework.relationshiptags', $hj_js_relationshiptags);

	// JS for colorpicker
	$hj_js_colorpicker = elgg_get_simplecache_url('js', 'vendors/colorpicker/colorpicker');
	elgg_register_js('hj.framework.colorpicker', $hj_js_colorpicker);

	// JS for filetree
	$hj_js_tree = elgg_get_simplecache_url('js', 'vendors/jstree/tree');
	elgg_register_js('hj.framework.tree', $hj_js_tree);

	// JS for CLEditor
	$hj_js_editor = elgg_get_simplecache_url('js', 'vendors/editor/editor');
	elgg_register_js('hj.framework.editor', $hj_js_editor);

//    if (elgg_get_plugin_setting('cleditor', 'hypeFramework') == 'on') {
//        elgg_load_js('hj.framework.editor');
//    }

	$hj_js_uploadify = elgg_get_simplecache_url('js', 'vendors/uploadify/jquery.uploadify');
	elgg_register_js('hj.framework.uploadify', $hj_js_uploadify);

	$hj_js_uploadify_init = elgg_get_simplecache_url('js', 'vendors/uploadify/multifile.init');
	elgg_register_js('hj.framework.multifile', $hj_js_uploadify_init);

	elgg_load_js('jquery.form');

	return true;
}

/**
 * Register hypeJunction CSS Libraries
 *
 * @return void
 */
function hj_framework_register_css() {
	// Load the CSS Framework
	elgg_extend_view('css/elgg', 'css/hj/framework/base');
	elgg_extend_view('css/admin', 'css/hj/framework/base');

	// Load the 960 Grid
	elgg_extend_view('css/elgg', 'css/hj/framework/grid');
	elgg_extend_view('css/admin', 'css/hj/framework/grid');

	// Profile CSS
	if (!elgg_is_active_plugin('profile')) {
		$hj_css_profile = elgg_get_simplecache_url('css', 'hj/framework/profile');
		elgg_register_css('hj.framework.profile', $hj_css_profile);
	}
	// CSS for colorpicker
	$hj_css_colorpicker = elgg_get_simplecache_url('css', 'vendors/colorpicker/colorpicker.css');
	elgg_register_css('hj.framework.colorpicker', $hj_css_colorpicker);

	// jQuery UI
	$hj_css_jq = elgg_get_simplecache_url('css', 'vendors/jquery/ui/theme');
	elgg_register_css('hj.framework.jquitheme', $hj_css_jq);

	// Carousel
	$hj_css_carousel = elgg_get_simplecache_url('css', 'vendors/carousel/rcarousel.css');
	elgg_register_css('hj.framework.jquitheme', $hj_css_carousel);

	// PL Upload
	$hj_css_uploadify = elgg_get_simplecache_url('css', 'vendors/uploadify/uploadify.css');
	elgg_register_css('hj.framework.uploadify', $hj_css_uploadify);

	return true;
}

/**
 * Register entity URL and page_handlers
 * @return void
 */
function hj_framework_register_page_handlers() {
	/**
	 * URL handlers
	 */
	// we need to protect certain entities from being viewed, as they do not have page handlers yet
	// these will be overriden within individual plugins
	elgg_register_entity_url_handler('object', 'hjform', 'hj_framework_entity_url_forwarder');
	elgg_register_entity_url_handler('object', 'hjfield', 'hj_framework_entity_url_forwarder');
	elgg_register_entity_url_handler('object', 'hjfile', 'hj_framework_entity_url_forwarder');
	elgg_register_entity_url_handler('object', 'hjfilefolder', 'hj_framework_entity_url_forwarder');

	elgg_register_entity_url_handler('object', 'hjannotation', 'hj_framework_annotation_url_forwarder');

	elgg_register_entity_url_handler('object', 'hjsegment', 'hj_framework_segment_url_forwarder');

	elgg_register_page_handler('hj', 'hj_framework_page_handlers');
}

/**
 *  Register plugin and even hooks
 *
 * @return void
 */
function hj_framework_register_hooks() {
	// Create new AJAXed menus
	elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_framework_entity_head_menu');
	elgg_register_plugin_hook_handler('register', 'menu:hjentityfoot', 'hj_framework_entity_foot_menu');
	elgg_register_plugin_hook_handler('register', 'menu:hjsegmenthead', 'hj_framework_segment_head_menu');
	elgg_register_plugin_hook_handler('register', 'menu:hjsectionfoot', 'hj_framework_section_foot_menu');

	// hjFile Icons
	elgg_register_plugin_hook_handler('entity:icon:url', 'all', 'hj_framework_entity_icons');

	// Add Widgets
	elgg_register_plugin_hook_handler('hj:framework:form:process', 'all', 'hj_framework_setup_segment_widgets');

	if (elgg_get_plugin_setting('cleditor', 'hypeFramework') == 'on') {
		if (elgg_is_active_plugin('tinymce'))
			elgg_unregister_plugin_hook_handler('register', 'menu:longtext', 'tinymce_longtext_menu');
		if (elgg_is_active_plugin('embed'))
			elgg_unregister_plugin_hook_handler('register', 'menu:longtext', 'embed_longtext_menu');
		elgg_unregister_js('elgg.tinymce');
	}

	// Allow writing to hjsegment containers
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'hj_framework_container_permissions_check');
	elgg_register_plugin_hook_handler('permissions_check:annotate', 'object', 'hj_framework_canannotate_check');

	// Process Input Types
	elgg_register_plugin_hook_handler('hj:formbuilder:fieldtypes', 'all', 'hj_framework_inputs');
	elgg_register_plugin_hook_handler('hj:framework:field:process', 'all', 'hj_framework_process_inputs');

	elgg_register_event_handler('create', 'object', 'hj_framework_widget_entity_list_update');
	elgg_register_event_handler('update', 'object', 'hj_framework_widget_entity_list_update');

	elgg_register_plugin_hook_handler('output', 'page', 'hj_framework_ajax_pageshell');
}

/**
 * Register necessary actions
 *
 * @return void
 */
function hj_framework_register_actions() {
	$shortcuts = hj_framework_path_shortcuts('hypeFramework');

	// View an entity (by its GUID) or render a view
	elgg_register_action('framework/entities/view', $shortcuts['actions'] . 'hj/framework/view.php', 'public');

	// Edit an entity
	elgg_register_action('framework/entities/edit', $shortcuts['actions'] . 'hj/framework/edit.php');
	// Process an hjForm on submit
	elgg_register_action('framework/entities/save', $shortcuts['actions'] . 'hj/framework/submit.php', 'public');
	// Delete an entity by guid
	elgg_register_action('framework/entities/delete', $shortcuts['actions'] . 'hj/framework/delete.php', 'public');
	// Reset priority attribute of an object
	elgg_register_action('framework/entities/move', $shortcuts['actions'] . 'hj/framework/move.php');
	// E-mail form details
	elgg_register_action('framework/form/email', $shortcuts['actions'] . 'hj/framework/email.php');
	// Add widget
	elgg_register_action('framework/widget/add', $shortcuts['actions'] . 'hj/framework/addwidget.php');
	// Add widget
	elgg_register_action('framework/widget/load', $shortcuts['actions'] . 'hj/framework/loadwidget.php');
	// Download file
	elgg_register_action('framework/file/download', $shortcuts['actions'] . 'hj/framework/download.php', 'public');
}

/**
 * Get plugin tree shortcut urls
 *
 * @param string  $plugin     Plugin name string
 * @return array
 */
function hj_framework_path_shortcuts($plugin) {
	$path = elgg_get_plugins_path();
	$plugin_path = $path . $plugin . '/';

	return $structure = array(
		"actions" => "{$plugin_path}actions/",
		"classes" => "{$plugin_path}classes/",
		"graphics" => "{$plugin_path}graphics/",
		"languages" => "{$plugin_path}languages/",
		"lib" => "{$plugin_path}lib/",
		"pages" => "{$plugin_path}pages/",
		"vendors" => "{$plugin_path}vendors/"
	);
}

function hj_framework_register_view_extentions() {
	if (elgg_get_plugin_setting('cleditor', 'hypeFramework') == 'on') {
		elgg_extend_view('input/longtext', 'js/vendors/editor/metatags');

		//elgg_extend_view('page/elements/head', 'js/vendors/editor/metatags');
	}
}

function hj_framework_decode_options_array(&$item, $key) {
	$item = htmlspecialchars_decode($item, ENT_QUOTES);
	if ($item == 'null') {
		$item = null;
	}
	if ($item == 'false') {
		$item = false;
	}
	if ($item == 'true') {
		$item = true;
	}
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

	$thumb_sizes = elgg_trigger_plugin_hook('hj:framework:form:iconsizes', 'file', array('handler' => $handler), $thumb_sizes);
	return $thumb_sizes;
}