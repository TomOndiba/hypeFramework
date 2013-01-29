<?php

$entity = elgg_extract('entity', $vars);
$entity_url = elgg_view('output/url', array(
	'text' => $entity->name,
	'href' => $entity->getURL()
		));

if (elgg_in_context('table-view')) {

	$icon = elgg_view_entity_icon($entity, 'small', array(
		'use_hover' => false,
		'class' => 'user-detail-icon'
			));
	$body .= '<div class="user-detail-name">' . $entity_url . '</div>';
	$body .= '<div class="user-detail-jobtitle">' . $entity->jobtitle . '</div>';
	$ia = elgg_set_ignore_access(true);
	$account = accounts_get_users_account($entity->guid);
	if ($account) {
		$body .= '<div class="user-detail-company">' . $account->title . '</div>';
	}
	elgg_set_ignore_access($ia);

	echo elgg_view_image_block($icon, $body);
} else {

	echo '<div class="user-detail-name"><h3>' . $entity_url . '</h3></div>';
}