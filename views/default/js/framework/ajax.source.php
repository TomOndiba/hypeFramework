<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>
elgg.provide('framework.ajax.base');

framework.ajax.base.init = function() {

	window.loader = '<div class="hj-ajax-loader hj-loader-circle"></div>';

	$('.hj-ajaxed-add')
	.unbind('click')
	.bind('click', framework.ajax.base.view);

	$('.hj-ajaxed-edit')
	.unbind('click')
	.bind('click', framework.ajax.base.view);

	$('.hj-ajaxed-view')
	.unbind('click')
	.bind('click', framework.ajax.base.view);

	$('.hj-ajaxed-remove')
	.die()
	.unbind('click')
	.bind('click', framework.ajax.base.remove);

	$('.hj-ajaxed-delete')
	.die()
	.unbind('click')
	.bind('click', framework.ajax.base.remove);

	$('.hj-ajaxed-save')
	.attr('onsubmit','')
	.unbind('submit')
	.bind('submit', framework.ajax.base.save);

	$('.hj-ajaxed-addwidget')
	.unbind('click')
	.bind('click', framework.ajax.base.addwidget);

	$('.elgg-widget-edit > form')
	.die()
	.bind('submit', framework.ajax.base.reloadwidget);

	$('.hj-ajaxed-gallery')
	.unbind('click')
	.bind('click', framework.ajax.base.gallery);

	$('a.elgg-widget-delete-button')
	.die()
	.live('click', elgg.ui.widgets.remove);

	if ($('.elgg-input-date').length) {
		elgg.ui.initDatePicker();
	}

	$('.hj-pagination-next')
	.unbind()
	.bind('click', framework.ajax.base.paginate_next);

	framework.ajax.base.initEntityMenu();

	var current_time = new Date();
	var current_timestamp = current_time.getTime();

	framework.ajax.base.triggerRefresh();

}

framework.ajax.base.view = function(event) {
	event.preventDefault();

	var action = $(this).attr('href'),
	values = $(this).data('options'),
	targetContainer = '#'+values.params.target,
	rel = $(this).attr('rel');

	if (rel == 'fancybox') {
		$.fancybox({
			content : window.loader
		});
	} else {
		$(targetContainer)
		.fadeIn()
		.html(window.loader);
	}

	elgg.action(action, {
		data : values,
		success : function(output) {
			if (rel == 'fancybox') {
				$.fancybox({
					padding: '30',
					content : output.output.data,
					autoDimensions : false,
					width : values.params.fbox_x || '500',
					height : values.params.fbox_y || '500',
					onComplete : function() {
						elgg.trigger_hook('success', 'ajax');
					}
				});
				$.fancybox.resize();
			} else {
				$(targetContainer)
				.slideDown(800)
				.html(output.output.data);
				elgg.trigger_hook('success', 'ajax');

			}
		}
	});
}

framework.ajax.base.remove = function(event) {
	var action = $(this).attr('href'),
	subjectGuid = $(this).attr('id').replace('hj-ajaxed-remove-', ''),
	targetContainer = 'elgg-object-'+subjectGuid,
	options = $(this).data('options'),
	sourceContainer = options.params.source,
	confirmText = $(this).attr('rel') || elgg.echo('question:areyousure');

	if (!subjectGuid.length) {
		return true;
	}

	$(this).attr('rel', '');
	$(this).attr('confirm', '');

	event.preventDefault();

	if (confirm(confirmText)) {
		elgg.system_message(elgg.echo('framework:processing'));
		elgg.action(action, {
			success : function(output) {
				$('[id="'+targetContainer+'"]')
				.each(function() {
					$(this).fadeOut(800, function() {
						$(this).remove()
					})
				});
				$('[id="'+sourceContainer+'"]')
				.each(function() {
					$(this).fadeOut(800, function() {
						$(this).remove()
					})
				});

			}
		});

	}
}

framework.ajax.base.gallery = function(event) {
	event.preventDefault();

	var values = $(this).data('options'),
	targetContainer = $(this).attr('href'),
	rel = $(this).attr('rel');

	var fbox_content = $(targetContainer).html();
	$.fancybox({
		content : fbox_content,
		autoDimensions : false,
		'width' : values.params.fbox_x || window.width - 200,
		'height' : values.params.fbox_y || window.height - 200,
		'padding' : '20'
	});
}

