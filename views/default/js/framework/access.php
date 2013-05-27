<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('framework');
	elgg.provide('framework.access');

	framework.access.init = function() {

		$('.access-select a')
		.live('click', function(e) {
			e.preventDefault();

			$elem = $(this);

			elgg.action($elem.attr('href'), {
				beforeSend : function() {
					$elem.addClass('loading');
				},
				complete : function() {
					$elem.removeClass('loading');
				},
				success : function(response) {

					if (response.status >= 0) {
						$elem.siblings().removeClass('elgg-state-selected');
						$elem.addClass('elgg-state-selected');
						$elem.closest('.access-group').find('.access-select-toggle').html($('span', $elem).eq(0).clone()).attr('title', $('i', $elem).text());
					}

				}
			})
		})
	}

	elgg.register_hook_handler('init', 'system', framework.access.init);

<?php if (FALSE) : ?></script><?php
endif;
?>
