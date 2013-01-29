<?php

$entity = $user = elgg_extract('entity', $vars);

$headers = elgg_extract('headers', $vars);

$cell_class_namespace = elgg_extract('cell_class_namespace', $vars, 'cell-');


echo "<tr id=\"elgg-object-{$entity->guid}\" class=\"table-row\">";

foreach ($headers as $key => $value) {

	if (is_array($value)) {
		$colspan = count($value);
		echo "<td class=\"$cell_class_namespace$key $highlight_class\" colspan=\"$colspan\">";
		foreach ($value as $header => $sortable) {
			echo "<div class=\"subcell-$header\">";
			echo elgg_view("page/components/profiles/elements/object/hjevent/$header", $vars);
			echo '</div>';
		}
		echo '</td>';
	} else {
		echo "<td class=\"$cell_class_namespace$key\">";
		echo elgg_view("page/components/profiles/elements/object/hjevent/$key", $vars);
		echo '</td>';
	}
	
}

echo '</tr>';
