/*
Uploadify v3.1.0
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

.uploadify {
	position: relative;
	margin-bottom: 1em;
}
.uploadify-button {
	background-color: #505050;
	background-image: linear-gradient(bottom, #505050 0%, #707070 100%);
	background-image: -o-linear-gradient(bottom, #505050 0%, #707070 100%);
	background-image: -moz-linear-gradient(bottom, #505050 0%, #707070 100%);
	background-image: -webkit-linear-gradient(bottom, #505050 0%, #707070 100%);
	background-image: -ms-linear-gradient(bottom, #505050 0%, #707070 100%);
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, #505050),
		color-stop(1, #707070)
	);
	background-position: center top;
	background-repeat: no-repeat;
	-webkit-border-radius: 30px;
	-moz-border-radius: 30px;
	border-radius: 30px;
	border: 2px solid #808080;
	color: #FFF;
	font: bold 12px Arial, Helvetica, sans-serif;
	text-align: center;
	text-shadow: 0 -1px 0 rgba(0,0,0,0.25);
	width: 100%;
}
.uploadify:hover .uploadify-button {
	background-color: #606060;
	background-image: linear-gradient(top, #606060 0%, #808080 100%);
	background-image: -o-linear-gradient(top, #606060 0%, #808080 100%);
	background-image: -moz-linear-gradient(top, #606060 0%, #808080 100%);
	background-image: -webkit-linear-gradient(top, #606060 0%, #808080 100%);
	background-image: -ms-linear-gradient(top, #606060 0%, #808080 100%);
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, #606060),
		color-stop(1, #808080)
	);
	background-position: center bottom;
}
.uploadify-button.disabled {
	background-color: #D0D0D0;
	color: #808080;
}
.uploadify-queue {
	margin-bottom: 1em;
}
.uploadify-queue-item {
	background-color: #F5F5F5;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	font: 11px Verdana, Geneva, sans-serif;
	margin-top: 5px;
	max-width: 350px;
	padding: 10px;
}
.uploadify-error {
	background-color: #FDE5DD !important;
}
.uploadify-queue-item .cancel a {
	background: url('<?php echo HYPEFRAMEWROK_PATH_GRAPHICS . 'uploadify-cancel.png' ?>') 0 0 no-repeat;
	float: right;
	height:	16px;
	text-indent: -9999px;
	width: 16px;
}
.uploadify-queue-item.completed {
	background-color: #E5E5E5;
}
.uploadify-progress {
	background-color: #E5E5E5;
	margin-top: 10px;
	width: 100%;
}
.uploadify-progress-bar {
	background-color: #0099FF;
	height: 3px;
	width: 1px;
}