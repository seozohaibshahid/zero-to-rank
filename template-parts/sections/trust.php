<?php
/**
 * 03 — Trust bar: the channels we optimise for and verifiable working principles.
 * Deliberately no client logos or unverified claims.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

$c = ztr_content( 'trust' );
?>
<section class="ztr-trust" aria-label="<?php echo esc_attr( $c['label'] ); ?>">
	<div class="ztr-container ztr-trust__inner">
		<p class="ztr-trust__label"><?php echo esc_html( $c['label'] ); ?></p>
		<div class="ztr-marquee" data-ztr-marquee>
			<ul class="ztr-marquee__track">
				<?php foreach ( $c['platforms'] as $p ) : ?>
					<li><?php echo esc_html( $p ); ?></li>
				<?php endforeach; ?>
			</ul>
			<ul class="ztr-marquee__track" aria-hidden="true">
				<?php foreach ( $c['platforms'] as $p ) : ?>
					<li><?php echo esc_html( $p ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="ztr-container">
		<ul class="ztr-trust__principles">
			<?php foreach ( $c['principles'] as $i => $p ) : ?>
				<li data-ztr-reveal style="--d:<?php echo (int) $i; ?>">
					<?php echo ztr_icon( $p['icon'] ); // phpcs:ignore ?>
					<span><?php echo esc_html( $p['text'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
