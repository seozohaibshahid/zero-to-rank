<?php
/**
 * Small helpers shared by the template, sections and integrations.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is the current request rendering the Zero to Rank landing template?
 *
 * @return bool
 */
function ztr_is_landing() {
	return is_page_template( ZTR_TEMPLATE );
}

/**
 * Is a dedicated SEO plugin handling titles, meta and canonicals?
 * When true, the template defers to it instead of printing duplicates.
 *
 * @return bool
 */
function ztr_seo_plugin_active() {
	$active = defined( 'WPSEO_VERSION' )            // Yoast SEO.
		|| class_exists( 'RankMath' )               // Rank Math.
		|| defined( 'AIOSEO_VERSION' )              // All in One SEO.
		|| defined( 'SEOPRESS_VERSION' )            // SEOPress.
		|| class_exists( 'The_SEO_Framework\Load' ); // The SEO Framework.

	/**
	 * Filter whether an SEO plugin is considered active.
	 *
	 * @param bool $active
	 */
	return (bool) apply_filters( 'ztr_seo_plugin_active', $active );
}

/**
 * Render a template part from template-parts/sections/.
 *
 * @param string $slug Section file name without extension.
 * @param array  $args Data passed to the part as $args.
 */
function ztr_section( $slug, $args = array() ) {
	get_template_part( 'template-parts/sections/' . sanitize_key( $slug ), null, $args );
}

/**
 * URL of a file inside assets/.
 *
 * @param string $path Relative path, e.g. 'images/logo-mark.svg'.
 * @return string
 */
function ztr_asset( $path ) {
	return ZTR_URI . 'assets/' . ltrim( $path, '/' );
}

/**
 * Inline SVG icons (decorative — always aria-hidden).
 *
 * @param string $name Icon key.
 * @param string $class Optional extra class.
 * @return string Safe SVG markup.
 */
function ztr_icon( $name, $class = '' ) {
	$paths = array(
		'arrow-up-right' => '<path d="M7 17 17 7M8 7h9v9"/>',
		'arrow-right'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-down'     => '<path d="M12 5v14M6 13l6 6 6-6"/>',
		'plus'           => '<path d="M12 5v14M5 12h14"/>',
		'check'          => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
		'search'         => '<circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.2-4.2"/>',
		'spark'          => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M6 18l2.5-2.5M15.5 8.5 18 6"/>',
		'chat'           => '<path d="M20 12a8 8 0 0 1-11.6 7.1L4 20l.9-4.4A8 8 0 1 1 20 12Z"/>',
		'mic'            => '<rect x="9" y="3" width="6" height="11" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v3"/>',
		'menu'           => '<path d="M4 8h16M4 16h16"/>',
		'close'          => '<path d="M6 6l12 12M18 6 6 18"/>',
		'mail'           => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'phone'          => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/>',
		'pin'            => '<path d="M12 21s-7-6.2-7-12a7 7 0 0 1 14 0c0 5.8-7 12-7 12Z"/><circle cx="12" cy="9" r="2.5"/>',
		'shield'         => '<path d="M12 3 4 6v6c0 4.5 3.4 8.3 8 9 4.6-.7 8-4.5 8-9V6l-8-3Z"/><path d="m9 12 2 2 4-4"/>',
		'chart'          => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
		'layers'         => '<path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 13 9 5 9-5"/>',
		'target'         => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r=".8"/>',
		'compass'        => '<circle cx="12" cy="12" r="9"/><path d="m15.5 8.5-2 5-5 2 2-5 5-2Z"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="ztr-icon %1$s" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		esc_attr( $class ),
		$paths[ $name ] // Static, trusted markup defined above.
	);
}

/**
 * The brand mark: a zero ring with a rising rank bar.
 *
 * @param string $class Extra class.
 * @return string
 */
function ztr_logo_mark( $class = '' ) {
	return '<svg class="ztr-logo__mark ' . esc_attr( $class ) . '" viewBox="0 0 40 40" width="40" height="40" aria-hidden="true" focusable="false">'
		. '<circle cx="20" cy="20" r="14" fill="none" stroke="currentColor" stroke-width="4"/>'
		. '<path d="M26 26 35 9" stroke="#58F6B1" stroke-width="4.5" stroke-linecap="round"/>'
		. '<circle cx="35" cy="9" r="3.6" fill="#58F6B1"/>'
		. '</svg>';
}

/**
 * Build an attribute string for analytics tracking.
 *
 * @param string $event    Event name (e.g. cta_click).
 * @param string $location Where on the page it lives.
 * @param string $label    Human label.
 * @return string Escaped attributes.
 */
function ztr_track_attrs( $event, $location, $label = '' ) {
	return sprintf(
		'data-ztr-track="%1$s" data-ztr-location="%2$s" data-ztr-label="%3$s"',
		esc_attr( $event ),
		esc_attr( $location ),
		esc_attr( $label )
	);
}

/**
 * Allow a tiny set of inline tags in authored copy (emphasis, line breaks).
 *
 * @param string $text Copy.
 * @return string
 */
function ztr_kses_inline( $text ) {
	return wp_kses(
		$text,
		array(
			'em'     => array( 'class' => true ),
			'strong' => array(),
			'br'     => array(),
			'span'   => array( 'class' => true ),
			'a'      => array( 'href' => true, 'class' => true ),
		)
	);
}

/**
 * The audit form anchor URL — used by every CTA so they stay in sync.
 *
 * @return string
 */
function ztr_audit_url() {
	return '#audit';
}
