<?php
/**
 * Mobile sticky CTA bar: slides up after the hero, hides while the audit
 * form is on screen (main.js). Small screens only.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'sticky_cta' );
if ( empty( $c['button'] ) ) {
	return;
}
?>
<div class="ztr-sticky-cta" data-ztr-sticky-cta>
	<span class="ztr-sticky-cta__text"><?php echo esc_html( $c['text'] ); ?></span>
	<a class="ztr-btn ztr-btn--glow ztr-btn--sm" href="<?php echo esc_url( ztr_link( ztr_audit_url() ) ); ?>" <?php echo ztr_track_attrs( 'cta_click', 'sticky_mobile', $c['button'] ); // phpcs:ignore ?>>
		<span><?php echo esc_html( $c['button'] ); ?></span>
		<span class="ztr-btn__arr" aria-hidden="true"><?php echo ztr_icon( 'arrow-right' ); // phpcs:ignore ?></span>
	</a>
</div>
