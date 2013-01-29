<?php

$entity = elgg_extract('entity', $vars);
$connection = elgg_extract('connection', $vars);
$viewer = elgg_get_logged_in_user_entity();

if (!$connection) return true;

$ia = elgg_set_ignore_access(true);
$reason = en_connections_get_recommendation_reason($connection->guid, $entity->guid);
elgg_set_ignore_access($ia);

$canedit = false;
if ((elgg_is_active_plugin('roles') && (roles_has_role('staff') || roles_has_role('ccd') || roles_has_role('ed'))) || elgg_is_admin_logged_in()) {
	$canedit = true;
}

if ((!$reason || empty($reason)) && !$canedit)  {
	return true;
}

if ($canedit) {

	$reason_edit_str = elgg_view('output/url', array(
		'text' => 'Edit',
		'class' => 'recommendation-reason-edit',
		'data-connectionguid' => $connection->guid,
		'data-entityguid' => $entity->guid
	));

	$reason_edit_form = elgg_view('input/hidden', array(
		'name' => 'connection_guid',
		'value' => $connection->guid
	));

	$reason_edit_form .= elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $entity->guid
	));

	$reason_edit_form .= elgg_view('input/plaintext', array(
		'name' => 'reason',
		'value' => $reason
	));

	$reason_edit_form .= elgg_view('input/submit');

	$reason_edit_form = elgg_view('input/form', array(
		'action' => 'action/connection/edit_reason',
		'body' => $reason_edit_form,
		'class' => 'hidden',
		'id' => "recommendation-form-$entity->guid-$connection->guid"
	));

}

$reason_str = "<div id=\"recommendation-reason-$entity->guid-$connection->guid\">$reason</div>";

if (!elgg_in_context('table-view')) {
	
	echo "<div id=\"recommendation-wrapper-$entity->guid-$connection->guid\" class=\"user-detail-recommendation-reason\">";
	echo $reason_edit_str;
	echo $reason_edit_form;
	echo $reason_str;
	echo '</div>';

} else {
	echo elgg_view('output/url', array(
		'text' => 'Why?',
		'href' => "#recommendation-wrapper-$entity->guid-$connection->guid",
		'rel' => 'toggle',
		'class' => 'reason-toggle'
	));

	echo "<div id=\"recommendation-wrapper-$entity->guid-$connection->guid\" class=\"user-detail-recommendation-reason hidden\">";
	echo $reason_edit_str;
	echo $reason_edit_form;
	echo $reason_str;
	echo '</div>';

}