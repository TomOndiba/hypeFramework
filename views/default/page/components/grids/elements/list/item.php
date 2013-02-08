<?php

$attributes = elgg_format_attributes(elgg_extract('attributes', $vars, array()));

$item = elgg_extract('entity', $vars);
$params = elgg_extract('params', $vars, array());

$full = $params['full_view'];

if (!$item instanceof hjObject ||
		($item->canView()) ||
		(!$full && $item->canPreview())) {
	$item_view = elgg_view_list_item($item, $vars);
} else if ($item instanceof hjObject && $full && $item->canPreview()) {
	$params['full_view'] = false;
	$item_view = '<div class="hj-framework-warning">' . elgg_echo('hj:framework:permissions:cannotviewfull') .'</div>';
	$item_view .= elgg_view_list_item($item, $vars);
} else {
	$item_view = '<div class="hj-framework-warning">' . elgg_echo('hj:framework:permissions:cannotview') .'</div>';
}

echo "<li $attributes>$item_view</li>";
