<?php
$base_url = elgg_get_site_url();
$graphics_url = $base_url . 'mod/hypeFramework/graphics/';
?>

.hj-entity-head-menu {
	display: none;
	position: absolute;
	z-index:2000;
}

.hj-entity-head-menu-default {
	margin-left:200px;
	margin-top:3px;
	overflow: hidden;
	width: 200px;
	background: #fff;
	border: 1px solid #B0B0B0;

	-moz-box-shadow: 0 0 4px #B0B0B0;
	-webkit-box-shadow: 0 0 4px #B0B0B0;
	box-shadow: 0 0 4px #B0B0B0;
}
.hj-entity-head-menu-default > li {
	padding:5px;
}
.hj-entity-head-menu-default > li a {
	color: #666;
}
.hj-entity-head-menu-default > li a:hover {
	color: #888;
	text-decoration:none;
}
.hj-entity-head-menu-default > li:hover {
	background:#f4f4f4;
}

.hj-hover-menu-block {
	position:relative;
	float:right;
	display:block;
}

#fancybox-content .hj-hover-menu-block {
	z-index:2000;
}
.hj-hover-menu-toggler {
	
}