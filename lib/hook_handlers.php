<?php

// Manage Menus
elgg_register_plugin_hook_handler('register', 'all', 'hj_framework_menu_manager');

if (elgg_get_plugin_setting('cleditor', hypeFramework) == 'on') {
	if (elgg_is_active_plugin('tinymce'))
		elgg_unregister_plugin_hook_handler('register', 'menu:longtext', 'tinymce_longtext_menu');
	if (elgg_is_active_plugin('embed'))
		elgg_unregister_plugin_hook_handler('register', 'menu:longtext', 'embed_longtext_menu');
	elgg_unregister_js('elgg.tinymce');
}

// Manage Entity Icons
elgg_register_plugin_hook_handler('entity:icon:url', 'all', 'hj_framework_entity_icons');

// Add Widgets to hjSegments
elgg_register_plugin_hook_handler('framework:form:process', 'all', 'hj_framework_setup_segment_widgets');

// Check permissions for container entities
elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'hj_framework_container_permissions_check');
elgg_register_plugin_hook_handler('permissions_check:annotate', 'object', 'hj_framework_canannotate_check');

// Process Input Types
elgg_register_plugin_hook_handler('framework:field:process', 'all', 'hj_framework_process_inputs');


// Manage output on AJAX Calls
elgg_register_plugin_hook_handler('output', 'page', 'hj_framework_ajax_pageshell');
elgg_register_plugin_hook_handler('output:after', 'layout', 'hj_framework_ajax_pagelayout');
elgg_unregister_plugin_hook_handler('forward', 'all', 'ajax_forward_hook');
elgg_register_plugin_hook_handler('forward', 'all', 'hj_framework_ajax_forward_hook');

// Entity forms
elgg_register_plugin_hook_handler('forms:config', 'framework:forms', 'hj_framework_setup_forms');

/**
 * Generic Menu Handler
 *
 * @param str $hook 'register'
 * @param str $type 'all'
 * @param mixed $return An array of menu items
 * @param mixed $params Options array
 * @return mixed An array of menu items
 */
