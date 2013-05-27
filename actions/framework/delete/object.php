<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof hjObject) {
	register_error(elgg_echo('hj:framework:delete:error:nothjobject'));
	forward(REFERER);
}

$container = $entity->getContainerEntity();
if ($entity->canEdit() && $entity->delete()) {
	if (elgg_is_xhr()) {
		print json_encode(array('guid' => $guid));
	}
	system_message(elgg_echo('hj:framework:delete:success'));
	forward($container->getURL());
} else {
	register_error(elgg_echo('hj:framework:delete:error:unknown'));
	forward(REFERER);
}