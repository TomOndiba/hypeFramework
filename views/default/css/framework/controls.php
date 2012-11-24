/* Original Project by: http://shrapp.nl/post/google-plus-ui-buttons */

ul.elgg-menu-controls.elgg-menu-hjentityhead {
	float:right;
}

ul.elgg-menu-controls.elgg-menu-hjentityhead > li {
	margin:0;
}

.framework-ui-controls {

}

.framework-ui-control {
	text-decoration: none;
	text-shadow: 0 1px 0 #fff;

	font: bold 11px Helvetica, Arial, sans-serif;
	color: #444;
	line-height: 17px;
	height: 18px;

	display: block;

	margin: 5px;
	padding: 5px 6px 4px 6px;

	background: #FFF;
	border: solid 1px #D9D9D9;

	border-radius: 2px;
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;

	-webkit-transition: border-color .20s;
	-moz-transition: border-color .20s;
	-o-transition: border-color .20s;
	transition: border-color .20s;

	cursor:pointer;
}

input[type="submit"].framework-ui-control {
	text-shadow:none;
	height:auto;
	width:60px;
	color:white;
	float:right;
}

.framework-ui-control.elgg-menu-inactive,
.framework-ui-control.elgg-menu-inactive:hover {
	border-color:transparent;
	background:transparent;1
}

.framework-ui-control.elgg-menu-open {
	background:#F3F3F3;
}

.framework-ui-control.left {
	margin: 5px 0 5px 5px;

	border-top-right-radius: 0;
	-webkit-border-top-right-radius: 0;
	-moz-border-radius-topright: 0;

	border-bottom-right-radius: 0;
	-webkit-border-bottom-right-radius: 0;
	-moz-border-radius-bottomright: 0;
}

.framework-ui-control.middle {
	margin: 5px 0;

	border-left-color: #F4F4F4;

	border-radius: 0;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
}

.framework-ui-control.right {
	margin: 5px 5px 5px 0;

	border-left-color: #F4F4F4;

	border-top-left-radius: 0;
	-webkit-border-top-left-radius: 0;
	-moz-border-radius-topleft: 0;

	border-bottom-left-radius: 0;
	-webkit-border-bottom-left-radius: 0;
	-moz-border-radius-bottomleft: 0;
}

.framework-ui-control:hover {
    background: #F4F4F4;
		border-color: #C0C0C0;
		color: #333;
}

.framework-ui-control:active {
	border-color: #4D90FE;
	color: #4D90FE;

	-moz-box-shadow:inset 0 0 10px #D4D4D4;
	-webkit-box-shadow:inset 0 0 10px #D4D4D4;
	box-shadow:inset 0 0 10px #D4D4D4;
}

.framework-ui-control.elgg-state-selected {
	border-color: #BBB;

	-moz-box-shadow:inset 0 0 10px #D4D4D4;
	-webkit-box-shadow:inset 0 0 10px #D4D4D4;
	box-shadow:inset 0 0 10px #D4D4D4;

}
.framework-ui-control.elgg-state-selected:hover {
	border-color: #BBB;

	-moz-box-shadow:inset 0 0 10px #D4D4D4;
	-webkit-box-shadow:inset 0 0 10px #D4D4D4;
	box-shadow:inset 0 0 10px #D4D4D4;
}
.framework-ui-control.elgg-state-selected:active {
	border-color: #4D90FE;
}

