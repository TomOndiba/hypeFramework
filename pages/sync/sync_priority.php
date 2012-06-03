<?php

if (elgg_is_xhr()) {
	$data = get_input('listdata');

	$sync = elgg_extract('sync', $data, 'new');
	$guids = elgg_extract('items', $data, 0);

	if (is_array($guids)) {
		$priority = get_entity($guids[0])->priority;
		foreach ($guids as $guid) {
			$ent_priority = get_entity($guid)->priority;
			if ($sync == 'new' && $ent_priority && $ent_priority < $priority) {
				$priority = $ent_priority;
			}
			if ($sync !== 'new' && $ent_priority && $ent_priority > $priority) {
				$priority = $ent_priority;
			}
		}
	}
	
	$options = elgg_extract('options', $data, array());
	array_walk_recursive($options, 'hj_framework_decode_options_array');

	$limit = elgg_extract('limit', $data['pagination'], 10);
	$offset = elgg_extract('offset', $data['pagination'], 0);
	$inverse_order = elgg_extract('inverse_order', $data['pagination'], false);
	if ($inverse_order == 'null') {
		$inverse_order = false;
	}

	$db_prefix = elgg_get_config('dbprefix');
	$defaults = array(
		'offset' => (int) $offset,
		'limit' => (int) $limit,
		'class' => 'hj-syncable-list',
		'joins' => array("JOIN {$db_prefix}metadata as mt on e.guid = mt.entity_guid
                      JOIN {$db_prefix}metastrings as msn on mt.name_id = msn.id
                      JOIN {$db_prefix}metastrings as msv on mt.value_id = msv.id"
		),
		'wheres' => array("((msn.string = 'priority') AND (msv.string > $priority))"),
		'order_by' => "CAST(msv.string AS SIGNED) ASC"
	);

	if ($sync == 'new' || $inverse_order) {
		$defaults['wheres'] = array("((msn.string = 'priority') AND (msv.string < $priority))");
	}
	$options = array_merge($defaults, $options);

	$items = elgg_get_entities($options);
	
	$item_view_params = elgg_extract('item_view_params', $data['pagination'], array());
	array_walk_recursive($item_view_params, 'hj_framework_decode_options_array');
	
	if (is_array($items) && count($items) > 0) {
		foreach ($items as $key => $item) {
			$id = "elgg-{$item->getType()}-{$item->guid}";
			$time = $item->time_created;

			$html = "<li id=\"$id\" class=\"elgg-item\">";
			$html .= elgg_view_list_item($item, $item_view_params);
			$html .= '</li>';

			$output[] = array('guid' => $item->guid, 'html' => $html);
		}
	}
	header('Content-Type: application/json');
	print(json_encode(array('output' => $output)));
	exit;
}

forward(REFERER);