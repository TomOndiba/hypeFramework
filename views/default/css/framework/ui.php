<?php
$base_url = elgg_get_site_url();
$graphics_url = $base_url . 'mod/hypeFramework/graphics/';
?>
<?php if (FALSE) : ?>
	<style>
	<?php endif; ?>

	.hj-ajax-loader {
		margin:0 auto;
	}

	.hj-loader-circle {
		background:transparent url(<?php echo $graphics_url ?>loader/circle.gif) no-repeat center center;
		width:75px;
		height:75px;
	}

	.hj-loader-overlay {
		background:#fff url(<?php echo $graphics_url ?>loader/circle.gif) no-repeat center center;
		position: absolute;
		width: 100%;
		height: 100%;
		min-width:75px;
		min-height:75px;
		top: 0;
		bottom:0;
		left: 0;
		right:0;
		z-index: 3000;
		opacity: 0.8;
	}

	.hj-loader-bar {
		background:transparent url(<?php echo $graphics_url ?>loader/bar.gif) no-repeat center center;
		width:16px;
		height:11px;
		display: inline-block;
		vertical-align: middle;
		margin-right: 10px;
	}
	.hj-loader-arrows {
		background:transparent url(<?php echo $graphics_url ?>loader/arrows.gif) no-repeat center center;
		width:16px;
		height:16px;
	}
	.hj-loader-indicator {
		background:transparent url(<?php echo $graphics_url ?>loader/indicator.gif) no-repeat center center;
		width:16px;
		height:16px;
		margin:8px auto;
	}


	/** jQuery UI Dialog */
	.hj-framework-dialog {
		border: 1px solid #e8e8e8;
		border-radius: 0;
		padding: 0;
	}
	.hj-framework-dialog .ui-dialog-titlebar {
		border-radius: 0;
		border: 0;
		border-bottom: 1px solid #e8e8e8;
		background: #f4f4f4;
		line-height: 30px;
		margin:2px;
		box-sizing:border-box;
	}
	.hj-framework-dialog .ui-dialog-titlebar-close {
		margin: -10px 7px 0 0;
	}
	.hj-framework-dialog .ui-dialog-buttonpane {
		border: 0;
		border-top: 1px solid #e8e8e8;
	}
	.hj-framework-dialog .elgg-module-form > .elgg-body {
		border: 0;
		padding: 5px;
		margin: 0;
		font-size: 0.9em;
	}

	.hj-draggable-element-handle {
		cursor:move;
	}

	.hj-draggable-element-placeholder {
		border:2px dashed #e8e8e8;
	}
	tr.hj-draggable-element-placeholder {
		background:#f4f4f4;
	}

	/** Toggler Icons **/

	.elgg-icon-hjtoggler-down {
		background:transparent url(<?php echo $graphics_url ?>toggle/hj-toggler-down.png) no-repeat 50% 50%;
		float:right;
		cursor:pointer;
	}

	.elgg-icon-hjtoggler-down:hover {
		background:transparent url(<?php echo $graphics_url ?>toggle/hj-toggler-down-hover.png) no-repeat 50% 50%;
	}

	.elgg-icon-hjtoggler-up {
		background:transparent url(<?php echo $graphics_url ?>toggle/hj-toggler-up.png) no-repeat 50% 50%;
		cursor:pointer;
	}

	.elgg-icon-hjtoggler-up:hover {
		background:transparent url(<?php echo $graphics_url ?>toggle/hj-toggler-up-hover.png) no-repeat 50% 50%;
	}

	.elgg-icon-hjtoggler-left {
		background:transparent url(<?php echo $graphics_url ?>toggle/hj-toggler-left.png) no-repeat 50% 50%;
		cursor:pointer;
	}

	.elgg-icon-hjtoggler-left:hover {
		background:transparent url(<?php echo $graphics_url ?>toggle/hj-toggler-left-hover.png) no-repeat 50% 50%;
	}

	.elgg-icon-hjtoggler-right {
		background:transparent url(<?php echo $graphics_url ?>toggle/hj-toggler-right.png) no-repeat 50% 50%;
		cursor:pointer;
	}

	.elgg-icon-hjtoggler-right:hover {
		background:transparent url(<?php echo $graphics_url ?>toggle/hj-toggler-right-hover.png) no-repeat 50% 50%;
	}

	/** Dropdown Entity Menu **/
	.elgg-menu-hjentityhead {
		z-index:100;
	}
	
	.elgg-menu-hjentityhead > li {
		position:relative;
		padding:4px;
	}

	.elgg-menu-hjentityhead > li > a {
	}

	.elgg-menu-hjentityhead .elgg-child-menu {
		min-width: 200px;
		background:white;
		border: 1px solid #B0B0B0;
		-moz-box-shadow: 2px 2px 5px #B0B0B0;
		-webkit-box-shadow: 2px 2px 5px #B0B0B0;
		box-shadow: 2px 2px 5px #B0B0B0;
		position:absolute;
		right:0px;
		top:23px;
		display:none;
		z-index:101;
	}

	.elgg-menu-hjentityhead .elgg-child-menu > li {
		background:#fff;
	}

	.elgg-menu-hjentityhead li:hover .elgg-child-menu {
		display:block;
	}
	.elgg-menu-hjentityhead .elgg-child-menu > li {
		padding:5px;
		height:16px;
		border-bottom:1px solid #B0B0B0;
	}
	.elgg-menu-hjentityhead .elgg-child-menu > li:first-child {
		border-top:1px solid #B0B0B0;
	}
	.elgg-menu-hjentityhead .elgg-child-menu > li:last-child {
		border-bottom:0;
	}
	.elgg-menu-hjentityhead .elgg-child-menu > li a {
		color: #666;
		font-size:12px;
		line-height:16px;
	}
	.elgg-menu-hjentityhead .elgg-child-menu > li a:hover {
		color: #888;
		text-decoration:none;
	}
	.elgg-menu-hjentityhead .elgg-child-menu > li:hover {
		background:#f4f4f4;
	}

	.elgg-menu-hjentityhead .elgg-child-menu > li.elgg-state-selected a {
		font-weight:bold;
		padding-left:20px;
		background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left;
		background-position: 0 -1477px;
	}

	.hj-framework-cover-image {
		background-image: url(http://localhost/hypetest186/framework/icon/546932/master/1360297391.jpg);
		width: 100%;
		height: 200px;
		background-position: center center;
		background-repeat: no-repeat;
		background-size: 100%;
	}

	.elgg-menu-entity > li.hidden,
	.elgg-menu-title > li.hidden {
		display:none;
	}