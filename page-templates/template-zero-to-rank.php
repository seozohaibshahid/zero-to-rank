<?php
/**
 * Template Name: Zero to Rank — Landing (classic)
 * Template Post Type: page
 *
 * The landing page rendered straight from PHP (no page builder). Content
 * comes from inc/content.php. To edit sections visually instead, use the
 * "Zero to Rank — Canvas (Elementor)" template and the Zero to Rank widgets.
 *
 * Reorder, remove or add sections with the `ztr_sections` filter.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$ztr_sections = apply_filters(
	'ztr_sections',
	array(
		'hero',        // 02 — Blue gradient hero.
		'logos',       // 03 — Platform logo strip.
		'statement',   // Point-of-view line (scroll-filled).
		'results',     // 04 — Case studies as stacked cards + CTA band.
		'services',    // 05 — Services as expanding panels.
		'shift',       // 06 — The visibility shift (head-to-head).
		'process',     // 07 — Process (dark band).
		'why',         // 08 — Why Zero to Rank.
		'industries',  // 09 — Who we help.
		'editor',      // Optional: page editor content, only when the page has content.
		'faq',         // 10 — FAQs.
		'audit',       // 11 — Free audit (CTA box + form).
	)
);

ztr_shell_open();
while ( have_posts() ) {
	the_post();
	foreach ( $ztr_sections as $ztr_slug ) {
		ztr_section( $ztr_slug );
	}
}
ztr_shell_close();
