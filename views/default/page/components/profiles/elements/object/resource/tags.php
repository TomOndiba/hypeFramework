<?php

$entity = elgg_extract('entity', $vars);
$tags = $entity->tags;
if ($tags) {
	if (!is_array($tags)) {
		$tags = array($tags);
	}
	foreach ($tags as $t) {
		$class = "tag";
		if ($t == get_input('tags')) {
			$class = "$class highlight";
		}
		$tags_str .= "<span class=\"$class\">$t</span>";
	}
}

echo $tags_str;