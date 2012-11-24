<?php

// View an entity (by its GUID) or render a view
elgg_register_action('framework/entities/view', HYPEFRAMEWORK_PATH_ACTIONS . 'framework/view.php', 'public');

// Edit an entity
elgg_register_action('framework/entities/edit', HYPEFRAMEWORK_PATH_ACTIONS . 'framework/edit.php');

// Process an hjForm on submit
elgg_register_action('framework/entities/save', HYPEFRAMEWORK_PATH_ACTIONS . 'framework/submit.php', 'public');

// Delete an entity by guid
elgg_register_action('framework/entities/delete', HYPEFRAMEWORK_PATH_ACTIONS . 'framework/delete.php', 'public');

// Reset priority attribute of an object
elgg_register_action('framework/entities/move', HYPEFRAMEWORK_PATH_ACTIONS . 'framework/move.php');

// E-mail form details
elgg_register_action('framework/form/email', HYPEFRAMEWORK_PATH_ACTIONS . 'framework/email.php');

// Add widget
elgg_register_action('framework/widget/add', HYPEFRAMEWORK_PATH_ACTIONS . 'framework/addwidget.php');

// Load widget
elgg_register_action('framework/widget/load', HYPEFRAMEWORK_PATH_ACTIONS . 'framework/loadwidget.php');

// Download file
elgg_register_action('framework/file/download', HYPEFRAMEWORK_PATH_ACTIONS . 'framework/download.php', 'public');