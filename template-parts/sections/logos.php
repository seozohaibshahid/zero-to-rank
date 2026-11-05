<?php
/**
 * 03 — Logo strip: the platforms we build visibility on, as real logos on a
 * plain background. Two copies of the row drift sideways (pauses on hover
 * and off-screen; a static, wrapping row with reduced motion).
 * Deliberately no client logos.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c     = ztr_args( 'logos', isset( $args ) ? $args : array() );
$items = array_values( (array) $c['items'] );
if ( ! $items ) {
	return;
}

/**
 * One logo item: uploaded image > built-in mark > name only.
 *
 * @param array $item Item.
 */
$ztr_logo_item = static function ( $item ) {
	$name  = isset( $item['name'] ) ? $item['name'] : '';
	$image = ! empty( $item['image'] ) ? ztr_image( $item['image'] ) : null;
	echo '<li class="ztr-logo-strip__item">';
	if ( $image && $image['url'] ) {
		printf( '<img class="ztr-logo-strip__img" src="%s" alt="" loading="lazy" decoding="async" height="28">', esc_url( $image['url'] ) );
	} elseif ( ! empty( $item['logo'] ) ) {
		echo ztr_platform_logo_svg( $item['logo'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG.
	}
	echo '<span>' . esc_html( $name ) . '</span></li>';
};
?>
<section class="ztr-logo-strip" aria-labelledby="ztr-logos-label" data-ztr-section="logos">
	<div class="ztr-container">
		<p class="ztr-logo-strip__label" id="ztr-logos-label"><?php echo esc_html( $c['label'] ); ?></p>
	</div>
	<div class="ztr-logo-strip__viewport" data-ztr-marquee>
		<div class="ztr-logo-strip__track">
			<ul class="ztr-logo-strip__set">
				<?php foreach ( $items as $item ) { $ztr_logo_item( $item ); } ?>
			</ul>
			<ul class="ztr-logo-strip__set" aria-hidden="true">
				<?php foreach ( $items as $item ) { $ztr_logo_item( $item ); } ?>
			</ul>
		</div>
	</div>
</section>