function hj_framework_menu_manager($hook, $type, $return, $params) {

	list($hook_type, $prepared_menu_name) = explode(':', $type);

	if ($hook_type != 'menu' || empty($prepared_menu_name)) {
		return $return;
	}

	switch ($type) {

		default :
			return $return;
			break;

		case 'menu:hjentityhead' :

			$return[] = ElggMenuItem::factory(array(
						'name' => 'options',
						'text' => '',
						'icon' => 'icon140',
						'toggle_icon' => 'hidden',
						'section' => 'dropdown',
						'href' => false,
						'priority' => 900
					));

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

			if ($handler == 'hjfile') {
				$file_guid = elgg_extract('file_guid', $params);

				if (hj_framework_allow_file_download($file_guid)) {
					$download = array(
						'parent_name' => 'options',
						'name' => 'download',
						//'title' => elgg_echo('framework:download'),
						'text' => elgg_echo('framework:download'),
						'id' => "hj-ajaxed-download-{$file_guid}",
						'href' => HYPEFRAMEWORK_PAGEHANDLER . "/file/download/{$file_guid}/",
						'target' => '_blank',
						'priority' => 500,
						'section' => 'dropdown'
					);
					$return[] = ElggMenuItem::factory($download);
				}
			}

			if ($entity && elgg_instanceof($entity) && $entity->canEdit()) {
				$edit = array(
					'parent_name' => 'options',
					'name' => 'edit',
					'title' => elgg_echo('framework:edit'),
					'icon' => 'edit',
					'text' => elgg_echo('framework:edit'),
					'rel' => 'fancybox',
					'href' => "action/framework/entities/edit",
					'data-options' => htmlentities($data, ENT_QUOTES, 'UTF-8'),
					'class' => "hj-ajaxed-edit framework-ui-control",
					'priority' => 800,
					'section' => 'dropdown'
				);
				$return[] = ElggMenuItem::factory($edit);

				// AJAXed Delete Button
				$delete = array(
					'parent_name' => 'options',
					'name' => 'delete',
					//'title' => elgg_echo('framework:delete'),
					'text' => elgg_echo('framework:delete'),
					'href' => "action/framework/entities/delete?e=$entity->guid",
					'data-options' => htmlentities($data, ENT_QUOTES, 'UTF-8'),
					'class' => 'hj-ajaxed-remove framework-ui-control',
					'icon' => 'delete',
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
				'title' => $access,
				'href' => false,
				'item_class' => 'framework-ui-control elgg-menu-inactive',
				'section' => 'dropdown',
				'priority' => 100,
			);
			$return[] = ElggMenuItem::factory($options);

			return $return;

			break;

		case 'menu:hjentityfoot' :
			$entity = elgg_extract('entity', $params);
			$handler = elgg_extract('handler', $params);

			if (elgg_in_context('print') || elgg_in_context('activity')) {
				return $return;
			}

			return $return;
			break;

		case 'menu:hjsegmenthead' :

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
					'title' => elgg_echo('framework:addwidget'),
					'text' => elgg_echo('framework:addwidget'),
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
					'title' => elgg_echo('framework:edit'),
					'text' => elgg_echo('framework:edit'),
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
					'title' => elgg_echo('framework:delete'),
					'text' => elgg_echo('framework:delete'),
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
				'title' => elgg_echo('framework:print'),
				'text' => elgg_echo('framework:print'),
				'href' => HYPEFRAMEWORK_PAGEHANDLER . "/print?{$url}",
				'target' => "_blank",
				'priority' => 200,
				'section' => 'dropdown'
			);
			$return[] = ElggMenuItem::factory($print);

			if (file_exists(HYPEFRAMEWORK_PATH . 'vendors/dompdf/dompdf_config.inc.php')) {
				$pdf = array(
					'name' => 'pdf',
					'title' => elgg_echo('framework:pdf'),
					'text' => elgg_echo('framework:pdf'),
					'href' => HYPEFRAMEWORK_PAGEHANDLER . "/pdf?{$url}",
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
//            'title' => elgg_echo('framework:email'),
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
			break;

		case 'menu:hjsectionfoot' :
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
					'title' => elgg_echo('framework:addnew'),
					'text' => elgg_view_icon('hj hj-icon-add') . '<span class="hj-icon-text">' . elgg_echo('framework:addnew') . '</span>',
					'href' => "action/framework/entities/edit",
					'data-options' => $data,
					'is_action' => true,
					'rel' => 'fancybox',
					'class' => "hj-ajaxed-add",
					'priority' => 200
				);
				$return[] = ElggMenuItem::factory($add);

//        // AJAXed Refresh Button
//        $refresh = array(
//            'name' => 'refresh',
//            'title' => elgg_echo('framework:refresh'),
//            'text' => elgg_view_icon('hj hj-icon-refresh') . '<span class="hj-icon-text">' . elgg_echo('framework:refresh') . '</span>',
//            'href' => "action/framework/entities/view?e=$entity->guid",
//            'data-options' => $data,
//            'is_action' => true,
//            'id' => "hj-ajaxed-refresh-{$entity->guid}",
//            'class' => "hj-ajaxed-refresh",
//            'target' => "#elgg-widget-{$widget->guid} #hj-section-{$section}",
//            'priority' => 300
//        );
//        $return[] = ElggMenuItem::factory($refresh);
			}

			return $return;
			break;

		case 'menu:list_filter' :

			$list_id = elgg_extract('list_id', $params);
			$list_options = elgg_extract('list_options', $params);

			// List Options
			$list_type_toggle = elgg_extract('list_type_toggle', $list_options);

			if ($list_type_toggle) {
				$list_type_toggle_options = elgg_extract('list_type_toggle_options', $list_options);
				if (is_array($list_type_toggle_options)) {
					$size = sizeof($list_type_toggle_options);
					$i = 0;
					foreach ($list_type_toggle_options as $toggler) {
						$i++;
						if ($i == 1 && $size > 1) {
							$group_class = 'left';
						} elseif ($i == $size && $size > 1) {
							$group_class = 'right';
						} else if ($size > 1) {
							$group_class = 'middle';
						}

						$return[] = ElggMenuItem::factory(array(
									'name' => "toggle_list_type:$toggler",
									'icon' => $toggler,
									'text' => '',
									'title' => elgg_echo("list_type:$toggler:tooltip"),
									'href' => hj_framework_http_add_url_query_elements(full_url(), array(
										"list_type[$list_id]" => $toggler
									)),
									'data-list' => $list_id,
									'class' => "framework-ui-control toggle-list-type $group_class",
									'section' => 'toggle_list_type',
									'selected' => ($toggler == $filter_values['list_type']),
									'priority' => $i * 100
								));
					}
				}
			}

			$return[] = ElggMenuItem::factory(array(
						'name' => 'filter',
						'section' => 'filter',
						'text' => elgg_echo('framework:toggle_filter'),
						'icon' => 'search',
						'href' => "#filter-$list_id",
						'class' => 'framework-ui-control',
						'data-list' => $list_id,
						'rel' => 'toggle'
					));

			return $return;
			break;
	}
}