framework.ajax.base.save = function(event) {
	event.preventDefault();

	var values = new Object();
	values = $.parseJSON($(this).find('input[name="params"]').val());

	if (values.target) {
		var target = values.target;
	} else {
		var target = 'elgg-object-'+values.entity_guid;
	}

	if (framework.fieldcheck.init($(this))) {
		$.fancybox({
			content : window.loader
		});
		$.fancybox.resize();
		values.push_context = 'fancybox';
		var params = ({
			dataType : 'json',
			success : function(output) {
				$.fancybox.close();
				if (values.event == 'update') {
					$('[id="' + target + '"]')
					.each(function() {
						$(this)
						.fadeIn()
						.html(output.output.data);
					});
				} else {
					if (values.dom_order == 'prepend') {
						$('[id="' + target + '"]')
						.each(function() {
							$(this).prepend(output.output.data);
						});
					} else {
						$('[id="' + target + '"]')
						.each(function() {
							$(this).append(output.output.data);
						});

					}
				}
				elgg.trigger_hook('success', 'ajax');
			}
		});

		if ($(this).hasClass('hj-ajaxed-file-save')) {
			params.iframe = true;
		} else {
			params.iframe = false;
		}
		$(this).ajaxSubmit(params);
	} else {
		event.preventDefault();
	}

}

framework.ajax.base.addwidget = function(event) {
	event.preventDefault();
	var action = $(this).attr('href'),
	values = $(this).data('options');

	elgg.system_message(elgg.echo('framework:processing'));

	elgg.action(action, {
		data : values,
		success: function(json) {
			$('#elgg-widget-col-1').prepend(json.output);
			elgg.trigger_hook('success', 'ajax');
		}
	});
}

framework.ajax.base.reloadwidget = function(event) {
	event.preventDefault();
	event.stopPropagation();

	var action = $(this).attr('action');
	var widgetGuid = $(this).parent().attr('id').replace('widget-edit-','');

	var sourceContainer = $('#elgg-widget-'+widgetGuid);

	$(sourceContainer)
	.removeClass()
	.wrap('<div></div>')
	.html(window.loader);

	elgg.action(action, {
		data : $(this).serialize(),
		success : function() {
			elgg.action('action/framework/widget/load', {
				data : {
					e : widgetGuid
				},
				success : function(output) {
					$(sourceContainer)
					.parent('div')
					.slideDown(800)
					.html(output.output.data);
					elgg.trigger_hook('success', 'ajax');

				}
			});
		}
	});
}

framework.ajax.base.paginate_next = function(event) {
	event.preventDefault();
	var button = $(this),
	list_id = $(this).attr('rel'),
	list = $('#' + list_id);

	if ($(window).data('ajaxready') == false) return;
	var loader = $('<span>').addClass('hj-ajax-loader hj-loader-bar');
	var last = list.find('li.elgg-item').last();

	if (!last.length) {
		last = list;
	}
	$(window).data('ajaxready', false);
	var guid = last.attr('id').replace('elgg-object-', '');
	var pagination_data = framework.data.lists[list_id].pagination;
	var url = pagination_data.baseurl;
	framework.data.lists[list_id].sync = 'old';
	button.prepend(loader);

	elgg.ajax(url, {
		data : {
			listdata : framework.data.lists[list_id]
		},
		dataType : 'json',
		type : 'POST',
		success : function(output) {
			if (output.output) {
				$.each(output.output, function(key, val) {
					var append_guid = parseInt(val['guid']);
					if (!isNaN(append_guid)) {
						if (pagination_data.inverse_order) {
							framework.data.lists[list_id].items.unshift(append_guid);
							list.prepend(val['html']);
						} else {
							framework.data.lists[list_id].items.push(append_guid);
							list.append(val['html']);
						}
					}
				});
				$(window).data('ajaxready', true);
				elgg.trigger_hook('success', 'ajax');
			}
			if (!output.output || framework.data.lists[list_id].items.length >= pagination_data.count || (output.output && (output.output.length < pagination_data.limit || pagination_data.limit == 0))) {
				$(window).data('ajaxready', true);
				button.hide();
			}
			loader.remove();
		}
	});
}

framework.ajax.base.initEntityMenu = function() {

	$('.hj-hover-menu-toggler')
	.each(function() {
		var button = $(this),
		item = $(this).closest('.elgg-item');

		if (!item.length) {
			item = $(this).closest('[id^="elgg-object-"]');
		}

		if (!item.length) {
			item = $(this).closest('[id^="elgg-"]');
		}

		var menu = item.find('.hj-entity-head-menu').first();

		item
		.unbind('.hjhovermenu')
		.bind('mouseenter.hjhovermenu', function(event) {
			button
			.unbind('click')
			.bind('click', function(event2) {
				menu
				.toggle()
				.unbind('click')
				.bind('click', function(event) {
					menu.hide()
				})
			})
			.show();
		})
		.bind('mouseleave.hjhovermenu', function(event) {
			button.fadeOut();
			if (menu.css("display") == "block") {
				menu.hide();
			}
		})
	})
}

