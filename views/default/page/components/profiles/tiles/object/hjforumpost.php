<?php

$post = $entity = $vars['entity'];

$thread = $entity->getContainerEntity();
$thread_link = elgg_view('output/url', array(
	'text' => $thread->title,
	'href' => $thread->getURL()
));

$forum = $thread->getContainerEntity();
$forum_link = elgg_view('output/url', array(
	'text' => $forum->title,
	'href' => $forum->getURL()
));

$author = $entity->getOwnerEntity();
$author_link = elgg_view('output/url', array(
	'text' => $author->name,
	'href' => $author->getURL()
));

$body = $post->description;

$body = preg_replace("'<blockquote>(.*?)</p>'si", '', $body);
$body = strip_tags($body);

$icon = elgg_view_entity_icon($author, 'small');

$time = date('g:ia F d, Y', $entity->time_created);

$content = <<<__HTML
<div class="forumpost-content">
	<div class="forumpost-author">
		$author_link posted
	</div>
	<div class="forumpost-body">
		$body
	</div>
	<div class="forumpost-extras">
		in $thread_link in the $forum_link - $time
	</div>
</div>
__HTML;

echo elgg_view_image_block($icon, $content);
