<?php
$entity = elgg_extract('entity', $vars);
?>

<a id="agenda_<?php echo $entity->guid; ?>" href="#" title="Click for more detail..."><?php echo (int) en_events_count_agenda_items($entity); ?></a>

<div id="agenda_popup_<?php echo $entity->guid; ?>" class="popup agenda" style="display:none;">
    <input style="width: 70px;" type="button" class="elgg-button closeNotes" value="X Close"></input>
    <ul>
		<?php
		$agenda_items = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtype' => 'hjagenda',
			'container_guid' => $entity->guid,
			'limit' => 0,
			'order_by_metadata' => array('name' => 'calendar_start', 'direction' => 'asc', 'as' => 'integer')
				));

		foreach ($agenda_items as $item) {
			?>
			<li>
				<?php echo elgg_view_entity($item, array('popup' => true)); ?>
			</li>
			<?php
		}
		?>
    </ul>
</div>