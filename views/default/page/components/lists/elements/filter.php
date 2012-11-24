<?php

$list_id = elgg_extract('list_id', $vars);
$list_options = elgg_extract('list_options', $vars);
$filter_values = elgg_extract('filter_values', $vars);

$form = new hjDynamicForm('list_filter', null, $vars);
$form_body = $form->getFormBody();

$content = elgg_view_menu('list_filter', array(
	'data-list' => $list_id,
	'class' => 'elgg-menu-hz framework-ui-controls clearfix',
	'sort_by' => 'priority',
	'list_id' => $list_id,
	'list_options' => $list_options
		));

$content .= "<div id=\"filter-$list_id\" data-list=\"$list_id\" class=\"framework-list-filter-form hidden\">";
$content .= elgg_view('input/form', array(
	'body' => $form_body,
	'action' => full_url(),
	'method' => 'GET',
	'disable_security' => true
		));
$content .= '</div>';

echo '<div class="framework-list-filter-area">' . $content . '</div>';