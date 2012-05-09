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
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 * @uses $vars['inverse_order']  Is this list in an inversed order in relation to data_options
 * $uses $vars['data-options'] An array of options that was used to render the items
 */
$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$limit_prev = elgg_extract('limit_prev', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');
$list_class = 'elgg-list hj-carousel hidden';
$list_id = elgg_extract('list_id', $vars, null);
$inverse_order = elgg_extract('inverse_order', $vars, null);
$data_options = elgg_extract('data-options', $vars, false);
$full_view = elgg_extract('full_view', $vars, false);

elgg_push_context('carousel');

if (is_array($data_options)) {
	$list_class = "$list_class hj-syncable";
}

if (isset($vars['list_class'])) {
	$list_class = "$list_class {$vars['list_class']}";
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
	'inverse_order' => $inverse_order,
	'full_view' => $full_view
);

if ($pagination && $count) {
	$pagination_options['ajaxify'] = false;
	if (is_array($data_options)) {
		$pagination_options['ajaxify'] = true;
	}

	$nav .= '<div class="hidden">' . elgg_view('navigation/pagination', $pagination_options) . '</div>';
}

$before = elgg_view('page/components/list/prepend', $vars);
$after = elgg_view('page/components/list/append', $vars);

$list_params = array('items', 'offset', 'limit', 'count', 'base_url', 'pagination', 'offset_key', 'position', 'list_class', 'list_id', 'data-options');
foreach ($list_params as $list_param) {
	if (isset($vars[$list_param])) {
		unset($vars[$list_param]);
	}
}

$html .= $before;

$data_options_list_items = array();
if (is_array($items) && count($items) > 0) {
	if (!$selected = get_input('guid')) {
		$selected = $items[0]->guid;
	}

	foreach ($items as $item) {

		if (elgg_instanceof($item)) {
			$id = "elgg-{$item->getType()}-{$item->getGUID()}";
			if (!$title = $item->title) {
				$title = $item->name;
			}
			$data_options_list_items[] = $item->getGUID();
			if ($item->getGUID() == $guid) {
				$selected = "hj-carousel-selected";
			} else {
				$selected = "";
			}
		}

		$html .= "<li id=\"$id\" class=\"$item_class $selected\" title=\"$title\">";
		$html .= elgg_view_list_item($item, $vars);
		$html .= '</li>';
	}
}

$html .= $after;

$html = "<ul id=\"$list_id\" class=\"$list_class\">$html</ul>";

$carousel = "<div id=\"carousel-$list_id\" class=\"hj-carousel-wrapper\">";
$carousel .= "<div class=\"hj-carousel-pagination clearfix\">";
$carousel .= "<div class=\"hj-carousel-prev elgg-col-1of3\"></div>";
$carousel .= "<div class=\"hj-carousel-pager elgg-col-1of3\"></div>";
$carousel .= "<div class=\"hj-carousel-next elgg-col-1of3\"></div>";
$carousel .= "</div>";
$carousel .= "<div class=\"hj-carousel-content\"></div>";
$carousel .= $html;
$carousel .= $nav;
$carousel .= '</div>';

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
			if (!window.hjdata.lists) {
				window.hjdata.lists = new Object();
			}
			if (!window.hjdata.lists["$list_id"]) {
				window.hjdata.lists["$list_id"] = new Object();
				var init = true;
			}
			window.hjdata = $.extend(true, window.hjdata, new_list);
			if (init) {
				var params = new Object();
				params.list_id = "$list_id";
				params.data = $data;
				elgg.trigger_hook('new_lists', 'hj:framework:ajax', params, true);
			}
		</script>
___JS;
	echo $script;
}
elgg_pop_context();

echo $carousel;
