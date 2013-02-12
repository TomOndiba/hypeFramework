<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof hjObject) {
	register_error(elgg_echo('hj:framework:delete:error:nothjobject'));
	forward(REFERER);
}

$container = $entity->getContainerEntity();
if ($entity->canEdit() && $entity->delete()) {
	system_message(elgg_echo('hj:framework:delete:success'));
} else {
	register_error(elgg_echo('hj:framework:delete:error:unknown'));
}

echo json_encode(array('guid' => $guid));
forward($container->getURL());