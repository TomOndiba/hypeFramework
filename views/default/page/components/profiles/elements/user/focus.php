<?php

$user = elgg_extract('entity', $vars);

if (isset($user->focustheme)) {
	if (!is_array($user->focustheme)) {
		$themes = array($user->focustheme);
	} else {
		$themes = $user->focustheme;
	}

	foreach ($themes as $t) {
		$class = "tag";
		if ($t == get_input('focus')) {
			$class = "$class highlight";
		}
		$theme_tags .= "<span class=\"$class\">$t</span>";
	}

	echo $theme_tags;
}