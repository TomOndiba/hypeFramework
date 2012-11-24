<?php
/*
 * Form Element Wrapper
 * Wraps input element, hint and label into a div
 *
 * @uses $vars['input'] markup of an input element
 * @uses $vars['label'] markup of a label element
 * @uses $vars['hint'] markup of a hint element
 * @uses $vars['type'] input type
 */

$type = elgg_extract('type', $vars, 'footer');

if ($type == 'hidden') {
	echo $vars['input'];
	return true;
}

$class = "elgg-input-wrapper elgg-input-wrapper-$type";
$label = elgg_extract('label', $vars, '');
$hint = elgg_extract('hint', $vars, '');
$input = elgg_extract('input', $vars, '');

echo <<<HTML
<div class="$class">
		$label
		<span>$hint</span>
		<span>$input</span>
</div>
HTML;
