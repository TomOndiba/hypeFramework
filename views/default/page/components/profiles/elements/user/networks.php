<?php

$entity = elgg_extract('entity', $vars);

// Get the members networks
$memberships = get_users_membership($entity->guid);

foreach ($memberships as $group) {
	if ($group->getSubtype() != 'network')
		continue;

	$networks[] = elgg_view('output/url', array(
		'href' => $group->getURL(),
		'text' => $group->code
			));
}

if ($networks) {
	$networks_str = implode(', ', $networks);
} else {
	return true;
}

if (!elgg_in_context('table-view')) {
	echo '<div class="user-detail-networks"><strong class="en-theme-sprite sprite-network"></strong>' . $networks_str . '</div>';
} else {
	echo '<div class="user-detail-networks">' . $networks_str . '</div>';
}
