<?php
$entity = $vars['entity'];

$icon = elgg_view('output/img', array(
	'src' => $entity->getIconURL('medium'),
	'class' => 'resource-profile-icon'
		));

$title = $entity->title;
$options = array(
	'relationship' => 'attachment',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => false,
);

$attached_to_entities = elgg_get_entities_from_relationship($options);

if ($attached_to_entities) {
	foreach ($attached_to_entities as $ent) {

		$attached_to_list[] = elgg_echo('item:object:' . $ent->getSubtype()) . ': ' . elgg_view('output/url', array(
					'text' => $ent->title,
					'href' => $ent->getURL()
				)) . ' by ' . elgg_view('output/url', array(
					'text' => $ent->getOwnerEntity()->name,
					'href' => $ent->getOwnerEntity()->getURL()
				));
	}

	$attached_to_str = '<b>Attached to: </b><br />';
	$attached_to_str .= implode('<br />', $attached_to_list);
}

$time_str = date('g:ia F d, Y', $entity->time_created);

$visit_link = elgg_view('output/url', array(
	'text' => 'Visit Resource',
	'href' => $enttity->address,
	'target' => '_blank'
		));
?>

<div class="resource-profile clearfix">
	<div class="resource-icon">
		<?php echo $icon ?>
	</div>
	<div class="resource-details">
		<ul>
			<li><h3><?php echo $title ?></h3></li>
			<?php if (!empty($attached_to_str)) : ?>
				<li class="resource-attachments">
					<a class="resource-attachments-toggle" href="#resource-attachments-<?php echo $entity->guid ?>" rel="toggle">Details</a>
					<div id="resource-attachments-<?php echo $entity->guid ?>" class="hidden"><?php echo $attached_to_str ?></div>
				</li>
			<?php endif; ?>
			<li>
				<span class="resource-uploaded"><?php echo $time_str ?></span>
				<span class="resource-download"><?php echo $visit_link ?></span>
			</li>
		</ul>
	</div>
</div>
