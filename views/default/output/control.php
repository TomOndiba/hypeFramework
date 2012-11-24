<?php
/**
 * @uses string $vars['text']        The string between the <a></a> tags.
 * @uses string $vars['href']        The unencoded url string
 * @uses bool   $vars['encode_text'] Run $vars['text'] through htmlspecialchars() (false)
 * @uses bool   $vars['is_action']   Is this a link to an action (false)
 * @uses bool   $vars['is_trusted']  Is this link trusted (false)
 * @uses string $vars['icon']		 Icon
 */

$url = elgg_extract('href', $vars, null);
if (!$url and isset($vars['value'])) {
	$url = trim($vars['value']);
	unset($vars['value']);
}

if (isset($vars['icon'])) {
	$text = "<span class=\"framework-ui-icon framework-ui-icon-{$vars['icon']}\"></span>";
}

if (isset($vars['text'])) {
	if (elgg_extract('encode_text', $vars, false)) {
		$str = htmlspecialchars($vars['text'], ENT_QUOTES, 'UTF-8', false);
	} else {
		$str = $vars['text'];
	}
	$text .= '<span class="label">' . $str . '</span>';
	unset($vars['text']);
} 

unset($vars['encode_text']);

if ($url) {
	$url = elgg_normalize_url($url);

	if (elgg_extract('is_action', $vars, false)) {
		$url = elgg_add_action_tokens_to_url($url, false);
	}

	if (!elgg_extract('is_trusted', $vars, false)) {
		if (!isset($vars['rel'])) {
			$vars['rel'] = 'nofollow';
			$url = strip_tags($url);
		}
	}

	$vars['href'] = $url;
}

if (isset($vars['class'])) {
	$vars['class'] = "framework-ui-control {$vars['class']}";
} else {
	$vars['class'] = "framework-ui-control";
}

unset($vars['is_action']);
unset($vars['is_trusted']);
unset($vars['icon']);

$attributes = elgg_format_attributes($vars);
echo "<a $attributes>$text</a>";
