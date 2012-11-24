<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

elgg.provide('framework');
elgg.provide('framework.data');
elgg.provide('framework.data.lists');
elgg.provide('framework.resources');
elgg.provide('framework.ajax');
elgg.provide('framework.ajax.scenarios');
elgg.provide('framework.loaders');

framework.loaders.circle = $('<div>').attr('id', 'framework-loader-circle').addClass('hj-ajax-loader').addClass('hj-loader-circle');
framework.loaders.indicator = $('<div>').attr('id', 'framework-loader-indicator').addClass('hj-ajax-loader').addClass('hj-loader-indicator');
framework.loaders.bar = $('<div>').attr('id', 'framework-loader-bar').addClass('hj-ajax-loader').addClass('hj-loader-bar');

framework.ajax.fetchOnSystemInit = function() {
	if (framework.resources.length <= 0) {
		framework.resources = new Array();
	} else {
		return;
	}

	// get loaded scripts
	$('script')
	.each(function() {
		framework.resources.push($(this).attr('src'));
	})

	$('link')
	.each(function() {
		framework.resources.push($(this).attr('href'));
	})
}

/**
 * Check if any new scripts and stylesheets need to be loaded
 */
framework.ajax.fetchOnAjaxSuccess = function(name, type, params, value) {
	if (!params) return;
	var resources = params.resources;

	if (!resources) return;

	for (var i=0;i < resources.js.length;i++) {
		if ($.inArray(resources.js[i], framework.resources) == -1) {
			$('head').append($('<script>').attr('type', 'text/javascript').attr('src', resources.js[i]));
			framework.resources.push(resources.js[i]);
		}
	}

	for (var i=0;i < resources.css.length;i++) {
		if ($.inArray(resources.css[i], framework.resources) == -1) {
			$('head').append($('<link>').attr('rel', 'stylesheet').attr('type', 'text/css').attr('href', resources.css[i]));
			framework.resources.push(resources.css[i]);
		}
	}
}

framework.ajax.init = function() {

	$('[data-scenario]')
	.live('click', function(event) {
		framework.ajax.scenarios[$(this).data('scenario')]($(this), event);
	})

	$('.hj-ajaxed-cancel-form')
	.live('click', function(event) {
		event.preventDefault();
		if ($(this).closest('#fancybox-content').length > 0) {
			$.fancybox.close();
		} else {
			window.history.back();
		}
	})
}

framework.ajax.scenarios.newListItem = function($element, event) {

	event.preventDefault();

	var $dialog = $('<div style="display:none"></div>')
	.html(framework.loaders.circle)
	.appendTo('body');

	var buttons = new Object();
	buttons[elgg.echo('cancel')] = function() {
		$(this).dialog('close');
	}
				
	$dialog.dialog({
		close: function(event, ui) {
			$dialog.remove();
		},
		buttons: buttons,
		title: elgg.echo('framework:ajax:loading_form'),
		width:650,
		maxHeight:500,
		autoResize:true
	});

	var data = new Object();
	var data = $(this).data();
	data.view = 'json';

	elgg.post($element.attr('href'), {
		data : data,
		dataType: 'json',
		success : function(response) {
			$dialog.html(response.output.body.content);
				
			$title = $dialog.find('.elgg-head');
			var title_text = $title.text();
			if (!title_text.length) {
				title_text = response.output.title;
			}
			$submit_button = $dialog.find('input[type=submit]');
			$cancel_button = $dialog.find('input.hj-ajaxed-cancel-form');
			submit_button_text = $submit_button.val();
			cancel_button_text = $cancel_button.val();

			var buttons = new Object();
			buttons[submit_button_text] = function() {
				$dialog.find('form').trigger('submit');
			}
			buttons[cancel_button_text] = function() {
				$(this).dialog('close');
			}

			$dialog.dialog({
				title: title_text,
				buttons: buttons
			});

			$submit_button.remove();
			$cancel_button.remove();
			$title.remove();

			var params = new Object();
			params.event = 'getForm';
			params.response = response;
			params.data = data;

			elgg.trigger_hook('success', 'ajax', params, true);

			$dialog
			.find('form')
			.submit(function(eventSubmit) {

				$form = $(this);
				
				if (!$element.data('list')) {
					return true;
				}

				var data = $element.data();
				data['X-Requested-With'] = 'XMLHttpRequest';
				data['view'] = 'json';
					
				var params = ({
					beforeSend : function() {
						$dialog.html(framework.loaders.circle);
						$dialog.focus();
						$dialog.dialog({buttons:null});
						return true;
					},
					dataType : 'json',
					data : data,
					success : function(response, status, xhr) {
						$dialog.dialog('close');

						var hookParams = new Object();
						hookParams.event = 'submitForm';
						hookParams.response = response;
						hookParams.data = $form.serialize();

						elgg.system_message(response.system_messages.success);
						elgg.trigger_hook('success', 'ajax', hookParams, true);

						var refreshParams = new Object();
						refreshParams.element = $element;
						refreshParams.list = $element.data('list');
						elgg.trigger_hook('reload', 'lists', refreshParams, true);
					}
				});

				if ($form.find('input[type=file]')) {
					params.iframe = true;
				} else {
					params.iframe = false;
				}

				$form.ajaxSubmit(params);

				return false;
			})

		}
	})

}

