<?php

$shortcuts = hj_framework_path_shortcuts('hypeFramework');
$path_actions = $shortcuts['actions'];

elgg_register_action('framework/edit/object', $path_actions . 'framework/edit/object.php');


// View an entity (by its GUID) or render a view
elgg_register_action('framework/entities/view', $path_actions . 'framework/view.php', 'public');

// Edit an entity
elgg_register_action('framework/entities/edit', $path_actions . 'framework/edit.php');

// Process an hjForm on submit
elgg_register_action('framework/entities/save', $path_actions . 'framework/submit.php', 'public');

// Delete an entity by guid
elgg_register_action('framework/entities/delete', $path_actions . 'framework/delete.php', 'public');

// Reset priority attribute of an object
elgg_register_action('framework/entities/move', $path_actions . 'framework/move.php');

// E-mail form details
elgg_register_action('framework/form/email', $path_actions . 'framework/email.php');

// Add widget
elgg_register_action('framework/widget/add', $path_actions . 'framework/addwidget.php');

// Load widget
elgg_register_action('framework/widget/load', $path_actions . 'framework/loadwidget.php');

// Download file
elgg_register_action('framework/file/download', $path_actions . 'framework/download.php', 'public');