<?php

$pagehandler_label = 'Page handler identifier for hypeFramework pages (e.g. www.your-elgg-site.com/framework/edit)';
$pagehandler = elgg_view('input/text', array(
	'name' => 'params[global_pagehandler',
	'value' => $vars['entity']->global_pagehandler
));

$cleditor_label = 'Enable CLEditor (replaces default TinyMCE editor)';
$cleditor = elgg_view('input/dropdown', array(
    'name' => 'params[cleditor]',
    'value' => $vars['entity']->cleditor,
    'options_values' => array('on' => 'Enable', 'off' => 'Disable')
        ));

$settings = <<<__HTML

	<h3>Global Settings</h3>
    <div>
        <p><i>$pagehandler_label</i><br>$pagehandler</p>
    </div>
    <hr>

    <h3>CLEditor</h3>
    <div>
        <p><i>$cleditor_label</i><br>$cleditor</p>
    </div>
    <hr>
</div>
__HTML;

echo $settings;