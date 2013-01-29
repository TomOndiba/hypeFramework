<?php

$question = elgg_extract('entity', $vars);
$author = $question->getOwnerEntity();
if (!$author)
	return true;


$content .= '<div class="dashboard-question">';

//if ($author->guid != elgg_get_logged_in_user_guid() || !elgg_in_context('dashboard')) {
	$content .= '<div class="dashboard-question-author">';
	$content .= elgg_view_entity_icon($author, 'small', array(
		'width' => 33,
		'height' => 33
			));
	$content .= '</div>';
//}

$content .= '<div class="dashboard-question-body">';
$content .= '<span class="dashboard-question-author-said">';
if ($author->guid != elgg_get_logged_in_user_guid()) {
	$author_link = elgg_view('output/url', array(
		'text' => $author->name,
		'href' => $author->getURL()
	));
	$content .= "$author_link asked:<br />";
}
$content .= '</span>';

$forum = $question->getContainerEntity();
$network = $forum->getContainerEntity();

if (elgg_instanceof($network, 'group', 'network')) {
	$forum_name = "$network->code: $forum->title";
} else {
	$forum_name = "$forum->title";
}

$forum_link = elgg_view('output/url', array(
	'text' => $forum_name,
	'href' => $forum->getURL()
));

$time = date('g:ia F d, Y', $question->time_created);

$content .= '<span>' . elgg_view('output/url', array(
	'text' => (!empty($question->title)) ? $question->title : elgg_get_excerpt(strip_tags($question->description)),
	'href' => $question->getURL()
		)) . '</span><br />';
$content .= " in the $forum_link <br />at $time";
$content .= '</div>';

$content .= '<div class="dashboard-question-replies">';
$content .= $question->countPosts() . ' replies';
$content .= '</div>';

$viewcount = $question->getViewCount();
if (!$viewcount) $viewcount = 0;

$content .= '<div class="dashboard-question-views">';
$content .=  $viewcount . ' views';
$content .= '</div>';

$content .= '</div>';

echo $content;