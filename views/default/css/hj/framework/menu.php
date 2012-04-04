<?php
$base_url = elgg_get_site_url();
$graphics_url = $base_url . 'mod/hypeFramework/graphics/';
?>

.elgg-menu-hjentityhead,
.elgg-menu-hjsegmenthead
{
float:right;
margin-left:0px;
}
.elgg-menu-hjentityfoot,
.elgg-menu-hjsectionfoot
{
float:none;
margin-left:0;
}
.elgg-menu-hjentityhead,
.elgg-menu-hjentityfoot,
.elgg-menu-hjsegmenthead,
.elgg-menu-hjsectionfoot
{
color: #aaa;
line-height: 20px;
vertical-align:middle;
margin-bottom: 5px;
}
.elgg-menu-hjentityhead > li,
.elgg-menu-hjentityfoot > li,
.elgg-menu-hjsegmenthead > li,
.elgg-menu-hjsectionfoot > li
{
margin-right: 12px;
}
.elgg-menu-hjentityhead > li > a,
.elgg-menu-hjentityfoot > li > a,
.elgg-menu-hjsegmenthead > li > a,
.elgg-menu-hjsectionfoot > li > a
{
color: #aaa;
}
.elgg-menu-hjentityhead > li > a,
.elgg-menu-hjentityfoot > li > a,
.elgg-menu-hjsegmenthead > li > a,
.elgg-menu-hjsectionfoot > li > a
{
display: block;
}
.elgg-menu-hjentityhead > li > span,
.elgg-menu-hjentityfoot > li > span,
.elgg-menu-hjsegmenthead > li > span,
.elgg-menu-hjsectionfoot > li > span
{
vertical-align: baseline;
}

.elgg-menu-hjentityhead > li > span:hover,
.elgg-menu-hjentityfoot > li > span:hover,
.elgg-menu-hjsegmenthead > li > span:hover,
.elgg-menu-hjsectionfoot > li > span:hover {
text-decoration:none;
}

.elgg-menu-hjentityhead > li > a:hover,
.elgg-menu-hjentityfoot > li > a:hover,
.elgg-menu-hjsegmenthead > li > a:hover,
.elgg-menu-hjsectionfoot > li > a:hover {
text-decoration:none;
}

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