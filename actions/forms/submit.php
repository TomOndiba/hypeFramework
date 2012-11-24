<?php

/**
 * Action to perform on hjForm submit
 * Saves an entity of a given type, subtype and handler
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category AJAX
 * @category User Interface
 * @category Forms
 */

$form_name = get_input('form_name', false);

if (!$form_name) {
	forward();
}

elgg_make_sticky_form($form_name);

$entity = hj_framework_process_form($form_name);

if (!$entity) {
	register_error(elgg_echo('framework:error:cannotcreateentity'));
	forward(REFERER);
}

$forward_url = elgg_trigger_plugin_hook('forward', 'form', array('entity' => $entity, 'form_name' => $form_name), $entity->getURL());

system_message(elgg_echo('framework:submit:success'));
elgg_clear_sticky_form($form_name);
forward($forward_url);