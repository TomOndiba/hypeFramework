<?php

if (elgg_in_context('table-view')) {
	return true;
}

$entity = elgg_extract('entity', $vars);
echo '<div class="user-detail-jobtitle">' . $entity->jobtitle . '</div>';