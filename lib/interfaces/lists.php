<?php

/**
 * View a styled list with filters
 *
 * @param str $list_id
 * @param array $list_options
 * @param array $getter_options
 * @param array $viewer_options
 * @param array $getter
 * @return type
 */
function hj_framework_view_list($list_id, $list_options = array(), $getter_options = array(), $viewer_options = array(), $getter = 'elgg_get_entities') {

	$list_type = get_input("list_type", array($list_id => elgg_extract('list_type', $list_options, 'list')));
	$order_by = get_input("order_by", array($list_id => elgg_extract('order_by', $list_options, 'e.time_created')));
	$direction = get_input("direction", array($list_id => elgg_extract('direction', $list_options, 'desc')));
	$query = get_input("query", array($list_id => null));
	$search_in = get_input("search_in", array($list_id => 'all'));
	$offset = get_input("offset", array($list_id => 0));
	$limit = get_input("limit", array($list_id => 10));
	$context = get_input("context", array($list_id => null));

//	if (elgg_is_xhr()) {
//		$list_options = get_input('list_options', $list_options);
//		$getter_options = get_input('getter_options', $getter_options);
//		$viewer_options = get_input('viewer_options', $viewer_options);
//		$getter = get_input('getter', $getter);
//	}

	$getter_options['offset'] = $offset["$list_id"];
	$getter_options['limit'] = $limit["$list_id"];

	$default_list_options = array(
		'list_type' => (isset($list_type["$list_id"])) ? $list_type["$list_id"] : 'list',
		'list_mode' => 'objects',
		'list_type_toggle' => false,
		'list_type_toggle_options' => false,
		'list_class' => null,
		'item_class' => null,
		'list_filter' => false,
		'list_filter_options' => false,
		'list_filter_callbacks' => false,
		'list_filter_view_override' => false,
		'list_pagination' => true,
		'list_pagination_type' => 'paginate', // or 'infinite'
		'list_pagination_position' => 'after',
		'list_pagination_offset' => $offset["$list_id"],
		'list_pagination_limit' => $limit["$list_id"],
		'list_pagination_base_url' => full_url()
	);

	$list_options = array_merge($default_list_options, $list_options);

	if ($list_options['list_filter'] && !$list_options['list_filter_options']) {
		switch ($list_options['list_mode']) {

			case 'objects' :
				$list_options['list_filter_options'] = array(
					'search_in_options' => array(
						'objects:all', 'objects:attributes', 'objects:tags'
					),
					'order_by_options' => array(
						'e.time_created', 'oe.title'
						));
				break;

			case 'users' :
				$list_options['list_filter_options'] = array(
					'search_in_options' => array(
						'users:all', 'users:attributes', 'users:tags'
					),
					'order_by_options' => array(
						'e.time_created', 'ue.last_login', 'ue.name', 'ue.username'
						));
				break;

			case 'groups' :
				$list_options['list_filter_options'] = array(
					'search_in_options' => array(
						'groups:all', 'groups:attributes', 'groups:tags'
					),
					'order_by_options' => array(
						'e.time_created', 'ge.name', 'md.featured_group'
						));
				break;

			default :
				$list_options['list_filter_options'] = array(
					'search_in_options' => array(
						'all:tags'
					),
					'order_by_options' => array(
						'e.time_created', 'oe.title'
						));
				break;
		}
	}

	// Get search options
	$pquery = $query["$list_id"];
	$psearch_in = $search_in["$list_id"];
	$getter_options = hj_framework_get_search_clause($pquery, $psearch_in, $getter_options);

	// Get ordering options
	$porder_by = $order_by["$list_id"];
	$pdirection = $direction["$list_id"];
	$getter_options = hj_framework_get_order_by_clause($porder_by, $pdirection, $getter_options);

	// Get owner guids
	$pcontext = $context["$list_id"];
	if ($pcontext) {
		$getter_options = hj_framework_get_owner_guids_clause($pcontext, $getter_options);
	}

	$callbacks = $list_options['list_filter_callbacks'];
	if (is_array($callbacks)) {
		foreach ($callbacks as $callback) {
			if (is_callable($callback)) {
				$getter_options = call_user_func_array($callback, $getter_options);
			}
		}
	}

	$getter_options['count'] = true;
	$count = $getter($getter_options);

	$getter_options['count'] = false;
	$entities = $getter($getter_options);

	$params = array(
		'list_id' => $list_id,
		'entities' => $entities,
		'count' => $count,
		'filter_values' => array(
			'order_by' => $order_by["$list_id"],
			'direction' => $direction["$list_id"],
			'query' => $query["$list_id"],
			'search_in' => $search_in["$list_id"],
			'list_type' => $list_type["$list_id"]
		),
		'list_options' => $list_options,
		'getter_options' => $getter_options,
		'viewer_options' => $viewer_options,
		'getter' => $getter
	);

	if (elgg_view_exists("page/components/lists/{$list_type["$list_id"]}")) {
		return elgg_view("page/components/lists/{$list_type["$list_id"]}", $params);
	} else {
		return elgg_view("page/components/lists/list", $params);
	}
}

