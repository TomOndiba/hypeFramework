<?php

$entity = elgg_extract('entity', $vars);

$thread = elgg_view('output/url', array(
	'href' => $entity->getURL(),
	'text' => $entity->title,
	'class' => 'thread_title',
	'is_trusted' => true
		));

$thread .= '<br />';

if ($entity->isAnnouncement()) {
	$thread .= '<small class="tag"><a href="#">Announcement</a></small>';
}

if ($entity->isSticky()) {
	$thread .= '<small class="tag"><a href="#">Sticky</a></small>';
}

echo $thread;