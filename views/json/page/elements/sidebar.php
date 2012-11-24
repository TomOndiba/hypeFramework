<?php

echo json_encode(array(
	'menus' => array(
		'extras' => elgg_view_menu('extras', array(
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		)),
		'page' => elgg_view_menu('page', array('sort_by' => 'name'))
	),
	'content' => $vars['sidebar'],
	'owner_block' => elgg_view('page/elements/owner_block', $vars)
));