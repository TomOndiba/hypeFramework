<?php

$subtypes = array(
	'hjform' => 'hjForm',
	'hjfield' => 'hjField',
	'hjfile' => 'hjFile',
	'hjfilefolder' => 'hjFileFolder',
	'hjsegment' => 'hjSegment',
	'hjannotation' => 'hjAnnotation'
);

foreach ($subtypes as $subtype => $class) {
	if (get_subtype_id('object', $subtype)) {
		update_subtype('object', $subtype, $class);
	} else {
		add_subtype('object', $subtype, $class);
	}
}