framework.ajax.scenarios.paginateList = function($element, event) {

	event.preventDefault();
	
	var refreshParams = new Object();
	refreshParams.element = $element;
	refreshParams.list = $element.data('list');
	elgg.trigger_hook('reload', 'lists', refreshParams, true);

	window.history.pushState(null, null, $element.attr('href'));
	window.location.hash = $element.data('list');
	
}
	
framework.ajax.success = function(hook, type, params) {

	//	if (params.data.length && params.data.list) {
	//		framework.ajax.refreshList(params.data.list, params);
	//	}

}

framework.ajax.getUpdatedList = function(hook, type, params) {

	var list_id = params.list_id;
	var $element = params.element;

	var lists = new Object();
	if (list_id) {
		lists[list_id] = framework.data.lists[list_id];
	} else {
		lists = framework.data.lists;
	}

	if ($element) {
		var postURL = $element.attr('href');
	} else {
		var postURL = document.URL;
	}
	$.each(lists, function(listId, currentList) {
		var $list = $('#' + listId);
		currentList.view = 'json';
		elgg.post(postURL, {
			beforeSend : function() {
				$("body").css("cursor", "progress");
				$list.prepend($('<li>').html(framework.loaders.bar));
			},
			data : currentList,
			dataType : 'json',
			complete : function() {
				$("body").css("cursor", "auto");
				$list.children('li').first().hide();
			},
			success : function(response) {

				var updatedList, $firstItem, $lastItem;

				updatedList = response.output.body.content;
				$firstItem = $list.children('li').first();
				$lastItem = $firstItem;
					
				$.each(updatedList.item_uids, function(pos, itemUid) {
					if ($.inArray(itemUid, framework.data.lists[listId].item_uids) < 0) {
						$lastItem.after(updatedList.item_views["uid" + itemUid]);
					}
					$lastItem = $lastItem.next();
				})
					
				$.each(framework.data.lists[listId].item_uids, function(pos, itemUid) {
					if ($.inArray(itemUid, updatedList.item_uids) < 0) {
						$list.find('li[data-uid=' + itemUid + ']').remove();
					}
				})
					
				$firstItem.remove();
				framework.data.lists[listId].item_uids = updatedList.item_uids;

				if (updatedList.pagination.length) {
					console.log(updatedList.pagination);
					$('.elgg-pagination[data-list="' + listId + '"]').replaceWith(updatedList.pagination);
				}
			}
		})
	}
)
}


