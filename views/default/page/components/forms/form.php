<?php

$form_name = get_input('form');

if (!$form_name) return true;

$form = hj_framework_init_form($form_name);
echo hj_framework_view_form($form);
