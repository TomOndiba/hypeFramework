<?php
$entity = elgg_extract('entity', $vars);
?>
<a id="resources_<?php echo $entity->guid; ?>" href="#" title="Click for more detail..."><?php echo (int) en_events_count_resources($entity); ?></a>
<div id="resources_popup_<?php echo $entity->guid; ?>" class="popup resources" style="display:none;">
    <input style="width: 70px;" type="button" class="elgg-button closeNotes" value="X Close"></input>
    <ul>
		<?php
		$files = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'subtype' => array('file', 'bookmarks'),
			'relationship' => 'attachment',
			'relationship_guid' => $entity->guid,
			'inverse_relationship' => true,
			'limit' => 0
				));
		foreach ($files as $item) {
			?>
			<li>
				<?php echo elgg_view_entity($item, array('popup' => true)); ?>
			</li>
			<?php
		}
		?>
    </ul>
</div>
