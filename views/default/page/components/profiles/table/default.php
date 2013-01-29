<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
	return true;
}

$type = $entity->getType();
$subtype = $entity->getSubtype();
if (!$subtype) $subtype = 'default';

$view = "page/components/profiles/table/$type/$subtype";

if (elgg_view_exists($view)) {
	echo elgg_view($view, $vars);
} else {
	$headers = elgg_extract('list_table_headers', $vars);
	if ($headers) {
		foreach ($headers as $header => $sortable) {
			echo "<td class=\"row-$header\">$entity->$header</td>";
		}
	}
}

