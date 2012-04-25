<?php
if (elgg_is_xhr()) {
    $data = get_input('listdata');

	$sync = elgg_extract('sync', $data, 'new');
	$guid = elgg_extract('items', $data, 0);
	if (is_array($guid)) {
		if ($sync == 'new') {
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
        $options['order_by'] = 'e.time_created asc';
        $options['limit'] = 0;
    } else {
        $options['wheres'] = array("e.guid < {$guid}");
        $options['order_by'] = 'e.time_created desc';
    }
    $defaults = array(
        'offset' => (int) $offset,
        'limit' => (int) $limit,
        'pagination' => TRUE,
		'class' => 'hj-syncable-list'
    );

    $options = array_merge($defaults, $options);
	
    $items = elgg_get_entities($options);

    if (is_array($items) && count($items) > 0) {
        foreach ($items as $item) {
			if (!elgg_instanceof($item)) {
				$item = get_entity($item->guid);
			}
			$id = "elgg-{$item->getType()}-{$item->guid}";
            $html = "<li id=\"$id\" class=\"elgg-item\">";
            $html .= elgg_view_list_item($item, $vars);
            $html .= '</li>';

            $output[] = array('guid' => $item->guid, 'html' => $html);
        }
    }
	header('Content-Type: application/json');
    print(json_encode(array('output' => $output)));
    exit;
}

forward(REFERER);