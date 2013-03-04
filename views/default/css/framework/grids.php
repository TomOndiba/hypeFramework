<?php if (FALSE) : ?>
	<style type="text/css">
	<?php endif; ?>

	.hj-framework-list-wrapper {
		position:relative;
	}
	.hj-framework-list-wrapper th {
		float:none;
	}
	tr.hj-framework-list-item-new {
		/*border-left: 5px solid #666;*/
	}
	tr.hj-framework-list-item-updated {
		/*border-left: 5px solid #bbb;*/
	}

	.sort-control {
		font-size: 1.3em;
		margin: -4px 0  0 -4px;
	}
	.sort-control.sort-control-asc {
		margin-left: 4px;
	}
	.sort-control.elgg-state-active {
		color: black;
	}

	.hj-framework-list-limit-select {
		margin: 8px;
	}

	.hj-framework-list-wrapper .hj-framework-list-filter {
		font-size: .9em;
		text-align:right;
	}
	.hj-framework-list-wrapper .hj-framework-list-filter input[type=text] {
		min-height: auto;
		padding: 3px 10px;
	}

	.table-header.table-header-menu {
		width: 125px;
	}

	.table-cell-menu .elgg-menu {
		float: none;
		height: auto;
	}

	.table-cell-menu .elgg-menu a,  .table-cell-menu .elgg-menu span {
		font-size:0.9em
	}

	.elgg-menu-list-filter {
		display: inline-block;
		margin: 5px 10px;
	}
	.elgg-menu-list-filter > li {
		display: inline-block;
		padding: 0 5px;
		border-right:1px solid #e8e8e8;
	}

	.elgg-menu-list-filter > li.elgg-state-selected a {
		color: #ccc;
		font-weight: bold;
	}
	.elgg-menu-list-filter > li:last-child {
		border: 0;
	}

	.elgg-table-alt.hj-framework-table-view td:first-child {
		width:auto;
	}