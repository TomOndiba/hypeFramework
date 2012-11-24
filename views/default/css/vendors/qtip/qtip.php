/*! qTip2 - Pretty powerful tooltips - v2.0.0 - 2012-09-10
* http://craigsworks.com/projects/qtip2/
* Copyright (c) 2012 Craig Michael Thompson; Licensed MIT, GPL */

/* Fluid class for determining actual width in IE */
#qtip-rcontainer{
	position: absolute;
	left: -28000px;
	top: -28000px;
	display: block;
	visibility: hidden;
}

	/* Fluid class for determining actual width in IE */
	#qtip-rcontainer .ui-tooltip{
		display: block !important;
		visibility: hidden !important;
		position: static !important;
		float: left !important;
	}

/* Core qTip styles */
.ui-tooltip, .qtip{
	position: absolute;
	left: -28000px;
	top: -28000px;
	display: none;

	max-width: 280px;
	min-width: 50px;

	font-size: 10.5px;
	line-height: 12px;
}

	.ui-tooltip-content{
		position: relative;
		padding: 5px 9px;
		overflow: hidden;

		text-align: left;
		word-wrap: break-word;
	}

	.ui-tooltip-titlebar{
		position: relative;
		min-height: 14px;
		padding: 5px 35px 5px 10px;
		overflow: hidden;

		border-width: 0 0 1px;
		font-weight: bold;
	}

	.ui-tooltip-titlebar + .ui-tooltip-content{ border-top-width: 0 !important; }

		/* Default close button class */
		.ui-tooltip-titlebar .ui-state-default{
			position: absolute;
			right: 4px;
			top: 50%;
			margin-top: -9px;

			cursor: pointer;
			outline: medium none;

			border-width: 1px;
			border-style: solid;
		}

		* html .ui-tooltip-titlebar .ui-state-default{ top: 16px; } /* IE fix */

		.ui-tooltip-titlebar .ui-icon,
		.ui-tooltip-icon .ui-icon{
			display: block;
			text-indent: -1000em;
			direction: ltr;
		}

		.ui-tooltip-icon, .ui-tooltip-icon .ui-icon{
			-moz-border-radius: 3px;
			-webkit-border-radius: 3px;
			border-radius: 3px;
			text-decoration: none;
		}

			.ui-tooltip-icon .ui-icon{
				width: 18px;
				height: 14px;

				text-align: center;
				text-indent: 0;
				font: normal bold 10px/13px Tahoma,sans-serif;

				color: inherit;
				background: transparent none no-repeat -100em -100em;
			}


/* Applied to 'focused' tooltips e.g. most recently displayed/interacted with */
.ui-tooltip-focus{}

/* Applied on hover of tooltips i.e. added/removed on mouseenter/mouseleave respectively */
.ui-tooltip-hover{}

/* Default tooltip style */
.ui-tooltip-default{
	border-width: 1px;
	border-style: solid;
	border-color: #F1D031;

	background-color: #FFFFA3;
	color: #555;
}

	.ui-tooltip-default .ui-tooltip-titlebar{
		background-color: #FFEF93;
	}

	.ui-tooltip-default .ui-tooltip-icon{
		border-color: #CCC;
		background: #F1F1F1;
		color: #777;
	}

	.ui-tooltip-default .ui-tooltip-titlebar .ui-state-hover{
		border-color: #AAA;
		color: #111;
	}


/*! Light tooltip style */
.ui-tooltip-light{
	background-color: white;
	border-color: #E2E2E2;
	color: #454545;
}

	.ui-tooltip-light .ui-tooltip-titlebar{
		background-color: #f1f1f1;
	}


/*! Dark tooltip style */
.ui-tooltip-dark{
	background-color: #505050;
	border-color: #303030;
	color: #f3f3f3;
}

	.ui-tooltip-dark .ui-tooltip-titlebar{
		background-color: #404040;
	}

	.ui-tooltip-dark .ui-tooltip-icon{
		border-color: #444;
	}

	.ui-tooltip-dark .ui-tooltip-titlebar .ui-state-hover{
		border-color: #303030;
	}


/*! Cream tooltip style */
.ui-tooltip-cream{
	background-color: #FBF7AA;
	border-color: #F9E98E;
	color: #A27D35;
}

	.ui-tooltip-cream .ui-tooltip-titlebar{
		background-color: #F0DE7D;
	}

	.ui-tooltip-cream .ui-state-default .ui-tooltip-icon{
		background-position: -82px 0;
	}


/*! Red tooltip style */
.ui-tooltip-red{
	background-color: #F78B83;
	border-color: #D95252;
	color: #912323;
}

	.ui-tooltip-red .ui-tooltip-titlebar{
		background-color: #F06D65;
	}

	.ui-tooltip-red .ui-state-default .ui-tooltip-icon{
		background-position: -102px 0;
	}

	.ui-tooltip-red .ui-tooltip-icon{
		border-color: #D95252;
	}

	.ui-tooltip-red .ui-tooltip-titlebar .ui-state-hover{
		border-color: #D95252;
	}


