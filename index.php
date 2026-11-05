<?php
/**
 * Fallback template. In standalone mode it renders the posts index,
 * archives, search results and 404 in the Zero to Rank design; any other
 * view gets a plain content column.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

if ( is_singular() && ! is_singular( 'post' ) ) {
	ztr_shell_open( 'ztr-main--page' );
	while ( have_posts() ) {
		the_post();
		ztr_section( 'page-hero', array( 'eyebrow' => '', 'title' => esc_html( get_the_title() ), 'text' => '' ) );
		echo '<section class="ztr-section ztr-article__wrap"><div class="ztr-container ztr-container--narrow ztr-prose">';
		the_content();
		echo '</div></section>';
	}
	ztr_shell_close();
	return;
}

if ( is_404() ) {
	$ztr_hero = array(
		'eyebrow' => '404',
		'title'   => __( 'This page <em>isn’t ranking.</em>', 'zero-to-rank' ),
		'text'    => __( 'The page you asked for doesn’t exist. Head back home, or read the latest articles below.', 'zero-to-rank' ),
	);
} elseif ( is_search() ) {
	$ztr_hero = array(
		'eyebrow' => __( 'Search', 'zero-to-rank' ),
		/* translators: %s: search query. */
		'title'   => sprintf( __( 'Results for <em>%s</em>', 'zero-to-rank' ), esc_html( get_search_query() ) ),
		'text'    => '',
	);
} elseif ( is_archive() ) {
	$ztr_hero = array(
		'eyebrow' => __( 'Archive', 'zero-to-rank' ),
		'title'   => esc_html( wp_strip_all_tags( get_the_archive_title() ) ),
		'text'    => wp_strip_all_tags( get_the_archive_description() ),
	);
} else {
	$ztr_hero = ztr_content( 'page_hero' );
}

ztr_shell_open( 'ztr-main--blog' );
ztr_section( 'page-hero', $ztr_hero );
?>
<section class="ztr-section ztr-blog">
	<div class="ztr-container">
		<?php if ( have_posts() && ! is_404() ) : ?>
			<div class="ztr-post-grid">
				<?php
				while ( have_posts() ) {
					the_post();
					ztr_post_card( get_the_ID() );
				}
				?>
			</div>
			<?php
			global $wp_query;
			ztr_pagination( (int) $wp_query->max_num_pages, (int) max( 1, get_query_var( 'paged' ) ) );
			?>
		<?php else : ?>
			<p class="ztr-lead"><?php esc_html_e( 'Nothing here yet.', 'zero-to-rank' ); ?></p>
			<p><a class="ztr-btn ztr-btn--blue" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span><?php esc_html_e( 'Back to home', 'zero-to-rank' ); ?></span><span class="ztr-btn__arr" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span></a></p>
		<?php endif; ?>
	</div>
</section>
<?php
ztr_shell_close();
