<?php

$entity = elgg_extract('entity', $vars);

$count = $entity->getSubThreads(array('count' => true));
$count = elgg_view('output/url', array(
	'href' => $entity->getURL(),
	'text' => $count,
	'class' => 'thread_count'
		));

echo $count;