function hj_framework_get_order_by_clause($order_by = 'e.time_created', $direction = 'desc', $options = array()) {

	$prefix = 'e';
	$column = 'time_created';

	list($prefix, $column) = explode('.', $order_by);

	$prefix = sanitize_string($prefix);
	$column = sanitize_string($column);
	$direction = sanitize_string($direction);

	if (!$prefix || !$column) {
		return $options;
	}

	$dbprefix = elgg_get_config('dbprefix');
	switch ($prefix) {

		case 'e' :
			$options['order_by'] = "e.$column $direction";
			break;

		case 'oe' :
			$options['joins'][] = "JOIN {$dbprefix}objects_entity oe_order_by ON oe_order_by.guid = e.guid";
			$options['order_by'] = "oe_order_by.$column $direction, e.time_created $direction";
			break;

		case 'md' :
			$options['joins'][] = "JOIN {$dbprefix}metadata md_order_by ON md_order_by.entity_guid = e.guid";
			$options['joins'][] = "JOIN {$dbprefix}metastrings msn_order_by ON msn_order_by.id = md_order_by.name_id";
			$options['joins'][] = "JOIN {$dbprefix}metastrings msv_order_by ON msv_order_by.id = md_order_by.value_id";
			$options['wheres'][] = "(msn_order_by.string = '$column')";
			$options['order_by'] = "msv_order_by.string $direction, e.time_created $direction";
			break;

		case 'ue' :
			$options['joins'][] = "JOIN {$dbprefix}users_entity ue_order_by ON ue_order_by.guid = e.guid";
			$options['order_by'] = "ue_order_by.$column $direction, e.time_created $direction";
			break;

		case 'ge' :
			$options['joins'][] = "JOIN {$dbprefix}groups_entity ge_order_by ON ge_order_by.guid = e.guid";
			$options['order_by'] = "ge_order_by.$column $direction, e.time_created $direction";
			break;

		case 'distance' :
			if (!$latitude = $user->getLatitude() || !$longitude = $user->getLongitude()) {
				register_error(elgg_echo('framework:nousergeocode'));
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
			break;
	}

	$options = elgg_trigger_plugin_hook('order_by_clause', 'framework:lists', array('order_by' => $order_by, 'direction' => $direction), $options);

	return $options;
}

function hj_framework_get_search_clause($query = null, $search_in = 'objects:all', $options = array()) {

	if (!$query || empty($query)) {
		return $options;
	}

	$query = sanitize_string($query);

	$dbprefix = elgg_get_config('dbprefix');

	list($mode, $search_area) = explode(':', $search_in);

	switch ($search_area) {

		case 'all' :
			switch ($mode) {
				case 'objects' :
					$join = "JOIN {$dbprefix}objects_entity oe_search ON e.guid = oe_search.guid";
					$options['joins'][] = $join;
					$fields = array('title', 'description');
					$where = search_get_where_sql('oe_search', $fields, array('query' => $query), FALSE);
					break;

				case 'users' :
					$join = "JOIN {$dbprefix}users_entity ue_search ON e.guid = ue_search.guid";
					$options['joins'][] = $join;
					$fields = array('name', 'username', 'email');
					$where = search_get_where_sql('ue_search', $fields, array('query' => $query), FALSE);
					break;

				case 'groups' :
					$join = "JOIN {$dbprefix}groups_entity ge_search ON e.guid = ge_search.guid";
					$options['joins'][] = $join;
					$fields = array('name', 'description');
					$where = search_get_where_sql('ge_search', $fields, array('query' => $query), FALSE);
					break;
				default :
					break;
			}

			$search_tag_names = elgg_get_registered_tag_metadata_names();

			$options['joins'][] = "JOIN {$dbprefix}metadata md_search on e.guid = md_search.entity_guid";
			$options['joins'][] = "JOIN {$dbprefix}metastrings msn_search on md_search.name_id = msn_search.id";
			$options['joins'][] = "JOIN {$dbprefix}metastrings msv_search on md_search.value_id = msv_search.id";

			$access = get_access_sql_suffix('md_search');
			$sanitised_tags = array();

			foreach ($search_tag_names as $tag) {
				$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
			}

			$tags_in = implode(',', $sanitised_tags);

			$options['wheres'][] = "$where OR (msn_search.string IN ($tags_in) AND msv_search.string LIKE '%$query%' AND $access)";

			break;

		case 'attributes' :

			switch ($mode) {
				case 'objects' :
					$join = "JOIN {$dbprefix}objects_entity oe_search ON e.guid = oe_search.guid";
					$options['joins'][] = $join;
					$fields = array('title', 'description');
					$where = search_get_where_sql('oe_search', $fields, array('query' => $query), FALSE);
					$options['wheres'][] = $where;
					break;

				case 'users' :
					$join = "JOIN {$dbprefix}users_entity ue_search ON e.guid = ue_search.guid";
					$options['joins'][] = $join;
					$fields = array('name', 'username', 'email');
					$where = search_get_where_sql('ue_search', $fields, array('query' => $query), FALSE);
					$options['wheres'][] = $where;
					break;

				case 'groups' :
					$join = "JOIN {$dbprefix}groups_entity ge_search ON e.guid = ge_search.guid";
					$options['joins'][] = $join;
					$fields = array('name', 'description');
					$where = search_get_where_sql('ge_search', $fields, array('query' => $query), FALSE);
					$options['wheres'][] = $where;
					break;

				default :
					break;
			}


		case 'tags' :
			$search_tag_names = elgg_get_registered_tag_metadata_names();

			$options['joins'][] = "JOIN {$dbprefix}metadata md_search on e.guid = md_search.entity_guid";
			$options['joins'][] = "JOIN {$dbprefix}metastrings msn_search on md_search.name_id = msn_search.id";
			$options['joins'][] = "JOIN {$dbprefix}metastrings msv_search on md_search.value_id = msv_search.id";
			$access = get_access_sql_suffix('md_search');
			$sanitised_tags = array();

			foreach ($search_tag_names as $tag) {
				$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
			}

			$tags_in = implode(',', $sanitised_tags);

			$options['wheres'][] = "(msn_search.string IN ($tags_in) AND msv_search.string LIKE '%$query%' AND $access)";
			break;

		case 'location' :
			$options['joins'][] = "JOIN {$dbprefix}metadata md_search on e.guid = md_search.entity_guid";
			$options['joins'][] = "JOIN {$dbprefix}metastrings msn_search on md_search.name_id = msn_search.id";
			$options['joins'][] = "JOIN {$dbprefix}metastrings msv_search on md_search.value_id = msv_search.id";
			$access = get_access_sql_suffix('md_search');
			$options['wheres'][] = "(msn_search.string IN ('location') AND msv_search.string LIKE '%$query%' AND $access)";
			break;

		default:
			break;
	}

	$options = elgg_trigger_plugin_hook('search_in_clause', 'framework:lists', array('query' => $query, 'search_in' => $search_in), $options);

	return $options;
}

function hj_framework_get_owner_guids_clause($context = null, $options = array()) {

	switch ($context) {

		case 'all' :
		default :
			break;

		case 'mine' :
			$options['owner_guids'] = array(elgg_get_logged_in_user_guid());
			break;

		case 'friends' :
			$user = elgg_get_logged_in_user_entity();
			$friends = $user->getFriends('', 0, 0);
			$options['owner_guids'] = array();
			foreach ($friends as $friend) {
				$options['owner_guids'][] = $friend->guid;
			}
	}

	return $options;
}

/**
 * Manage Filter Form
 */
elgg_register_plugin_hook_handler('init', 'form:list_filter', 'hj_framework_list_filter_form');

function hj_framework_list_filter_form($hook, $type, $return, $params) {

	$form = elgg_extract('form', $params);

	$list_id = elgg_extract('list_id', $params);
	$list_options = elgg_extract('list_options', $params);
	$filter_values = elgg_extract('filter_values', $params);

	$form->registerFieldset('search', array(
		'priority' => 100,
		'legend' => elgg_echo('framework:filter:fieldset:search')
	));

	$form->registerFieldset('sorting', array(
		'prirority' => 200,
		'legend' => elgg_echo('framework:filter:fieldset:sorting')
	));

	$list_filter_options = elgg_extract('list_filter_options', $list_options);
	$search_in_options = elgg_extract('search_in_options', $list_filter_options, false);
	$order_by_options = elgg_extract('order_by_options', $list_filter_options, false);
	$context_options = elgg_extract('context_options', $list_filter_options, false);

	if ($search_in_options) {
		$options_values = array();
		foreach ($search_in_options as $search_in_option) {
			$options_values[$search_in_option] = elgg_echo("framework:filter:search_in:$search_in_option");
		}

		$form->registerInput("query[$list_id]", 'text', false, array(
			'value' => $filter_values['query'],
			'label' => array('text' => elgg_echo('framework:filter:query')),
			'data-tooltip' => elgg_echo('framework:filter:query:tooltip'),
			'priority' => 100,
			'fieldset' => 'search'
		));
		$form->registerInput("search_in[$list_id]", 'dropdown', false, array(
			'value' => $filter_values['search_in'],
			'options_values' => $options_values,
			'label' => array('text' => elgg_echo('framework:filter:search_in')),
			'priority' => 200,
			'fieldset' => 'search'
		));
	}

	if ($order_by_options) {
		$options_values = array();
		foreach ($order_by_options as $order_by_option) {
			$options_values[$order_by_option] = elgg_echo("framework:filter:order_by:$order_by_option");
		}

		$form->registerInput("order_by[$list_id]", 'dropdown', false, array(
			'value' => $filter_values['order_by'],
			'options_values' => $options_values,
			'label' => array('text' => elgg_echo('framework:filter:order_by')),
			'priority' => 100,
			'fieldset' => 'sorting'
		));
	}

	$form->registerInput("direction[$list_id]", 'dropdown', false, array(
		'value' => $filter_values['direction'],
		'options_values' => array(
			'desc' => elgg_echo('framework:filter:direction:asc'),
			'asc' => elgg_echo('framework:filter:direction:desc')
		),
		'label' => array('text' => elgg_echo('framework:filter:direction')),
		'priority' => 200,
		'fieldset' => 'sorting'
	));

	$form->registerInput("limit[$list_id]", 'dropdown', false, array(
		'value' => $filter_values['limit'],
		'options' => array(10, 25, 50, 100),
		'label' => array('text' => elgg_echo('framework:filter:limit')),
		'priority' => 300,
		'fieldset' => 'sorting'
	));

	if ($context_options) {
		$form->registerFieldset('context', array(
			'prirority' => 300,
			'legend' => elgg_echo('framework:filter:fieldset:context')
		));
		$form->registerInput("context[$list_id]", 'dropdown', false, array(
			'value' => $filter_values['context'],
			'label' => array('text' => elgg_echo('framework:filter:context')),
			'options_values' => array(
				'all' => elgg_echo('all'),
				'friends' => elgg_echo('friends'),
				'mine' => elgg_echo('mine')
			),
			'priority' => 100,
			'fieldset' => 'context'
		));
	}

	$url = parse_url(full_url());
	$form->registerInput('reset', 'custom', false, array(
		'tag' => 'a',
		'attr' => array(
			'href' => $url['path'],
			'class' => 'framework-ui-control gray',
		),
		'text' => '<span class="label">' . elgg_echo('reset') . '</span>',
		'label' => false,
		'fieldset' => 'footer'
	));

	$form->registerInput('submit', 'submit', false, array(
		'value' => elgg_echo('filter'),
		'class' => 'framework-ui-control blue',
		//'fieldset' => 'footer',
		'label' => false,
		'fieldset' => 'footer'
	));
}