<?php

elgg_push_context('table-view');

$entities = elgg_extract('entities', $vars);
unset($vars['entities']);

$list_options = elgg_extract('list_options', $vars, array());


$class = "elgg-table-alt hj-framework-table-view";

if (isset($list_options['list_class'])) {
	$class = "$class {$list_options['list_class']}";
}

$id = $vars['list_id'];

$table .= "<table id=\"$id\" class=\"$class\">";
$table .= elgg_view('page/components/grids/elements/table/head', $vars);

foreach ($entities as $e) {
	$vars['entity'] = $e;
	$table .= elgg_view('page/components/grids/elements/table/row', $vars);
}

$table .= '</table>';

$show_pagination = elgg_extract('pagination', $list_options, false);

if ($show_pagination) {
	$pagination_type = elgg_extract('pagination_type', $list_options, 'paginate');
	$pagination = elgg_view("page/components/grids/elements/pagination/$pagination_type", $vars);
	$position = elgg_extract('pagination_position', $list_options, 'after');

	if ($position == 'both') {
		$table = "$pagination $table $pagination";
	} else if ($position == 'before') {
		$table = "$pagination $table";
	} else {
		$table = "$table $pagination";
	}
}

echo $table;

elgg_pop_context();