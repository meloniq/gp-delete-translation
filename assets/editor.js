/* global $gpdts_editor_options, $gp, wp */
/* eslint camelcase: "off" */
var $gpdts = $gpdts || {};
$gpdts.editor = (
	function ($) {
		return {
			table: null,
			init: function (table) {
				$gpdts.editor.table = table;
				$gpdts.editor.install_hooks();
			},
			install_hooks: function () {
				$($gpdts.editor.table).on('click', 'button.delete', $gpdts.editor.hooks.delete_translation);
			},
			delete_translation: function (button) {
				var data;

				button.prop('disabled', true);
				$gp.notices.notice(wp.i18n.__('Removing translation...', 'glotpress'));

				data = {
					action: 'gpdts_delete_translation',
					translation_id: button.data('id'),
					original_id: button.data('original_id'),
					nonce: button.data('nonce'),
				};

				$.ajax({
					type: 'POST',
					url: $gpdts_editor_options.ajax_url,
					data: data,
					success: function (response) {
						$gp.notices.success(response.data.message);
						// delete editor and preview rows
						$('#preview-' + button.data('original_id') + '-' + button.data('id')).remove();
						$('#editor-' + button.data('original_id') + '-' + button.data('id')).remove();
					},
					error: function (xhr, msg) {
						/* translators: %s: Error message. */
						msg = xhr.responseText ? wp.i18n.sprintf(wp.i18n.__('Error: %s', 'glotpress'), xhr.responseText) : wp.i18n.__('Error removing translation!', 'glotpress');
						$gp.notices.error(msg);
					},
				});
			},
			hooks: {
				delete_translation: function () {
					$gpdts.editor.delete_translation($(this));
					return false;
				},
			},
		};
	}(jQuery)
);

jQuery(function ($) {
	$gpdts.editor.init($('#translations'));
});
