<?php
/**
 * Ajax class.
 *
 * @package Meloniq\GpDeleteTranslation
 */

namespace Meloniq\GpDeleteTranslation;

use GP;

/**
 * Ajax class.
 */
class Ajax {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_gpdts_delete_translation', array( $this, 'handle_delete_translation' ) );
	}

	/**
	 * Handle the AJAX request to delete a translation.
	 *
	 * @return void
	 */
	public function handle_delete_translation(): void {
		// Get translation ID from POST data.
		$translation_id = isset( $_POST['translation_id'] ) ? intval( $_POST['translation_id'] ) : 0;

		// Check nonce for security.
		check_ajax_referer( 'gpdts_delete_translation_' . $translation_id, 'nonce', true );

		if ( $translation_id <= 0 ) {
			wp_send_json_error( array( 'message' => __( 'Invalid translation ID.', 'gp-delete-translation' ) ) );
		}

		$translation = GP::$translation->find_one( "id = '$translation_id'" );

		if ( ! $translation ) {
			wp_send_json_error( array( 'message' => __( 'Translation not found.', 'gp-delete-translation' ) ) );
		}

		// Perform deletion.
		$translation->delete();

		wp_send_json_success( array( 'message' => __( 'Translation deleted successfully.', 'gp-delete-translation' ) ) );
	}
}