.framework-ui-control.elgg-button-action {
	border: 1px solid #D8D8D8 !important;

	background: #F2F2F2;
	background: -webkit-linear-gradient(top, #F5F5F5, #F1F1F1);
	background: -moz-linear-gradient(top, #F5F5F5, #F1F1F1);
	background: -ms-linear-gradient(top, #F5F5F5, #F1F1F1);
	background: -o-linear-gradient(top, #F5F5F5, #F1F1F1);

	-webkit-transition: border .20s;
	-moz-transition: border .20s;
	-o-transition: border .20s;
	transition: border .20s;
}

.framework-ui-control.elgg-button-action:hover {
	border: 1px solid #C6C6C6 !important;

	background: #F3F3F3;
	background: -webkit-linear-gradient(top, #F8F8F8, #F1F1F1);
	background: -moz-linear-gradient(top, #F8F8F8, #F1F1F1);
	background: -ms-linear-gradient(top, #F8F8F8, #F1F1F1);
	background: -o-linear-gradient(top, #F8F8F8, #F1F1F1);
}

.framework-ui-control.blue {
	border: 1px solid #3079ED !important;

	background: #4B8DF8;
	background: -webkit-linear-gradient(top, #4C8FFD, #4787ED);
	background: -moz-linear-gradient(top, #4C8FFD, #4787ED);
	background: -ms-linear-gradient(top, #4C8FFD, #4787ED);
	background: -o-linear-gradient(top, #4C8FFD, #4787ED);

	-webkit-transition: border .20s;
	-moz-transition: border .20s;
	-o-transition: border .20s;
	transition: border .20s;
}

.framework-ui-control.blue:hover {
	border: 1px solid #2F5BB7 !important;

	background: #3F83F1;
	background: -webkit-linear-gradient(top, #4D90FE, #357AE8);
	background: -moz-linear-gradient(top, #4D90FE, #357AE8);
	background: -ms-linear-gradient(top, #4D90FE, #357AE8);
	background: -o-linear-gradient(top, #4D90FE, #357AE8);
}

.framework-ui-control.green {
	border: 1px solid #29691D !important;

	background: #3A8E00;
	background: -webkit-linear-gradient(top, #3C9300, #398A00);
	background: -moz-linear-gradient(top, #3C9300, #398A00);
	background: -ms-linear-gradient(top, #3C9300, #398A00);
	background: -o-linear-gradient(top, #3C9300, #398A00);

	-webkit-transition: border .20s;
	-moz-transition: border .20s;
	-o-transition: border .20s;
	transition: border .20s;
}

.framework-ui-control.green:hover {
	border: 1px solid #2D6200 !important;

	background: #3F83F1;
	background: -webkit-linear-gradient(top, #3C9300, #368200);
	background: -moz-linear-gradient(top, #3C9300, #368200);
	background: -ms-linear-gradient(top, #3C9300, #368200);
	background: -o-linear-gradient(top, #3C9300, #368200);
}

.framework-ui-control.red {
	border: 1px solid #D14836 !important;

	background: #D64937;
	background: -webkit-linear-gradient(top, #DC4A38, #D14836);
	background: -moz-linear-gradient(top, #DC4A38, #D14836);
	background: -ms-linear-gradient(top, #DC4A38, #D14836);
	background: -o-linear-gradient(top, #DC4A38, #D14836);

	-webkit-transition: border .20s;
	-moz-transition: border .20s;
	-o-transition: border .20s;
	transition: border .20s;
}

.framework-ui-control.red:hover {
	border: 1px solid #B0281A !important;

	background: #D14130;
	background: -webkit-linear-gradient(top, #DC4A38, #C53727);
	background: -moz-linear-gradient(top, #DC4A38, #C53727);
	background: -ms-linear-gradient(top, #DC4A38, #C53727);
	background: -o-linear-gradient(top, #DC4A38, #C53727);
}


.framework-ui-control.elgg-button-action:hover {
	-moz-box-shadow: 0 1px 0px #DDD;
	-webkit-box-shadow: 0 1px 0px #DDD;
	box-shadow:iset 0 1px 0px #DDD;
}

.framework-ui-control.elgg-button-action:active {
	-moz-box-shadow: none !important;
	-webkit-box-shadow: none !important;
	box-shadow: none !important;
	border-color: #C6C6C6 !important;
}

.framework-ui-control.blue:active {
	border-color: #2F5BB7 !important;
}

.framework-ui-control.green:active {
	border-color: #2D6200 !important;
}

.framework-ui-control.red:active {
	border-color: #B0281A !important;
}



.framework-ui-ddm span.label,
.framework-ui-control span.label {
	display: inline-block;
	float: left;
	line-height: 17px;
	height: 18px;
	padding: 0 1px;
	overflow: hidden;
	color: #444;

	-webkit-transition: color .20s;
	-moz-transition: color .20s;
	-o-transition: color .20s;
	transition: color .20s;
}

.framework-ui-ddm:active span.label,
.framework-ui-control:active span.label {
    color: #4D90FE;
}

.framework-ui-control:hover .label {
    color: #333;
}
.framework-ui-control:hover .label.red {
    color: #DB4A37;
}

.framework-ui-control:hover .label.blue {
    color: #7090C8;
}

.framework-ui-control:hover .label.green {
    color: #42B449;
}

.framework-ui-control:hover .label.yellow {
    color: #F7CB38;
}

.framework-ui-control.blue .label {
	color: #FFF !important;
	text-shadow: 0 1px 0 #2F5BB7 !important;
}

.framework-ui-control.green .label {
	color: #FFF !important;
	text-shadow: 0 1px 0 #2D6200 !important;
}

.framework-ui-control.red .label {
	color: #FFF !important;
	text-shadow: 0 1px 0 #B0281A !important;
}

.framework-ui-control.elgg-button-action .label {
	padding: 0 17px !important;
}

.framework-ui-control.elgg-button-action:active .label {
	color: #333 !important;
}


.framework-ui-control.blue:active .label,
.framework-ui-control.green:active .label,
.framework-ui-control.red:active .label {
	color: #FFF !important;
}


.framework-ui-icon {
	background-image: url(<?php echo elgg_get_site_url() ?>mod/hypeFramework/graphics/ui.buttons/google+-ui-sprite-gray.png);

	display: inline-block;
	margin: 0 7px;
	float: left;

	line-height: 18px;
	height: 18px;
	width: 18px;
	max-width: 18px;

	overflow: hidden;
	text-indent: -9999px;

	background-repeat: no-repeat;

	-webkit-transition: background-image 0.20s linear;
	-moz-transition: background-image 0.20s linear;
	-o-transition: background-image 0.20s linear;
	transition: background-image 0.20s linear;
}
.framework-ui-icon:hover,
.framework-ui-ddm:hover .framework-ui-icon,
.framework-ui-ddm:hover .framework-ui-icon:hover,
.framework-ui-ddm .framework-ui-icon:hover,
.framework-ui-control:hover .framework-ui-icon,
.framework-ui-control:hover .framework-ui-icon:hover,
.framework-ui-control .framework-ui-icon:hover {
	background-image: url(<?php echo elgg_get_site_url() ?>mod/hypeFramework/graphics/ui.buttons/google+-ui-sprite-colour.png);
}



/* Sprite Row 1 */
.framework-ui-icon.framework-ui-icon-abacus {background-position: -0px -0px;}
.framework-ui-icon.framework-ui-icon-access-point {background-position: -18px -0px;}
.framework-ui-icon.framework-ui-icon-add {background-position: -36px -0px;}
.framework-ui-icon.framework-ui-icon-administrator {background-position: -54px -0px;}
.framework-ui-icon.framework-ui-icon-alarm {background-position: -72px -0px;}
.framework-ui-icon.framework-ui-icon-arrow-bidirectional {background-position: -90px -0px;}
.framework-ui-icon.framework-ui-icon-arrow-down {background-position: -108px -0px;}
.framework-ui-icon.framework-ui-icon-arrow-left {background-position: -126px -0px;}
.framework-ui-icon.framework-ui-icon-arrow-right {background-position: -144px -0px;}
.framework-ui-icon.framework-ui-icon-arrow-up {background-position: -162px -0px;}
.framework-ui-icon.framework-ui-icon-attachment {background-position: -180px -0px;}
.framework-ui-icon.framework-ui-icon-icon12 {background-position: -198px -0px;}
.framework-ui-icon.framework-ui-icon-icon13 {background-position: -216px -0px;}
.framework-ui-icon.framework-ui-icon-icon14 {background-position: -234px -0px;}
.framework-ui-icon.framework-ui-icon-icon15 {background-position: -252px -0px;}
.framework-ui-icon.framework-ui-icon-icon16 {background-position: -270px -0px;}
.framework-ui-icon.framework-ui-icon-icon17 {background-position: -288px -0px;}
.framework-ui-icon.framework-ui-icon-icon18 {background-position: -306px -0px;}
.framework-ui-icon.framework-ui-icon-icon19 {background-position: -324px -0px;}
.framework-ui-icon.framework-ui-icon-icon20 {background-position: -342px -0px;}

/* Sprite Row 2 */
.framework-ui-icon.framework-ui-icon-icon21 {background-position: -0px -18px;}
.framework-ui-icon.framework-ui-icon-icon22 {background-position: -18px -18px;}
.framework-ui-icon.framework-ui-icon-icon23 {background-position: -36px -18px;}
.framework-ui-icon.framework-ui-icon-icon24 {background-position: -54px -18px;}
.framework-ui-icon.framework-ui-icon-icon25 {background-position: -72px -18px;}
.framework-ui-icon.framework-ui-icon-icon26 {background-position: -90px -18px;}
.framework-ui-icon.framework-ui-icon-icon27 {background-position: -108px -18px;}
.framework-ui-icon.framework-ui-icon-icon28 {background-position: -126px -18px;}
.framework-ui-icon.framework-ui-icon-icon29 {background-position: -144px -18px;}
.framework-ui-icon.framework-ui-icon-icon30 {background-position: -162px -18px;}
.framework-ui-icon.framework-ui-icon-icon31 {background-position: -180px -18px;}
.framework-ui-icon.framework-ui-icon-icon32 {background-position: -198px -18px;}
.framework-ui-icon.framework-ui-icon-calendar {background-position: -216px -18px;}
.framework-ui-icon.framework-ui-icon-icon34 {background-position: -234px -18px;}
.framework-ui-icon.framework-ui-icon-icon35 {background-position: -252px -18px;}
.framework-ui-icon.framework-ui-icon-icon36 {background-position: -270px -18px;}
.framework-ui-icon.framework-ui-icon-icon37 {background-position: -288px -18px;}
.framework-ui-icon.framework-ui-icon-icon38 {background-position: -306px -18px;}
.framework-ui-icon.framework-ui-icon-icon39 {background-position: -324px -18px;}
.framework-ui-icon.framework-ui-icon-icon40 {background-position: -342px -18px;}

/* Sprite Row 3 */
.framework-ui-icon.framework-ui-icon-icon41 {background-position: -0px -36px;}
.framework-ui-icon.framework-ui-icon-icon42 {background-position: -18px -36px;}
.framework-ui-icon.framework-ui-icon-checkmark {background-position: -36px -36px;}
.framework-ui-icon.framework-ui-icon-icon44 {background-position: -54px -36px;}
.framework-ui-icon.framework-ui-icon-icon45 {background-position: -72px -36px;}
.framework-ui-icon.framework-ui-icon-icon46 {background-position: -90px -36px;}
.framework-ui-icon.framework-ui-icon-icon47 {background-position: -108px -36px;}
.framework-ui-icon.framework-ui-icon-icon48 {background-position: -126px -36px;}
.framework-ui-icon.framework-ui-icon-icon49 {background-position: -144px -36px;}
.framework-ui-icon.framework-ui-icon-icon50 {background-position: -162px -36px;}
.framework-ui-icon.framework-ui-icon-icon51 {background-position: -180px -36px;}
.framework-ui-icon.framework-ui-icon-icon52 {background-position: -198px -36px;}
.framework-ui-icon.framework-ui-icon-icon53 {background-position: -216px -36px;}
.framework-ui-icon.framework-ui-icon-icon54 {background-position: -234px -36px;}
.framework-ui-icon.framework-ui-icon-icon55 {background-position: -252px -36px;}
.framework-ui-icon.framework-ui-icon-delete {background-position: -270px -36px;}
.framework-ui-icon.framework-ui-icon-icon57 {background-position: -288px -36px;}
.framework-ui-icon.framework-ui-icon-icon58 {background-position: -306px -36px;}
.framework-ui-icon.framework-ui-icon-icon59 {background-position: -324px -36px;}
.framework-ui-icon.framework-ui-icon-icon60 {background-position: -342px -36px;}

/* Sprite Row 4 */
.framework-ui-icon.framework-ui-icon-icon61 {background-position: -0px -54px;}
.framework-ui-icon.framework-ui-icon-icon62 {background-position: -18px -54px;}
.framework-ui-icon.framework-ui-icon-icon63 {background-position: -36px -54px;}
.framework-ui-icon.framework-ui-icon-icon64 {background-position: -54px -54px;}
.framework-ui-icon.framework-ui-icon-icon65 {background-position: -72px -54px;}
.framework-ui-icon.framework-ui-icon-icon66 {background-position: -90px -54px;}
.framework-ui-icon.framework-ui-icon-icon67 {background-position: -108px -54px;}
.framework-ui-icon.framework-ui-icon-icon68 {background-position: -126px -54px;}
.framework-ui-icon.framework-ui-icon-icon69 {background-position: -144px -54px;}
.framework-ui-icon.framework-ui-icon-download {background-position: -162px -54px;}
.framework-ui-icon.framework-ui-icon-icon71 {background-position: -180px -54px;}
.framework-ui-icon.framework-ui-icon-icon72 {background-position: -198px -54px;}
.framework-ui-icon.framework-ui-icon-icon73 {background-position: -216px -54px;}
.framework-ui-icon.framework-ui-icon-icon74 {background-position: -234px -54px;}
.framework-ui-icon.framework-ui-icon-icon75 {background-position: -252px -54px;}
.framework-ui-icon.framework-ui-icon-icon76 {background-position: -270px -54px;}
.framework-ui-icon.framework-ui-icon-icon77 {background-position: -288px -54px;}
.framework-ui-icon.framework-ui-icon-icon78 {background-position: -306px -54px;}
.framework-ui-icon.framework-ui-icon-icon79 {background-position: -324px -54px;}
.framework-ui-icon.framework-ui-icon-icon80 {background-position: -342px -54px;}

/* Sprite Row 5 */
.framework-ui-icon.framework-ui-icon-icon81 {background-position: -0px -72px;}
.framework-ui-icon.framework-ui-icon-icon82 {background-position: -18px -72px;}
.framework-ui-icon.framework-ui-icon-eraser {background-position: -36px -72px;}
.framework-ui-icon.framework-ui-icon-eye {background-position: -54px -72px;}
.framework-ui-icon.framework-ui-icon-icon85 {background-position: -72px -72px;}
.framework-ui-icon.framework-ui-icon-icon86 {background-position: -90px -72px;}
.framework-ui-icon.framework-ui-icon-icon87 {background-position: -108px -72px;}
.framework-ui-icon.framework-ui-icon-icon88 {background-position: -126px -72px;}
.framework-ui-icon.framework-ui-icon-icon89 {background-position: -144px -72px;}
.framework-ui-icon.framework-ui-icon-icon90 {background-position: -162px -72px;}
.framework-ui-icon.framework-ui-icon-icon91 {background-position: -180px -72px;}
.framework-ui-icon.framework-ui-icon-icon92 {background-position: -198px -72px;}
.framework-ui-icon.framework-ui-icon-icon93 {background-position: -216px -72px;}
.framework-ui-icon.framework-ui-icon-icon94 {background-position: -234px -72px;}
.framework-ui-icon.framework-ui-icon-icon95 {background-position: -252px -72px;}
.framework-ui-icon.framework-ui-icon-settings {background-position: -270px -72px;}
.framework-ui-icon.framework-ui-icon-icon97 {background-position: -288px -72px;}
.framework-ui-icon.framework-ui-icon-icon98 {background-position: -306px -72px;}
.framework-ui-icon.framework-ui-icon-icon99 {background-position: -324px -72px;}
.framework-ui-icon.framework-ui-icon-icon100 {background-position: -342px -72px;}

/* Sprite Row 6 */
.framework-ui-icon.framework-ui-icon-icon101 {background-position: -0px -90px;}
.framework-ui-icon.framework-ui-icon-icon102 {background-position: -18px -90px;}
.framework-ui-icon.framework-ui-icon-icon103 {background-position: -36px -90px;}
.framework-ui-icon.framework-ui-icon-icon104 {background-position: -54px -90px;}
.framework-ui-icon.framework-ui-icon-icon105 {background-position: -72px -90px;}
.framework-ui-icon.framework-ui-icon-icon106 {background-position: -90px -90px;}
.framework-ui-icon.framework-ui-icon-icon107 {background-position: -108px -90px;}
.framework-ui-icon.framework-ui-icon-icon108 {background-position: -126px -90px;}
.framework-ui-icon.framework-ui-icon-icon109 {background-position: -144px -90px;}
.framework-ui-icon.framework-ui-icon-icon110 {background-position: -162px -90px;}
.framework-ui-icon.framework-ui-icon-icon111 {background-position: -180px -90px;}
.framework-ui-icon.framework-ui-icon-icon112 {background-position: -198px -90px;}
.framework-ui-icon.framework-ui-icon-icon113 {background-position: -216px -90px;}
.framework-ui-icon.framework-ui-icon-icon114 {background-position: -234px -90px;}
.framework-ui-icon.framework-ui-icon-icon115 {background-position: -252px -90px;}
.framework-ui-icon.framework-ui-icon-icon116 {background-position: -270px -90px;}
.framework-ui-icon.framework-ui-icon-icon117 {background-position: -288px -90px;}
.framework-ui-icon.framework-ui-icon-icon118 {background-position: -306px -90px;}
.framework-ui-icon.framework-ui-icon-icon119 {background-position: -324px -90px;}
.framework-ui-icon.framework-ui-icon-list {background-position: -342px -90px;}

/* Sprite Row 7 */
.framework-ui-icon.framework-ui-icon-location {background-position: -0px -108px;}
.framework-ui-icon.framework-ui-icon-map {background-position: -0px -108px;}
.framework-ui-icon.framework-ui-icon-icon122 {background-position: -18px -108px;}
.framework-ui-icon.framework-ui-icon-icon123 {background-position: -36px -108px;}
.framework-ui-icon.framework-ui-icon-icon124 {background-position: -54px -108px;}
.framework-ui-icon.framework-ui-icon-icon125 {background-position: -72px -108px;}
.framework-ui-icon.framework-ui-icon-icon126 {background-position: -90px -108px;}
.framework-ui-icon.framework-ui-icon-icon127 {background-position: -108px -108px;}
.framework-ui-icon.framework-ui-icon-icon128 {background-position: -126px -108px;}
.framework-ui-icon.framework-ui-icon-icon129 {background-position: -144px -108px;}
.framework-ui-icon.framework-ui-icon-icon130 {background-position: -162px -108px;}
.framework-ui-icon.framework-ui-icon-icon131 {background-position: -180px -108px;}
.framework-ui-icon.framework-ui-icon-icon132 {background-position: -198px -108px;}
.framework-ui-icon.framework-ui-icon-icon133 {background-position: -216px -108px;}
.framework-ui-icon.framework-ui-icon-icon134 {background-position: -234px -108px;}
.framework-ui-icon.framework-ui-icon-icon135 {background-position: -252px -108px;}
.framework-ui-icon.framework-ui-icon-icon136 {background-position: -270px -108px;}
.framework-ui-icon.framework-ui-icon-icon137 {background-position: -288px -108px;}
.framework-ui-icon.framework-ui-icon-icon138 {background-position: -306px -108px;}
.framework-ui-icon.framework-ui-icon-icon139 {background-position: -324px -108px;}
.framework-ui-icon.framework-ui-icon-icon140 {background-position: -342px -108px;}

/* Sprite Row 8 */
.framework-ui-icon.framework-ui-icon-input {background-position: -0px -126px;}
.framework-ui-icon.framework-ui-icon-icon142 {background-position: -18px -126px;}
.framework-ui-icon.framework-ui-icon-icon143 {background-position: -36px -126px;}
.framework-ui-icon.framework-ui-icon-icon144 {background-position: -54px -126px;}
.framework-ui-icon.framework-ui-icon-edit {background-position: -72px -126px;}
.framework-ui-icon.framework-ui-icon-icon146 {background-position: -90px -126px;}
.framework-ui-icon.framework-ui-icon-gallery {background-position: -108px -126px;}
.framework-ui-icon.framework-ui-icon-icon148 {background-position: -126px -126px;}
.framework-ui-icon.framework-ui-icon-carousel {background-position: -144px -126px;}
.framework-ui-icon.framework-ui-icon-icon150 {background-position: -162px -126px;}
.framework-ui-icon.framework-ui-icon-icon151 {background-position: -180px -126px;}
.framework-ui-icon.framework-ui-icon-icon152 {background-position: -198px -126px;}
.framework-ui-icon.framework-ui-icon-icon153 {background-position: -216px -126px;}
.framework-ui-icon.framework-ui-icon-icon154 {background-position: -234px -126px;}
.framework-ui-icon.framework-ui-icon-icon155 {background-position: -252px -126px;}
.framework-ui-icon.framework-ui-icon-icon156 {background-position: -270px -126px;}
.framework-ui-icon.framework-ui-icon-icon157 {background-position: -288px -126px;}
.framework-ui-icon.framework-ui-icon-reload {background-position: -306px -126px;}
.framework-ui-icon.framework-ui-icon-icon159 {background-position: -324px -126px;}
.framework-ui-icon.framework-ui-icon-icon160 {background-position: -342px -126px;}

/* Sprite Row 9 */
.framework-ui-icon.framework-ui-icon-icon161 {background-position: -0px -144px;}
.framework-ui-icon.framework-ui-icon-icon162 {background-position: -18px -144px;}
.framework-ui-icon.framework-ui-icon-icon163 {background-position: -36px -144px;}
.framework-ui-icon.framework-ui-icon-icon164 {background-position: -54px -144px;}
.framework-ui-icon.framework-ui-icon-icon165 {background-position: -72px -144px;}
.framework-ui-icon.framework-ui-icon-icon166 {background-position: -90px -144px;}
.framework-ui-icon.framework-ui-icon-icon167 {background-position: -108px -144px;}
.framework-ui-icon.framework-ui-icon-icon168 {background-position: -126px -144px;}
.framework-ui-icon.framework-ui-icon-icon169 {background-position: -144px -144px;}
.framework-ui-icon.framework-ui-icon-icon170 {background-position: -162px -144px;}
.framework-ui-icon.framework-ui-icon-icon171 {background-position: -180px -144px;}
.framework-ui-icon.framework-ui-icon-icon172 {background-position: -198px -144px;}
.framework-ui-icon.framework-ui-icon-icon173 {background-position: -216px -144px;}
.framework-ui-icon.framework-ui-icon-icon174 {background-position: -234px -144px;}
.framework-ui-icon.framework-ui-icon-table {background-position: -252px -144px;}
.framework-ui-icon.framework-ui-icon-square {background-position: -270px -144px;}
.framework-ui-icon.framework-ui-icon-icon177 {background-position: -288px -144px;}
.framework-ui-icon.framework-ui-icon-icon178 {background-position: -306px -144px;}
.framework-ui-icon.framework-ui-icon-icon179 {background-position: -324px -144px;}
.framework-ui-icon.framework-ui-icon-icon180 {background-position: -342px -144px;}

/* Sprite Row 10 */
.framework-ui-icon.framework-ui-icon-icon181 {background-position: -0px -162px;}
.framework-ui-icon.framework-ui-icon-icon182 {background-position: -18px -162px;}
.framework-ui-icon.framework-ui-icon-icon183 {background-position: -36px -162px;}
.framework-ui-icon.framework-ui-icon-target {background-position: -54px -162px;}
.framework-ui-icon.framework-ui-icon-icon185 {background-position: -72px -162px;}
.framework-ui-icon.framework-ui-icon-icon186 {background-position: -90px -162px;}
.framework-ui-icon.framework-ui-icon-icon187 {background-position: -108px -162px;}
.framework-ui-icon.framework-ui-icon-icon188 {background-position: -126px -162px;}
.framework-ui-icon.framework-ui-icon-upload {background-position: -144px -162px;}
.framework-ui-icon.framework-ui-icon-icon190 {background-position: -162px -162px;}
.framework-ui-icon.framework-ui-icon-icon191 {background-position: -180px -162px;}
.framework-ui-icon.framework-ui-icon-icon192 {background-position: -198px -162px;}
.framework-ui-icon.framework-ui-icon-icon193 {background-position: -216px -162px;}
.framework-ui-icon.framework-ui-icon-icon194 {background-position: -234px -162px;}
.framework-ui-icon.framework-ui-icon-icon195 {background-position: -252px -162px;}
.framework-ui-icon.framework-ui-icon-icon196 {background-position: -270px -162px;}
.framework-ui-icon.framework-ui-icon-icon197 {background-position: -288px -162px;}
.framework-ui-icon.framework-ui-icon-search {background-position: -306px -162px;}
.framework-ui-icon.framework-ui-icon-filter {background-position: -306px -162px;}
.framework-ui-icon.framework-ui-icon-icon199 {background-position: -324px -162px;}
.framework-ui-icon.framework-ui-icon-icon200 {background-position: -342px -162px;}



/*------------------------------------*\
	$DROPDOWN MENU
\*------------------------------------*/
div.framework-ui-dropdown {
	float: left;
	position: relative;
}

div.framework-ui-dropdown > .framework-ui-control {
	position:relative;
}

div.framework-ui-dropdown > .framework-ui-control.elgg-menu-open {
	border-bottom:0;
	z-index:1000;
}

span.framework-ui-toggle {
	width: 19px;
	height: 16px;

	margin-left: 7px;
	margin-top: 1px;
	margin-right: 2px;
	padding-left: 8px;

	float: right;

	background: url(<?php echo elgg_get_site_url() ?>mod/hypeFramework/graphics/ui.buttons/toggle.png) top right no-repeat;
	border-left: 1px solid #D9D9D9;

	-webkit-transition: border-color .20s;
	-moz-transition: border .20s;
	-o-transition: border-color .20s;
	transition: border-color .20s;
}
span.framework-ui-toggle.elgg-state-active {
	background: url(<?php echo elgg_get_site_url() ?>mod/hypeFramework/graphics/ui.buttons/toggle.png) bottom right no-repeat;
}
div.framework-ui-dropdown
button:hover span.framework-ui-toggle,
.framework-ui-control:hover span.framework-ui-toggle {
	border-color: #C0C0C0;
}

div.framework-ui-dropdown-slider {
	display: none;

	overflow: hidden;

	margin: 4px 5px 5px 7px;
	position: absolute;
	top: 35px;
	right: 0;

	background: #F2F2F2;

	border: solid 1px #D9D9D9;

	-webkit-border-bottom-right-radius: 2px;
	-webkit-border-bottom-left-radius: 2px;
	-moz-border-radius-bottomright: 2px;
	-moz-border-radius-bottomleft: 2px;
	border-bottom-right-radius: 2px;
	border-bottom-left-radius: 2px;

	-webkit-transition: border-color .20s;
	-moz-transition: border .20s;
	-o-transition: border-color .20s;
	transition: border-color .20s;

	position:absolute;
}

.framework-ui-dropdown-slider.elgg-state-active {
	z-index:995;
}

.left div.framework-ui-dropdown-slider {
	margin: 0 1px 5px 7px;
}
.middle div.framework-ui-dropdown-slider {
	margin: 0 1px 5px 1px;
}
.right div.framework-ui-dropdown-slider {
	margin: 0 7px 5px 1px;
}
div.framework-ui-dropdown-slider .framework-ui-ddm {
	display: block;
	background: #F2F2F2;
	color: #585858;

	text-decoration: none;
	text-shadow: 0 1px 0 #fff;
	font: bold 11px Helvetica, Arial, sans-serif;
	line-height: 18px;
	height: 18px;

	margin: 0;
	padding: 5px 6px 4px 6px;
	width: 200px;
	float: left;

	border-top: 1px solid #FFF;
	border-bottom: 1px solid #D9D9D9;
}
div.framework-ui-dropdown-slider .framework-ui-ddm:hover {
	background: #F4F4F4;
	border-bottom-color: #C0C0C0;
}
div.framework-ui-dropdown-slider .framework-ui-ddm:active {
	border-bottom-color: #4D90FE;
	color: #4D90FE;

	-moz-box-shadow:inset 0 0 10px #D4D4D4;
	-webkit-box-shadow:inset 0 0 10px #D4D4D4;
	box-shadow:inset 0 0 10px #D4D4D4;
}
div.framework-ui-dropdown-slider .framework-ui-ddm:last-child {
	border-bottom: none;
}

.elgg-item .elgg-menu-controls-wrapper {
	float:right;
}

ul.elgg-menu-controls {
	display:inline-block;
}

.elgg-col .framework-ui-ddm:first-child {
	border-top:0;
}

.framework-ui-slider-content {
	padding:3px;
	font-size:11px;
}

.framework-ui-slider-content td {
	padding:5px;
	vertical-align:middle;
}

ul.elgg-menu-controls.elgg-menu-list-filter {
	float: right;
}

.framework-ui-dropdown-slider form {
	width: 600px;
	margin: 5px;
	padding: 10px;
	border: 1px solid #CCC;
	background: white;
	font-size: 11px;
}
