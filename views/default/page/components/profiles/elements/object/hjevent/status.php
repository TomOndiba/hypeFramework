<?php

$entity = elgg_extract('entity', $vars);

?>
<div class="status"><?php
if (($entity->calendar_end < time()) || ($entity->archived == 'yes'))
	echo elgg_echo('status:value:closed');
else
	echo elgg_echo('status:value:' . $entity->status);
?>
</div>

<div class="openclosed">
	<?php
	$open = elgg_view('output/date_and_time', array('value' => $entity->registration_open_on, 'entity' => $entity));
	$cutoff = elgg_view('output/date_and_time', array('value' => $entity->registration_cutoff, 'entity' => $entity));

	if (!$entity->registration_open_on)
		$open = "TBA";

	echo "Registration opens: $open <br />";

	if (!$entity->registration_cutoff)
		$cutoff = "TBA";

	echo "Registration cutoff: $cutoff  <br />";
	?>

</div>

<?php
$members_invited = (int) en_events_count_members_invited($entity);
$members_registered = (int) en_events_count_members_invited($entity, true);

$guests_invited = (int) en_events_count_guests_invited($entity);
$guests_registered = (int) en_events_count_guests_invited($entity, true);
?>
<div class="members"><a href="<?php echo $entity->getUrl(); ?>/invites/members/"><?php echo elgg_echo('en_events:status:registration:member', array($members_registered, $members_invited)); ?></a></div>
<div class="guests"><a href="<?php echo $entity->getUrl(); ?>/invites/guests/"><?php echo elgg_echo('en_events:status:registration:guest', array($guests_registered, $guests_invited)); ?></a></div>

