<?php

$main = elgg_extract('default', $vars['menu'], array());
$dropdown = elgg_extract('dropdown', $vars['menu'], array());

if (empty($main) && empty($dropdown)) {
	return true;
}

if (elgg_in_context('print') || elgg_in_context('activity')) {
	return true;
}

if (elgg_in_context('river') || elgg_in_context('widgets')) {
	array_merge($main, $dropdown);
	unset($vars['menu']['default']);
}

if (sizeof($dropdown) > 0) {
	$id = "hjentityheadmenu" . rand(100, 9999);

	echo '<div class="hj-hover-menu-block">';
	echo '<a class="hj-hover-menu-toggler" rel="popup" href="#' . $id . '">' . elgg_view_icon('hjtoggler-down') . '</a>';

	echo '<ul id="' . $id . '" class="elgg-menu hj-entity-head-menu">';

	echo '<li>';

	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu hj-entity-head-menu-default',
		'items' => $dropdown,
	));

	echo '</li>';

	echo '</ul>';
	echo '</div>';

	unset($vars['menu']['dropdown']);
}

if (sizeof($main) > 0) {
	echo elgg_view('navigation/menu/default', $vars);
}