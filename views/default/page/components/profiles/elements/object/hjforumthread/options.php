<?php

$entity = elgg_extract('entity', $vars);

$menu = elgg_view('object/hjforumthread/menu', array('entity' => $entity));
echo $menu;