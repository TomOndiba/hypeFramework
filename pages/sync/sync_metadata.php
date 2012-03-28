<?php

if (elgg_is_xhr()) {
	$data = get_input('listdata');
	$sync = elgg_extract('sync', $data, 'new');
	$guid = elgg_extract('items', $data, 0);
	$inverse_order = elgg_extract('inverse_order', $data['pagination'], false);
	if ($inverse_order == 'null') {
		$inverse_order = false;
	}

	if (is_array($guid)) {
		if (($sync == 'new' && !$inverse_order) || ($sync == 'old' && $inverse_order)) {
			$guid = $guid[0];
		} else {
			$guid = end($guid);
		}
	} else {
		$guid = 0;
	}
	
	$options = elgg_extract('options', $data, array());
	array_walk_recursive($options, 'hj_framework_decode_options_array');

	$limit = elgg_extract('limit', $data['pagination'], 10);
	$offset = elgg_extract('offset', $data['pagination'], 0);

	if ($sync == 'new') {
		$options['wheres'] = array("e.guid > {$guid}");
		$options['limit'] = 0;
	} else {
		$options['wheres'] = array("e.guid < {$guid}");
		if ($inverse_order) {
			$options['limit'] = 0;
		}
	}
	$defaults = array(
		'offset' => (int) $offset,
		'limit' => (int) $limit,
		'pagination' => TRUE,
		'class' => 'hj-syncable-list'
	);

	$options = array_merge($defaults, $options);
	$items = elgg_get_entities_from_metadata($options);

	if ($sync == 'new' && $inverse_order) {
		$items = array_reverse($items);
	}
	if (is_array($items) && count($items) > 0) {
		foreach ($items as $key => $item) {
			$id = "elgg-{$item->getType()}-{$item->guid}";
			$html = "<li id=\"$id\" class=\"elgg-item\">";
			$html .= elgg_view_list_item($item, array('full_view' => $data['pagination']['full_view']));
			$html .= '</li>';

			$output[] = array('guid' => $item->guid, 'html' => $html);
		}
	}
	header('Content-Type: application/json');
	print(json_encode(array('output' => $output)));
	exit;
}

forward(REFERER);