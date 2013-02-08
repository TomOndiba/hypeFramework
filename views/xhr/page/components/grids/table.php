<?php

elgg_push_context('table-view');

$list_id = $output['list_id'] = elgg_extract('list_id', $vars);
$entities = elgg_extract('entities', $vars);
$count = elgg_extract('count', $vars);
$list_options = elgg_extract('list_options', $vars);
$output['list_type'] = $list_options['list_type'];
$getter_options = elgg_extract('getter_options', $vars);
$viewer_options = elgg_extract('viewer_options', $vars);
$getter = elgg_extract('getter', $vars);

$class = "elgg-table-alt hj-framework-table-view";
$item_class = trim("elgg-item " . elgg_extract('item_class', $list_options, ''));

if (isset($list_options['list_class'])) {
	$class = "$class {$list_options['list_class']}";
}

$id = $vars['list_id'];

$output['head'] = elgg_view('page/components/grids/elements/table/head', $vars);

$output['items'] = array();

if (is_array($entities) && count($entities) > 0) {

	foreach ($entities as $item) {
		if (!elgg_instanceof($item) && is_numeric($item)) {
			$item = get_entity($item);
		}

		$id = false;

		if (elgg_instanceof($item)) {
			$id = "elgg-entity-$item->guid";
			$uid = $item->guid;
			$ts = max(array($item->time_created, $item->time_updated, $item->last_action));
		} elseif ($item instanceof ElggRiverItem) {
			$id = "elgg-river-{$item->id}";
			$uid = $item->id;
			$ts = $item->posted;
		} elseif ($item instanceof ElggAnnotation) { // Thanks to Matt Beckett for the fix
			$id = "item-{$item->name}-{$item->id}";
			$uid = $item->id;
			$ts = $item->time_created;
		}

		if ($id !== false) {
			$attr = array(
				'id' => $id,
				'class' => $item_class,
				'data-uid' => $uid,
				'data-ts' => $ts
			);

			$output['items'][] = elgg_view('page/components/grids/elements/table/row', array(
				'entity' => $item,
				'attributes' => $attr,
				'list_options' => $list_options
			));
		}
	}

} else {
	$output['items'][] = elgg_view('page/components/grids/elements/table/placeholder', array(
		'class' => $item_class,
		'data-uid' => -1,
		'data-ts' => time(),
		'colspan' => count($list_options['list_view_options']['table']['head'])
			));
}

$show_pagination = elgg_extract('pagination', $list_options, true);

$pagination_type = elgg_extract('pagination_type', $list_options, 'paginate');

if ($show_pagination) {
	$pagination = elgg_view("page/components/grids/elements/pagination/$pagination_type", $vars);
}

$pagination = '<div class="hj-framework-list-pagination-wrapper row-fluid">' . $pagination . '</div>';

$output['pagination'] = $pagination;

echo json_encode($output);

elgg_pop_context();