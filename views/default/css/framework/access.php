<?php
$base_url = elgg_get_site_url();
$graphics_url = $base_url . 'mod/hypeFramework/graphics/';
?>
<?php if (FALSE) : ?>
	<style>
	<?php endif; ?>

	.access-select {
		position: absolute;
		top: 0;
		right: 0;
		z-index: 1000;
		display: none;
		float: left;
		min-width: 170px;
		padding: 0;
		margin: 2px 0 0;
		list-style: none;
		background-color: #ffffff;
		border: 1px solid #ccc;
		text-align: left;
	}
	.access-group {
		position: relative;
		text-align: right;
	}
	.access-select-toggle {
		text-align: right;
		width: 50px;
		height: 25px;
		display: block;
	}
	.access-select a {
		padding: 5px 20px 5px 10px;
		display: block;
		border-bottom: 1px solid #ccc;
	}
	.access-select a.elgg-state-selected, .access-select a.elgg-state-selected:hover {
		background: #f4f4f4 url(<?php echo $graphics_url; ?>access/selected.png) no-repeat 95% 50%;
		font-weight:bold;
		background-size: 12px;
	}
	.access-select a:hover {
		background: #f8f8f8 url(<?php echo $graphics_url; ?>access/select.png) no-repeat 95% 50%;
		background-size: 12px;
	}
	.access-select a.loading {
		background: #f8f8f8 url(<?php echo $graphics_url; ?>access/ajax-loader.gif) no-repeat 95% 50%;
	}
	.access-group:hover .access-select {
		display: block;
	}
	.access-default {
		width: 16px;
		height:16px;
		display: inline-block;
		background: transparent;
		vertical-align: middle;
		line-height: 20px;
		margin-right: 10px;
		opacity:0.2;
		background-size: 12px;
		background-repeat: no-repeat;
		background-position: 50% 50%;
	}
	.access-private {
		background-image: url(<?php echo $graphics_url; ?>access/private.png);

	}
	.access-public {
		background-image: url(<?php echo $graphics_url; ?>access/public.png);
		background-size: 14px;
	}
	.access-friends {
		background-image: url(<?php echo $graphics_url; ?>access/friends.png);
		background-size: 16px;
	}
	.access-loggedin {
		background-image: url(<?php echo $graphics_url; ?>access/loggedin.png);
		background-size: 14px;
	}
	.access-custom {
		background-image: url(<?php echo $graphics_url; ?>access/custom.png);
		background-size: 14px;
	}
	.access-select span, .access-select i {
		display: inline-block;
		vertical-align: middle;
		line-height: 20px;
		font-style: normal;
	}