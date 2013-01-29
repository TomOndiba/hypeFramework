<?php

$order_by = get_input('order_by', false);
$direction = get_input('direction', 'DESC');

$headers = $vars['list_options']['list_view_options']['table']['head'];

if (!$headers)
	return true;

foreach ($headers as $key => $value) {

	if (!$value)
		continue;

	if (isset($value['colspan'])) {
		foreach ($value['colspan'] as $colspan_key => $colspan_value) {
			$ungrouped_headers[$colspan_key] = $colspan_value;
		}
	} else {
		$ungrouped_headers[$key] = $value;
	}
}

foreach ($ungrouped_headers as $header => $options) {

	if (!$options['sortable'])
		continue;

	$active_class = ($order_by == $options['sort_key'] && $direction == 'ASC') ? 'elgg-state-active' : 'elgg-state-disabled';
	$controls[$header]['asc'] = elgg_view('output/url', array(
		'text' => elgg_view_icon('asc'),
		'title' => elgg_echo('ascending'),
		'href' => elgg_http_add_url_query_elements(full_url(), array('order_by' => $options['sort_key'], 'direction' => 'ASC')),
		'class' => "sort-control-asc $active_class"
			));

	$active_class = ($order_by == $options['sort_key'] && $direction == 'DESC') ? 'elgg-state-active' : 'elgg-state-disabled';
	$controls[$header]['desc'] = elgg_view('output/url', array(
		'text' => elgg_view_icon('desc'),
		'title' => elgg_echo('descending'),
		'href' => elgg_http_add_url_query_elements(full_url(), array('order_by' => $options['sort_key'], 'direction' => 'DESC')),
		'class' => "sort-control-asc $active_class"
			));

	if ($order_by != $options['sort_key'] || ($order_by == $options['sort_key'] && $direction == 'DESC')) {
		$title_url = elgg_http_add_url_query_elements(full_url(), array('order_by' => $options['sort_key'], 'direction' => 'ASC'));
	} else {
		$title_url = elgg_http_add_url_query_elements(full_url(), array('order_by' => $options['sort_key'], 'direction' => 'DESC'));
	}

	$title_class = ($order_by == $options['sort_key']) ? 'elgg-state-active' : 'elgg-state-selectable';

	$text = (isset($options['text'])) ? $options['text'] : elgg_echo("table:head:$header");

	$controls[$header]['title'] = elgg_view('output/url', array(
		'text' => $text,
		'title' => elgg_echo('sort:column'),
		'href' => $title_url,
		'class' => "sort-title $title_class"
			));
}

echo '<thead>';
echo '<tr class="table-header">';

foreach ($headers as $key => $options) {

	if (isset($options['colspan'])) {
		$colspan = count($options['colspan']);
		$class = "table-header table-header-spanned table-header-$key";
		if (isset($options['class'])) {
			$class = "$class {$options['class']}";
		}
		echo "<th colspan=\"$colspan\" class=\"$class\">";
		foreach ($options['colspan'] as $col_key => $col_options) {
			echo '<div>';
			if (isset($controls[$col_key])) {
				echo $controls[$col_key]['title'];
				echo $controls[$col_key]['asc'];
				echo $controls[$col_key]['desc'];
			} else {
				$text = (isset($col_options['text'])) ? $col_options['text'] : elgg_echo("table:head:$header");
				echo $text;
			}
			echo '</div>';
		}
		echo '</th>';
	} else {
		$header = $key;
		$class = "table-header table-header-$key";
		echo "<th class=\"$class\">";
		if (isset($controls[$header])) {
			echo $controls[$header]['title'];
			echo $controls[$header]['asc'];
			echo $controls[$header]['desc'];
		} else {
			$text = (isset($options['text'])) ? $options['text'] : elgg_echo("table:head:$header");
			echo $text;
		}
		echo '</th>';
	}
}

echo '</tr>';
echo '</thead>';