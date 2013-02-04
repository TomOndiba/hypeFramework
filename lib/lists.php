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
	if (!isset($getter_options['order_by'])) {
		$porder_by = get_input($list_options['order_by_key'], 'e.time_created');
	}
	if (!isset($getter_options['direction'])) {
		$pdirection = get_input($list_options['direction_key'], 10);
	}

	$getter_options = hj_framework_get_order_by_clause($porder_by, $pdirection, $getter_options);

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
		return elgg_view("page/components/grids/{$list_options['list_type']}", $params);
	} else {
		return elgg_view("page/components/grids/list", $params);
	}
}

/**
 * Uniform function to handle lists
 *
 * @return type
 */
//function hj_framework_view_list($list_id, $getter_options = array(), $list_options = array(), $viewer_options = array(), $getter = 'elgg_get_entities') {
//
//	$list_type = get_input("list_type", array($list_id => elgg_extract('list_type', $list_options, 'list')));
//	$order_by = get_input("order_by", array($list_id => elgg_extract('order_by', $list_options, 'e.time_created')));
//	$direction = get_input("direction", array($list_id => elgg_extract('direction', $list_options, 'desc')));
//	$query = get_input("query", array($list_id => null));
//	$search_in = get_input("search_in", array($list_id => 'all'));
//	$offset = get_input("offset", array($list_id => 0));
//	$limit = get_input("limit", array($list_id => 10));
//	$context = get_input("context", array($list_id => null));
//
//	$getter_options['offset'] = $offset["$list_id"];
//	$getter_options['limit'] = $limit["$list_id"];
//
//	$default_list_options = array(
//		'list_type' => 'list',
//		'list_class' => null,
//		'item_class' => null,
//		'list_filter' => false,
//		'list_pagination' => true,
//		'list_pagination_type' => 'paginate', // or 'infinite'
//		'list_pagination_position' => 'after',
//		'list_pagination_offset' => $offset["$list_id"],
//		'list_pagination_limit' => $limit["$list_id"],
//		'list_pagination_base_url' => full_url()
//	);
//
//	$list_options = array_merge($default_list_options, $list_options);
//
//	if ($list_options['list_filter'] && !$list_options['list_filter_options']) {
//		switch ($list_options['list_mode']) {
//
//			case 'objects' :
//				$list_options['list_filter_options'] = array(
//					'search_in_options' => array(
//						'objects:all', 'objects:attributes', 'objects:tags'
//					),
//					'order_by_options' => array(
//						'e.time_created', 'oe.title'
//						));
//				break;
//
//			case 'users' :
//				$list_options['list_filter_options'] = array(
//					'search_in_options' => array(
//						'users:all', 'users:attributes', 'users:tags'
//					),
//					'order_by_options' => array(
//						'e.time_created', 'ue.last_login', 'ue.name', 'ue.username'
//						));
//				break;
//
//			case 'groups' :
//				$list_options['list_filter_options'] = array(
//					'search_in_options' => array(
//						'groups:all', 'groups:attributes', 'groups:tags'
//					),
//					'order_by_options' => array(
//						'e.time_created', 'ge.name', 'md.featured_group'
//						));
//				break;
//
//			default :
//			case 'mixed' :
//				$list_options['list_filter_options'] = array(
//					'search_in_options' => array(
//						'all:tags'
//					),
//					'order_by_options' => array(
//						'e.time_created', 'oe.title'
//						));
//				break;
//		}
//	}
//
//	// Get search options
//	$pquery = $query["$list_id"];
//	$psearch_in = $search_in["$list_id"];
//	$getter_options = hj_framework_get_search_clause($pquery, $psearch_in, $getter_options);
//
//	// Get ordering options
//	$porder_by = $order_by["$list_id"];
//	$pdirection = $direction["$list_id"];
//	$getter_options = hj_framework_get_order_by_clause($porder_by, $pdirection, $getter_options);
//
//	// Get owner guids
//	$pcontext = $context["$list_id"];
//	if ($pcontext) {
//		$getter_options = hj_framework_get_owner_guids_clause($pcontext, $getter_options);
//	}
//
//	$callbacks = $list_options['list_filter_callbacks'];
//	if (is_array($callbacks)) {
//		foreach ($callbacks as $callback) {
//			if (is_callable($callback)) {
//				$getter_options = call_user_func_array($callback, $getter_options);
//			}
//		}
//	}
//
//	$getter_options['count'] = true;
//	$count = $getter($getter_options);
//
//	$getter_options['count'] = false;
//	$entities = $getter($getter_options);
//
//	$params = array(
//		'list_id' => $list_id,
//		'entities' => $entities,
//		'count' => $count,
//		'filter_values' => array(
//			'order_by' => $order_by["$list_id"],
//			'direction' => $direction["$list_id"],
//			'query' => $query["$list_id"],
//			'search_in' => $search_in["$list_id"],
//			'list_type' => $list_type["$list_id"]
//		),
//		'list_options' => $list_options,
//		'getter_options' => $getter_options,
//		'viewer_options' => $viewer_options,
//		'getter' => $getter
//	);
//
//	if (elgg_view_exists("page/components/grids/{$list_type["$list_id"]}")) {
//		return elgg_view("page/components/grids/{$list_type["$list_id"]}", $params);
//	} else {
//		return elgg_view("page/components/grids/list", $params);
//	}
//}

