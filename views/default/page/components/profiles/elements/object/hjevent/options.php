<?php
$entity = elgg_extract('entity', $vars);

$options = "";
$base = elgg_get_site_url();
if ($entity->archived == 'yes') {
	$menu = array(
		'clone' => $entity->getUrl() . "/clone/",
	);
} else if ($entity->calendar_end < time()) {
	$menu = array(
		'clone' => $entity->getUrl() . "/clone/",
			//'archive' => "{$base}events/archive/" . $entity->guid,
	);
} else {
	$menu = array(
		'edit' => "{$base}events/edit/" . $entity->guid,
		//'associate' => "{$base}events/associate/" . $entity->guid,
		'clone' => $entity->getUrl() . "/clone/",
		'delete' => elgg_add_action_tokens_to_url("{$base}action/framework/entities/delete?e=" . $entity->guid, false),
		//'archive' => "{$base}events/archive/" . $entity->guid,
		'locations' => $entity->getUrl() . "/locations/",
		'agenda' => $entity->getUrl() . "/agenda/",
		'resources' => $entity->getUrl() . "/resources/",
		'invites' => $entity->getUrl() . "/invites/members/",
		'reg' => $entity->getUrl() . "/reg/",
		'eventpage' => $entity->getUrl() . "/eventpage/",
		'communications' => $entity->getUrl() . "/communication/registration",
	);
}

foreach ($menu as $option => $url) {
	if ($entity->canEdit()) {

		$view = 'url';
		if ($option == 'delete')
			$view = 'confirmlink';

		$options .= elgg_view("output/$view", array('href' => $url, 'text' => elgg_echo("en_events:event:option:$option")));
		$options .= '<br />';
	}
	//$options .= "<a href=\"$url\">".elgg_echo("en_events:event:option:$option") . "</a>, ";
}

echo $options;
?>

<script type="text/javascript">

	$(document).ready(function(){

		$("#agenda_<?php echo $entity->guid; ?>").click(function() {
			$("#agenda_popup_<?php echo $entity->guid; ?>").fadeIn();
		});

		$("#agenda_popup_<?php echo $entity->guid; ?>").click(function() {
			$("#agenda_popup_<?php echo $entity->guid; ?>").fadeOut();
		});

		$("#locations_<?php echo $entity->guid; ?>").click(function() {
			$("#locations_popup_<?php echo $entity->guid; ?>").fadeIn();
		});

		$("#locations_popup_<?php echo $entity->guid; ?>").click(function() {
			$("#locations_popup_<?php echo $entity->guid; ?>").fadeOut();
		});

		$("#resources_<?php echo $entity->guid; ?>").click(function() {
			$("#resources_popup_<?php echo $entity->guid; ?>").fadeIn();
		});

		$("#resources_popup_<?php echo $entity->guid; ?>").click(function() {
			$("#resources_popup_<?php echo $entity->guid; ?>").fadeOut();
		});

		$("#feature_<?php echo $entity->guid; ?>").change(function() {
			$.post( elgg.config.wwwroot + "action/en/events/featuretoggle",
			{
				entity_guid: <?php echo $entity->guid; ?>,
				__elgg_token: elgg.security.token.__elgg_token,
				__elgg_ts: elgg.security.token.__elgg_ts
			});
		});
	});

</script>
