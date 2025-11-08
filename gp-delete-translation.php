<?php
/**
 * Plugin Name:       GP Delete Translation
 * Plugin URI:        https://blog.meloniq.net/gp-delete-translation/
 *
 * Description:       GlotPress plugin to permanently delete unwanted translations.
 * Tags:              glotpress, translations, delete, cleanup
 *
 * Requires at least: 4.9
 * Requires PHP:      7.4
 * Version:           1.0
 *
 * Author:            MELONIQ.NET
 * Author URI:        https://meloniq.net/
 *
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain:       gp-delete-translation
 *
 * Requires Plugins:  glotpress
 *
 * @package Meloniq\GpDeleteTranslation
 */

namespace Meloniq\GpDeleteTranslation;

// If this file is accessed directly, then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'GPDTS_TD', 'gp-delete-translation' );
define( 'GPDTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GPDTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * GP Init Setup.
 *
 * @return void
 */
function gp_init() {
	global $gpdts_button;

	require_once __DIR__ . '/src/class-ajax.php';
	require_once __DIR__ . '/src/class-frontend.php';

	$gpdts_button['ajax']     = new Ajax();
	$gpdts_button['frontend'] = new Frontend();
}
add_action( 'gp_init', 'Meloniq\GpDeleteTranslation\gp_init' );
