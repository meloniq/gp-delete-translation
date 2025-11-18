/* global $gprts_editor_options, $gp, wp */
var $gprts = $gprts || {};

$gprts.editor = (function () {
	return {
		table: null,

		init: function (table) {
			$gprts.editor.table = table;
			$gprts.editor.install_hooks();
		},

		install_hooks: function () {
			if (!$gprts.editor.table) return;
			$gprts.editor.table.addEventListener('click', function (event) {
				const button = event.target.closest('button.remove');
				if (button) {
					event.preventDefault();
					$gprts.editor.remove_translation(button);
				}
			});
		},

		remove_translation: function (button) {
			button.disabled = true;
			$gp.notices.notice(wp.i18n.__('Removing translation...', 'glotpress'));

			const data = new URLSearchParams({
				action: 'gprts_remove_translation',
				translation_id: button.dataset.id,
				original_id: button.dataset.original_id,
				nonce: button.dataset.nonce,
			});

			fetch($gprts_editor_options.ajax_url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: data.toString(),
			})
				.then(async (response) => {
					if (!response.ok) {
						const text = await response.text();
						throw new Error(text || 'HTTP error');
					}
					return response.json();
				})
				.then((response) => {
					$gp.notices.success(response.data.message);
					const { original_id, id } = button.dataset;
					document.getElementById(`preview-${original_id}-${id}`)?.remove();
					document.getElementById(`editor-${original_id}-${id}`)?.remove();
				})
				.catch((error) => {
					const msg = error.message
						? wp.i18n.sprintf(wp.i18n.__('Error: %s', 'glotpress'), error.message)
						: wp.i18n.__('Error removing translation!', 'glotpress');
					$gp.notices.error(msg);
				});
		},
	};
})();

document.addEventListener('DOMContentLoaded', function () {
	const table = document.getElementById('translations');
	if (table) {
		$gprts.editor.init(table);
	}
});