/*! Green tooltip style */
.ui-tooltip-green{
	background-color: #CAED9E;
	border-color: #90D93F;
	color: #3F6219;
}

	.ui-tooltip-green .ui-tooltip-titlebar{
		background-color: #B0DE78;
	}

	.ui-tooltip-green .ui-state-default .ui-tooltip-icon{
		background-position: -42px 0;
	}


/*! Blue tooltip style */
.ui-tooltip-blue{
	background-color: #E5F6FE;
	border-color: #ADD9ED;
	color: #5E99BD;
}

	.ui-tooltip-blue .ui-tooltip-titlebar{
		background-color: #D0E9F5;
	}

	.ui-tooltip-blue .ui-state-default .ui-tooltip-icon{
		background-position: -2px 0;
	}


/* Add shadows to your tooltips in: FF3+, Chrome 2+, Opera 10.6+, IE9+, Safari 2+ */
.ui-tooltip-shadow{
	-webkit-box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.15);
	-moz-box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.15);
	box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.15);
}

/* Add rounded corners to your tooltips in: FF3+, Chrome 2+, Opera 10.6+, IE9+, Safari 2+ */
.ui-tooltip-rounded,
.ui-tooltip-tipsy,
.ui-tooltip-bootstrap{
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
}

/* Youtube tooltip style */
.ui-tooltip-youtube{
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	border-radius: 2px;

	-webkit-box-shadow: 0 0 3px #333;
	-moz-box-shadow: 0 0 3px #333;
	box-shadow: 0 0 3px #333;

	color: white;
	border-width: 0;

	background: #4A4A4A;
	background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0,#4A4A4A),color-stop(100%,black));
	background-image: -webkit-linear-gradient(top,#4A4A4A 0,black 100%);
	background-image: -moz-linear-gradient(top,#4A4A4A 0,black 100%);
	background-image: -ms-linear-gradient(top,#4A4A4A 0,black 100%);
	background-image: -o-linear-gradient(top,#4A4A4A 0,black 100%);
}

	.ui-tooltip-youtube .ui-tooltip-titlebar{
		background-color: #4A4A4A;
		background-color: rgba(0,0,0,0);
	}

	.ui-tooltip-youtube .ui-tooltip-content{
		padding: .75em;
		font: 12px arial,sans-serif;

		filter: progid:DXImageTransform.Microsoft.Gradient(GradientType=0,StartColorStr=#4a4a4a,EndColorStr=#000000);
		-ms-filter: "progid:DXImageTransform.Microsoft.Gradient(GradientType=0,StartColorStr=#4a4a4a,EndColorStr=#000000);";
	}

	.ui-tooltip-youtube .ui-tooltip-icon{
		border-color: #222;
	}

	.ui-tooltip-youtube .ui-tooltip-titlebar .ui-state-hover{
		border-color: #303030;
	}


/* jQuery TOOLS Tooltip style */
.ui-tooltip-jtools{
	background: #232323;
	background: rgba(0, 0, 0, 0.7);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#717171), to(#232323));
	background-image: -moz-linear-gradient(top, #717171, #232323);
	background-image: -webkit-linear-gradient(top, #717171, #232323);
	background-image: -ms-linear-gradient(top, #717171, #232323);
	background-image: -o-linear-gradient(top, #717171, #232323);

	border: 2px solid #ddd;
	border: 2px solid rgba(241,241,241,1);

	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	border-radius: 2px;

	-webkit-box-shadow: 0 0 12px #333;
	-moz-box-shadow: 0 0 12px #333;
	box-shadow: 0 0 12px #333;
}

	/* IE Specific */
	.ui-tooltip-jtools .ui-tooltip-titlebar{
		background-color: transparent;
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#717171,endColorstr=#4A4A4A);
		-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#717171,endColorstr=#4A4A4A)";
	}
	.ui-tooltip-jtools .ui-tooltip-content{
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#4A4A4A,endColorstr=#232323);
		-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#4A4A4A,endColorstr=#232323)";
	}

	.ui-tooltip-jtools .ui-tooltip-titlebar,
	.ui-tooltip-jtools .ui-tooltip-content{
		background: transparent;
		color: white;
		border: 0 dashed transparent;
	}

	.ui-tooltip-jtools .ui-tooltip-icon{
		border-color: #555;
	}

	.ui-tooltip-jtools .ui-tooltip-titlebar .ui-state-hover{
		border-color: #333;
	}


/* Cluetip style */
.ui-tooltip-cluetip{
	-webkit-box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.4);
	-moz-box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.4);
	box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.4);

	background-color: #D9D9C2;
	color: #111;
	border: 0 dashed transparent;
}

	.ui-tooltip-cluetip .ui-tooltip-titlebar{
		background-color: #87876A;
		color: white;
		border: 0 dashed transparent;
	}

	.ui-tooltip-cluetip .ui-tooltip-icon{
		border-color: #808064;
	}

	.ui-tooltip-cluetip .ui-tooltip-titlebar .ui-state-hover{
		border-color: #696952;
		color: #696952;
	}


/* Tipsy style */
.ui-tooltip-tipsy{
	background: black;
	background: rgba(0, 0, 0, .87);

	color: white;
	border: 0 solid transparent;

	font-size: 11px;
	font-family: 'Lucida Grande', sans-serif;
	font-weight: bold;
	line-height: 16px;
	text-shadow: 0 1px black;
}

	.ui-tooltip-tipsy .ui-tooltip-titlebar{
		padding: 6px 35px 0 10;
		background-color: transparent;
	}

	.ui-tooltip-tipsy .ui-tooltip-content{
		padding: 6px 10;
	}

	.ui-tooltip-tipsy .ui-tooltip-icon{
		border-color: #222;
		text-shadow: none;
	}

	.ui-tooltip-tipsy .ui-tooltip-titlebar .ui-state-hover{
		border-color: #303030;
	}


/* Tipped style */
.ui-tooltip-tipped{
	border: 3px solid #959FA9;

	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;

	background-color: #F9F9F9;
	color: #454545;

	font-weight: normal;
	font-family: serif;
}

	.ui-tooltip-tipped .ui-tooltip-titlebar{
		border-bottom-width: 0;

		color: white;
		background: #3A79B8;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#3A79B8), to(#2E629D));
		background-image: -webkit-linear-gradient(top, #3A79B8, #2E629D);
		background-image: -moz-linear-gradient(top, #3A79B8, #2E629D);
		background-image: -ms-linear-gradient(top, #3A79B8, #2E629D);
		background-image: -o-linear-gradient(top, #3A79B8, #2E629D);
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#3A79B8,endColorstr=#2E629D);
		-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#3A79B8,endColorstr=#2E629D)";
	}

	.ui-tooltip-tipped .ui-tooltip-icon{
		border: 2px solid #285589;
		background: #285589;
	}

		.ui-tooltip-tipped .ui-tooltip-icon .ui-icon{
			background-color: #FBFBFB;
			color: #555;
		}


/**
 * Twitter Bootstrap style.
 *
 * Tested with IE 8, IE 9, Chrome 18, Firefox 9, Opera 11.
 * Does not work with IE 7.
 */
.ui-tooltip-bootstrap{
	font-size: 13px;
	line-height: 18px;

	color: #333333;
	background-color: #ffffff;


	border: 1px solid #ccc;
	border: 1px solid rgba(0, 0, 0, 0.2);

	*border-right-width: 2px;
	*border-bottom-width: 2px;

	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;

	-webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	-moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);

	-webkit-background-clip: padding-box;
	-moz-background-clip: padding;
	background-clip: padding-box;
}

	.ui-tooltip-bootstrap .ui-tooltip-titlebar{
		font-size: 18px;
		line-height: 22px;

		border-bottom: 1px solid #ccc;
		background-color: transparent;
	}

		.ui-tooltip-bootstrap .ui-tooltip-titlebar .ui-state-default{
			right: 9px; top: 49%;
			border-style: none;
		}

	.ui-tooltip-bootstrap .ui-tooltip-icon{
		background: white;
	}

		.ui-tooltip-bootstrap .ui-tooltip-icon .ui-icon{
			width: auto;
			height: auto;
			float: right;
			font-size: 20px;
			font-weight: bold;
			line-height: 18px;
			color: #000000;
			text-shadow: 0 1px 0 #ffffff;
			opacity: 0.2;
			filter: alpha(opacity=20);
		}

		.ui-tooltip-bootstrap .ui-tooltip-icon .ui-icon:hover{
			color: #000000;
			text-decoration: none;
			cursor: pointer;
			opacity: 0.4;
			filter: alpha(opacity=40);
		}


/* IE9 fix - removes all filters */
.ui-tooltip:not(.ie9haxors) div.ui-tooltip-content,
.ui-tooltip:not(.ie9haxors) div.ui-tooltip-titlebar{
	filter: none;
	-ms-filter: none;
}


/* Tips plugin */
.ui-tooltip .ui-tooltip-tip{
	margin: 0 auto;
	overflow: hidden;
	z-index: 10;
}

	.ui-tooltip .ui-tooltip-tip,
	.ui-tooltip .ui-tooltip-tip .qtip-vml{
		position: absolute;

		line-height: 0.1px !important;
		font-size: 0.1px !important;
		color: #123456;

		background: transparent;
		border: 0 dashed transparent;
	}

	.ui-tooltip .ui-tooltip-tip canvas{ top: 0; left: 0; }

	.ui-tooltip .ui-tooltip-tip .qtip-vml{
		behavior: url(#default#VML);
		display: inline-block;
		visibility: visible;
	}
/* Modal plugin */
#qtip-overlay{
	position: fixed;
	left: -10000em;
	top: -10000em;
}

	/* Applied to modals with show.modal.blur set to true */
	#qtip-overlay.blurs{ cursor: pointer; }

	/* Change opacity of overlay here */
	#qtip-overlay div{
		position: absolute;
		left: 0; top: 0;
		width: 100%; height: 100%;

		background-color: black;

		opacity: 0.7;
		filter:alpha(opacity=70);
		-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=70)";
	}

