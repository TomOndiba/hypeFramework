<?php

$entity = $user = elgg_extract('entity', $vars);

if (!isset($vars['connection']) && isset($vars['connections'])) {
	$vars['connection'] = $vars['connections'][$entity->guid];
}

if (isset($vars['connection'])) {
	
	$vars['recommendation_date'] = $vars['connection']->time_created;

	if ($vars['recommendation_date'] > time() - 7 * 24 * 60 * 60) {
		$highlight_class = "highlight-miniprofile";
	}

	$reason = elgg_view('page/components/profiles/elements/user/recommendation_reason', $vars);

}

echo "<div class=\"profile-miniprofile $highlight_class\">";
echo '<div class="profile-miniprofile-inner">';
echo '<div class="profile-miniprofile-icon-wrapper">';

echo elgg_view('en/request_connection/button', $vars);

echo elgg_view_entity_icon($entity, 'large', array(
	'use_hover' => false,
	'width' => 158,
	'height' => 158
));

echo '</div>';

echo '<div class="profile-miniprofile-details">';
echo elgg_view('page/components/profiles/elements/user/name', $vars);
echo elgg_view('page/components/profiles/elements/user/jobtitle', $vars);
echo elgg_view('page/components/profiles/elements/user/company', $vars);
echo elgg_view('page/components/profiles/elements/user/networks', $vars);
echo elgg_view('page/components/profiles/elements/user/recommendation_date', $vars);
echo '</div>';

$focus_tags = elgg_view('page/components/profiles/elements/user/focus', $vars);
if (!empty($focus_tags)) {
	$focus = "<p>$user->firstname is focusing on:<br>$focus_tags</p>";
}

$share_tags = elgg_view('page/components/profiles/elements/user/shares', $vars);
if (!empty($share_tags)) {
	$shares = "<p>$user->firstname is focusing on:<br>$share_tags</p>";
}

if ($vars['full_view']) {

	echo '<div class="profile-miniprofile-interests">';
	echo $focus;
	echo $shares;
	echo '</div>';

	if (!empty($reason)) {
		echo "<a class=\"profile-miniprofile-connection-reason-toggle\" href=\"#connection-reason-$user->guid\" rel=\"popup\">Why?</a>";
		echo "<div id=\"connection-reason-$user->guid\" class=\"elgg-module elgg-module-popup profile-miniprofile-connection-reason-popup hidden\">";
		echo $reason;
		echo '</div>';
	}
	
} else {

	echo '<div class="profile-miniprofile-extras clearfix">';

	if (!empty($focus) || !empty($shares)) {
		echo "<a class=\"profile-miniprofile-interests-toggle\" href=\"#interests-$user->guid\" rel=\"popup\">See Interests</a>";
		echo "<div id=\"interests-$user->guid\" class=\"elgg-module elgg-module-popup profile-miniprofile-interests-popup hidden\">";
		echo $focus;
		echo $shares;
		echo '</div>';
	}

	if (!empty($reason)) {
		echo "<a class=\"profile-miniprofile-connection-reason-toggle\" href=\"#connection-reason-$user->guid\" rel=\"popup\">Why?</a>";
		echo "<div id=\"connection-reason-$user->guid\" class=\"elgg-module elgg-module-popup profile-miniprofile-connection-reason-popup hidden\">";
		echo $reason;
		echo '</div>';
	}

	echo '</div>';
}

echo '</div>';
echo '</div>';
