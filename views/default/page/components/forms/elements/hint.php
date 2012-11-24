<?php
/**
 * Elgg Input Hint View
 *
 * @uses $vars['hint'] Hint options
 */

$hint = elgg_extract('hint', $vars, false);
if (!$hint) {
	return true;
}

if (!isset($hint['text'])) {
	return true;
}

$text = $hint['text'];
unset($hint['text']);

if (isset($hint['class'])) {
	$hint['class'] = "elgg-input-hint {$hint['class']}";
} else {
	$hint['class'] = "elgg-input-hint";
}

echo '<span ' . elgg_format_attributes($hint) . '>' . $text . '</span>';