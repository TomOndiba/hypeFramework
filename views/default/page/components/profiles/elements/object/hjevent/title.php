<?php
$entity = elgg_extract('entity', $vars, false);

$title = elgg_view('output/url', array('text' => $entity->title, 'href' => $entity->getURL()));

$time_icon = '<strong class="en-theme-sprite sprite-calendar"></strong>';

if ($entity->calendar_start) {
	$starttime_str = '<a href="' . $entity->getUrl() . '?view=ical">' . $time_icon . '</a>' . 'From ' . elgg_view('output/date_and_time', array('value' => $entity->calendar_start, 'entity' => $entity));
	//$starttime_str = elgg_echo('hj:events:starttime', array($starttime));
}

if ($entity->calendar_end) {
	$endtime_str = '<br /> to ' . elgg_view('output/date_and_time', array('value' => $entity->calendar_end, 'entity' => $entity));
	//$endtime_str = elgg_echo('hj:events:endtime', array($endtime));
}

$icon = elgg_view_entity_icon($entity, 'small');
?>

<div class="title"><?php echo $title; ?></div>
<div class="shortdescription"><small><?php echo elgg_get_excerpt($entity->description); ?></small></div>
<div class="eventdates">
	<span class="startdate">
		<?php echo $starttime_str ?>
	</span>
	<span class="enddate">
		<?php echo $endtime_str ?>
	</span>
</div>
<div class="feature">
	<label>
		<input type="checkbox" id="feature_<?php echo $entity->guid; ?>" <?php if ($entity->featured == 'yes')
			echo "checked"; ?> /> <?php echo elgg_echo("en_events:feature"); ?>
	</label>
</div>
