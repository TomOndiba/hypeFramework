<?php

$field = elgg_extract('entity', $vars);

$subject_guid = elgg_extract('subject_guid', $vars);
$subject = get_entity($subject_guid);

if (!elgg_instanceof($field)) {
    return true;
}

$form = $field->getContainerForm();
$options = $field->getParams($subject);

if ($field->input_type != 'hidden') {
    $field_input .= elgg_view('input/' . $field->input_type, $options);

    if ($field->mandatory || $field->required) {
        $required_class = "required";
		$required = true;
    } else {
        $required_class = null;
    }

    $field_view = <<<HTML
    <div class="$required_class">
        <label for="$field->name"><span class="hj-field-label">{$field->getLabel()}</span></label><br/>
        <div class="hj-formbuilder-input hj-margin-ten">$field_input</div>
    </div>
HTML;
} else {
    $field_view = elgg_view('input/' . $field->input_type, $options);
}


if (elgg_is_admin_logged_in() && elgg_in_context('admin')) {
    $field_view .= elgg_view('framework/formbuilder/menu', $vars);
}

echo $field_view;