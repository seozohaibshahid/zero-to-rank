<?php
/**
 * Theme setup and asset loading.
 *
 * Assets load ONLY on pages using the Zero to Rank template, so the rest of
 * the site (and any parent theme) is untouched.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'zero-to-rank', ZTR_DIR . 'languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

		register_nav_menus(
			array(
				'ztr_primary' => __( 'Zero to Rank — landing header', 'zero-to-rank' ),
				'ztr_footer'  => __( 'Zero to Rank — landing footer', 'zero-to-rank' ),
			)
		);
	}
);

/**
 * Enqueue landing page CSS and JS.
 */
function ztr_enqueue_assets() {
	if ( ! ztr_is_landing() ) {
		return;
	}

	$ver = static function ( $rel ) {
		$file = ZTR_DIR . 'assets/' . $rel;
		return file_exists( $file ) ? ZTR_VERSION . '.' . filemtime( $file ) : ZTR_VERSION;
	};

	// Styles: base → animations → responsive overrides.
	wp_enqueue_style( 'zero-to-rank-main', ztr_asset( 'css/main.css' ), array(), $ver( 'css/main.css' ) );
	wp_enqueue_style( 'zero-to-rank-animations', ztr_asset( 'css/animations.css' ), array( 'zero-to-rank-main' ), $ver( 'css/animations.css' ) );
	wp_enqueue_style( 'zero-to-rank-responsive', ztr_asset( 'css/responsive.css' ), array( 'zero-to-rank-main' ), $ver( 'css/responsive.css' ) );

	$defer = array(
		'in_footer' => true,
		'strategy'  => 'defer',
	);

	// GSAP + ScrollTrigger (self-hosted; used only for scroll-linked effects).
	$use_gsap = (bool) apply_filters( 'ztr_use_gsap', true );
	$anim_dep = array();
	if ( $use_gsap ) {
		wp_enqueue_script( 'zero-to-rank-gsap', ztr_asset( 'js/vendor/gsap.min.js' ), array(), '3.13.0', $defer );
		wp_enqueue_script( 'zero-to-rank-scrolltrigger', ztr_asset( 'js/vendor/ScrollTrigger.min.js' ), array( 'zero-to-rank-gsap' ), '3.13.0', $defer );
		$anim_dep = array( 'zero-to-rank-scrolltrigger' );
	}

	// Lenis smooth scrolling (self-hosted). Disable with: add_filter( 'ztr_use_lenis', '__return_false' );
	if ( (bool) apply_filters( 'ztr_use_lenis', true ) ) {
		wp_enqueue_script( 'zero-to-rank-lenis', ztr_asset( 'js/vendor/lenis.min.js' ), array(), '1.1.13', $defer );
		$anim_dep[] = 'zero-to-rank-lenis';
	}

	wp_enqueue_script( 'zero-to-rank-main', ztr_asset( 'js/main.js' ), array(), $ver( 'js/main.js' ), $defer );
	wp_enqueue_script( 'zero-to-rank-interactions', ztr_asset( 'js/interactions.js' ), array( 'zero-to-rank-main' ), $ver( 'js/interactions.js' ), $defer );
	wp_enqueue_script( 'zero-to-rank-animations', ztr_asset( 'js/animations.js' ), array_merge( array( 'zero-to-rank-main' ), $anim_dep ), $ver( 'js/animations.js' ), $defer );

	wp_localize_script(
		'zero-to-rank-main',
		'ztrData',
		array(
			'restUrl' => esc_url_raw( rest_url( 'ztr/v1/audit' ) ),
			'i18n'    => array(
				'sending'  => __( 'Sending…', 'zero-to-rank' ),
				'close'    => __( 'Close', 'zero-to-rank' ),
				'error'    => __( 'Something went wrong. Please try again, or email us directly.', 'zero-to-rank' ),
				'required' => __( 'This field is required.', 'zero-to-rank' ),
				'email'    => __( 'Please enter a valid email address.', 'zero-to-rank' ),
				'url'      => __( 'Please enter a valid website address, e.g. example.com', 'zero-to-rank' ),
				'consent'  => __( 'Please confirm we can contact you about your audit.', 'zero-to-rank' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'ztr_enqueue_assets', 20 );

/**
 * On the landing template, drop the active theme's own stylesheets so global
 * theme rules cannot clash with the scoped landing styles. Plugin styles are
 * left alone. Disable with: add_filter( 'ztr_dequeue_theme_styles', '__return_false' );
 */
function ztr_dequeue_theme_styles() {
	if ( ! ztr_is_landing() || ! apply_filters( 'ztr_dequeue_theme_styles', true ) ) {
		return;
	}

	$styles = wp_styles();
	$roots  = array_unique( array( get_template_directory_uri(), get_stylesheet_directory_uri() ) );

	foreach ( (array) $styles->queue as $handle ) {
		if ( 0 === strpos( $handle, 'zero-to-rank-' ) || empty( $styles->registered[ $handle ] ) ) {
			continue;
		}
		$src = (string) $styles->registered[ $handle ]->src;
		foreach ( $roots as $root ) {
			if ( '' !== $src && 0 === strpos( $src, $root ) ) {
				wp_dequeue_style( $handle );
				break;
			}
		}
	}

	// Block styles are only needed if the page body contains blocks.
	if ( ! has_blocks( get_queried_object_id() ) ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'ztr_dequeue_theme_styles', 999 );

/**
 * Remove emoji detection on the landing page (saves a render-blocking script).
 */
add_action(
	'wp',
	function () {
		if ( ztr_is_landing() && apply_filters( 'ztr_disable_emoji', true ) ) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
		}
		// Classic-theme global styles (theme.json presets) are not used by the landing page.
		if ( ztr_is_landing() && ! has_blocks( get_queried_object_id() ) && ! wp_is_block_theme() ) {
			remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
			remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
			remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles' );
		}
	}
);

/**
 * Preload the display and body fonts so the hero headline (the LCP element) renders fast.
 */
add_action(
	'wp_head',
	function () {
		if ( ! ztr_is_landing() ) {
			return;
		}
		// Display face (hero headline, the LCP element) and body face.
		foreach ( array( 'fonts/bricolage-grotesque-latin-var.woff2', 'fonts/manrope-latin-var.woff2' ) as $font ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( ztr_asset( $font ) )
			);
		}
		if ( ! has_site_icon() ) {
			printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( ztr_asset( 'images/logo-mark.svg' ) ) );
		}
		// Mark JS as available before first paint so reveal styles never hide content for no-JS visitors.
		echo "<script>document.documentElement.classList.add('ztr-js');</script>\n";
	},
	1
);

/**
 * Body class hook for scoping.
 */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( ztr_is_landing() ) {
			$classes[] = 'ztr-landing';
		}
		return $classes;
	}
);

