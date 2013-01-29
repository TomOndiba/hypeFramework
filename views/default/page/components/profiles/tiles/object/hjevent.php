<?php
$entity = $vars['entity'];

elgg_load_css('hj.events.base');
elgg_load_css('eventpage.css');

$icon = elgg_view_entity_icon($entity, 'medium');

$title = $entity->title;

$coordinators = elgg_get_entities_from_relationship(array(
	'relationship' => 'coordinator',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true
		));

foreach ($coordinators as $coord) {

	$coordinator_list[] = elgg_view('output/url', array(
		'text' => $coord->name,
		'href' => $coord->getURL(),
		'class' => 'dashboard-event-coordinator'
			));
}

$coordinators_str = "Event Coordinator " . implode(', ', $coordinator_list);

$networks = elgg_get_entities_from_relationship(array(
	'types' => 'group',
	'subtypes' => 'network',
	'relationship' => 'in_network',
	'relationship_guid' => $entity->guid,
		));

foreach ($networks as $net) {

	$networks_list[] = elgg_view('output/url', array(
		'text' => $net->code,
		'href' => $net->getURL(),
		'class' => 'dashboard-event-network'
			));
}

$networks_str = implode(', ', $networks_list);

$attendees = elgg_get_entities_from_relationship(array(
	'type' => 'user',
	'relationship' => 'confirmed_invite',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'limit' => 0
		));


foreach ($attendees as $att) {

	$attendee_list[] = '<li>' . elgg_view_entity_icon($att, 'medium', array(
				'width' => 33,
				'height' => 33
			)) . '</li>';
}

if (sizeof($attendee_list) > 0) {
	if (count($attendees) > 7) {
		$next = '<div id="event-attendees-' . $entity->guid . '-next" class="event-attendees-next"></div>';
		$prev = '<div id="event-attendees-' . $entity->guid . '-prev" class="event-attendees-prev"></div>';
	}

	$slider .= '<div class="dashboard-event-attendees-prefix">Who\'s<br />Attending</div>';
	$slider .= '<div class="dashboard-event-attendees-layout">';
	$slider .= '<ul id="event-attendees-' . $entity->guid . '" class="dashboard-event-attendees-list">' . implode('', $attendee_list) . '</ul>';
	$slider .= $prev . $next;
	$slider .= '</div>';
}

if (check_entity_relationship(elgg_get_logged_in_user_guid(), 'confirmed_invite', $entity->guid)) {
	$request_invitation = elgg_view('output/url', array(
		'text' => 'Attendance Confirmed',
		'href' => '#',
		'class' => 'elgg-button elgg-button-action dashboard-request-invitation disabled'
			));
} else if (check_entity_relationship(elgg_get_logged_in_user_guid(), 'invitee', $entity->guid)) {
	$request_invitation = elgg_view('output/url', array(
		'text' => 'Pending Invitation',
		'href' => '#',
		'class' => 'elgg-button elgg-button-action dashboard-request-invitation'
			));
} else {
	$request_invitation = elgg_view('output/url', array(
		'text' => 'Request Invitation',
		'href' => '#',
		'class' => 'elgg-button elgg-button-action dashboard-request-invitation'
			));
}
?>

<div class="eventpage dashboard-event-profile">
	<div class="body clearfix">
		<div class="col col1">
			<?php echo $icon ?>
		</div>
		<div class="col col2">
			<div>
				<h2 class="dashboard-event-name"><?php echo $title ?></h2>
				<span class="dashboard-event-coordinators"><?php echo $coordinators_str ?></span>
			</div>

			<div>
				<ul class="dashboard-event-details">
					<?php if (isset($entity->region) && !empty($entity->region)) : ?>
						<li><strong class="en-theme-sprite sprite-location"></strong> <?php echo elgg_echo('region:value:' . $entity->region); ?></li>
					<?php endif; ?>
					<li><strong class="en-theme-sprite sprite-calendar"></strong><?php echo date('M d, Y', $entity->calendar_start) ?></li>
					<li><strong class="en-theme-sprite sprite-network"></strong><?php echo $networks_str ?></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="footer clearfix">
		<div class="dashboard-event-attendees clearfix">
			<?php echo $prefix ?>
			<div class="dashboard-attendee-slider">
				<?php echo $slider ?>
			</div>
			<div class="dashboard-event-registration">
				<div class="dashboard-request-invitation-wrapper">
					<?php echo $request_invitation ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	$(document).ready(function() {
		var uid = <?php echo $entity->guid ?>;

		if (!window.sliders) {
			window.sliders = new Array();
		}

		if (window.sliders.indexOf('attendees'+uid) == -1) {
			$('#event-attendees-' + uid)
			.bxSlider({
				pager : false,
				controls : true,
				nextSelector : '#event-attendees-' + uid + '-next',
				prevSelector : '#event-attendees-' + uid + '-prev',
				infiniteLoop: false,
				hideControlOnEnd: true,
				minSlides : 1,
				maxSlides : 7,
				slideWidth : 33,
				slideMargin : 5
			});
			window.sliders.push('attendees'+uid);
		}
	})
	
</script>