<?php

/**
 * A single element of a menu.
 *
 * @package Elgg.Core
 * @subpackage Navigation
 *
 * @uses $vars['item']       ElggMenuItem
 * @uses $vars['item_class'] Additional CSS class for the menu item
 */
$item = $vars['item'];

$link_class = 'elgg-menu-closed';
if ($item->getSelected()) {
	// @todo switch to addItemClass when that is implemented
	//$item->setItemClass('elgg-state-selected');
	$link_class = 'elgg-menu-opened';
}

$children = $item->getChildren();
if ($children) {
	$item->addLinkClass($link_class);
	$item->addLinkClass('elgg-menu-parent');
}

$item_class = $item->getItemClass();
if ($item->getSelected()) {
	$item_class = "$item_class elgg-state-selected";
}
if (isset($vars['item_class']) && $vars['item_class']) {
	$item_class .= ' ' . $vars['item_class'];
}

$item->setText("<span class=\"label\">{$item->getText()}</span>");
if (isset($item->icon)) {
	$item->setText("<span class=\"framework-ui-icon framework-ui-icon-{$item->icon}\"></span>{$item->getText()}");
}
echo "<li class=\"$item_class\">";
if ($children) {
	$item->setText("{$item->getText()}<span class=\"framework-ui-toggle $item->toggle_icon\"></span>");

	echo '<div class="framework-ui-dropdown">';
	echo '<div class="framework-ui-control">' . $item->getContent() . '</div>';

	$cols = ceil(sizeof($children) / 10);
	$slider_width = $cols * 200;
	echo "<div class=\"framework-ui-dropdown-slider clearfix\" style=\"min-width:{$slider_width}px\">";

	for ($i = 0; $i < $cols; $i++) {
		echo "<div class=\"elgg-col elgg-col-1of$cols\">";
		for ($k = 0; $k < 10; $k++) {
			$index = $i * 10 + $k;
			$child = $children[$index];
			if ($child) {
				if (isset($child->icon)) {
					$child->setText("<span class=\"framework-ui-icon framework-ui-icon-{$child->icon}\"></span>{$child->getText()}");
				}
				echo $child->getContent();
			}
		}
		echo '</div>';
	}
	foreach ($children as $child) {

	}
	echo '</div>';
	echo '</div>';
} else {
	echo $item->getContent();
}

echo '</li>';
