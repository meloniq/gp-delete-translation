<?php
/**
 * Template for the "Delete Translation" button in the translation row editor
 *
 * @package Meloniq\GpDeleteTranslation
 */

$translation_id = isset( $translation->id ) ? $translation->id : 0;
$original_id    = isset( $translation->original_id ) ? $translation->original_id : 0;

$nonce        = wp_create_nonce( 'gpdts_delete_translation_' . $translation_id );
$button_title = __( 'Delete this translation permanently.', 'gp-delete-translation' );
?>
<dl class="gpdts-delete-translation">
	<dt><?php esc_html_e( 'Action:', 'glotpress' ); ?></dt>
	<dd>
		<button
			class="button is-small delete"
			data-nonce="<?php echo esc_attr( $nonce ); ?>"
			data-id="<?php echo esc_attr( $translation_id ); ?>"
			data-original_id="<?php echo esc_attr( $original_id ); ?>"
			title="<?php echo esc_attr( $button_title ); ?>"
		>
			<strong>ˣ</strong>
			<span><?php echo esc_html_x( 'Delete', 'Action', 'gp-delete-translation' ); ?></span>
		</button>
	</dd>
</dl>
