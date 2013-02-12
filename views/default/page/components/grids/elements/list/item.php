<?php

$attributes = elgg_format_attributes(elgg_extract('attributes', $vars, array()));

$item = elgg_extract('entity', $vars);

$item_view = elgg_view_list_item($item, $vars);

echo "<li $attributes>$item_view</li>";