/**
 * Generic entity icon handler
 *
 * @param str $hook 'entity:icon:url'
 * @param str $type 'all'
 * @param str $return Default url
 * @param mixed $params
 * @return str URL of the Icon
 */
function hj_framework_entity_icons($hook, $type, $return, $params) {
	$entity = $params['entity'];
	$size = $params['size'];

	if (!elgg_instanceof($entity)) {
		return $return;
	}

	switch ($entity->getSubtype()) {
		case 'hjfile' :
			if ($entity->simpletype == 'image') {
				return HYPEFRAMEWORK_PATH . "pages/file/icon.php?guid={$entity->guid}&size={$size}";
			}

			$mapping = hj_framework_mime_mapping();

			$mime = $entity->mimetype;
			if ($mime) {
				$base_type = substr($mime, 0, strpos($mime, '/'));
			} else {
				$mime = 'none';
				$base_type = 'none';
			}

			if (isset($mapping[$mime])) {
				$type = $mapping[$mime];
			} elseif (isset($mapping[$base_type])) {
				$type = $mapping[$base_type];
			} else {
				$type = 'general';
			}

			$url = HYPEFRAMEWORK_PATH_GRAPHICS . "mime/{$size}/{$type}.png";
			return $url;

			break;

		case 'hjfilefolder' :

			$type = $folder->datatype;
			if (!$type)
				$type = "default";

			$url = "mod/" . hypeFramework . "/graphics/folder/{$size}/{$type}.png";
			return $url;

			break;

		default :
			if ($entity->hjicontime) {
				return elgg_get_config('url') . "framework/icon/$entity->guid/$size/$entity->hjicontime.jpg";
			}
			break;
	}

	return $return;
}

/**
 * Add default widgets to an hjSegment on creation
 */
function hj_framework_setup_segment_widgets($hook, $type, $return, $params) {

	$entity_guid = elgg_extract('guid', $params, 0);
	$entity = get_entity($entity_guid);
	$context = elgg_extract('context', $params, 'framework');
	$event = elgg_extract('event', $params, 'update');

	if ($entity->getSubtype() == 'hjsegment' && $event == 'create') {
		$sections = elgg_trigger_plugin_hook('framework:widget:types', 'all', array('context' => $context), array());

		if (is_array($sections)) {
			foreach ($sections as $section => $name) {
				$entity->addWidget($section, null, $context);
			}
		}
	}

	return $return;
}

/**
 * Alter container write permissions
 */
function hj_framework_container_permissions_check($hook, $type, $return, $params) {
	$container = elgg_extract('container', $params, false);
	$subtype = elgg_extract('subtype', $params, false);

	if ($subtype == 'hjforumsubmission') {
		return true;
	}
	if (elgg_instanceof($container, 'object', 'hjsegment') || $subtype == 'hjsegment') {
		return true;
	}
	if (elgg_instanceof($container, 'user') && $subtype == 'widget') {
		return true;
	}
	if (elgg_instanceof($container, 'object', 'hjannotation')) {
		return true;
	}
	if (elgg_instanceof($container) && $subtype == 'hjannotation') {
		return $container->canAnnotate();
	}

	if (elgg_instanceof($container, 'object', 'hjfilefolder')) {
		return true;
	}
	$container = get_entity($container->container_guid);
	if (elgg_instanceof($container, 'group') && $subtype == 'hjannotation') {
		return $container->canWriteToContainer();
	}

	return $return;
}

/**
 * Alter permissions for hjAnnotations
 */
function hj_framework_canannotate_check($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params, false);

	if (elgg_instanceof($entity, 'object', 'hjannotation')) {
		$container = $entity->findOriginalContainer();
		if ($container->getType() == 'river') {
			return true;
		}
		if (elgg_instanceof($container_top, 'group')) {
			return $container_top->canWriteToContainer();
		} else {
			return $container->canAnnotate();
		}
	}
	return $return;
}

/**
 * Change processing logic for input types
 */
