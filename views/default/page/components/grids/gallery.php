<?php

elgg_push_context('gallery-view');

$list_id = elgg_extract('list_id', $vars);
$entities = elgg_extract('entities', $vars);
$count = elgg_extract('count', $vars);
$list_options = elgg_extract('list_options', $vars);
$getter_options = elgg_extract('getter_options', $vars);
$viewer_options = elgg_extract('viewer_options', $vars);

$getter = elgg_extract('getter', $vars);

$class = "elgg-gallery hj-framework-gallery-view";
$item_class = trim("elgg-item elgg-gallery-item" . elgg_extract('item_class', $list_options, ''));

if (isset($list_options['list_class'])) {
	$class = "$class {$list_options['list_class']}";
}

$list_head = elgg_view('page/components/grids/elements/gallery/head', $vars);

if (is_array($entities) && count($entities) > 0) {

	foreach ($entities as $item) {
		if (!elgg_instanceof($item) && is_numeric($item)) {
			$item = get_entity($item);
		}

		$id = false;

		if (elgg_instanceof($item)) {
			$id = "elgg-entity-$item->guid";
			$uid = $item->guid;
			$timestamps[] = $item->time_created;
			$timestamps[] = $item->time_updated;
			$timestamps[] = $item->last_action;
			$ts = max($timestamps);
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

			$view_params = array_merge($viewer_options, array(
				'entity' => $item,
				'attributes' => $attr,
				'list_options' => $list_options
					));
			$list_body .= elgg_view('page/components/grids/elements/gallery/item', $view_params);
		}
	}
} else {
	$list_body = elgg_view('page/components/grids/elements/gallery/placeholder', array(
		'class' => $item_class,
		'data-uid' => -1,
		'data-ts' => time()
			));
}

$list = "$list_head<ul id=\"$list_id\" class=\"$class\">$list_body</ul>";

$show_pagination = elgg_extract('pagination', $list_options, true);

$pagination_type = elgg_extract('pagination_type', $list_options, 'paginate');

if ($show_pagination) {
	$pagination = elgg_view("page/components/grids/elements/pagination/$pagination_type", $vars);
}

$pagination = '<div class="hj-framework-list-pagination-wrapper row-fluid">' . $pagination . '</div>';
$position = elgg_extract('pagination_position', $list_options, 'after');

if ($position == 'both') {
	$list = "$pagination $list $pagination";
} else if ($position == 'before') {
	$list = "$pagination $list";
} else {
	$list = "$list $pagination";
}

echo $list;

elgg_pop_context();