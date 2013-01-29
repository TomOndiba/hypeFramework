<?php

$entity = $user = elgg_extract('entity', $vars);

echo '<div class="profile-miniprofile">';
echo '<div class="profile-miniprofile-inner">';
echo '<div class="profile-miniprofile-icon-wrapper">';

if (!$entity->isFriend()) {
	echo elgg_view('output/url', array(
		'text' => 'Request Connection',
		'href' => "action/friends/add?friend=$entity->guid",
		'class' => 'elgg-button elgg-button-action request-connection-btn'
	));
}

echo elgg_view('output/img', array(
	'src' => $entity->getIconURL('large'),
	'width' => 158,
	'height' => 158
));

echo '</div>';

echo '<ul class="profile-miniprofile-details">';

echo '<li class="name"><h3>' . $entity->name . '</h3></li>';
echo '<li class="jobtitle">' . $entity->jobtitle . '</li>';

$ia = elgg_set_ignore_access(true);
$account = accounts_get_users_account($entity->guid);
if ($account) {
	echo '<li class="company">' . $account->title . '</li>';
}
elgg_set_ignore_access($ia);

// Get the members networks
$memberships = get_users_membership($user->guid);

foreach ($memberships as $group) {
	if ($group->getSubtype() != 'network')
		continue;

	$networks[] = elgg_view('output/url', array(
		'href' => $group->getURL(),
		'text' => $group->code
			));
}

$networks_str = implode(', ', $networks);

if (!empty($networks_str)) {
	echo '<li class="network"><strong class="en-theme-sprite sprite-network"></strong>' . $networks_str . '</li>';
}
echo '</ul>';

if (isset($user->focustheme)) {
	if (!is_array($user->focustheme)) {
		$themes = array($user->focustheme);
	} else {
		$themes = $user->focustheme;
	}

	foreach ($themes as $t) {
		$class = "tag";
		if ($t == get_input('focus')) {
			$class = "$class highlight";
		}
		$theme_tags .= "<span class=\"$class\">$t</span>";
	}

	$themes_str = "<p>$user->firstname is focusing on:<br>$theme_tags</p>";
}

if (isset($user->thingstoshare)) {
	if (!is_array($user->thingstoshare)) {
		$shares = array($user->thingstoshare);
	} else {
		$shares = $user->thingstoshare;
	}

	foreach ($shares as $t) {
		$class = "tag";
		if ($t == get_input('interest')) {
			$class = "$class highlight";
		}
		$share_tags .= "<span class=\"$class\">$t</span>";
	}

	$shares_str = "<p>$user->firstname is willing to share:<br>$share_tags</p>";
}

if ($vars['full_view']) {

	echo '<div class="profile-miniprofile-interests">';
	echo $themes_str;
	echo $shares_str;
	echo '</div>';
} else {

	if (!empty($themes_str) || !empty($shares_str)) {
		echo '<div class="clearfix">';
		echo "<a class=\"profile-miniprofile-interests-toggle\" href=\"#interests-$user->guid\" rel=\"popup\">See Interests</a>";
		echo '<a class="profile-miniprofile-interests-why" href="#">Why?</a>';
		echo "<div id=\"interests-$user->guid\" class=\"elgg-module elgg-module-popup profile-miniprofile-interests-popup hidden\">";
		echo $themes_str;
		echo $shares_str;
		echo '</div>';
		echo '</div>';
	}
}


echo '</div>';
echo '</div>';
