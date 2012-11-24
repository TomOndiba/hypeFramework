<?php if (FALSE) : ?>
<script type="text/javascript">
<?php endif; ?>
    
    elgg.provide('framework.fieldcheck');
    
    framework.fieldcheck.init = function(form_container) {
        var check = true;
        $('[mandatory="1"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        $('[mandatory="true"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        $('[mandatory="yes"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        $('[mandatory="mandatory"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
		$('[required"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
		$('[required="1"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        $('[required="true"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        $('[required="yes"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        $('[required="required"]', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        if (!check) alert(elgg.echo('framework:formcheck:fieldmissing'));
        return check;
    }
    
    elgg.register_hook_handler('init', 'system', framework.fieldcheck.init);
    elgg.register_hook_handler('success', 'ajax', framework.fieldcheck.init);
    
<?php if (FALSE) : ?></script><?php endif; ?>