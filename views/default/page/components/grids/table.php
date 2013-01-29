<?php

elgg_push_context('table-view');

$entities = elgg_extract('entities', $vars);
unset($vars['entities']);

$class = "elgg-table hj-framework-table-view";

if (isset($vars['list_class'])) {
	$class = "$class {$vars['list_class']}";
	unset($vars['list_class']);
}

$id = $vars['list_id'];

$table .= "<table id=\"$id\" class=\"$class\">";
$table .= elgg_view('page/components/grids/elements/table/head', $vars);

foreach ($entities as $e) {
	$vars['entity'] = $e;
	$table .= elgg_view('page/components/grids/elements/table/row', $vars);
}

$table .= '</table>';

echo $table;

elgg_pop_context();