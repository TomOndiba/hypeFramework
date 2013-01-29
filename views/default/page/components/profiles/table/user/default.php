<?php

$entity = $user = elgg_extract('entity', $vars);

$headers = elgg_extract('headers', $vars);
$cell_class_namespace = elgg_extract('cell_class_namespace', $vars, 'cell-');

if (!isset($vars['connection']) && isset($vars['connections'])) {
	$vars['connection'] = $vars['connections'][$entity->guid];
}

if (isset($vars['connection'])) {

	$vars['recommendation_date'] = $vars['connection']->time_created;

	if ($vars['recommendation_date'] > time() - 7 * 24 * 60 * 60) {
		$highlight_class = "highlight-miniprofile";
	}

}

echo "<tr id=\"elgg-user-{$entity->guid}\" class=\"table-row\">";

foreach ($headers as $key => $value) {

	if (is_array($value)) {
		$colspan = count($value);
		echo "<td class=\"$cell_class_namespace-$key $highlight_class\" colspan=\"$colspan\">";
		foreach ($value as $header => $sortable) {
			echo "<div class=\"subcell-$header\">";
			echo elgg_view("page/components/profiles/elements/user/$header", $vars);
			echo '</div>';
		}
		echo '</td>';
	} else {
		echo "<td class=\"cell-$key\">";
		echo elgg_view("page/components/profiles/elements/user/$key", $vars);
		echo '</td>';
	}
}

echo '</tr>';