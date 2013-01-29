<?php

$attributes = elgg_format_attributes(elgg_extract('attributes', $vars, array()));

$item = elgg_extract('item', $vars);
$params = elgg_extract('params', $vars, array());

$item_view = elgg_view_list_item($item, $params);


echo "<li $attributes>$item_view</li>";
