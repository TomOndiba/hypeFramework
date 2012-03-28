<?php
$content = elgg_extract('content', $vars);
?>

<div class="hj-overlay-sidebar">
	<div class="hj-overlay-sidebar-open">
		<a href="" title="<?php echo elgg_echo('hj:showpanel') ?>"><?php echo elgg_view_icon('hjtoggler-right') ?></a>
	</div>
	<div class="hj-overlay-sidebar-content hidden">
		<div class="hj-overlay-sidebar-close">
			<a href="" title="<?php echo elgg_echo('hj:hidepanel')?>"><?php echo elgg_view_icon('hjtoggler-left') ?></a>
		</div>
		<?php echo $content ?>
		<?php echo $nav ?>
	</div>
</div>
