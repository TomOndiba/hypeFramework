<?php

function hj_framework_view_list($list_id, $getter_options = array(), $list_options = array(), $viewer_options = array(), $getter = 'elgg_get_entities') {

	$default_list_options = array(
		'list_type' => 'list',
		'list_class' => null,
		'item_class' => null,
		'base_url' => current_page_url(),
		'num_pages' => 5,
		'pagination' => true,
		'limit_key' => "__lim_$list_id",
		'offset_key' => "__off_$list_id",
		'order_by_key' => "__ord_$list_id",
		'direction_key' => "__dir_$list_id",
	);

	$list_options = array_merge($default_list_options, $list_options);


	if (!isset($getter_options['limit'])) {
		$getter_options['limit'] = get_input($list_options['limit_key'], 10);
	}
	if (!isset($getter_options['offset'])) {
		$getter_options['offset'] = get_input($list_options['offset_key'], 0);
	}

	$porder_by = get_input($list_options['order_by_key'], '');
	$pdirection = get_input($list_options['direction_key'], 'DESC');

	$getter_options = hj_framework_get_order_by_clause($porder_by, $pdirection, $getter_options);
	if (isset($list_options['filter_callback']) && is_callable($list_options['filter_callback'])) {
		$getter_options = call_user_func_array($list_options['filter_callback'], array($list_id, $getter_options));
	}

	$getter_options = elgg_trigger_plugin_hook('custom_sql_clause', 'framework:lists', array(
		'list_options' => $list_options,
		'viewer_options' => $viewer_options
	), $getter_options);

	$getter_options['count'] = true;
	$count = $getter($getter_options);

	$getter_options['count'] = false;
	$entities = $getter($getter_options);

	$params = array(
		'list_id' => $list_id,
		'entities' => $entities,
		'count' => $count,
		'list_options' => $list_options,
		'getter_options' => $getter_options,
		'viewer_options' => $viewer_options,
		'getter' => $getter
	);

	if (elgg_view_exists("page/components/grids/{$list_options['list_type']}")) {
		$list = elgg_view("page/components/grids/{$list_options['list_type']}", $params);
	} else {
		$list = elgg_view("page/components/grids/list", $params);
	}

	if (elgg_is_xhr() && get_input('view') == 'xhr') {
		global $XHR_OUTPUT;
		$XHR_OUTPUT['lists'][$list_id] = $list;
	}

	return elgg_view('page/components/grids/wrapper', array(
				'body' => $list
			));
}

function hj_framework_get_order_by_clause($porder_by = 'e.time_created', $pdirection = 'DESC', $options = array()) {

	list($prefix, $column) = explode('.', $porder_by);

	if (!$prefix || !$column) {
		return $options;
	}

	$prefix = sanitize_string($prefix);
	$column = sanitize_string($column);

	if (!in_array($pdirection, array('ASC', 'DESC'))) {
		$pdirection = 'DESC';
	}

	$pdirection = $direction = sanitize_string($pdirection);

	$dbprefix = elgg_get_config('dbprefix');
	switch ($prefix) {

		case 'e' :
			switch ($column) {

				case 'guid' :
				case 'type' :
				case 'subtype' :
				case 'owner_guid' :
				case 'site_guid' :
				case 'container_guid' :
				case 'access_id' :
				case 'time_created' :
				case 'time_updated' :
				case 'last_action' :
				case 'enabled' :

					$order_by = "e.$column $direction";
					break;
			}

			break;

		case 'oe' :
			switch ($column) {

				case 'title' :
				case 'description' :
					$options['joins'][] = "JOIN {$dbprefix}objects_entity oe_order_by ON oe_order_by.guid = e.guid";
					$order_by = "oe_order_by.$column $direction, e.time_created $direction";
					break;
			}

			break;

		case 'md' :

			switch ($column) {

				case 'priority' :
					$options['selects'][] = "CAST(eprioritymsv.string AS SIGNED) epriority";
					$options['joins'][] = "JOIN {$dbprefix}metadata eprioritymd ON e.guid = eprioritymd.entity_guid";
					$options['joins'][] = "JOIN {$dbprefix}metastrings eprioritymsn ON (eprioritymsn.string = 'priority')";
					$options['joins'][] = "LEFT JOIN {$dbprefix}metastrings eprioritymsv ON (eprioritymd.name_id = eprioritymsn.id AND eprioritymd.value_id = eprioritymsv.id)";
					$options['group_by'] = 'e.guid';
					$order_by = "ISNULL(epriority), epriority ASC, e.time_created DESC";

					break;

				case 'distance' :
					$user = elgg_get_logged_in_user_entity();
					if (!$user || !$latitude = $user->getLatitude() || !$longitude = $user->getLongitude()) {
						register_error(elgg_echo('hj:framework:nousergeocode'));
					} else {
						$options['joins'][] = "JOIN {$dbprefix}metadata md_geo_lat ON md_geo_lat.entity_guid = e.guid";
						$options['joins'][] = "JOIN {$dbprefix}metastrings msn_geo_lat ON msn_geo_lat.id = md_geo_lat.name_id";
						$options['joins'][] = "JOIN {$dbprefix}metastrings msv_geo_lat ON msv_geo_lat.id = md_geo_lat.value_id";
						$options['joins'][] = "JOIN {$dbprefix}metadata md_geo_long ON md_geo_long.entity_guid = e.guid";
						$options['joins'][] = "JOIN {$dbprefix}metastrings msn_geo_long ON msn_geo_long.id = md_geo_long.name_id";
						$options['joins'][] = "JOIN {$dbprefix}metastrings msv_geo_long ON msv_geo_long.id = md_geo_long.value_id";
						$options['wheres'][] = "(msn_geo_lat.string = 'geo:lat' AND msn_geo_long.string = 'geo:long')";
						$options['wheres'][] = "(msv_geo_lat.string NOT NULL AND msv_get_long.string NOT NULL)";
						$options['selects'][] = "(((acos(sin(($latitude*pi()/180)) * sin((msv_geo_lat.string*pi()/180))+cos(($latitude*pi()/180)) * cos((msv_geo_lat.string*pi()/180)) * cos((($longitude - msv_geo_long.string)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance";
						$order_by = "distance $direction";
					}
					break;

				default :
					$options['joins'][] = "JOIN {$dbprefix}metadata md_order_by ON md_order_by.entity_guid = e.guid";
					$options['joins'][] = "JOIN {$dbprefix}metastrings msn_order_by ON msn_order_by.string = '$column'";
					$options['joins'][] = "JOIN {$dbprefix}metastrings msv_order_by ON (msn_order_by.id = md_order_by.name_id AND msv_order_by.id = md_order_by.value_id)";
					$order_by = "ISNULL(msv_order_by.string), msv_order_by.string $direction, e.time_created DESC";
					break;
			}


			break;

		default :
			break;
	}

	if ($order_by) {
		if (isset($options['order_by'])) {
			$options['order_by'] = "{$options['order_by']}, $order_by";
		} else {
			$options['order_by'] = $order_by;
		}
	}

	$options = elgg_trigger_plugin_hook('order_by_clause', 'framework:lists', array('order_by' => $porder_by, 'direction' => $pdirection), $options);

	return $options;
}