function hj_framework_get_order_by_clause($order_by = 'e.time_created', $direction = 'DESC', $options = array()) {

	$prefix = 'e';
	$column = 'time_created';

	list($prefix, $column) = explode('.', $order_by);

	$prefix = sanitize_string($prefix);
	$column = sanitize_string($column);
	if (!in_array($direction, array('ASC', 'DESC'))) {
		$direction = 'DESC';
	}
	$direction = sanitize_string($direction);

	if (!$prefix || !$column) {
		return $options;
	}

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
					$options['order_by'] = "e.$column $direction";
					break;
			}

			break;

		case 'oe' :
			switch ($column) {

				case 'guid' :
				case 'title' :
				case 'description' :
					$options['joins'][] = "JOIN {$dbprefix}objects_entity oe_order_by ON oe_order_by.guid = e.guid";
					$options['order_by'] = "oe_order_by.$column $direction, e.time_created $direction";
					break;
			}

			break;

		case 'md' :

			switch ($column) {

				case 'priority' :
					$options['selects'][] = "CAST(eprioritymsv.string AS SIGNED) AS epriority";
					$options['joins'][] = "JOIN {$dbprefix}metadata eprioritymd ON e.guid = eprioritymd.entity_guid";
					$options['joins'][] = "LEFT JOIN {$dbprefix}metastrings eprioritymsn ON (eprioritymd.name_id = eprioritymsn.id AND eprioritymsn.string = 'priority')";
					$options['joins'][] = "LEFT JOIN {$dbprefix}metastrings eprioritymsv ON (eprioritymd.value_id = eprioritymsv.id)";
					$options['order_by'] = "epriority = 0, epriority";

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
						$options['order_by'] = "distance $direction";
					}
					break;

				default :
					$options['joins'][] = "JOIN {$dbprefix}metadata md_order_by ON md_order_by.entity_guid = e.guid";
					$options['joins'][] = "JOIN {$dbprefix}metastrings msn_order_by ON msn_order_by.id = md_order_by.name_id";
					$options['joins'][] = "JOIN {$dbprefix}metastrings msv_order_by ON msv_order_by.id = md_order_by.value_id";
					$options['wheres'][] = "(msn_order_by.string = '$column')";
					$options['order_by'] = "msv_order_by.string $direction, e.time_created $direction";
					break;
			}


			break;

		default :
			break;
	}

	$options = elgg_trigger_plugin_hook('order_by_clause', 'framework:lists', array('order_by' => $order_by, 'direction' => $direction), $options);

	return $options;
}
