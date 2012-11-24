<?php
$base_url = elgg_get_site_url();

echo elgg_view('css/framework/icons');
echo elgg_view('css/framework/forms');
echo elgg_view('css/framework/elgg');
echo elgg_view('css/framework/menu');
echo elgg_view('css/framework/canvas');
echo elgg_view('css/framework/controls');
echo elgg_view('css/vendors/qtip/qtip');


//Third Parties
echo elgg_view('css/lightbox');
?>

.hj-left { float:left }
.hj-right { float:right }
.hj-left:after,
.hj-right:after {
content: ".";
display: block;
height: 0;
clear: both;
visibility: hidden;
}

.hidden { display:none }

.hj-ajax-loader {
	margin:0 auto;
	display:block;
}

.hj-loader-circle {
	background:transparent url(<?php echo HYPEFRAMEWORK_PATH_GRAPHICS ?>loader/circle.gif) no-repeat center center;
	width:75px;
	height:75px;
}
.hj-loader-bar {
	background:transparent url(<?php echo HYPEFRAMEWORK_PATH_GRAPHICS ?>loader/bar.gif) no-repeat center center;
	width:16px;
	height:11px;
}
.hj-loader-arrows {
	background:transparent url(<?php echo HYPEFRAMEWORK_PATH_GRAPHICS ?>loader/arrows.gif) no-repeat center center;
	width:16px;
	height:16px;
}
.hj-loader-indicator {
	background:transparent url(<?php echo HYPEFRAMEWORK_PATH_GRAPHICS ?>loader/indicator.gif) no-repeat center center;
	width:16px;
	height:16px;
}

div.mandatory,
div.required {
	padding-right:15px;
	background:transparent url(<?php echo HYPEFRAMEWORK_PATH_GRAPHICS ?>mandatory.png) no-repeat 98% 23px;
}

input.hj-input-processing,
input.hj-input-processing:hover,
textarea.hj-input-processing,
textarea.hj-input-processing:hover
{
	background:white url(<?php echo HYPEFRAMEWORK_PATH_GRAPHICS ?>loader/indicator.gif) no-repeat right;
}

.hj-field-module {
    font-size:0.9em;
}

.hj-field-module-output {
    padding:5px 0;
    margin:0 10px;
    border-bottom:1px solid #f4f4f4;
}

.hj-output-label {
    font-style:italic;
    font-size:0.8em;
    vertical-align:top;
    display:inline-block;
    text-align:right;
    width:35%;
}

.hj-output-text {
    font-weight:bold;
    vertical-align:top;
    margin-left:10px;
    display:inline-block;
    text-align:left;
    width:58%;
}

.hj-icon-text {
    height:16px;
    display:inline-block;
    vertical-align:top;
    font-size:0.9em;
    margin-left:5px;
    margin-right:7px;
}

.elgg-list .elgg-module-aside .elgg-head {
	padding-top:10px;
}

.hj-view-list,
.hj-view-list > li {
	border:none;
}

.elgg-item {
	position:relative;
}

.elgg-menu > li > a.hidden {
	display:none;
}

.hj-active-temp {
	padding:10px;
	background:#f4f4f4;
}

.hj-gallery-view {
	max-width:620px;
	padding:5px;
	text-align:center;
	margin:0 auto;
}

.hj-file-icon-preview {
	margin:0 auto;
	text-align:center;
}

.hj-file-icon-master {
	
}

#hj-sortable-tabs > li:hover {
	cursor:move;
}
#hj-sortable-tabs > a:hover {
	cursor:pointer;
}

#fancybox-content > div > div.elgg-module {
    margin:0 20px;
    padding-bottom:100px;
}

.hj-pagination-next {
	padding: 3px;
	text-align: center;
	background: #F4F4F4;
	border-bottom: #E8E8E8 1px solid;
	color: #666;
	cursor: pointer;
	margin-top:10px;
}

.hj-pagination-next .hj-ajax-loader {
	float:left;
	margin:2px 5px 0;
}

/* SLIDESHOW */

.hj-carousel-pagination {
	font-size:10px;
}

.hj-carousel-next {
	text-align:right;
	float:left;
}

