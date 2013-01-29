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
$list_body = '';

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
				'data-list' => $list_id,
			);
			$list_item_uids[] = $item->guid;
		} elseif ($item instanceof ElggRiverItem) {
			$id = "elgg-river-{$item->id}";
			$attr = array(
				'id' => $id,
				'class' => $item_class,
				'data-uid' => $item->id,
				'data-list' => $list_id,
			);
			$list_item_uids[] = $item->id;
		} elseif ($item instanceof ElggAnnotation) { // Thanks to Matt Beckett for the fix
			$id = "item-{$item->name}-{$item->id}";
			$attr = array(
				'id' => $id,
				'class' => $item_class,
				'data-uid' => $item->id,
				'data-list' => $list_id,
			);
			$list_item_uids[] = $item->id;
		}

		if ($id !== false) {
			$list_body .= elgg_view('page/components/grids/list/item', array(
				'item' => $item,
				'attributes' => $attr,
				'params' => $viewer_options
					));
		}
	}
} else {
	$list_body = elgg_view('page/components/grids/list/placeholder', array(
		'class' => $item_class,
		'data-list' => $list_id
			));
}

$list = "<ul id=\"$list_id\" class=\"$list_class\">$list_body</ul>";

// We are storing details of this list in the framework.data object
// This gives us access to guids of elements that have been rendered and other data we can use in JS

$data = array(
	'lists' => array(
		"$list_id" => array(
			'list_id' => $list_id,
			'item_uids' => $list_item_uids,
			'list_options' => $list_options,
			'getter_options' => $getter_options,
			'viewer_options' => $viewer_options,
			'getter' => $getter
	)));

$data = elgg_trigger_plugin_hook('client_side_list_options', 'framework:lists', $vars, $data);

$data = json_encode($data);
$script = <<<___JS
		<script type="text/javascript">
			var new_list = $data;
			if (!framework.data) {
				framework.data = new Object();
			}
			framework.data = $.extend(true, framework.data, new_list);
		</script>
___JS;

echo "$list $script";