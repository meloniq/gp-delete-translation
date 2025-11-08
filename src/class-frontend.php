<?php
/**
 * Frontend class.
 *
 * @package Meloniq\GpDeleteTranslation
 */

namespace Meloniq\GpDeleteTranslation;

use GP;

/**
 * Frontend class.
 */
class Frontend {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register_default_scripts();

		add_action( 'gp_pre_tmpl_load', array( $this, 'enqueue_editor_script' ), 10, 1 );
		add_action( 'gp_post_tmpl_load', array( $this, 'add_delete_translation_button' ), 10, 2 );
	}

	/**
	 * Register default scripts and styles.
	 *
	 * @return void
	 */
	public function register_default_scripts(): void {
		wp_register_script( 'gpdts-editor', GPDTS_PLUGIN_URL . 'assets/editor.js', array( 'gp-common' ), '1.0', true );
	}

	/**
	 * Enqueue the editor script and localize its options.
	 *
	 * @param string $template The template name.
	 *
	 * @return void
	 */
	public function enqueue_editor_script( $template ): void {
		if ( 'header' !== $template ) {
			return;
		}

		gp_enqueue_scripts( array( 'gpdts-editor' ) );

		$editor_options = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		);
		wp_localize_script( 'gpdts-editor', '$gpdts_editor_options', $editor_options );
	}

	/**
	 * Adds a "Delete Translation" button to the translation row editor.
	 *
	 * @param string $template The template name.
	 * @param array  $args     Arguments passed to the template. Passed by reference.
	 *
	 * @return void
	 */
	public function add_delete_translation_button( $template, &$args ): void {
		// check the template name.
		if ( 'translation-row-editor-meta-status' !== $template ) {
			return;
		}

		// Extract arguments for easier access.
		extract( $args, EXTR_SKIP ); // phpcs:ignore

		// Ensure we have a valid translation object.
		if ( ! isset( $translation->id ) ) {
			return;
		}

		// Check if the current user can delete this translation. Same capability as approving translations.
		$can_delete_translation = GP::$permission->current_user_can( 'approve', 'translation', $translation->id, array( 'translation' => $translation ) );
		if ( ! $can_delete_translation ) {
			return;
		}

		// Include the button template.
		include GPDTS_PLUGIN_DIR . 'template/delete-translation-button.php';
	}
}
