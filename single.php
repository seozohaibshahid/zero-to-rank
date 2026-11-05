<?php
/**
 * Single blog post in the Zero to Rank design: article header, featured
 * image, readable prose column, an audit CTA after the article, and related
 * posts. (Posts built in Elementor can use the Canvas template instead.)
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

ztr_shell_open( 'ztr-main--article' );

while ( have_posts() ) :
	the_post();
	$ztr_id   = get_the_ID();
	$ztr_cats = get_the_category();
	?>
	<article <?php post_class( 'ztr-article' ); ?>>
		<header class="ztr-page-hero ztr-page-hero--article ztr-dark">
			<div class="ztr-container ztr-container--narrow">
				<p class="ztr-article__crumbs ztr-mono">
					<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'zero-to-rank' ); ?></a>
					<?php if ( $ztr_cats ) : ?>
						<span aria-hidden="true">/</span>
						<a href="<?php echo esc_url( get_category_link( $ztr_cats[0] ) ); ?>"><?php echo esc_html( $ztr_cats[0]->name ); ?></a>
					<?php endif; ?>
				</p>
				<h1 class="ztr-page-hero__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="ztr-page-hero__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<p class="ztr-article__meta">
					<span><?php echo esc_html( get_the_author() ); ?></span>
					<span aria-hidden="true">·</span>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<?php if ( get_the_modified_date( 'Y-m-d' ) !== get_the_date( 'Y-m-d' ) ) : ?>
						<span aria-hidden="true">·</span>
						<span><?php echo esc_html( sprintf( /* translators: %s: date */ __( 'Updated %s', 'zero-to-rank' ), get_the_modified_date() ) ); ?></span>
					<?php endif; ?>
					<span aria-hidden="true">·</span>
					<span><?php echo esc_html( sprintf( /* translators: %d: minutes */ _n( '%d min read', '%d min read', ztr_reading_time( $ztr_id ), 'zero-to-rank' ), ztr_reading_time( $ztr_id ) ) ); ?></span>
				</p>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="ztr-article__image ztr-container ztr-container--narrow">
				<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
			</figure>
		<?php endif; ?>

		<div class="ztr-section ztr-article__wrap">
			<div class="ztr-container ztr-container--narrow ztr-prose">
				<?php the_content(); ?>
				<?php
				wp_link_pages(
					array(
						'before' => '<nav class="ztr-pagination">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>

			<aside class="ztr-container ztr-container--narrow">
				<div class="ztr-cta-band ztr-cta-band--article ztr-dark">
					<div>
						<p class="ztr-cta-band__title"><?php esc_html_e( 'Want to know where you stand?', 'zero-to-rank' ); ?></p>
						<p class="ztr-cta-band__text"><?php esc_html_e( 'Get a free visibility audit across Google, answer engines and AI assistants, reviewed by a person.', 'zero-to-rank' ); ?></p>
					</div>
					<a class="ztr-btn ztr-btn--glow ztr-btn--lg" href="<?php echo esc_url( home_url( '/#audit' ) ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'article_end', 'Free audit' ); // phpcs:ignore ?>>
						<span><?php esc_html_e( 'Get my free audit', 'zero-to-rank' ); ?></span>
						<span class="ztr-btn__arr" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
					</a>
				</div>
			</aside>
		</div>
	</article>

	<?php
	$ztr_related = get_posts(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( $ztr_id ),
			'ignore_sticky_posts' => true,
			'category__in'        => $ztr_cats ? wp_list_pluck( $ztr_cats, 'term_id' ) : array(),
		)
	);
	if ( $ztr_related ) :
		?>
		<section class="ztr-section ztr-blog ztr-blog--related" aria-labelledby="ztr-related-title">
			<div class="ztr-container">
				<header class="ztr-head">
					<p class="ztr-eyebrow"><span></span><?php esc_html_e( 'Keep reading', 'zero-to-rank' ); ?></p>
					<h2 class="ztr-h2" id="ztr-related-title"><?php esc_html_e( 'Related articles', 'zero-to-rank' ); ?></h2>
				</header>
				<div class="ztr-post-grid">
					<?php
					foreach ( $ztr_related as $ztr_post ) {
						ztr_post_card( $ztr_post->ID );
					}
					?>
				</div>
			</div>
		</section>
		<?php
	endif;
endwhile;

ztr_shell_close();