//	framework.loaders.circle = '<div class="hj-ajax-loader hj-loader-circle"></div>';
//
//	$('.framework-ajax-view')
//	.live('click', framework.ajax.view);
//
//
//	$('.hj-ajaxed-add')
//	.unbind('click')
//	.bind('click', framework.ajax.view);
//
//	$('.hj-ajaxed-edit')
//	.unbind('click')
//	.bind('click', framework.ajax.view);
//
//	$('.hj-ajaxed-view')
//	.unbind('click')
//	.bind('click', framework.ajax.view);
//
//	$('.hj-ajaxed-remove')
//	.die()
//	.unbind('click')
//	.bind('click', framework.ajax.remove);
//
//	$('.hj-ajaxed-delete')
//	.die()
//	.unbind('click')
//	.bind('click', framework.ajax.remove);
//
//	$('.hj-ajaxed-save')
//	.attr('onsubmit','')
//	.unbind('submit')
//	.bind('submit', framework.ajax.save);
//
//	$('.hj-ajaxed-addwidget')
//	.unbind('click')
//	.bind('click', framework.ajax.addwidget);
//
//	$('.elgg-widget-edit > form')
//	.die()
//	.bind('submit', framework.ajax.reloadwidget);
//
//	$('.hj-ajaxed-gallery')
//	.unbind('click')
//	.bind('click', framework.ajax.gallery);
//
//	$('a.elgg-widget-delete-button')
//	.die()
//	.live('click', elgg.ui.widgets.remove);
//
//	if ($('.elgg-input-date').length) {
//		elgg.ui.initDatePicker();
//	}
//
//	$('.elgg-widgets-add-panel li.elgg-state-available').unbind('click').click(elgg.ui.widgets.add);
//
//	$('.hj-ajaxed-cancel-form')
//	.unbind('click')
//	.bind('click', function(event) {
//		event.preventDefault();
//
//		if (!$.fancybox.close()) {
//			framework.location.href = document.referrer;
//		}
//
//	});
//
//	$('.hj-pagination-next')
//	.unbind()
//	.bind('click', framework.ajax.paginateNext);
//
//	var current_time = new Date();
//	var current_timestamp = current_time.getTime();
//
//	framework.ajax.triggerRefresh();
//
//	$('.hj-entity-head-menu')
//	.live('click', function() {$(this).hide()});
//
//	$('body')
//	.unbind('click.headmenu')
//	.bind('click.headmenu', function() {
//		$('.hj-entity-head-menu').each(function() {$(this).hide()});
//	})
//
//	$('.hj-overlay-sidebar-open')
//	.unbind('click')
//	.bind('click', function(event) {
//		event.preventDefault();
//		var open = $(this);
//		open.hide();
//		$(this).closest('.hj-overlay-sidebar').find('.hj-overlay-sidebar-content').show().find('.hj-overlay-sidebar-close').unbind('click').bind('click', function(event) {
//			event.preventDefault();
//			$(this).closest('.hj-overlay-sidebar').find('.hj-overlay-sidebar-content').hide();
//			open.show();
//		});
//	})


