<?php

function hj_framework_view_icon($icon) {
	return "<span class=\"framework-ui-icon framework-ui-icon-$icon\"></span>";
}
/**
 * Various Menu Hook Handlers
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category AJAX
 * @category Menu
 * @category Object
 *
 */

/**
 * Hook handler for menu:hjentityhead
 * Header menu of an entity
 * By default, contains Edit and Delete buttons
 *      Edit button loads ajax and replaces entity content in <div id="elgg-object-{$guid}">
 *      Delete button sends an ajax request and on success removes <div id="elgg_object_{$guid}">
 *
 *
 * @param string $hook
 * @param string $type
 * @param array $return
 * @param array $params
 * @return array
 *
 */
function hj_framework_entity_head_menu($hook, $type, $return, $params) {

//	if (elgg_in_context('print') || elgg_in_context('activity') || !elgg_is_logged_in()) {
//		return $return;
//	}
	// Extract available parameters
	$entity = elgg_extract('entity', $params);
	$handler = elgg_extract('handler', $params);

	//$handler = elgg_extract('handler', $params);

	$current_view = elgg_extract('current_view', $params);

	if (!$current_view) {
		$params['short_view'] = true;
	}
	$params = hj_framework_extract_params_from_params($params);
	$data = hj_framework_json_query($params);

	if (!$current_view || (elgg_is_xhr() && !elgg_in_context('fancybox'))) {
		if (!isset($params['has_full_view']) || $params['has_full_view'] === true) {
			$params['params']['full_view'] = true;
			$params['params']['push_context'] = 'fancybox';
			$data = hj_framework_json_query($params);
			$fullview = array(
				'name' => 'fullview',
				'title' => elgg_echo('hj:framework:gallerytitle', array($entity->title)),
				'text' => elgg_echo('hj:framework:gallerytitle', array($entity->title)),
				'href' => "action/framework/entities/view?e=$entity->guid",
				'data-options' => htmlentities($data, ENT_QUOTES, 'UTF-8'),
				'rel' => 'fancybox',
				'class' => 'hj-ajaxed-view',
				'priority' => 300,
				'section' => 'dropdown'
			);
			$return[] = ElggMenuItem::factory($fullview);
		}
	}

	if ($handler == 'hjfile') {
		$file_guid = elgg_extract('file_guid', $params);

		if (hj_framework_allow_file_download($file_guid)) {
			$download = array(
				'name' => 'download',
				'title' => elgg_echo('hj:framework:download'),
				'text' => elgg_echo('hj:framework:download'),
				'id' => "hj-ajaxed-download-{$file_guid}",
				'href' => "framework/file/download/{$file_guid}/",
				'target' => '_blank',
				'priority' => 500,
				'section' => 'dropdown'
			);
			$return[] = ElggMenuItem::factory($download);
		}
	}

	if ($entity && elgg_instanceof($entity) && $entity->canEdit()) {
		$edit = array(
			'name' => 'edit',
			'title' => elgg_echo('hj:framework:edit'),
			'text' => elgg_echo('hj:framework:edit'),
			'rel' => 'fancybox',
			'href' => "framework/edit/$entity->guid",
			'data-options' => htmlentities($data, ENT_QUOTES, 'UTF-8'),
			'class' => "hj-ajaxed-edit",
			'priority' => 800,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($edit);

		// AJAXed Delete Button
		$delete = array(
			'name' => 'delete',
			'title' => elgg_echo('hj:framework:delete'),
			'text' => elgg_echo('hj:framework:delete'),
			'href' => "action/framework/entities/delete?e=$entity->guid",
			'data-options' => htmlentities($data, ENT_QUOTES, 'UTF-8'),
			'class' => 'hj-ajaxed-remove',
			'id' => "hj-ajaxed-remove-{$entity->guid}",
			'priority' => 900,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($delete);
	}

	// access
	$access = elgg_view('output/access', array('entity' => $entity));
	$options = array(
		'name' => 'access',
		'text' => $access,
		'href' => false,
		'priority' => 100,
	);
	$return[] = ElggMenuItem::factory($options);

	return $return;
}

/**
 * Hook handler for menu:hjentityfoot
 * Footer menu of an entity
 * By default, contains a full_view of an element in a hidden div
 * @return array
 */
function hj_framework_entity_foot_menu($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);
	$handler = elgg_extract('handler', $params);

	if (elgg_in_context('print') || elgg_in_context('activity')) {
		return $return;
	}

	return $return;
}

/**
 * Hook handler for menu:hjsegmenthead
 * Contains a sectional menu
 * By default, contains Add and Refresh
 *      Add button - loads a form to add a new element
 *      Refresh button - reloads section content
 *
 */
function hj_framework_segment_head_menu($hook, $type, $return, $params) {

	// Extract available parameters
	$entity = elgg_extract('entity', $params);

	$container_guid = elgg_extract('container_guid', $params['params']);
	$container = get_entity($container_guid);

	$section = elgg_extract('subtype', $params['params']);
	$handler = elgg_extract('handler', $params['params']);

	$data = hj_framework_json_query($params);
	$url = hj_framework_http_build_query($params);

	if (elgg_instanceof($entity, 'object', 'hjsegment') && elgg_instanceof($container) && $container->canEdit()) {

		// Add widget
		$widget = array(
			'name' => 'widget',
			'title' => elgg_echo('hj:framework:addwidget'),
			'text' => elgg_echo('hj:framework:addwidget'),
			'href' => "action/framework/widget/add",
			'data-options' => $data,
			'id' => "hj-ajaxed-addwidget-{$entity->guid}",
			'class' => "hj-ajaxed-addwidget",
			'target' => "elgg-object-{$entity->guid}",
			'priority' => 100,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($widget);

		// AJAXed Edit Button
		$edit = array(
			'name' => 'edit',
			'title' => elgg_echo('hj:framework:edit'),
			'text' => elgg_echo('hj:framework:edit'),
			'href' => "action/framework/entities/edit",
			'data-options' => $data,
			'id' => "hj-ajaxed-edit-{$entity->guid}",
			'class' => "hj-ajaxed-edit",
			'target' => "elgg-object-{$entity->guid}",
			'priority' => 800,
			'section' => 'dropdown'
				
		);
		$return[] = ElggMenuItem::factory($edit);

		// AJAXed Delete Button
		$delete = array(
			'name' => 'delete',
			'title' => elgg_echo('hj:framework:delete'),
			'text' => elgg_echo('hj:framework:delete'),
			'href' => "action/framework/entities/delete?e=$entity->guid",
			'data-options' => $data,
			'id' => "hj-ajaxed-remove-{$entity->guid}",
			'class' => 'hj-ajaxed-remove',
			'priority' => 900,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($delete);
	}

	$print = array(
		'name' => 'print',
		'title' => elgg_echo('hj:framework:print'),
		'text' => elgg_echo('hj:framework:print'),
		'href' => "framework/print?{$url}",
		'target' => "_blank",
		'priority' => 200,
		'section' => 'dropdown'
	);
	$return[] = ElggMenuItem::factory($print);

	if (file_exists(elgg_get_plugins_path() . 'hypeFramework/lib/dompdf/dompdf_config.inc.php')) {
		$pdf = array(
			'name' => 'pdf',
			'title' => elgg_echo('hj:framework:pdf'),
			'text' => elgg_echo('hj:framework:pdf'),
			'href' => "framework/pdf?{$url}",
			//'is_action' => false,
			'target' => "_blank",
			'priority' => 300,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($pdf);
	}
//        $email_form = hj_framework_get_data_pattern('object', 'hjemail');
//        $email_f = $email_form->guid;
//
//        $email = array(
//            'name' => 'email',
//            'title' => elgg_echo('hj:framework:email'),
//            'text' => elgg_view_icon('hj hj-icon-email'),
//            'href' => "action/framework/entities/edit?f={$email_f}&s={$entity->guid}",
//            //'is_action' => true,
//            'rel' => 'fancybox',
//            'id' => "hj-ajaxed-email-{$entity->guid}",
//            'class' => "hj-ajaxed-edit",
//            'target' => "#elgg-object-{$entity->guid}",
//            'priority' => 300
//        );
//	$return[] = ElggMenuItem::factory($email);

	return $return;
}

/**
 * Hook handler for menu:hjsectionfoot
 * Contains a sectional menu
 * By default, contains Add and Refresh
 *      Add button - loads a form to add a new element
 *      Refresh button - reloads section content
 *
 * @param string $hook
 * @param string $type
 * @param array $return
 * @param array $params
 * @return array
 *
 * - $c - container entity
 * - $o - owner entity
 * - $f - form entity
 * - $context - context
 * - $sn - section name
 *
 */
function hj_framework_section_foot_menu($hook, $type, $return, $params) {

	$container_guid = elgg_extract('container_guid', $params['params']);
	$container = get_entity($container_guid);

	$widget_guid = elgg_extract('widget_guid', $params['params']);
	$widget = get_entity($widget_guid);

	$segment_guid = elgg_extract('segment_guid', $params['params']);
	$segment = get_entity($segment_guid);

	$section = elgg_extract('subtype', $params['params']);

	$data = hj_framework_json_query($params);

	if (elgg_instanceof($container) && $container->canEdit()) {
		// AJAXed Add Button
		$add = array(
			'name' => 'add',
			'title' => elgg_echo('hj:framework:addnew'),
			'text' => elgg_view_icon('hj hj-icon-add') . '<span class="hj-icon-text">' . elgg_echo('hj:framework:addnew') . '</span>',
			'href' => "framework/add/$container_guid/object/$section/default/$widget_guid/$segment_guid",
			'data-options' => $data,
			'is_action' => true,
			'rel' => 'fancybox',
			'class' => "hj-ajaxed-add",
			'priority' => 200
		);
		$return[] = ElggMenuItem::factory($add);

		}

	return $return;
}