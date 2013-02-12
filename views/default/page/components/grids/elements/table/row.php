<?php

$entity = elgg_extract('entity', $vars, false);
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
		$item_view .= "<td $colspan class=\"table-cell-$header\">$cell</td>";
	}
} else {
	$item_view .= elgg_view_list_item($entity, $vars);
}

echo "<tr $attributes>$item_view</tr>";