//framework.ajax.view = function(event) {
//
//	var action = $(this).attr('href'),
//	values = $(this).data('options'),
//	rel = $(this).attr('rel');
//
//	if (values) {
//		targetContainer = '#'+values.params.target;
//	}
//
//	if (rel == 'fancybox') {
//		$.fancybox({
//			content : framework.loaders.circle
//		});
//	} else {
//		$(targetContainer)
//		.fadeIn()
//		.html(framework.loaders.circle);
//	}
//
//	elgg.post(action, {
//		data : values,
//		dataType : 'json',
//		success : function(output) {
//			if (rel == 'fancybox') {
//				$.fancybox({
//					padding: '30',
//					content : output.output.body,
//					autoDimensions : true,
//					//						width : values.params.fbox_x || '500',
//					//						height : values.params.fbox_y || '500',
//					onComplete : function() {
//						elgg.trigger_hook('success', 'ajax');
//					}
//				});
//				$.fancybox.resize();
//			} else {
//				$(targetContainer)
//				.slideDown(800)
//				.html(output.output.body);
//				elgg.trigger_hook('success', 'ajax');
//
//			}
//		}
//	});
//
//	event.preventDefault();
//
//}
//
//framework.ajax.remove = function(event) {
//	var action = $(this).attr('href'),
//	subjectGuid = $(this).attr('id').replace('hj-ajaxed-remove-', ''),
//	targetContainer = 'elgg-object-'+subjectGuid,
//	options = $(this).data('options'),
//	sourceContainer = options.params.source,
//	confirmText = $(this).attr('rel') || elgg.echo('question:areyousure');
//
//	if (!subjectGuid.length) {
//		return true;
//	}
//
//	$(this).attr('rel', '');
//	$(this).attr('confirm', '');
//
//	event.preventDefault();
//
//	if (confirm(confirmText)) {
//		elgg.system_message(elgg.echo('framework:processing'));
//		elgg.action(action, {
//			success : function(output) {
//				$('[id="'+targetContainer+'"]')
//				.each(function() {
//					$(this).fadeOut(800, function() {
//						$(this).remove()
//					})
//				});
//				$('[id="'+sourceContainer+'"]')
//				.each(function() {
//					$(this).fadeOut(800, function() {
//						$(this).remove()
//					})
//				});
//
//			}
//		});
//
//	}
//}
//
//framework.ajax.gallery = function(event) {
//	event.preventDefault();
//
//	var values = $(this).data('options'),
//	targetContainer = $(this).attr('href'),
//	rel = $(this).attr('rel');
//
//	var fbox_content = $(targetContainer).html();
//	$.fancybox({
//		content : fbox_content,
//		autoDimensions : false,
//		'width' : values.params.fbox_x || framework.width - 200,
//		'height' : values.params.fbox_y || framework.height - 200,
//		'padding' : '20'
//	});
//}
//
//framework.ajax.save = function(event) {
	
