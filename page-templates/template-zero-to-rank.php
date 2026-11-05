<?php
/**
 * Template Name: Zero to Rank — Landing
 * Template Post Type: page
 *
 * Full-canvas landing page. Renders its own header and footer so the parent
 * theme's chrome cannot interfere, while still calling wp_head()/wp_footer()
 * so plugins (analytics, caching, consent, SEO) keep working.
 *
 * Sections are modular template parts in template-parts/sections/. Reorder,
 * remove or add sections with the `ztr_sections` filter.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$ztr_sections = apply_filters(
	'ztr_sections',
	array(
		'hero',        // 02
		'trust',       // 03
		'statement',   // Point-of-view line (scroll-filled).
		'results',     // 04
		'shift',       // 05
		'services',    // 06
		'why',         // 07
		'process',     // 08
		'industries',  // 09
		'editor',      // Optional: page editor content, only when the page has content.
		'audit',       // 10
		'faq',         // 11
	)
);
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
	<a class="ztr-skip" href="#ztr-main"><?php esc_html_e( 'Skip to content', 'zero-to-rank' ); ?></a>

	<?php ztr_section( 'header' ); // 01 — Navigation. ?>

	<main id="ztr-main" class="ztr-main" tabindex="-1">
		<?php
		while ( have_posts() ) {
			the_post();
			foreach ( $ztr_sections as $ztr_slug ) {
				ztr_section( $ztr_slug );
			}
		}
		?>
	</main>

	<?php ztr_section( 'footer' ); // 12 — Footer. ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
