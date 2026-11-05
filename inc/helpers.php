<?php
/**
 * Small helpers shared by the template, sections and integrations.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is this the Zero to Rank landing page? True for the classic landing
 * template, and for the Elementor canvas template when it is the front page.
 * Landing-only output (SEO title/meta, Service + FAQ schema) keys off this.
 *
 * @return bool
 */
function ztr_is_landing() {
	return is_page_template( ZTR_TEMPLATE ) || ( is_front_page() && is_page_template( ZTR_CANVAS ) );
}

/**
 * Is the theme running on its own (not as a child of another theme)?
 *
 * @return bool
 */
function ztr_is_standalone() {
	return get_template() === get_stylesheet();
}

/**
 * Should this request use the Zero to Rank design (assets, header, footer)?
 * Pages on either Zero to Rank template always do. In standalone mode, the
 * blog views (posts index, single posts, archives, search, 404) do as well.
 *
 * @return bool
 */
function ztr_has_assets() {
	if ( is_page_template( ZTR_TEMPLATE ) || is_page_template( ZTR_CANVAS ) ) {
		return true;
	}
	if ( ztr_is_standalone() && ( is_singular( 'post' ) || is_home() || is_archive() || is_search() || is_404() ) ) {
		return true;
	}
	return (bool) apply_filters( 'ztr_has_assets', false );
}

/**
 * Section content: the defaults from inc/content.php with any arguments
 * (from an Elementor widget or a direct call) laid over them.
 *
 * @param string $key  Content key, e.g. 'services'.
 * @param array  $args Overrides.
 * @return array
 */
function ztr_args( $key, $args = array() ) {
	$defaults = ztr_content( $key );
	if ( ! is_array( $args ) || ! $args ) {
		return $defaults;
	}
	return array_merge( $defaults, $args );
}

/**
 * Resolve an image value: an attachment ID, a full URL, or a path inside
 * the theme's assets/ folder.
 *
 * @param int|string $src Image reference.
 * @return array { url, width, height } (width/height 0 when unknown).
 */
function ztr_image( $src ) {
	$out = array( 'url' => '', 'width' => 0, 'height' => 0 );
	if ( is_numeric( $src ) && (int) $src > 0 ) {
		$img = wp_get_attachment_image_src( (int) $src, 'full' );
		if ( $img ) {
			$out = array( 'url' => $img[0], 'width' => (int) $img[1], 'height' => (int) $img[2] );
		}
		return $out;
	}
	$src = (string) $src;
	if ( '' === $src ) {
		return $out;
	}
	if ( preg_match( '#^([a-z][a-z0-9+.-]*:)?//#i', $src ) ) {
		$out['url'] = $src;
		$id         = attachment_url_to_postid( $src );
		if ( $id ) {
			$img = wp_get_attachment_image_src( $id, 'full' );
			if ( $img ) {
				$out['width']  = (int) $img[1];
				$out['height'] = (int) $img[2];
			}
		} elseif ( 0 === strpos( $src, ZTR_URI ) ) {
			$src = substr( $src, strlen( ZTR_URI . 'assets/' ) );
		} else {
			return $out;
		}
		if ( $out['width'] ) {
			return $out;
		}
	}
	$file = ZTR_DIR . 'assets/' . ltrim( $src, '/' );
	if ( ! $out['url'] ) {
		$out['url'] = ztr_asset( $src );
	}
	if ( is_readable( $file ) ) {
		$size = @getimagesize( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		if ( $size ) {
			$out['width']  = (int) $size[0];
			$out['height'] = (int) $size[1];
		}
	}
	return $out;
}

/**
 * Turn "a | b | c" lines into rows keyed by $cols (used for metric lists).
 *
 * @param string|array $value Lines or rows already.
 * @param array        $cols  Column keys.
 * @return array
 */
function ztr_rows( $value, $cols ) {
	if ( is_array( $value ) ) {
		return $value;
	}
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $value ) as $line ) {
		if ( '' === trim( $line ) ) {
			continue;
		}
		$parts = array_map( 'trim', explode( '|', $line ) );
		$row   = array();
		foreach ( $cols as $i => $col ) {
			$row[ $col ] = isset( $parts[ $i ] ) ? $parts[ $i ] : '';
		}
		$rows[] = $row;
	}
	return $rows;
}

/**
 * Turn newline-separated text into a list (arrays pass through).
 *
 * @param string|array $value Text or list.
 * @return array
 */
function ztr_lines( $value ) {
	if ( is_array( $value ) ) {
		return array_values( array_filter( array_map( 'trim', $value ), 'strlen' ) );
	}
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $value ) ), 'strlen' ) );
}

/**
 * Link for an in-page anchor that also works away from the homepage.
 *
 * @param string $url URL or #anchor.
 * @return string
 */
function ztr_link( $url ) {
	$url = (string) $url;
	if ( '' !== $url && '#' === $url[0] && ! ( is_front_page() || is_page_template( ZTR_TEMPLATE ) ) ) {
		return home_url( '/' . $url );
	}
	return $url;
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
 * The brand mark: a zero ring holding three rising rank bars.
 * The ring takes the text colour; the bars are ice blue.
 *
 * @param string $class Extra class.
 * @return string
 */
function ztr_logo_mark( $class = '' ) {
	return '<svg class="ztr-logo__mark ' . esc_attr( $class ) . '" viewBox="0 0 40 40" width="40" height="40" aria-hidden="true" focusable="false">'
		. '<circle cx="20" cy="20" r="15.5" fill="none" stroke="currentColor" stroke-width="4"/>'
		. '<rect x="11.6" y="21.5" width="4.2" height="6.5" rx="1.4" fill="#6FD6F7" opacity=".55"/>'
		. '<rect x="17.9" y="17" width="4.2" height="11" rx="1.4" fill="#6FD6F7" opacity=".8"/>'
		. '<rect x="24.2" y="12" width="4.2" height="16" rx="1.4" fill="#6FD6F7"/>'
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
