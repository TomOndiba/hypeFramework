<?php
elgg_load_js('framework.colorpicker');
elgg_load_css('framework.colorpicker');

$vars['class'] = "{$vars['class']} hj-color-picker";
$vars['maxlength'] = '7';
$vars['size'] = '7';

echo elgg_view('input/text', $vars);
