<?php

$tag = elgg_extract('tag', $vars, 'div');
$attr = elgg_format_attributes(elgg_extract('attr', $vars, array()));
$text = elgg_extract('text', $vars, '');

echo "<$tag $attr>$text</$tag>";
