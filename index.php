<?php
/**
 * Fallback template.
 *
 * Only used when Zero to Rank runs as a standalone theme (child-theme mode
 * inherits the parent's templates). Keeps the rest of the site readable.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<main id="primary" style="max-width:760px;margin:0 auto;padding:48px 20px;font-family:system-ui,sans-serif;line-height:1.6">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			echo '<article>';
			if ( is_singular() ) {
				the_title( '<h1>', '</h1>' );
				the_content();
			} else {
				the_title( '<h2><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
				the_excerpt();
			}
			echo '</article>';
		}
		the_posts_pagination();
	} else {
		echo '<p>' . esc_html__( 'Nothing found.', 'zero-to-rank' ) . '</p>';
	}
	?>
</main>
<?php wp_footer(); ?>
</body>
</html>
