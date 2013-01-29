<?php
$entity = elgg_extract('entity', $vars);

$ccds = en_events_get_event_ccds($entity);
if ($ccds) {
	echo "<p><strong>NDs:</strong>";
	foreach ($ccds as $ccd) {
		?>
		<a class="ccd" href="<?php echo $ccd->getUrl(); ?>"><?php echo $ccd->name; ?></a>&nbsp;

		<?php
	}
	echo "</p>";
}
else
	echo "<p>" . elgg_echo('en_events:noccds') . "</p>";


$pps = get_entity($entity->pointperson);
if ($pps) {
	if (!is_array($pps))
		$pps = array($pps);
	echo "<p><strong>Point person:</strong>";
	foreach ($pps as $pp) {
		?>
		<a class="pointperson" href="<?php echo $pp->getUrl(); ?>"><?php echo $pp->name; ?></a>&nbsp;

		<?php
	}
	echo "</p>";
}

$coordinators = elgg_get_entities_from_relationship(array(
	'relationship_guid' => $entity->guid,
	'relationship' => 'coordinator',
	'inverse_relationship' => true,
	'limit' => 0
		));
if ($coordinators) {
	echo "<p><strong>Coordinators:</strong>";
	foreach ($coordinators as $coordinator) {
		?>
		<a class="coordinator" href="<?php echo $coordinator->getUrl(); ?>"><?php echo $coordinator->name; ?></a>&nbsp;

		<?php
	}
	echo "</p>";
}
?>