<?php

$list_options = elgg_extract('list_options', $vars);

$show_filter = elgg_extract('list_filter', $list_options, false);
$filter = elgg_extract('list_filter_view_override', $list_options, false);

$before = elgg_view('page/components/lists/list/before', $vars);
$list = elgg_view('page/components/lists/list/body', $vars);
$after = elgg_view('page/components/lists/list/after', $vars);

if ($show_filter) {
	if (!$filter) {
		$before .= elgg_view('page/components/lists/elements/filter', $vars);
	} else {
		$before .= elgg_view($filter, $vars);
	}
}

$show_pagination = elgg_extract('list_pagination', $list_options, false);

if ($show_pagination) {
	$pagination_type = elgg_extract('list_pagination_type', $list_options, 'paginate');
	$pagination = elgg_view("page/components/lists/elements/pagination/$pagination_type", $vars);
	$position = elgg_extract('list_pagination_bosition', $list_options, 'after');

	if ($position == 'both') {
		$before = "$before $pagination";
		$after = "$pagination $after";
	} else if ($position == 'before') {
		$before = "$before $pagination";
	} else {
		$after = "$pagination $after";
	}
}

echo "$before $list $after";