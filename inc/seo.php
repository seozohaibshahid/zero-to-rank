<?php
/**
 * On-page SEO for the landing template: unique title, meta description,
 * canonical and social tags. Defers completely to an SEO plugin when one
 * is active (Yoast, Rank Math, AIOSEO, SEOPress, The SEO Framework).
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default title and description, used when the page has no custom values.
 *
 * @return array
 */
function ztr_seo_defaults() {
	return apply_filters(
		'ztr_seo_defaults',
		array(
			'title'       => __( 'SEO, AEO & GEO Agency | Zero to Rank — Search Visibility, Redefined', 'zero-to-rank' ),
			'description' => __( 'Be the answer, not just another search result. Zero to Rank grows businesses through SEO, Answer Engine Optimization and Generative Engine Optimization. Get a free visibility audit.', 'zero-to-rank' ),
		)
	);
}

/**
 * Resolved SEO title for the current landing page.
 *
 * @return string
 */
function ztr_seo_title() {
	$custom = trim( (string) get_post_meta( get_queried_object_id(), '_ztr_seo_title', true ) );
	$d      = ztr_seo_defaults();
	return '' !== $custom ? $custom : $d['title'];
}

/**
 * Resolved meta description for the current landing page.
 *
 * @return string
 */
function ztr_seo_description() {
	$id     = get_queried_object_id();
	$custom = trim( (string) get_post_meta( $id, '_ztr_meta_description', true ) );
	if ( '' === $custom && has_excerpt( $id ) ) {
		$custom = wp_strip_all_tags( get_the_excerpt( $id ) );
	}
	$d = ztr_seo_defaults();
	return '' !== $custom ? $custom : $d['description'];
}

/* -------------------------------------------------------------------------
 * Front end output.
 * ---------------------------------------------------------------------- */

add_filter(
	'pre_get_document_title',
	function ( $title ) {
		if ( ztr_is_landing() && ! ztr_seo_plugin_active() ) {
			return ztr_seo_title();
		}
		return $title;
	},
	20
);

add_action(
	'wp_head',
	function () {
		if ( ! ztr_is_landing() || ztr_seo_plugin_active() ) {
			return;
		}

		$url   = get_permalink( get_queried_object_id() );
		$title = ztr_seo_title();
		$desc  = ztr_seo_description();

		echo "\n<!-- Zero to Rank SEO -->\n";
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );

		// Core prints rel=canonical for singular pages via rel_canonical(); only add one if it was removed.
		if ( ! has_action( 'wp_head', 'rel_canonical' ) && $url ) {
			printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
		}

		printf( '<meta property="og:type" content="website">' . "\n" );
		printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
		printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );

		$image = get_the_post_thumbnail_url( get_queried_object_id(), 'full' );
		if ( ! $image && has_site_icon() ) {
			$image = get_site_icon_url( 512 );
		}
		if ( $image ) {
			printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		}
		printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );
		printf( '<meta name="theme-color" content="#010102">' . "\n" );
		echo "<!-- / Zero to Rank SEO -->\n";
	},
	2
);

/* -------------------------------------------------------------------------
 * Editor meta box (only when no SEO plugin is active).
 * ---------------------------------------------------------------------- */

add_action(
	'init',
	function () {
		foreach ( array( '_ztr_seo_title', '_ztr_meta_description' ) as $key ) {
			register_post_meta(
				'page',
				$key,
				array(
					'type'              => 'string',
					'single'            => true,
					'show_in_rest'      => false,
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => static function () {
						return current_user_can( 'edit_pages' );
					},
				)
			);
		}
	}
);

add_action(
	'add_meta_boxes_page',
	function () {
		if ( ztr_seo_plugin_active() ) {
			return;
		}
		add_meta_box( 'ztr_seo', __( 'Zero to Rank — SEO', 'zero-to-rank' ), 'ztr_render_seo_metabox', 'page', 'side', 'default' );
	}
);

/**
 * Meta box markup.
 *
 * @param WP_Post $post Page.
 */
function ztr_render_seo_metabox( $post ) {
	wp_nonce_field( 'ztr_seo_save', 'ztr_seo_nonce' );
	$d = ztr_seo_defaults();
	?>
	<p><em><?php esc_html_e( 'Used when this page uses the Zero to Rank template.', 'zero-to-rank' ); ?></em></p>
	<p>
		<label for="ztr_seo_title"><strong><?php esc_html_e( 'SEO title', 'zero-to-rank' ); ?></strong></label>
		<input type="text" class="widefat" id="ztr_seo_title" name="ztr_seo_title" maxlength="70" placeholder="<?php echo esc_attr( $d['title'] ); ?>" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ztr_seo_title', true ) ); ?>">
	</p>
	<p>
		<label for="ztr_meta_description"><strong><?php esc_html_e( 'Meta description', 'zero-to-rank' ); ?></strong></label>
		<textarea class="widefat" rows="4" id="ztr_meta_description" name="ztr_meta_description" maxlength="170" placeholder="<?php echo esc_attr( $d['description'] ); ?>"><?php echo esc_textarea( get_post_meta( $post->ID, '_ztr_meta_description', true ) ); ?></textarea>
	</p>
	<?php
}

add_action(
	'save_post_page',
	function ( $post_id ) {
		if ( ! isset( $_POST['ztr_seo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ztr_seo_nonce'] ) ), 'ztr_seo_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
		$fields = array(
			'ztr_seo_title'        => '_ztr_seo_title',
			'ztr_meta_description' => '_ztr_meta_description',
		);
		foreach ( $fields as $field => $meta ) {
			$value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
			if ( '' === $value ) {
				delete_post_meta( $post_id, $meta );
			} else {
				update_post_meta( $post_id, $meta, $value );
			}
		}
	}
);
