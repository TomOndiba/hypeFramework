<?php

$entity = $vars['entity'];

if (!$entity) {
	return;
}

if (!$entity->icontime) {
	return;
}

echo '<div class="hj-framework-cover-image" style="background-image:url(' . $entity->getIconURL('master') . ')"></div>';