//
//}
//
//framework.ajax.addwidget = function(event) {
//	event.preventDefault();
//	var action = $(this).attr('href'),
//	values = $(this).data('options');
//
//	elgg.system_message(elgg.echo('framework:processing'));
//
//	elgg.action(action, {
//		data : values,
//		success: function(json) {
//			$('#elgg-widget-col-1').prepend(json.output);
//			elgg.trigger_hook('success', 'ajax');
//		}
//	});
//}
//
//framework.ajax.reloadwidget = function(event) {
//	event.preventDefault();
//	event.stopPropagation();
//
//	var action = $(this).attr('action');
//	var widgetGuid = $(this).parent().attr('id').replace('widget-edit-','');
//
//	var sourceContainer = $('#elgg-widget-'+widgetGuid);
//
//	$(sourceContainer)
//	.removeClass()
//	.wrap('<div></div>')
//	.html(framework.loaders.circle);
//
//	elgg.action(action, {
//		data : $(this).serialize(),
//		success : function() {
//			elgg.action('action/framework/widget/load', {
//				data : {
//					e : widgetGuid
//				},
//				success : function(output) {
//					$(sourceContainer)
//					.parent('div')
//					.slideDown(800)
//					.html(output.output.data);
//					elgg.trigger_hook('success', 'ajax');
//
//				}
//			});
//		}
//	});
//}
//
//framework.ajax.paginateNext = function(event) {
//	event.preventDefault();
//	var button = $(this),
//	list_id = $(this).attr('rel'),
//	list = $('#' + list_id);
//
//	if ($(window).data('ajaxready') == false) return;
//	var loader = $('<span>').addClass('hj-ajax-loader hj-loader-bar');
//	var last = list.find('li.elgg-item').last();
//
//	if (!last.length) {
//		last = list;
//	}
//	$(window).data('ajaxready', false);
//	var guid = last.attr('id').replace('elgg-object-', '');
//	var pagination_data = framework.data.lists[list_id].pagination;
//	var url = pagination_data.baseurl;
//	framework.data.lists[list_id].sync = 'old';
//	button.prepend(loader);
//
//	elgg.ajax(url, {
//		data : {
//			listdata : framework.data.lists[list_id]
//		},
//		dataType : 'json',
//		type : 'POST',
//		success : function(output) {
//			if (output.output) {
//				$.each(output.output, function(key, val) {
//					var append_guid = parseInt(val['guid']);
//					if (!isNaN(append_guid)) {
//						if (pagination_data.inverse_order) {
//							framework.data.lists[list_id].items.unshift(append_guid);
//							list.prepend(val['html']);
//						} else {
//							framework.data.lists[list_id].items.push(append_guid);
//							list.append(val['html']);
//						}
//					}
//				});
//				$(window).data('ajaxready', true);
//				elgg.trigger_hook('success', 'ajax');
//
//				var params = new Object();
//				params.list_id = list_id;
//				params.data = output.output;
//				elgg.trigger_hook('new_lists', 'ajax', params, true);
//			}
//			if ((!output.output) || (framework.data.lists[list_id].items.length >= pagination_data.count) || (output.output && pagination_data.limit == 0)) {
//				$(window).data('ajaxready', true);
//				button.hide();
//			}
//			loader.remove();
//		}
//	});
//}
//framework.ajax.triggerRefresh = function() {
//	if (!framework.data) {
//		return;
//	}
//	var time = 30000; // frequency at which lists on the page are refreshed
//
//	var time_current = new Date().getTime();
//	if (time_current - framework.hjLastUpdate >= time) {
//		framework.ajax.listRefresh();
//	}
//	framework.hjLastUpdate = new Date().getTime();
//}
//
//framework.ajax.listRefresh = function(list_id) {
//	var lists = new Object();
//	if (list_id) {
//		lists[list_id] = framework.data.lists[list_id];
//	} else {
//		lists = framework.data.lists;
//	}
//	$.each(lists, function(k, v) {
//		v.sync = 'new';
//		if (v.pagination.autorefresh === false) {
//
//		} else {
//			if (v.pagination.baseurl.length) {
//				var url = v.pagination.baseurl;
//			} else {
//				var url = "framework/sync/";
//			}
//			var list = $('#' + k);
//			var k_id = k;
//
//			elgg.ajax(url, {
//				data : {
//					listdata : v
//				},
//				dataType : 'json',
//				type : 'POST',
//				success : function(output) {
//					if (output.output && output.output.length > 0) {
//						$.each(output.output, function(key, val) {
//							var append_guid = parseInt(val['guid']);
//							if (!isNaN(append_guid)) {
//								if (v.pagination.inverse_order) {
//									framework.data.lists[k_id].items.push(append_guid);
//									list.append(val['html']);
//								} else {
//									framework.data.lists[k_id].items.unshift(append_guid);
//									list.prepend(val['html']);
//								}
//							}
//						});
//
//						$(window).data('ajaxready', true);
//						elgg.trigger_hook('success', 'ajax');
//
//						var params = new Array();
//						params['list_id'] = k_id;
//						params['data'] = output.output;
//
//						elgg.trigger_hook('new_lists', 'ajax', params);
//					}
//				}
//
//			});
//		}
//	});
//
//}

// JS and CSS fetching
elgg.register_hook_handler('init', 'system', framework.ajax.fetchOnSystemInit, 1);
elgg.register_hook_handler('success', 'ajax', framework.ajax.fetchOnAjaxSuccess, 1);
elgg.register_hook_handler('init', 'system', framework.ajax.init);
elgg.register_hook_handler('reload', 'lists', framework.ajax.getUpdatedList);
//elgg.register_hook_handler('refresh:complete', 'lists', framework.ajax);


<?php if (FALSE) : ?></script><?php
endif;
?>