function hj_framework_process_inputs($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params, false);
	$field = elgg_extract('field', $params, false);
	if (!$entity || !$field || !elgg_instanceof($entity)) {
		return true;
	}
	$type = $entity->getType();
	$subtype = $entity->getSubtype();

	switch ($field->input_type) {

		case 'file' :
			if (elgg_is_logged_in()) {
				global $_FILES;
				$field_name = $field->name;
				$file = $_FILES[$field_name];

				// Maybe someone doesn't want us to save the file in this particular way
				if (!empty($file['name']) && !elgg_trigger_plugin_hook('framework:form:fileupload', 'all', array('entity' => $entity, 'file' => $file, 'field_name' => $field_name), false)) {
					hj_framework_process_file_upload($file, $entity, $field_name);
				}
			}
			break;

		case 'entity_icon' :
			$field_name = $field->name;

			global $_FILES;
			if ((isset($_FILES[$field_name])) && (substr_count($_FILES[$field_name]['type'], 'image/'))) {
				hj_framework_generate_entity_icons($entity, $field_name);
				$entity->$field_name = null;
			}
			break;

		case 'relationship_tags' :
			$field_name = $field->name;
			$tags = get_input('relationship_tag_guids');
			$relationship_name = get_input('relationship_tags_name', 'tagged_in');

			$current_tags = elgg_get_entities_from_relationship(array(
				'relationship' => $relationship_name,
				'relationship_guid' => $entity->guid,
				'inverse_relationship' => true
					));
			if (is_array($current_tags)) {
				foreach ($current_tags as $current_tag) {
					if (!in_array($current_tag->guid, $tags)) {
						remove_entity_relationship($current_tag->guid, $relationship_name, $entity->guid);
					}
				}
			}
			if (is_array($tags)) {
				foreach ($tags as $tag_guid) {
					add_entity_relationship($tag_guid, $relationship_name, $entity->guid);
				}
				$tags = implode(',', $tags);
			}

			$entity->$field_name = $tags;

			break;

		case 'multifile' :

			if (elgg_is_logged_in()) {
				$values = get_input($field->name);
				if (is_array($values)) {
					foreach ($values as $value) {
						create_metadata($entity->guid, $field->name, $value, '', $entity->owner_guid, $entity->access_id, true);
						if (!elgg_trigger_plugin_hook('framework:form:multifile', 'all', array('entity' => $entity, 'file_guid' => $value, 'field_name' => $field_name), false)) {
							make_attachment($entity->guid, $value);
						}
					}
				}
			}

			break;
	}

	return true;
}

/**
 * Serve json on AJAX calls instead of defaul page shell
 */
function hj_framework_ajax_pageshell($hook, $type, $return, $params) {

	if (elgg_is_xhr()) {
		$output = $params;
		foreach ($output as $key => $val) {
			if (is_string($val) && $json = json_decode($val)) {
				$output[$key] = $json;
			}
		}

		$js = elgg_get_loaded_js();
		$js_foot = elgg_get_loaded_js('footer');

		$js = array_merge($js, $js_foot);

		$css = elgg_get_loaded_css();

		$resources = array(
			'js' => array(),
			'css' => array()
		);

		foreach ($js as $script) {
			$resources['js'][] = $script;
		}

		foreach ($css as $link) {
			$resources['css'][] = $link;
		}


		$params = array(
			'output' => $output,
			'status' => 0,
			'system_messages' => array(
				'error' => array(),
				'success' => array()
			),
			'resources' => $resources
		);

		//Grab any system messages so we can inject them via ajax too
		$system_messages = system_messages(NULL, "");

		if (isset($system_messages['success'])) {
			$params['system_messages']['success'] = $system_messages['success'];
		}

		if (isset($system_messages['error'])) {
			$params['system_messages']['error'] = $system_messages['error'];
			$params['status'] = -1;
		}

		// Check the requester can accept JSON responses, if not fall back to
		// returning JSON in a plain-text response.  Some libraries request
		// JSON in an invisible iframe which they then read from the iframe,
		// however some browsers will not accept the JSON MIME type.
		if (stripos($_SERVER['HTTP_ACCEPT'], 'application/json') === FALSE) {
			header("Content-type: text/plain");
		} else {
			header("Content-type: application/json");
		}

		return json_encode($params);
	}

	return $return;
}

/**
 * Encode $vars passed to elgg_view_layout() on AJAX calls
 */
