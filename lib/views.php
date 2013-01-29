<?php

elgg_unregister_js('jquery');
elgg_register_js('jquery', 'mod/hypeFramework/vendors/jquery/jquery-1.8.2.min.js');
elgg_load_js('jquery');

elgg_unregister_js('jquery-ui');
elgg_register_js('jquery-ui', 'mod/hypeFramework/vendors/jquery/jquery-ui-1.9.0.custom.min.js');
elgg_load_js('jquery-ui');

elgg_register_css('jquery-ui-theme', 'mod/hypeFramework/vendors/jquery/jquery-ui-1.9.0.custom.min.css', 100);
elgg_load_css('jquery-ui-theme');

elgg_register_css('bootstrap', 'mod/hypeFramework/vendors/bootstrap/css/bootstrap.min.css', 110);
elgg_load_css('bootstrap');

elgg_register_css('bootstrap-responsive', 'mod/hypeFramework/vendors/bootstrap/css/bootstrap-responsive.min.css', 120);
elgg_load_css('bootstrap-responsive');

elgg_register_js('bootstrap', 'mod/hypeFramework/vendors/bootstrap/js/bootsrap.min.js');
elgg_load_js('bootsrap');

elgg_register_js('jquery.validation', 'mod/hypeFramework/vendors/jquery.validate/jquery.validate.min.js');


$hj_js_ajax = elgg_get_simplecache_url('js', 'framework/ajax');
elgg_register_js('framework.ajax', $hj_js_ajax);
elgg_load_js('framework.ajax');
elgg_register_simplecache_view('js/framework/ajax');

$hj_js_ui = elgg_get_simplecache_url('js', 'framework/ui');
elgg_register_js('framework.ui', $hj_js_ui);
elgg_load_js('framework.ui');
elgg_register_simplecache_view('js/framework/ui');

$hj_js_tabs = elgg_get_simplecache_url('js', 'framework/tabs');
elgg_register_js('framework.tabs', $hj_js_tabs);
elgg_register_simplecache_view('js/framework/tabs');

$hj_js_sortable_tabs = elgg_get_simplecache_url('js', 'framework/tabs.sortable');
elgg_register_js('framework.tabs.sortable', $hj_js_sortable_tabs);
elgg_register_simplecache_view('js/framework/tabs/sortable');

$hj_js_sortable_list = elgg_get_simplecache_url('js', 'framework/list.sortable');
elgg_register_js('framework.list.sortable', $hj_js_sortable_list);
elgg_register_simplecache_view('js/framework/list.sortable');

// JS to check mandatory fields
$hj_js_relationshiptags = elgg_get_simplecache_url('js', 'framework/relationshiptags');
elgg_register_js('framework.relationshiptags', $hj_js_relationshiptags);
elgg_register_simplecache_view('js/framework/relationshiptags');

// JS for colorpicker
$hj_js_colorpicker = elgg_get_simplecache_url('js', 'vendors/colorpicker/colorpicker');
elgg_register_js('framework.colorpicker', $hj_js_colorpicker);
elgg_register_simplecache_view('js/vendors/colorpicker/colorpicker');

// JS for filetree
$hj_js_tree = elgg_get_simplecache_url('js', 'vendors/jstree/tree');
elgg_register_js('framework.tree', $hj_js_tree);
elgg_register_simplecache_view('js/vendors/jstree/tree');

// JS for CLEditor
$hj_js_editor = elgg_get_simplecache_url('js', 'vendors/editor/editor');
elgg_register_js('framework.editor', $hj_js_editor);
elgg_register_simplecache_view('js/vendors/editor/editor');

//    if (elgg_get_plugin_setting('cleditor', 'hypeFramework') == 'on') {
//        elgg_load_js('framework.editor');
//    }

$hj_js_uploadify = elgg_get_simplecache_url('js', 'vendors/uploadify/jquery.uploadify');
elgg_register_js('framework.uploadify', $hj_js_uploadify);
elgg_register_simplecache_view('js/vendors/uploadify/jquery.uploadify');

$hj_js_uploadify_init = elgg_get_simplecache_url('js', 'vendors/uploadify/multifile.init');
elgg_register_js('framework.multifile', $hj_js_uploadify_init);
elgg_register_simplecache_view('js/vendors/uploadify/multifile.init');

elgg_load_js('jquery.form');

$css = elgg_get_simplecache_url('css', 'framework/base');
elgg_register_css('framework', $css);
elgg_register_simplecache_view('css/framework/base');
elgg_load_css('framework');

// Load the 960 Grid
elgg_extend_view('css/elgg', 'css/framework/grid');
elgg_extend_view('css/admin', 'css/framework/grid');

// Profile CSS
if (!elgg_is_active_plugin('profile')) {
	$hj_css_profile = elgg_get_simplecache_url('css', 'framework/profile');
	elgg_register_css('framework.profile', $hj_css_profile);
	elgg_register_simplecache_view('css/framework/profile');
}
// CSS for colorpicker
$hj_css_colorpicker = elgg_get_simplecache_url('css', 'vendors/colorpicker/colorpicker.css');
elgg_register_css('framework.colorpicker', $hj_css_colorpicker);
elgg_register_simplecache_view('css/vendors/colorpicker/colorpicker.css');

// Carousel
$hj_css_carousel = elgg_get_simplecache_url('css', 'vendors/carousel/rcarousel.css');
elgg_register_css('framework.carousel', $hj_css_carousel);
elgg_register_simplecache_view('css/vendors/carousel/rcarousel.css');

// PL Upload
$hj_css_uploadify = elgg_get_simplecache_url('css', 'vendors/uploadify/uploadify.css');
elgg_register_css('framework.uploadify', $hj_css_uploadify);
elgg_register_simplecache_view('css/vendors/uploadify/uploadify.css');

if (elgg_get_plugin_setting('cleditor', 'hypeFramework') == 'on') {
	elgg_extend_view('input/longtext', 'js/vendors/editor/metatags');

	//elgg_extend_view('page/elements/head', 'js/vendors/editor/metatags');
}