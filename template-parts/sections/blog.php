<?php
/**
 * Blog grid: the latest posts as cards, with category filters and
 * pagination. Used by the "ZTR Blog Grid" Elementor widget on the blog page.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c        = ztr_args( 'blog', isset( $args ) ? $args : array() );
$per_page = max( 1, (int) $c['per_page'] );
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter.
$cat   = isset( $_GET['topic'] ) ? sanitize_title( wp_unslash( $_GET['topic'] ) ) : '';
$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $per_page,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
		'category_name'       => $cat,
	)
);
$cats = get_categories( array( 'hide_empty' => true ) );
$base = get_permalink();
?>
<section class="ztr-section ztr-blog" data-ztr-section="blog">
	<div class="ztr-container">
		<?php if ( ! empty( $c['title'] ) ) : ?>
			<header class="ztr-head ztr-head--split">
				<div>
					<?php if ( ! empty( $c['eyebrow'] ) ) : ?>
						<p class="ztr-eyebrow" data-ztr-reveal><span></span><?php echo esc_html( $c['eyebrow'] ); ?></p>
					<?php endif; ?>
					<h2 class="ztr-h2" data-ztr-words><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h2>
				</div>
				<?php if ( ! empty( $c['intro'] ) ) : ?>
					<p class="ztr-lead" data-ztr-reveal><?php echo esc_html( $c['intro'] ); ?></p>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<?php if ( count( $cats ) > 1 ) : ?>
			<nav class="ztr-blog__filters" aria-label="<?php esc_attr_e( 'Topics', 'zero-to-rank' ); ?>">
				<a class="<?php echo '' === $cat ? 'is-active' : ''; ?>" href="<?php echo esc_url( $base ); ?>"><?php esc_html_e( 'All', 'zero-to-rank' ); ?></a>
				<?php foreach ( $cats as $term ) : ?>
					<a class="<?php echo $cat === $term->slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'topic', $term->slug, $base ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>

		<?php if ( $query->have_posts() ) : ?>
			<div class="ztr-post-grid">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					ztr_post_card( get_the_ID() );
				}
				wp_reset_postdata();
				?>
			</div>
			<?php ztr_pagination( (int) $query->max_num_pages, $paged ); ?>
		<?php else : ?>
			<p class="ztr-lead"><?php esc_html_e( 'No articles yet. Check back soon.', 'zero-to-rank' ); ?></p>
		<?php endif; ?>
	</div>
</section>
