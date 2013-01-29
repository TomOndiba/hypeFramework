<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
	return true;
}

$type = $entity->getType();
$subtype = $entity->getSubtype();
if (!$subtype)
	$subtype = 'default';

$class = 'hj-framework-table-row';
if (isset($vars['item_class'])) {
	$class = "$class {$vars['itme_class']}";
}

$headers = $vars['list_options']['list_view_options']['table']['head'];

if ($headers) {
	echo "<tr id=\"elgg-$type-{$entity->guid}\" class=\"$class\" data-uid=\"$entity->guid\">";
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
		echo "<td $colspan class=\"table-cell-$header\">$cell</td>";
	}
	echo '</tr>';
} else {
	echo elgg_view_entity($entity, $vars);
}