function hj_framework_ajax_pagelayout($hook, $type, $return, $output) {

	if (elgg_is_xhr()) {
		$params['breadcrumbs'] = elgg_get_breadcrumbs();
		foreach ($output as $key => $value) {
			$json = json_decode($value);
			if (isset($json)) {
				$output[$key] = $json;
			} else {
				$output[$key] = $value;
			}
		}
		return json_encode($output);
	}

	return $return;
}

/**
 * Follow Elgg's default forward behavior for AJAX calls and add registered JS and CSS resources to the json output
 */
function hj_framework_ajax_forward_hook($hook, $type, $reason, $params) {
	if (elgg_is_xhr() || get_input('xhr_mode', false)) {
		$js = elgg_get_loaded_js();
		$js_foot = elgg_get_loaded_js('footer');

		$js = array_merge($js, $js_foot);

		$css = elgg_get_loaded_css();

		$resources = array(
			'js' => array(),
			'css' => array()
		);

		foreach ($js as $script) {
			$resources['js'][] = $script;
		}

		foreach ($css as $link) {
			$resources['css'][] = $link;
		}

		// always pass the full structure to avoid boilerplate JS code.
		$params = array(
			'output' => '',
			'status' => 0,
			'system_messages' => array(
				'error' => array(),
				'success' => array()
			),
			'resources' => $resources
		);

		//grab any data echo'd in the action
		$output = ob_get_clean();

		//Avoid double-encoding in case data is json
		$json = json_decode($output);
		if (isset($json)) {
			$params['output'] = $json;
		} else {
			$params['output'] = $output;
		}

		//Grab any system messages so we can inject them via ajax too
		$system_messages = system_messages(NULL, "");

		if (isset($system_messages['success'])) {
			$params['system_messages']['success'] = $system_messages['success'];
		}

		if (isset($system_messages['error'])) {
			$params['system_messages']['error'] = $system_messages['error'];
			$params['status'] = -1;
		}

		// Check the requester can accept JSON responses, if not fall back to
		// returning JSON in a plain-text response.  Some libraries request
		// JSON in an invisible iframe which they then read from the iframe,
		// however some browsers will not accept the JSON MIME type.
		if (stripos($_SERVER['HTTP_ACCEPT'], 'application/json') === FALSE) {
			header("Content-type: text/plain");
		} else {
			header("Content-type: application/json");
		}

		echo json_encode($params);
		exit;
	}
}

/**
 * Forms config
 */
function hj_framework_setup_forms($hook, $type, $return, $params) {

	$return['framework:filefolder'] = array(
		'title' => 'hypeFramework:filefolder',
		'label' => 'File Folder Creation Form',
		'description' => 'hypeFramework File Folder Creation Form',
		'subject_entity_subtype' => 'hjfilefolder',
		'notify_admins' => false,
		'add_to_river' => false,
		'comments_on' => false,
		'ajaxify' => true,
		'fields' => array(
			'title' => array(
				'name' => 'title',
				'requied' => true
			),
			'description' => array(
				'name' => 'description',
				'input_type' => 'longtext',
				'class' => 'elgg-input-longtext'
			),
			'tags' => array(
				'name' => 'tags',
				'input_type' => 'tags'
			),
			'datatype' => array(
				'name' => 'datatype',
				'input_type' => 'dropdown',
				'options_values' => "hj_framework_get_filefolder_types"
			),
			'access_id' => array(
				'name' => 'access_id',
				'input_type' => 'access'
			)
		)
	);

	$return['framework:file'] = array(
		'title' => 'hypeFramework:fileupload',
		'description' => 'hypeFramework File Upload Form',
		'subject_entity_subtype' => 'hjfile',
		'notify_admins' => false,
		'add_to_river' => true,
		'comments_on' => true,
		'fields' => array(
			'title' => array(
				'name' => 'title',
				'required' => true
			),
			'description' => array(
				'name' => 'description',
				'input_type' => 'longtext',
				'class' => 'elgg-input-longtext'
			),
			'tags' => array(
				'name' => 'tags',
				'input_type' => 'tags'
			),
			'filefolder' => array(
				'name' => 'filefolder',
				'input_type' => 'dropdown',
				'options_values' => 'hj_framework_get_user_file_folders'
			),
			'newfilefolder' => array(
				'name' => 'newfilefolder'
			),
			'fileupload' => array(
				'name' => 'fileupload',
				'input_type' => 'file',
				'required' => true
			),
			'access_id' => array(
				'title' => 'Access Level',
				'name' => 'access_id',
				'input_type' => 'access'
			)
		)
	);

	return $return;
}