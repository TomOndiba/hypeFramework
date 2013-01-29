<?php

$entity = elgg_extract('entity', $vars);
$author = $entity->getOwnerEntity();

$icon = elgg_view_entity_icon($author, 'medium', array(
	'height' => 33,
	'width' => 33
		));
$author_link = elgg_view('output/url', array(
	'text' => $author->name,
	'href' => $author->getURL()
		));

$time = date('g:ia F d, Y', $entity->time_created);

$title = $entity->title;

$description = elgg_view('output/longtext', array(
	'value' => $entity->description
		));


$content = <<<__HTML
<h3 class="blog-title">$title</h3>
<div class="blog-subtitle">
	<span class="blog-author">$author_link</span>
	<span class="blog-time">$time</span>
</div>
<div class="blog-body">
	$description
</div>
__HTML;

echo elgg_view_image_block($icon, $content);
