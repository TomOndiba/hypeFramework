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

	framework.loaders.overlay = $('<div>').addClass('hj-ajax-loader').addClass('hj-loader-overlay');
	framework.loaders.circle = $('<div>').addClass('hj-ajax-loader').addClass('hj-loader-circle');
	framework.loaders.indicator = $('<div>').addClass('hj-ajax-loader').addClass('hj-loader-indicator');
	framework.loaders.bar = $('<div>').addClass('hj-ajax-loader').addClass('hj-loader-bar');

	/**
	 * Create a record of loaded scripts and stylesheets
	 */
	framework.ajax.fetchOnSystemInit = function() {
		if (!framework.resources.length) {
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

	framework.ajax.getScript = function(url, callback) {
		var head = document.getElementsByTagName("head")[0];
		var script = document.createElement("script");
		script.src = url;

		// Handle Script loading
		{
			var done = false;

			// Attach handlers for all browsers
			script.onload = script.onreadystatechange = function(){
				if ( !done && (!this.readyState ||
					this.readyState == "loaded" || this.readyState == "complete") ) {
					done = true;
					if (callback)
						callback();

					// Handle memory leak in IE
					script.onload = script.onreadystatechange = null;
				}
			};
		}

		head.appendChild(script);

	}

	framework.ajax.getStylesheet = function(url, callback) {
		var head = document.getElementsByTagName("head")[0];
		var link = document.createElement("link");
		link.rel = 'stylesheet';
		link.type = 'text/css';
		link.href = url;

		// Handle Script loading
		{
			var done = false;

			// Attach handlers for all browsers
			link.onload = link.onreadystatechange = function(){
				if ( !done && (!this.readyState ||
					this.readyState == "loaded" || this.readyState == "complete") ) {
					done = true;
					if (callback)
						callback();

					// Handle memory leak in IE
					link.onload = link.onreadystatechange = null;
				}
			};
		}

		head.appendChild(link);

	}

	/**
	 * Check if any new scripts and stylesheets need to be loaded
	 */
	framework.ajax.fetchOnAjaxSuccess = function(name, type, params, value) {

		if (!params) return;
		var resources = params.response.resources;

		if (!resources) return;

		for (var i=0;i < resources.js.length;i++) {
			if ($.inArray(resources.js[i], framework.resources) == -1) {
				framework.ajax.getScript(resources.js[i]);
				framework.resources.push(resources.js[i]);
			}
		}

		for (var i=0;i < resources.css.length;i++) {
			if ($.inArray(resources.css[i], framework.resources) == -1) {
				framework.ajax.getStylesheet(resources.css[i]);
				framework.resources.push(resources.css[i]);
			}
		}
	}

	framework.ajax.init = function() {

		$('form .elgg-button-cancel-trigger')
		.live('click', function(event) {
			if ($(this).closest('#fancybox-content').length > 0) {
				$.fancybox.close();
				return false;
			} else if ($(this).closest('#dialog').length > 0) {
				$(this).closest('#dialog').dialog('close');
				return false;
			} else {
				window.history.back();
			}
		})

		$('form .elgg-button-reset')
		.live('click', function(event) {
			event.preventDefault();
			$(this).closest('form').resetForm().trigger('submit');
		})

		$('a.list-filter-control, a.sort-control, a.sort-title, .hj-framework-list-pagination > li > a')
		.live('click', framework.ajax.getUpdatedList);

		$('.hj-framework-list-filter form')
		.live('submit', framework.ajax.filterList);

		$('.hj-framework-list-filter form select')
		.live('change', function() {
			$(this).closest('form').trigger('submit');
		});
		
		$('.elgg-button-create-entity')
		.live('click', framework.ajax.scenarios.createEntity);

		$('.elgg-button-edit-entity')
		.live('click', framework.ajax.scenarios.editEntity);

		$('.elgg-button-delete-entity')
		.live('click', framework.ajax.scenarios.deleteEntity);

		$('.elgg-button-subscription')
		.live('click', function(e) {
			e.preventDefault();
			$element = $(this);
			elgg.action($(this).attr('href'), {
				success : function(response) {
					if (response.status >= 0) {
						if ($element.text() == elgg.echo('hj:framework:subscription:remove')) {
							$element.text(elgg.echo('hj:framework:subscription:create'));
						} else {
							$element.text(elgg.echo('hj:framework:subscription:remove'));
						}
					}
				}
			})
		})

		$('.elgg-button-bookmark')
		.live('click', function(e) {
			e.preventDefault();
			$element = $(this);
			elgg.action($(this).attr('href'), {
				success : function(response) {
					if (response.status >= 0) {
						if ($element.text() == elgg.echo('hj:framework:bookmark:remove')) {
							$element.text(elgg.echo('hj:framework:bookmark:create'));
						} else {
							$element.text(elgg.echo('hj:framework:bookmark:remove'));
						}
					}
				}
			})
		})

	}

	framework.ajax.scenarios.createEntity = function(event) {

		$element = $(this);

		if ($element.data('toggle') !== 'dialog') { // Make sure element's data-toggle attribute is set to dialog
			return true;
		}

		var params = {
			element : $element,
			event : event
		}

		return elgg.trigger_hook('form:dialog', 'framework', params, false);

	}

	framework.ajax.scenarios.editEntity = function(event) {

		$element = $(this);

		if ($element.data('toggle') !== 'dialog') { // Make sure element's data-toggle attribute is set to dialog
			return true;
		}

		var params = {
			element : $element,
			event : event
		}

		return elgg.trigger_hook('form:dialog', 'framework', params, false);

	}

	framework.ajax.scenarios.deleteEntity = function(event) {

		$element = $(this);

		var uid = $element.data('uid');
		var action = $element.attr('href');

		var params = {
			element : $element,
			action : action,
			uid : uid
		}

		return elgg.trigger_hook('delete:entity', 'framework', params, false);

	}

	framework.ajax.formDialog = function(name, type, params, value) {

		var data = $(this).data();
		data['X-Requested-With'] = 'XMLHttpRequest';
		data.view = 'xhr'; // set viewtype
		data.endpoint = 'layout'; // 'pageshell', 'layout', 'layout-elements'

		elgg.post($element.attr('href'), {
			data : data,
			dataType: 'json',
			beforeSend : function() {
				$dialog = $('<div id="dialog">')
				.html(framework.loaders.circle)
				.appendTo('body')
				.dialog({
					dialogClass: 'hj-framework-dialog',
					title : elgg.echo('hj:framework:ajax:loading'),
					buttons : false,
					//modal : true,
					//autoResize : true,
					width : 650,
					maxHeight : 500
				});
			},
			complete : function() {

			},
			success : function(response) {

				$content = $(response.output.body.content);

				$title = $content.find('.elgg-head');
				var title = $title.text();
				if (!title.length) {
					title = response.output.body.title;
				}

				$($title, $content).remove();

				$dialog
				.html($content)
				.dialog({
					title: title
				});

				var params = new Object();
				params.event = 'getForm';
				params.response = response;
				params.data = data;

				elgg.trigger_hook('ajax:success', 'framework', params, true);

				$dialog
				.find('form')
				.submit(function(eventSubmit) {

					if (!$element.data('callback')) {
						return true;
					}
					
					$form = $(this);

					var data = $element.data();
					data['X-Requested-With'] = 'XMLHttpRequest';
					data.view = 'xhr'; // set viewtype
					data.endpoint = 'layout'; // 'pageshell', 'layout', 'layout-elements'

					var params = ({
						dataType : 'json',
						data : data,
						beforeSend : function() {
							$dialog.html(framework.loaders.circle);
							$dialog.focus();
							$dialog.dialog({
								title : elgg.echo('hj:framework:ajax:saving'),
								buttons:null
							});
							return true;
						},
						complete : function() {
							$dialog.dialog('close');
						},
						success : function(response, status, xhr) {

							var hookParams = new Object();
							hookParams.event = 'submitForm';
							hookParams.response = response;
							hookParams.element = $element;
							hookParams.data = $form.serialize();

							if (response.output.guid) {
								hookParams.href = framework.ajax.updateUrlQuery(window.location.href, { '__goto' : response.output.guid });
							}
						
							elgg.trigger_hook('ajax:success', 'framework', hookParams, true);

							if (response.status < 0) {
								$dialog.remove();
								$element.trigger('click');
								return false;
							}

							if ($element.data('callback')) {
								var hook = $element.data('callback').split('::');
								elgg.trigger_hook(hook[0], hook[1], hookParams);
							}

							$form.resetForm();
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

		return false;

	}

	framework.ajax.deleteEntity = function(name, type, params, value) {

		//		var uid = params.uid;
		var action = params.action;
		var $element = params.element;

		var confirmText = elgg.echo('question:areyousure');
		if (!confirm(confirmText)) {
			return false;
		}
		
		elgg.action(action, {
			dataType : 'json',
			beforeSend : function() {
				elgg.system_message(elgg.echo('hj:framework:ajax:deleting'));
				$element.addClass('loading')
			},
			complete : function() {
				$element.removeClass('loading')
			},
			success : function(response) {
				//elgg.trigger_hook('ajax:success', 'framework', {response : response});
				if (response.status < 0) {

				} else {
					var uid = response.output.guid;
					$('[data-uid="' + uid + '"], [id="elgg-object-' + uid + '"]')
					.each(function() { $(this).remove()})
				}
			}

		})

		return false;
	}

	framework.ajax.success = function(hook, type, params) {
		elgg.register_error(params.response.system_messages.error);
		elgg.system_message(params.response.system_messages.success);

		if (elgg.tinymce) {
			elgg.tinymce.init();
		}
	}

	framework.ajax.getUpdatedList = function(e) {
		elgg.trigger_hook('refresh:lists', 'framework', { element : $(this), href : $(this).attr('href')});
		return false;
	}

	framework.ajax.filterList = function(e) {
		var params = $(this).serialize();
		var href = framework.ajax.updateUrlQuery(window.location.href, params);
		elgg.trigger_hook('refresh:lists', 'framework', { element : $(this), href : href });
		return false;
	}

	framework.ajax.getUpdatedLists = function(hook, type, params) {

		var $element = params.element;
		if ($element) {
			var $container = $element.closest('.hj-framework-list-wrapper');
		} else {
			var $container = $('.hj-framework-list-wrapper');
		}

		console.log(params.href);
		if (params.href) {
			var href = params.href;
		} else {
			var href = window.location.href;
		}
		
		elgg.post(href, {
			beforeSend : function() {
				$('.hj-ajax-loader', $element).show();
				$('.hj-ajax-loader', $container).show();
			},

			complete : function() {
				$('.hj-ajax-loader', $element).hide();
				$('.hj-ajax-loader', $container).hide();
			},

			data : {
				'X-Requested-With' : 'XMLHttpRequest',
				'view' : 'xhr',
				'endpoint' : 'global_xhr_output'
			},
			dataType : 'json',

			success : function(response) {

				var updatedLists = response.output.body.lists;

				$.each(updatedLists, function(key, listParams) {

					var		updatedList = listParams,
					listType = updatedList.list_type,

					$currentList = $('#' + updatedList.list_id),
					$currentListItems = $('.elgg-item', $currentList),

					updatedListItemUids = new Array(), currentListItemUids = new Array(), updatedListItemViews = new Array();

					$currentListItems.each(function() {
						currentListItemUids.push($(this).data('uid'));
					});
					
					if (!updatedList.items) {
						updatedList.items = new Array();
					}
					switch (listType) {

						case 'list' :
							var $listBody = $currentList;
							break;

						case 'gallery' :
							var $listBody = $currentList;
							break;

						case 'table' :
							$currentList.children('thead').replaceWith(updatedList.head);
							var $listBody = $currentList.children('tbody');
							break;

					}

					$.each(updatedList.items, function(pos, itemView) {
						var itemUid = $(itemView).data('uid');
						updatedListItemUids.push(itemUid);
						updatedListItemViews[itemUid] = itemView;
						$new = $(itemView).addClass('hj-framework-list-item-new');
						$existing = $currentList.find('.elgg-item[data-uid=' + itemUid + ']:first');
						if (($existing.length == 0) || ($existing.length && $new.data('ts') > $existing.data('ts'))) {
							$append = $new;
						} else if ($existing.length && $new.data('ts') <= $existing.data('ts')) {
							$append = $existing.clone();
						}
						$existing.remove();
						$listBody.append($new);
					})

					$.each(currentListItemUids, function(pos, itemUid) {
						if ($.inArray(itemUid, updatedListItemUids) < 0) {
							$currentList.find('[data-uid=' + itemUid + ']').remove();
						}
					});

					//console.log(currentListItemUids);
					//console.log(updatedListItemUids);
					//$('[rel=placeholder]', $currentList).remove();

					$('.hj-framework-list-pagination-wrapper', $currentList.closest('.hj-framework-list-wrapper')).replaceWith(updatedList.pagination);
					//$('.hj-framework-list-filter', $currentList.closest('.hj-framework-list-wrapper')).replaceWith(updatedList.filter);
					
					window.history.replaceState(null, response.output.title, response.href);
				
				})
			}
		})
	}

	framework.ajax.getUpdatedPage = function(hook, type, params) {

		window.location.reload();

	}

	framework.ajax.updateUrlQuery = function(url, params) {
		if (elgg.isString(url)) {
			var parts = elgg.parse_url(url),
			args = {},
			base = '';

			if (parts['host'] == undefined) {
				if (url.indexOf('?') === 0) {
					base = '?';
					args = elgg.parse_str(parts['query']);
				}
			} else {
				if (parts['query'] != undefined) {
					// with query string
					args = elgg.parse_str(parts['query']);
				}
				var split = url.split('?');
				base = split[0] + '?';
			}

			if (elgg.isString(params)) {
				params = elgg.parse_str(params);
			}

			$.each(params, function(key, value) {
				args[key] = value;
			})

			return base + $.param(args);
		}
	}

	// JS and CSS fetching
	elgg.register_hook_handler('init', 'system', framework.ajax.fetchOnSystemInit, 999);
	elgg.register_hook_handler('ajax:success', 'framework', framework.ajax.fetchOnAjaxSuccess, 999);
	
	elgg.register_hook_handler('init', 'system', framework.ajax.init);

	elgg.register_hook_handler('ajax:success', 'framework', framework.ajax.success);

	elgg.register_hook_handler('form:dialog', 'framework', framework.ajax.formDialog);
	elgg.register_hook_handler('refresh:lists', 'framework', framework.ajax.getUpdatedLists);
	elgg.register_hook_handler('reload:page', 'framework', framework.ajax.getUpdatedPage);

	elgg.register_hook_handler('delete:entity', 'framework', framework.ajax.deleteEntity);

<?php if (FALSE) : ?></script><?php
endif;
?>