.hj-carousel-pager {
	text-align:center;
	float:left;
}

.hj-carousel-prev {
	text-align:left;
	float:left;
}

.bx-window {
	border-top:1px solid #f4f4f4;
	border-bottom:1px solid #f4f4f4;
	margin-bottom:10px;
}

.hj-pagination-next.hj-pagination-carousel {
	padding:0;
	margin:0;
	font-size:10px;
	text-align:right;
	background:none;
	border:0;
}


/* Overlay sidebar */

.hj-overlay-sidebar {
	position: absolute;
	top: 0;
	left: 0;
	height: 100%;
	background: white;
	border-right: 1px solid #B0B0B0;
	-moz-box-shadow: 1px 0px 4px #b0b0b0;
	-webkit-box-shadow: 1px 0px 4px #b0b0b0;
	box-shadow: 1px 0px 4px #b0b0b0;
}

.hj-overlay-sidebar-content {
	width:250px;
}

.hj-overlay-sidebar-open,
.hj-overlay-sidebar-close
{
	font-weight: bold;
	font-size: 10px;
	padding:10px 5px;
}

.hj-overlay-sidebar-close {
	float:right;
}

label[for="photos"] .hj-field-label {
	display:none;
}

.elgg-plugin-category-hypeJunction {
	border:2px solid #8A2BE2;
}


.framework-page {}

.framework-page h2,
.framework-page h3 {
	font-family:Helvetica, Arial, sans-serif;
}
.framework-page .elgg-main .elgg-head {
	margin:5px 0 10px;
}
.framework-page .elgg-main .elgg-head h2 {
	line-height:35px;
	vertical-align:middle;
}
.framework-page .elgg-list {
	border-top:1px solid #f4f4f4;
}

.framework-page .elgg-list > li {
	padding:5px;
	border-bottom:1px solid #f4f4f4;
	min-height:80px;
}

.framework-list-filter-area form {
	text-decoration: none;
	text-shadow: 0 1px 0 white;
	font: bold 11px Helvetica, Arial, sans-serif;
	color: #444;
	line-height: 17px;
	display: block;
	margin: 5px;
	background: white;
	border: solid 1px #D9D9D9;
	border-radius: 2px;
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	-webkit-transition: border-color .20s;
	-moz-transition: border-color .20s;
	-o-transition: border-color .20s;
	transition: border-color .20s;
}

.framework-list-filter-area form .elgg-input-wrapper {
	width:25%;
	margin-bottom:10px;
	display: inline-block;
	vertical-align: top;

}
.framework-list-filter-area form .elgg-input-wrapper.elgg-input-wrapper-submit {
	margin: 10px 0 0 0;
	width: 65px;
}
.framework-list-filter-area form .elgg-input-wrapper.elgg-input-wrapper-custom {
	margin: 10px 0 0 0;
	width: 65px;
}

.framework-list-filter-area form input[type="text"],
.framework-list-filter-area form select,
.framework-list-filter-area form textarea {
	width:90%;
	font-size:11px;
	border-radius: 0px;
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
}

.ui-tooltip, .ui-tooltip-arrow:after {
     background: #666;
	 border:0;
	 box-shadow:none;
	 -webkit-border-radius:0;
	 -moz-border-radius:0;
	 border-radius:0;
}

.ui-tooltip {
	padding: 1px;
	color: white;
	font-family:Helvetica, Arial, sans-serif;
}
.ui-tooltip-arrow {
	width: 50px;
	height: 13px;
	overflow: hidden;
	position: absolute;
	left: 50%;
	margin-left: -18px;
	bottom: -13px;
}
.ui-tooltip-arrow.top {
	top: -13px;
	bottom: auto;
}
.ui-tooltip-arrow.left {
	left: 20%;
}
.ui-tooltip-arrow:after {
	content: "";
	position: absolute;
	left: 10px;
	top: -10px;
	width: 15px;
	height: 15px;
	-webkit-transform: rotate(45deg);
	-moz-transform: rotate(45deg);
	-ms-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	tranform: rotate(45deg);
}
.ui-tooltip-arrow.top:after {
	bottom: -13px;
	top: auto;
}