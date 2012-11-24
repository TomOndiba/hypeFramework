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

$type = elgg_extract('type', $vars, 'hidden');


$class = "elgg-input-wrapper elgg-input-wrapper-$type";
$label = elgg_extract('label', $vars, '');
$hint = elgg_extract('hint', $vars, '');
$input = elgg_extract('input', $vars, '');

if ($type == 'hidden') {
	$class = 'hidden';
	$label = '';
	$hint = '';
}
echo <<<HTML
<li class="$class">
		$label
		$input
		$hint
</li>
HTML;
