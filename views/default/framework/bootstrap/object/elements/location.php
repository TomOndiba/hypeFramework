<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) return true;

echo '<div class="elgg-entity-location">';
echo $entity->getLocation();
echo '</div>';