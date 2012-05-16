<?php
elgg_load_js('hj.framework.uploadify');
elgg_load_js('hj.framework.multifile');

elgg_load_css('hj.framework.uploadify');

echo elgg_view('input/file', array(
	'id' => 'hj-multifile-upload',
	'name' => $vars['name'],
	'class' => 'hidden'
));

echo elgg_view('input/hidden', array(
	'name' => 'hj-multifile-upload-fieldname',
	'value' => $vars['name']
));