framework.ajax.base.triggerRefresh = function() {
	var time = 60000;
	var time2 = 90000;
	var time_current = new Date().getTime();
	if (time_current - window.hjLastUpdate >= time) {
		framework.ajax.base.listRefresh();
	}
	window.hjLastUpdate = new Date().getTime();
}

framework.ajax.base.listRefresh = function(list_id) {
	var lists = new Object();
	if (list_id) {
		lists[list_id] = framework.data.lists[list_id];
	} else {
		lists = framework.data.lists;
	}

	$.each(lists, function(k, v) {
		v.sync = 'new';
		if (v.pagination.baseurl.length) {
			var url = v.pagination.baseurl;
		} else {
			var url = "framework/sync/";
		}
		var list = $('#' + k);
		var k_id = k;

		elgg.ajax(url, {
			data : {
				listdata : v
			},
			dataType : 'json',
			type : 'POST',
			success : function(output) {
				if (output.output && output.output.length > 0) {
					$.each(output.output, function(key, val) {
						var append_guid = parseInt(val['guid']);
						if (!isNaN(append_guid)) {
							if (v.pagination.inverse_order) {
								framework.data.lists[k_id].items.push(append_guid);
								list.append(val['html']);
							} else {
								framework.data.lists[k_id].items.unshift(append_guid);
								list.prepend(val['html']);
							}
						}
					});

					$(window).data('ajaxready', true);
					elgg.trigger_hook('success', 'ajax');
				}
			}
		});
	});
}

framework.ajax.base.initSlideshow = function() {

	$('.hj-carousel-wrapper')
	.each(function() {
		framework.ajax.base.paginate($(this));
	});
}

framework.ajax.base.paginate = function(selector) {

	var target = selector.find('.hj-carousel').last().attr('id');
	var wrapper = '#' + selector.attr('id');

	if (!window.slider) {
		window.slider = new Object();
	}

	$(wrapper).find('.hj-carousel-content').html($('<ul id="temp-' + target + '" class=\"elgg-list\"></ul>').html($('#' + target).clone().html()));
	framework.ajax.base.init();
	if (window.slider[target]) {
		$(wrapper).find('.hj-carousel-pagination').children('div').each(function() {
			$(this).html('');
		});
		window.slider[target].destroyShow();
	}

	if (!window.slider_pos) {
		window.slider_pos = new Object();
	}
	if (!window.slider_pos[target]) {
		window.slider_pos[target] = 0;
	}

	var new_slider = $("#temp-" + target, $(wrapper)).bxSlider({
		pager : true,
		pagerType : 'short',
		pagerSelector : wrapper + ' .hj-carousel-pager',
		//hideControlOnEnd : true,

		prevText : elgg.echo('previous'),
		prevImage : elgg.get_site_url() + 'mod/hypeFramework/graphics/buttons/arrow-left.png',
		prevSelector : wrapper + ' .hj-carousel-prev',

		nextText : elgg.echo('next'),
		nextImage : elgg.get_site_url() + 'mod/hypeFramework/graphics/buttons/arrow-right.png',
		nextSelector : wrapper + ' .hj-carousel-next',

		onLastSlide : function (a,b,c) {
			$(wrapper).find('.hj-pagination-next').trigger('click');
			$(wrapper + ' .hj-carousel-pager').append($('<div class="hj-ajax-loader hj-loader-indicator"></div>').fadeIn().delay(1000).fadeOut(3000));
			window.slider_pos[target] = a;
		},

		startingSlide : window.slider_pos[target] || 0,

		infiniteLoop : false
	});

	window.slider[target] = new_slider;

}

elgg.register_hook_handler('init', 'system', framework.ajax.base.init);
//elgg.register_hook_handler('success', 'ajax', elgg.security.refreshToken, 1);
elgg.register_hook_handler('success', 'ajax', elgg.ui.widgets.init);
elgg.register_hook_handler('success', 'ajax', framework.ajax.base.init);
elgg.register_hook_handler('success', 'ajax', framework.ajax.base.triggerRefresh);

elgg.register_hook_handler('init', 'system', framework.ajax.base.initSlideshow);
elgg.register_hook_handler('success', 'ajax', framework.ajax.base.initSlideshow);

<?php if (FALSE) : ?></script><?php
endif;
?>