<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('framework');
	elgg.provide('framework.ui');

	framework.ui.init = function() {
	}

	elgg.register_hook_handler('init', 'system', framework.ui.init);

<?php if (FALSE) : ?></script><?php
endif;
?>
