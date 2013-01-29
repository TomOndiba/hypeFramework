<?php

$list_id = elgg_extract('list_id', $vars);
$entities = elgg_extract('entities', $vars);
$count = elgg_extract('count', $vars);
$list_options = elgg_extract('list_options', $vars);
$getter_options = elgg_extract('getter_options', $vars);
$viewer_options = elgg_extract('viewer_options', $vars);
$getter = elgg_extract('getter', $vars);

$list_class = "elgg-list " . elgg_extract('list_class', $list_options);
$item_class = "elgg-item " . elgg_extract('item_class', $list_options);

$list_item_uids = array();
$list_item_views = array();

if (is_array($entities) && count($entities) > 0) {
	foreach ($entities as $item) {
		if (!elgg_instanceof($item) && is_numeric($item)) {
			$item = get_entity($item);
		}

		$id = false;

		if (elgg_instanceof($item)) {
			$id = "elgg-entity-$item->guid";
			$attr = array(
				'id' => $id,
				'class' => $item_class,
				'data-uid' => $item->guid,
				'data-entity-type' => $item->getType(),
				'data-entity-subtype' => $item->getSubtype(),
				'data-list' => $list_id,
				'data-timestamp' => $item->time_created
			);
			$list_item_uids[] = $item->guid;
			$list_item_views["uid$item->guid"] = elgg_view('page/components/grids/list/item', array(
				'item' => $item,
				'attributes' => $attr,
				'params' => $viewer_options
					));
		} elseif ($item instanceof ElggRiverItem) {
			$id = "elgg-river-{$item->id}";
			$attr = array(
				'id' => $id,
				'class' => $item_class,
				'data-uid' => $item->id,
				'data-entity-type' => 'river',
				'data-list' => $list_id,
				'data-timestamp' => $item->posted
			);
			$list_item_uids[] = $item->id;
			$list_item_views["uid$item->id"] = elgg_view('page/components/grids/list/item', array(
				'item' => $item,
				'attributes' => $attr,
				'params' => $viewer_options
					));
		} elseif ($item instanceof ElggAnnotation) { // Thanks to Matt Beckett for the fix
			$id = "item-{$item->name}-{$item->id}";
			$attr = array(
				'id' => $id,
				'class' => $item_class,
				'data-uid' => $item->id,
				'data-entity-type' => $item->name,
				'data-list' => $list_id,
				'data-timestamp' => $item->time_created
			);
			$list_item_uids[] = $item->id;
			$list_item_views["uid$item->id"] = htmlentities(elgg_view('page/components/grids/list/item', array(
				'item' => $item,
				'attributes' => $attr,
				'params' => $viewer_options
					)), ENT_QUOTES);
		}
	}
}


// We are storing details of this list in the framework.data object
// This gives us access to guids of elements that have been rendered and other data we can use in JS

$data = array(
	'list_id' => $list_id,
	'item_uids' => $list_item_uids,
	'item_views' => $list_item_views,
);

$show_pagination = elgg_extract('list_pagination', $list_options, false);

if ($show_pagination) {
	$pagination_type = elgg_extract('list_pagination_type', $list_options, 'paginate');
	$data['pagination'] = elgg_view("page/components/grids/elements/pagination/$pagination_type", $vars);
}

$data = elgg_trigger_plugin_hook('client_side_list_options', 'framework:lists', $vars, $data);

$data = json_encode($data);

echo $data;