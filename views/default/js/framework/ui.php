<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('framework');
	elgg.provide('framework.ui');

	framework.ui.init = function() {

		$('.elgg-menu > li')
		.each(function() {
			$child = $(this).children('a').first();
			$(this).attr('data-menu-tooltip', $child.attr('title'))
			$child.removeAttr('title');
		})
		
		$(document).tooltip({
			items : "[data-menu-tooltip],[data-tooltip]",
			track : false,
			content: function() {
				var element = $(this);
				if (element.is('[data-menu-tooltip]')) {
					return $(this).data('menu-tooltip');
				}
				if (element.is('[data-tooltip]')) {
					return $(this).data('tooltip');
				}
			},
			position: {
				my: 'center top',
				at: 'center bottom',
				using: function(position, feedback) {
					target = feedback['target'];
					element = feedback['element'];
					//$target = $(target.element);
					
					$(this).css({
						'left' : element.left + 'px',
						'top' : element.top + 'px',
					});
					
					$("<div>")
					.addClass( "ui-tooltip-arrow" )
					.addClass( feedback.vertical )
					.addClass( feedback.horizontal )
					.prependTo( this );
				}
			},
		});

	
		//	$('[role="framework-ui-controller"]')
		//	.live('click', function(event) {
		//		event.preventDefault();
		//		fn = $(this).attr('controller');
		//		if(typeof fn === 'function') {
		//			fn(event, $(this));
		//		}
		//	})
		//
		//	$('.framework-dynamic-content-trigger > a').live('click', function(event) {
		//		event.preventDefault();
		//		$elem = $(this);
		//		$parent = $(this).parent();
		//		$parent.siblings().removeClass('elgg-state-selected');
		//		$wrapper = $parent.closest('[role=dynamic-ui-wrapper]');
		//		$target = $wrapper.find('[role=dynamic-ui-content]');
		//		$parent.addClass('elgg-state-selected');
		//
		//		$target.html($('<div>').addClass('hj-ajax-loader').addClass('hj-loader-circle'));
		//
		//		elgg.post($elem.attr('href'), {
		//			dataType : 'json',
		//			success : function(response) {
		//				$target.html(response.output.body.content);
		//				elgg.trigger_hook('success', 'ajax', response);
		//			}
		//		});
		//	})
		//
		//	$('.framework-dynamic-content-trigger.elgg-state-selected > a').trigger('click');
	

		// Toggle the dropdown menu's
		$(".framework-ui-dropdown .framework-ui-control").live('click' , function (event) {

			$(this).toggleClass('elgg-menu-open').toggleClass('elgg-state-active');
			$(this).find('.framework-ui-toggle').toggleClass('elgg-state-active');

			var $slider = $(this).data('slider') || null;

			if (!$slider) {
				$slider = $(this).parent().find('.framework-ui-dropdown-slider');
				$(this).data('slider', $slider);
			}

			$('.framework-ui-dropdown-slider:visible').not($slider).slideUp('fast').removeClass('elgg-state-active');
			$('.framework-ui-dropdown .framework-ui-control.elgg-menu-open').not($(this)).removeClass('elgg-menu-open');
			$('.framework-ui-dropdown .framework-ui-control.elgg-state-active').not($(this)).removeClass('elgg-state-active');
			$('.framework-ui-dropdown .framework-ui-control:has(.framework-ui-toggle.elgg-state-active)').not($(this)).find('.framework-ui-toggle.elgg-state-active').removeClass('elgg-state-active');

			// close hovermenu if arrow is clicked & menu already open
			if ($slider.css('display') == "block") {
				$slider.fadeOut();
			} else {
				var offset = $(this).offset();
				var top = $(this).height() + offset.top + 4 + 'px';
				var right = (document.width - offset.left) - $(this).width() - 19 + 'px';

				$slider.appendTo('body')
				.css('position', 'absolute')
				.css("top", top)
				.css("right", right)
				.fadeIn('normal')
				.addClass('elgg-state-active');
			}

			event.preventDefault();
		});

		$(document).click(function(event) {
			if ($(event.target).parents(".framework-ui-dropdown").length == 0
				&& $(event.target).parents(".framework-ui-dropdown-slider").length == 0) {
				$(".framework-ui-dropdown-slider").slideUp('fast').removeClass('elgg-state-active');
				$(".framework-ui-dropdown .framework-ui-control").removeClass('elgg-menu-open').removeClass('elgg-state-active')
				$(".framework-ui-dropdown span.framework-ui-toggle").removeClass('elgg-state-active');
			}
		});

		$('.elgg-form-fieldset legend').live('click', function(event) {
			$(this).siblings().slideToggle('fast');
			$(this).find('.framework-ui-toggle').toggleClass('elgg-state-active');
		})

	}

	framework.ui.initMulti = function() {

		$(".framework-ui-widgets").sortable({
			items:                'div.elgg-module-widget.elgg-state-draggable',
			connectWith:          '.elgg-widgets',
			handle:               '.elgg-widget-handle',
			forcePlaceholderSize: true,
			placeholder:          'elgg-widget-placeholder',
			opacity:              0.8,
			revert:               500,
			stop:                 elgg.ui.widgets.move
		});

	}
	
	elgg.register_hook_handler('init', 'system', framework.ui.init);
	elgg.register_hook_handler('success', 'ajax', framework.ui.initMulti);

<?php if (FALSE) : ?></script><?php
endif;
?>