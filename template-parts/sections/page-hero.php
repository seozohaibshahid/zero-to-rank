<?php
/**
 * Inner-page hero (blog page and other inner pages): a dark rounded panel
 * with an eyebrow, a large title and one line of intro.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_args( 'page_hero', isset( $args ) ? $args : array() );
?>
<header class="ztr-page-hero ztr-dark" data-ztr-section="page_hero">
	<span class="ztr-page-hero__orb" aria-hidden="true"></span>
	<div class="ztr-container">
		<?php if ( ! empty( $c['eyebrow'] ) ) : ?>
			<p class="ztr-eyebrow"><span></span><?php echo esc_html( $c['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h1 class="ztr-page-hero__title" data-ztr-words><?php echo ztr_kses_inline( $c['title'] ); // phpcs:ignore ?></h1>
		<?php if ( ! empty( $c['text'] ) ) : ?>
			<p class="ztr-page-hero__text"><?php echo esc_html( $c['text'] ); ?></p>
		<?php endif; ?>
	</div>
</header>
