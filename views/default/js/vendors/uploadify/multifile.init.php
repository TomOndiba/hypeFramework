<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('framework.uploadify');

	framework.uploadify.init = function() {

		$(function() {
			$container = $('#hj-multifile-upload');
			var input_name = $('input[name="hj-multifile-upload-fieldname"]').val();
			$container.uploadify({
				'swf'      : <?php echo HYPEFRAMEWROK_PATH_VIEWS ?> + 'js/vendors/uploadify/uploadify.swf',
				'uploader' : elgg.security.addToken(elgg.get_site_url() + '<?php echo HYPEFRAMEWROK_PAGEHANDLER ?>/multifile'),
				'removeCompleted' : false,
				'onUploadSuccess' : function(file, data, response) {
					var json = $.parseJSON(data);
					$input = $('<input>').attr('type', 'hidden').attr('name', input_name + '[]').val(json.value);
					$('#hj-multifile-upload').append($input);
				},
				'onUploadError' : function(file, errorCode, errorMsg, errorStr) {
				},
				'formData' : { 
					'Elgg' : '<?php echo session_id() ?>',
					'SESSION' : '<?php global $_SESSION; echo json_encode($_SESSION); ?>',
					'userid' : elgg.get_logged_in_user_guid()
				},
				'buttonClass' : 'elgg-button elgg-button-action hj-framework-button-multifile'
			});
		});

	}


	elgg.register_hook_handler('init', 'system', framework.uploadify.init);
	elgg.register_hook_handler('success', 'ajax', framework.uploadify.init);

<?php if (FALSE) : ?></script><?php
endif;
?>
