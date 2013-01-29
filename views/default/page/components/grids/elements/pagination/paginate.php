<?php

if (elgg_in_context('widget')) {
	return true;
}

$list_id = elgg_extract('list_id', $vars);

$list_options = elgg_extract('list_options', $vars);

$offset = abs((int) elgg_extract('list_pagination_offset', $list_options, 0));
$offset_key = "offset[$list_id]";

if (!$limit = (int) elgg_extract('list_pagination_limit', $list_options, 10)) {
	$limit = 10;
}

$count = (int) elgg_extract('count', $vars, 0);

$base_url = elgg_extract('list_pagination_base_url', $list_options, current_page_url());
$num_pages = elgg_extract('list_pagination_num_pages', $list_options, 10);

$delta = ceil($num_pages / 2);

if ($count <= $limit && $offset == 0) {
	// no need for pagination
	return true;
}

$total_pages = ceil($count / $limit);
$current_page = ceil($offset / $limit) + 1;

$pages = new stdClass();
$pages->prev = array(
	'text' => '&laquo; ' . elgg_echo('previous'),
	'href' => '',
	'is_trusted' => true,
	'data-list' => $list_id,
	'data-scenario' => 'paginateList'
);
$pages->next = array(
	'text' => elgg_echo('next') . ' &raquo;',
	'href' => '',
	'is_trusted' => true,
	'data-list' => $list_id,
	'data-scenario' => 'paginateList'
);
$pages->items = array();

// Add pages before the current page
if ($current_page > 1) {
	$prev_offset = $offset - $limit;
	if ($prev_offset < 0) {
		$prev_offset = 0;
	}

	$pages->prev['href'] = hj_framework_http_add_url_query_elements($base_url, array($offset_key => $prev_offset));

	$first_page = $current_page - $delta;
	if ($first_page < 1) {
		$first_page = 1;
	}

	$pages->items = range($first_page, $current_page - 1);
}


$pages->items[] = $current_page;


// add pages after the current one
if ($current_page < $total_pages) {
	$next_offset = $offset + $limit;
	if ($next_offset >= $count) {
		$next_offset--;
	}

	$pages->next['href'] = hj_framework_http_add_url_query_elements($base_url, array($offset_key => $next_offset));

	$last_page = $current_page + $delta;
	if ($last_page > $total_pages) {
		$last_page = $total_pages;
	}

	$pages->items = array_merge($pages->items, range($current_page + 1, $last_page));
}


echo "<ul class=\"elgg-pagination\" data-list=\"$list_id\">";

if ($pages->prev['href']) {
	$link = elgg_view('output/url', $pages->prev);
	echo "<li>$link</li>";
} else {
	echo "<li class=\"elgg-state-disabled\"><span>{$pages->prev['text']}</span></li>";
}

foreach ($pages->items as $page) {
	if ($page == $current_page) {
		echo "<li class=\"elgg-state-selected\"><span>$page</span></li>";
	} else {
		$page_offset = (($page - 1) * $limit);
		$url = hj_framework_http_add_url_query_elements($base_url, array($offset_key => $page_offset));
		$link = elgg_view('output/url', array(
			'href' => $url,
			'text' => $page,
			'is_trusted' => true,
			'data-list' => $list_id,
			'data-scenario' => 'paginateList'
		));
		echo "<li>$link</li>";
	}
}

if ($pages->next['href']) {
	$link = elgg_view('output/url', $pages->next);
	echo "<li>$link</li>";
} else {
	echo "<li class=\"elgg-state-disabled\"><span>{$pages->next['text']}</span></li>";
}

echo '</ul>';