<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);
$attributes = elgg_format_attributes(elgg_extract('attributes', $vars, array()));

if (!$entity) {
	return true;
}

$type = $entity->getType();
$subtype = $entity->getSubtype();
if (!$subtype)
	$subtype = 'default';

$class = "hj-framework-table-row elgg-$type-$subtype";
if (isset($vars['item_class'])) {
	$class = "$class {$vars['item_class']}";
}

$headers = $vars['list_options']['list_view_options']['table']['head'];

if ($headers) {

	$item_view = '';
	foreach ($headers as $header => $options) {
		$colspan = '';
		if (elgg_view_exists("$type/$subtype/elements/$header")) {
			$cell = elgg_view("$type/$subtype/elements/$header", $vars);
		} else if (isset($options['colspan'])) {
			foreach ($options['colspan'] as $col_header => $col_options) {
				if (elgg_view_exists("$type/$subtype/elements/$col_header")) {
					$cell .= elgg_view("$type/$subtype/elements/$col_header", $vars);
				} else {
					$cell .= '<div>' . $entity->$col_header . '</div>';
				}
			}
			$colspan = ' colspan="' . count($options['colspan']) . '"';
		} else {
			$cell = '<div>' . $entity->$header . '</div>';
		}
		if ($item instanceof hjObject && $item->canView()) {
			$item_view .= "<td $colspan class=\"table-cell-$header\">" . elgg_echo('hj:framework:permissions:cannotview:cell') . "</td>";
		} else {
			$item_view .= "<td $colspan class=\"table-cell-$header\">$cell</td>";
		}
	}
} else {
	if (!$item instanceof hjObject ||
			($item->canView()) ||
			(!$full && $item->canPreview())) {
		$item_view = elgg_view_list_item($item, $params);
	} else if ($item instanceof hjObject && $full && $item->canPreview()) {
		$params['full_view'] = false;
		$item_view = '<div class="hj-framework-warning">' . elgg_echo('hj:framework:permissions:cannotviewfull') . '</div>';
		$item_view .= elgg_view_list_item($item, $params);
	} else {
		$item_view = '<div class="hj-framework-warning">' . elgg_echo('hj:framework:permissions:cannotview') . '</div>';
	}
}

echo "<tr $attributes>$item_view</tr>";

