<?php
if (elgg_in_context('table-view')) {
	return true;
}

$entity = elgg_extract('entity', $vars);

$ia = elgg_set_ignore_access(true);
$account = accounts_get_users_account($entity->guid);
if ($account) {
	echo '<div class="user-detail-company">' . $account->title . '</div>';
}
elgg_set_ignore_access($ia);