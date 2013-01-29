<?php

$user = elgg_extract('entity', $vars);

if (isset($user->thingstoshare)) {
	if (!is_array($user->thingstoshare)) {
		$shares = array($user->thingstoshare);
	} else {
		$shares = $user->thingstoshare;
	}

	foreach ($shares as $t) {
		$class = "tag";
		if ($t == get_input('interest')) {
			$class = "$class highlight";
		}
		$share_tags .= "<span class=\"$class\">$t</span>";
	}

	echo $share_tags;
}