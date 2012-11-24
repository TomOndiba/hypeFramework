<?php
/**
 * Elgg Label From Element
 *
 * @uses $vars['label'] Array of label attributes array('text' => $label_text, 'for' => $input_name)
 * @uses $vars['required'] Is associated field required?
 */
$label = elgg_extract('label', $vars, false);

if (!$label) {
	return true;
}
if (!isset($label['text'])) {
	return true;
}

$text = $label['text'];
unset($label['text']);

if ($vars['required']) {
	$text .= elgg_view('output/url', array(
		'text' => '<span class="elgg-input-required">' . elgg_echo('required') . '</span>',
		//'title' => elgg_echo('required'),
		'href' => false,
		'class' => 'framework-ui-tooltip framework-label-required'
			));
}

if (isset($label['class'])) {
	$label['class'] = "elgg-input-label {$label['class']}";
} else {
	$label['class'] = "elgg-input-label";
}
?>
<label <?php echo elgg_format_attributes($label); ?>><?php echo $text ?></label>