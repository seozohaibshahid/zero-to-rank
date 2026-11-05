<?php
/**
 * Zero to Rank — theme bootstrap.
 *
 * Every function is prefixed `ztr_` and every asset handle `zero-to-rank-*`
 * so this file can live safely alongside a parent theme and plugins.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

define( 'ZTR_VERSION', '1.0.0' );
define( 'ZTR_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'ZTR_URI', trailingslashit( get_stylesheet_directory_uri() ) );
define( 'ZTR_TEMPLATE', 'page-templates/template-zero-to-rank.php' );

$ztr_includes = array(
	'inc/helpers.php',    // Template detection, icons, small utilities.
	'inc/content.php',    // All landing-page copy, filterable via `ztr_content`.
	'inc/customizer.php', // Contact details, audit recipient, social profiles.
	'inc/setup.php',      // Theme supports, menus, asset enqueueing.
	'inc/seo.php',        // Title, meta description, canonical, Open Graph.
	'inc/schema.php',     // JSON-LD structured data.
	'inc/form.php',       // Visibility-audit form: validation, spam protection, storage, email.
);

foreach ( $ztr_includes as $ztr_file ) {
	require_once ZTR_DIR . $ztr_file;
}
unset( $ztr_includes, $ztr_file );
