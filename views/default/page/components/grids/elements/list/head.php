<?php

if (isset($vars['list_options']['filter'])) {
	echo '<div class="list-filter">';
	echo $vars['list_options']['filter'];
	echo '</div>';
}