/**
 * One-time setup on activation: create the landing page if none uses the
 * template yet, and make it the front page — but only when the site still
 * shows its latest posts (a fresh install). Existing front pages are never
 * replaced. Runs on `after_switch_theme`, or on the first request after
 * activation when a host activates the theme without firing that hook.
 * Disable with: add_filter( 'ztr_auto_setup', '__return_false' );
 */
function ztr_auto_setup() {
	if ( get_option( 'ztr_setup_done' ) || ! apply_filters( 'ztr_auto_setup', true ) ) {
		return;
	}
	update_option( 'ztr_setup_done', time(), false );

	$template = 'page-templates/template-zero-to-rank.php';
	$existing = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'private' ),
			'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $template, // phpcs:ignore WordPress.DB.SlowDBQuery
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return;
	}
	$page_id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_title'  => __( 'Home', 'zero-to-rank' ),
			'post_status' => 'publish',
			'post_name'   => 'home',
		)
	);
	if ( ! $page_id || is_wp_error( $page_id ) ) {
		return;
	}
	update_post_meta( $page_id, '_wp_page_template', $template );
	if ( 'posts' === get_option( 'show_on_front' ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_id );
	}
}
add_action( 'after_switch_theme', 'ztr_auto_setup' );
add_action(
	'init',
	function () {
		if ( ! get_option( 'ztr_setup_done' ) && get_stylesheet() === basename( ZTR_DIR ) ) {
			ztr_auto_setup();
		}
	},
	20
);
