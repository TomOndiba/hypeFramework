<?php
/**
 * Menu group
 *
 * @uses $vars['items']                Array of menu items
 * @uses $vars['class']                Additional CSS class for the section
 * @uses $vars['name']                 Name of the menu
 * @uses $vars['section']              The section name
 * @uses $vars['item_class']           Additional CSS class for each menu item
 * @uses $vars['show_section_headers'] Do we show headers for each section
 */

$headers = elgg_extract('show_section_headers', $vars, false);
$class = elgg_extract('class', $vars, '');
$item_class = elgg_extract('item_class', $vars, '');
$link_class = "framework-ui-control";

if ($headers) {
	$name = elgg_extract('name', $vars);
	$section = elgg_extract('section', $vars);
	echo '<h2>' . elgg_echo("menu:$name:header:$section") . '</h2>';
}

echo "<ul class=\"$class\">";
$i = 1;
foreach ($vars['items'] as $menu_item) {
//	if (sizeof($vars['items']) > 1) {
//		if ($i == 1) {
//			$link_class = "$link_class left";
//		} else if ($i == sizeof($vars['items'])) {
//			$link_class = "$link_class right";
//		} else {
//			$link_class = "$link_class middle";
//		}
//	}

	$menu_item->addLinkClass($link_class);
	echo elgg_view('navigation/menu/controls/item', array(
		'item' => $menu_item,
		'item_class' => $item_class,
	));
	$i++;
}
echo '</ul>';
