<?php
/**
 * Template Name: Zero to Rank — Canvas (Elementor)
 * Template Post Type: page, post
 *
 * Full-width canvas with the Zero to Rank navigation and footer. Build the
 * page in Elementor from the "Zero to Rank" widgets (one per section); every
 * text, link, list and image in them is editable and the design and
 * animations stay intact.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

ztr_shell_open( 'ztr-main--canvas' );
while ( have_posts() ) {
	the_post();
	the_content();
}
ztr_shell_close();
