<?php

/**
 * View a list of items
 *
 * @package Elgg
 *
 * @uses $vars['items']       Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['offset']      Index of the first list item in complete list
 * @uses $vars['limit']       Number of items per page
 * @uses $vars['count']       Number of items in the complete list
 * @uses $vars['base_url']    Base URL of list (optional)
 * @uses $vars['pagination']  Show pagination? (default: true)
 * @uses $vars['position']    Position of the pagination: before, after, or both
 * @uses $vars['full_view']   Show the full view of the items (default: false)
 * @uses $vars['gallery_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 * @uses $vars['inverse_order']  Is this list in an inversed order in relation to data_options
 * $uses $vars['data-options'] An array of options that was used to render the items
 */

$list_type = elgg_extract('list_type', $vars, 'gallery');

if ($list_type !== 'gallery' && elgg_view_exists("page/components/$list_type")) {
	echo elgg_view("page/components/$list_type", $vars);
	return true;
}
$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$limit_prev = elgg_extract('limit_prev', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');
$gallery_class = 'elgg-gallery';
$list_id = elgg_extract('list_id', $vars, null);
$inverse_order = elgg_extract('inverse_order', $vars, null);
$data_options = elgg_extract('data-options', $vars, false);
$full_view = elgg_extract('full_view', $vars, false);

if (is_array($data_options)) {
	$gallery_class = "$gallery_class hj-syncable";
}

if (isset($vars['gallery_class'])) {
	$gallery_class = "$gallery_class {$vars['gallery_class']}";
}

$item_class = 'elgg-item';
if (isset($vars['item_class'])) {
	$item_class = "$item_class {$vars['item_class']}";
}

$html = "";
$nav = "";

if ($data_options['type'] && $data_options['subtype']) {
	$pagination_str = elgg_echo("items:{$data_options['type']}:{$data_options['subtype']}");
}
$pagination_options = array(
	'baseurl' => $base_url,
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit, // this is a limit for how many items to load on refresh / pagination
	'limit_prev' => $limit_prev, // this is a limit that was used to render the initial list
	'string' => $pagination_str, // comes in handy when rendering a language string for show all/ show next
	'offset_key' => $offset_key,
	'list_id' => $list_id,
	'inverse_order' => $inverse_order
);

$pagination_options['item_view_params'] = elgg_extract('item_view_params', $vars, array());
$pagination_options['item_view_params']['full_view'] = $full_view;

if ($pagination && $count) {
	$pagination_options['ajaxify'] = false;
	if (is_array($data_options)) {
		$pagination_options['ajaxify'] = true;
	}

	$nav .= elgg_view('navigation/pagination', $pagination_options);
}

$before = elgg_view('page/components/list/prepend', $vars);
$after = elgg_view('page/components/list/append', $vars);

$list_params = array('items', 'offset', 'limit', 'count', 'base_url', 'pagination', 'offset_key', 'position', 'gallery_class', 'list_id', 'data-options', 'item_view_params');
foreach ($list_params as $list_param) {
	if (isset($vars[$list_param])) {
		unset($vars[$list_param]);
	}
}

$html .= $before;

$data_options_list_items = array();
if (is_array($items) && count($items) > 0) {
	foreach ($items as $item) {
		if (elgg_instanceof($item)) {
			$id = "elgg-{$item->getType()}-{$item->getGUID()}";
			$data_options_list_items[] = $item->getGUID();
		} else {
			$id = "item-{$item->getType()}-{$item->id}";
			$time = $item->posted;
			$data_options_list_items[] = $item->id;
		}

		elgg_push_context('gallery');
		$html .= "<li id=\"$id\" class=\"$item_class\">";
		$html .= elgg_view_list_item($item, $vars);
		$html .= '</li>';
		elgg_pop_context();
	}
}

$html .= $after;

$html = "<ul id=\"$list_id\" class=\"$gallery_class\">$html</ul>";

if ($position == 'before' || $position == 'both' && !$ajaxify) {
	$html = $nav . $html;
}

if ($position == 'after' || $position == 'both') {
	$html .= $nav;
}
echo $html;

// We are storing details of this list in the window.hjdata object
// This gives us access to guids of elements that have been rendered and other data we can use in JS

if ($list_id) {
	$data = array(
		'lists' => array(
			"$list_id" => array(
				'options' => $data_options,
				'items' => $data_options_list_items,
				'pagination' => $pagination_options
		)));
	$data = json_encode($data);
	$script = <<<___JS
		<script type="text/javascript">
			var new_list = $data;
			if (!window.hjdata) {
				window.hjdata = new Object();
			}
			window.hjdata = $.extend(true, window.hjdata, new_list);
		</script>
___JS;
	echo $script;
}