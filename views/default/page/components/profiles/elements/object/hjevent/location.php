<?php
$entity = elgg_extract('entity', $vars);
?>
<a id="locations_<?php echo $entity->guid; ?>" href="#" title="Click for more detail..."><?php echo (int) en_events_count_locations($entity); ?></a>

<div id="locations_popup_<?php echo $entity->guid; ?>" class="popup locations" style="display:none;">
    <input style="width: 70px;" type="button" class="elgg-button closeNotes" value="X Close"></input>
    <ul>
		<?php
		$location_items = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtype' => 'hjlocation',
			'container_guid' => $entity->guid,
			'limit' => 0,
				));
		foreach ($location_items as $item) {
			?>
			<li>
				<?php echo elgg_view_entity($item, array('popup' => true)); ?>
			</li>
			<?php
		}
		?>
    </ul>

</div>