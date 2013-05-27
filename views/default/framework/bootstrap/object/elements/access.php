<?php

elgg_load_js('framework.access.js');
elgg_load_css('framework.access.css');

$entity = elgg_extract('entity', $vars);

$access_id = $entity->access_id;

if ($entity->canEdit()) {

	$access = get_write_access_array($entity->owner_guid);

	foreach ($access as $id => $name) {
		switch ($id) {
			default :
				$classname = preg_replace('/[^a-z0-9\-]/i', '-', strtolower($name));
				$class = "access-default access-custom access-$classname";
				break;

			case ACCESS_PUBLIC :
				$class = "access-default access-public";
				break;

			case ACCESS_LOGGED_IN :
				$class = "access-default access-loggedin";
				break;

			case ACCESS_FRIENDS :
				$class = "access-default access-friends";
				break;

			case ACCESS_PRIVATE :
				$class = "access-default access-private";
				break;
		}

		if ($id == $access_id) {
			$toggle = elgg_view('output/url', array(
				'text' => "<span class=\"$class\"></span>",
				'title' => $name,
				'data-guid' => $entity->guid,
				'href' => false,
				'class' => 'access-select-toggle'
			));
		}

		$options[] = elgg_view('output/url', array(
			'text' => "<span class=\"$class\"></span><i>$name</i>",
			'href' => "action/framework/access/set?guid=$entity->guid&access_id=$id",
			'is_action' => true,
			'class' => ($id == $access_id) ? 'elgg-state-selected' : null
		));
	}

	echo '<div class="access-group">';
	echo $toggle;
	echo "<div class=\"hidden access-select\" data-guid=\"$entity->guid\">";
	echo implode('', $options);
	echo '</div>';
	echo '</div>';

}
