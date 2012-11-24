<?php
echo elgg_view('js/vendors/colorpicker/colorpicker.js');
?>
    elgg.provide('framework.colorpicker');
       
    framework.colorpicker.init = function() {
        $('.hj-color-picker').miniColors();
    };
    
    elgg.register_hook_handler('init', 'system', framework.colorpicker.init);
    elgg.register_hook_handler('success', 'ajax', framework.colorpicker.init);
