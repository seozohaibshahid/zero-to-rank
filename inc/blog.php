<?php
/**
 * Page shell shared by every Zero to Rank template (landing, canvas, blog,
 * single post), blog helpers, and the per-post "noindex" switch.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

/**
 * Open the page: <html>, <head>, <body>, the .ztr wrapper, the cursor, the
 * skip link and the navigation, then <main>.
 *
 * @param string $main_class Extra class for <main>.
 */
function ztr_shell_open( $main_class = '' ) {
	?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="ztr" id="ztr-top">
	<div class="ztr-cursor" aria-hidden="true"><span class="ztr-cursor__dot"></span><span class="ztr-cursor__ring"></span></div>
	<a class="ztr-skip" href="#ztr-main"><?php esc_html_e( 'Skip to content', 'zero-to-rank' ); ?></a>

	<?php ztr_section( 'header' ); ?>

	<main id="ztr-main" class="ztr-main <?php echo esc_attr( $main_class ); ?>" tabindex="-1">
	<?php
}

/**
 * Close the page: </main>, footer, mobile CTA bar, wp_footer().
 */
function ztr_shell_close() {
	?>
	</main>

	<?php ztr_section( 'footer' ); ?>
	<?php ztr_section( 'sticky-cta' ); ?>
</div>
	<?php wp_footer(); ?>
</body>
</html>
	<?php
}

/* -------------------------------------------------------------------------
 * Noindex: a "Hide from search engines" switch on posts and pages.
 * ---------------------------------------------------------------------- */

add_action(
	'init',
	function () {
		foreach ( array( 'post', 'page' ) as $type ) {
			register_post_meta(
				$type,
				'_ztr_noindex',
				array(
					'type'          => 'boolean',
					'single'        => true,
					'show_in_rest'  => false,
					'auth_callback' => static function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}
);

/**
 * Is this post or page marked noindex?
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function ztr_is_noindex( $post_id ) {
	return (bool) get_post_meta( $post_id, '_ztr_noindex', true );
}

add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( is_singular() && ztr_is_noindex( get_queried_object_id() ) ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
			unset( $robots['max-image-preview'] );
		}
		return $robots;
	}
);

/* Keep noindexed posts out of the core XML sitemap. */
add_filter(
	'wp_sitemaps_posts_query_args',
	function ( $args ) {
		$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
			'relation' => 'OR',
			array(
				'key'     => '_ztr_noindex',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'   => '_ztr_noindex',
				'value' => '1',
				'compare' => '!=',
			),
		);
		return $args;
	}
);

add_action(
	'add_meta_boxes',
	function () {
		foreach ( array( 'post', 'page' ) as $type ) {
			add_meta_box( 'ztr_noindex', __( 'Search engines', 'zero-to-rank' ), 'ztr_render_noindex_box', $type, 'side', 'default' );
		}
	}
);

/**
 * Noindex meta box.
 *
 * @param WP_Post $post Post.
 */
function ztr_render_noindex_box( $post ) {
	wp_nonce_field( 'ztr_noindex_save', 'ztr_noindex_nonce' );
	?>
	<label><input type="checkbox" name="ztr_noindex" value="1" <?php checked( ztr_is_noindex( $post->ID ) ); ?>> <?php esc_html_e( 'Hide from search engines (noindex)', 'zero-to-rank' ); ?></label>
	<?php
}

add_action(
	'save_post',
	function ( $post_id ) {
		if ( ! isset( $_POST['ztr_noindex_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ztr_noindex_nonce'] ) ), 'ztr_noindex_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( ! empty( $_POST['ztr_noindex'] ) ) {
			update_post_meta( $post_id, '_ztr_noindex', 1 );
		} else {
			delete_post_meta( $post_id, '_ztr_noindex' );
		}
	}
);

/* -------------------------------------------------------------------------
 * Blog helpers.
 * ---------------------------------------------------------------------- */

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'ztr-card', 800, 500, true );
	}
);

/**
 * Estimated reading time in minutes.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function ztr_reading_time( $post_id ) {
	$words = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) ) );
	return max( 1, (int) ceil( $words / 220 ) );
}

/**
 * One post card (used by the blog grid widget, the posts index and related posts).
 *
 * @param int $post_id Post ID.
 */
function ztr_post_card( $post_id ) {
	$cats = get_the_category( $post_id );
	?>
	<article class="ztr-post-card" data-ztr-reveal="card" data-ztr-tilt>
		<a class="ztr-post-card__media" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			if ( has_post_thumbnail( $post_id ) ) {
				echo get_the_post_thumbnail( $post_id, 'ztr-card', array( 'loading' => 'lazy', 'alt' => '' ) );
			} else {
				echo '<span class="ztr-post-card__ph">' . ztr_logo_mark() . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG.
			}
			?>
		</a>
		<div class="ztr-post-card__body">
			<p class="ztr-post-card__meta ztr-mono">
				<?php if ( $cats ) : ?>
					<span><?php echo esc_html( $cats[0]->name ); ?></span>
				<?php endif; ?>
				<span><?php echo esc_html( sprintf( /* translators: %d: minutes */ _n( '%d min read', '%d min read', ztr_reading_time( $post_id ), 'zero-to-rank' ), ztr_reading_time( $post_id ) ) ); ?></span>
			</p>
			<h3 class="ztr-post-card__title"><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3>
			<p class="ztr-post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), 24 ) ); ?></p>
			<span class="ztr-post-card__more"><?php esc_html_e( 'Read article', 'zero-to-rank' ); ?> <?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
		</div>
	</article>
	<?php
}

/**
 * Pagination links in the theme's style.
 *
 * @param int $total   Total pages.
 * @param int $current Current page.
 */
function ztr_pagination( $total, $current ) {
	if ( $total < 2 ) {
		return;
	}
	$links = paginate_links(
		array(
			'total'     => $total,
			'current'   => max( 1, $current ),
			'type'      => 'array',
			'prev_text' => __( '← Newer', 'zero-to-rank' ),
			'next_text' => __( 'Older →', 'zero-to-rank' ),
		)
	);
	if ( $links ) {
		echo '<nav class="ztr-pagination" aria-label="' . esc_attr__( 'Posts', 'zero-to-rank' ) . '">' . implode( '', $links ) . '</nav>'; // phpcs:ignore WordPress.Security.EscapeOutput -- core markup.
	}
}
